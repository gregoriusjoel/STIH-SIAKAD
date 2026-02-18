<?php

namespace Tests\Feature;

use App\Models\Installment;
use App\Models\InstallmentRequest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentProof;
use App\Models\Student;
use App\Models\User;
use App\Services\ApproveInstallmentRequestService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $financeUser;
    protected User $studentUser;
    protected Student $student;
    protected Invoice $invoice;

    protected function setUp(): void
    {
        parent::setUp();

        // Create finance user
        $this->financeUser = User::factory()->create(['role' => 'finance']);

        // Create student
        $this->studentUser = User::factory()->create(['role' => 'mahasiswa']);
        $this->student = Student::factory()->create(['user_id' => $this->studentUser->id]);

        // Create invoice
        $this->invoice = Invoice::factory()->create([
            'student_id' => $this->student->id,
            'total_tagihan' => 5000000,
            'status' => 'PUBLISHED',
            'created_by' => $this->financeUser->id,
        ]);
    }

    /** @test */
    public function approve_installment_request_creates_correct_installments()
    {
        // Create installment request
        $request = InstallmentRequest::factory()->create([
            'invoice_id' => $this->invoice->id,
            'student_id' => $this->student->id,
            'requested_terms' => 3,
            'status' => 'SUBMITTED',
        ]);

        $service = new ApproveInstallmentRequestService();
        
        $this->actingAs($this->financeUser);
        $service->approve($request, 3);

        // Assert installments created
        $this->assertDatabaseCount('installments', 3);

        $installments = Installment::where('invoice_id', $this->invoice->id)
            ->orderBy('installment_no')
            ->get();

        // Total: 5.000.000
        // Cicilan 1-2: 1.666.000
        // Cicilan 3: 1.668.000 (menyerap selisih)
        $this->assertEquals(1666000, $installments[0]->amount);
        $this->assertEquals(1666000, $installments[1]->amount);
        $this->assertEquals(1668000, $installments[2]->amount);

        // Total must match
        $total = $installments->sum('amount');
        $this->assertEquals(5000000, $total);

        // Invoice status updated
        $this->invoice->refresh();
        $this->assertEquals('IN_INSTALLMENT', $this->invoice->status);
    }

    /** @test */
    public function student_cannot_view_other_student_invoice()
    {
        $otherStudent = Student::factory()->create();
        $otherInvoice = Invoice::factory()->create([
            'student_id' => $otherStudent->id,
            'status' => 'PUBLISHED',
        ]);

        $this->actingAs($this->studentUser);
        
        $response = $this->get(route('mahasiswa.invoices.show', $otherInvoice));
        
        $response->assertForbidden();
    }

    /** @test */
    public function upload_payment_proof_changes_installment_status()
    {
        // Create installment
        $installment = Installment::factory()->create([
            'invoice_id' => $this->invoice->id,
            'installment_no' => 1,
            'amount' => 1666000,
            'status' => 'UNPAID',
        ]);

        $this->actingAs($this->studentUser);

        $response = $this->post(route('mahasiswa.payment-proofs.store'), [
            'installment_id' => $installment->id,
            'transfer_date' => now()->toDateString(),
            'amount_submitted' => 1666000,
            'method' => 'Transfer Bank',
            'file' => \Illuminate\Http\UploadedFile::fake()->image('proof.jpg'),
        ]);

        $response->assertRedirect();
        
        // Assert proof created
        $this->assertDatabaseHas('payment_proofs', [
            'installment_id' => $installment->id,
            'amount_submitted' => 1666000,
            'status' => 'UPLOADED',
        ]);

        // Assert installment status changed
        $installment->refresh();
        $this->assertEquals('WAITING_VERIFICATION', $installment->status);
    }

    /** @test */
    public function approve_proof_creates_payment_and_updates_installment()
    {
        $installment = Installment::factory()->create([
            'invoice_id' => $this->invoice->id,
            'installment_no' => 1,
            'amount' => 1666000,
            'status' => 'WAITING_VERIFICATION',
        ]);

        $proof = PaymentProof::factory()->create([
            'invoice_id' => $this->invoice->id,
            'installment_id' => $installment->id,
            'amount_submitted' => 1666000,
            'status' => 'UPLOADED',
        ]);

        $this->actingAs($this->financeUser);

        $response = $this->post(route('finance.payment-proofs.review', $proof), [
            'action' => 'approve',
            'notes' => 'Approved',
        ]);

        $response->assertRedirect();

        // Assert payment created
        $this->assertDatabaseHas('payments', [
            'proof_id' => $proof->id,
            'installment_id' => $installment->id,
            'amount_approved' => 1666000,
        ]);

        // Assert installment updated
        $installment->refresh();
        $this->assertEquals('PAID', $installment->status);
        $this->assertNotNull($installment->paid_at);

        // Assert proof updated
        $proof->refresh();
        $this->assertEquals('APPROVED', $proof->status);
        $this->assertNotNull($proof->approved_at);
    }

    /** @test */
    public function invoice_becomes_lunas_when_all_installments_paid()
    {
        // Create 3 installments
        $installments = Installment::factory()->count(3)->create([
            'invoice_id' => $this->invoice->id,
            'amount' => 1666000,
        ]);

        $this->actingAs($this->financeUser);

        // Approve all installments
        foreach ($installments as $index => $installment) {
            $proof = PaymentProof::factory()->create([
                'invoice_id' => $this->invoice->id,
                'installment_id' => $installment->id,
                'amount_submitted' => $installment->amount,
                'status' => 'UPLOADED',
            ]);

            $this->post(route('finance.payment-proofs.review', $proof), [
                'action' => 'approve',
            ]);

            $this->invoice->refresh();

            // Only after last installment, invoice should be LUNAS
            if ($index === 2) {
                $this->assertEquals('LUNAS', $this->invoice->status);
            }
        }
    }

    /** @test */
    public function reject_proof_saves_reason_and_allows_reupload()
    {
        $installment = Installment::factory()->create([
            'invoice_id' => $this->invoice->id,
            'installment_no' => 1,
            'status' => 'WAITING_VERIFICATION',
        ]);

        $proof = PaymentProof::factory()->create([
            'invoice_id' => $this->invoice->id,
            'installment_id' => $installment->id,
            'status' => 'UPLOADED',
        ]);

        $this->actingAs($this->financeUser);

        $response = $this->post(route('finance.payment-proofs.review', $proof), [
            'action' => 'reject',
            'notes' => 'Bukti tidak jelas',
        ]);

        $response->assertRedirect();

        // Assert proof rejected
        $proof->refresh();
        $this->assertEquals('REJECTED', $proof->status);
        $this->assertEquals('Bukti tidak jelas', $proof->finance_notes);
        $this->assertNotNull($proof->rejected_at);

        // Assert installment status updated
        $installment->refresh();
        $this->assertEquals('REJECTED_PAYMENT', $installment->status);

        // Student can upload again
        $this->actingAs($this->studentUser);
        
        $response = $this->get(route('mahasiswa.payment-proofs.create', $installment));
        $response->assertOk();
    }

    /** @test */
    public function cannot_double_approve_same_proof()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Proof already processed');

        $proof = PaymentProof::factory()->create([
            'invoice_id' => $this->invoice->id,
            'status' => 'APPROVED',
        ]);

        $service = app(\App\Services\ApprovePaymentProofService::class);
        
        $this->actingAs($this->financeUser);
        $service->approve($proof);
    }

    /** @test */
    public function cannot_pay_next_installment_before_previous_paid()
    {
        $installment1 = Installment::factory()->create([
            'invoice_id' => $this->invoice->id,
            'installment_no' => 1,
            'status' => 'UNPAID',
        ]);

        $installment2 = Installment::factory()->create([
            'invoice_id' => $this->invoice->id,
            'installment_no' => 2,
            'status' => 'UNPAID',
        ]);

        $this->actingAs($this->studentUser);

        // Try to pay installment 2
        $response = $this->get(route('mahasiswa.payment-proofs.create', $installment2));
        
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Cicilan sebelumnya harus dibayar terlebih dahulu');
    }

    /** @test */
    public function amount_must_match_if_partial_not_allowed()
    {
        $this->invoice->update(['allow_partial' => false]);

        $installment = Installment::factory()->create([
            'invoice_id' => $this->invoice->id,
            'amount' => 1666000,
            'status' => 'WAITING_VERIFICATION',
        ]);

        $proof = PaymentProof::factory()->create([
            'invoice_id' => $this->invoice->id,
            'installment_id' => $installment->id,
            'amount_submitted' => 1500000, // Wrong amount
            'status' => 'UPLOADED',
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Amount must match installment amount');

        $service = app(\App\Services\ApprovePaymentProofService::class);
        
        $this->actingAs($this->financeUser);
        $service->approve($proof);
    }

    /** @test */
    public function student_can_only_see_published_invoices()
    {
        $draftInvoice = Invoice::factory()->create([
            'student_id' => $this->student->id,
            'status' => 'DRAFT',
        ]);

        $publishedInvoice = Invoice::factory()->create([
            'student_id' => $this->student->id,
            'status' => 'PUBLISHED',
        ]);

        $this->actingAs($this->studentUser);

        // Can access published
        $response = $this->get(route('mahasiswa.invoices.show', $publishedInvoice));
        $response->assertOk();

        // Cannot access draft
        $response = $this->get(route('mahasiswa.invoices.show', $draftInvoice));
        $response->assertForbidden();
    }

    /** @test */
    public function payment_history_only_shows_approved_payments()
    {
        $installment = Installment::factory()->create([
            'invoice_id' => $this->invoice->id,
        ]);

        $uploadedProof = PaymentProof::factory()->create([
            'invoice_id' => $this->invoice->id,
            'installment_id' => $installment->id,
            'status' => 'UPLOADED',
        ]);

        $approvedProof = PaymentProof::factory()->create([
            'invoice_id' => $this->invoice->id,
            'installment_id' => $installment->id,
            'status' => 'APPROVED',
        ]);

        Payment::factory()->create([
            'invoice_id' => $this->invoice->id,
            'installment_id' => $installment->id,
            'proof_id' => $approvedProof->id,
        ]);

        $this->actingAs($this->studentUser);
        
        $response = $this->get(route('mahasiswa.payments.history'));
        
        $response->assertOk();
        $response->assertSee($approvedProof->id);
        $response->assertDontSee($uploadedProof->id);
    }

    /** @test */
    public function installment_calculation_with_odd_total()
    {
        $invoice = Invoice::factory()->create([
            'total_tagihan' => 8123456,
            'status' => 'PUBLISHED',
        ]);

        $request = InstallmentRequest::factory()->create([
            'invoice_id' => $invoice->id,
            'requested_terms' => 5,
            'status' => 'SUBMITTED',
        ]);

        $service = new ApproveInstallmentRequestService();
        
        $this->actingAs($this->financeUser);
        $service->approve($request, 5);

        $installments = Installment::where('invoice_id', $invoice->id)
            ->orderBy('installment_no')
            ->get();

        // Cicilan 1-4: 1.624.000
        // Cicilan 5: 1.627.456 (menyerap selisih)
        $this->assertEquals(1624000, $installments[0]->amount);
        $this->assertEquals(1624000, $installments[1]->amount);
        $this->assertEquals(1624000, $installments[2]->amount);
        $this->assertEquals(1624000, $installments[3]->amount);
        $this->assertEquals(1627456, $installments[4]->amount);

        // Total exact match
        $this->assertEquals(8123456, $installments->sum('amount'));
    }

    /** @test */
    public function cannot_approve_installment_if_invoice_not_published()
    {
        $this->invoice->update(['status' => 'DRAFT']);

        $request = InstallmentRequest::factory()->create([
            'invoice_id' => $this->invoice->id,
            'requested_terms' => 3,
            'status' => 'SUBMITTED',
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invoice must be PUBLISHED');

        $service = new ApproveInstallmentRequestService();
        
        $this->actingAs($this->financeUser);
        $service->approve($request, 3);
    }

    /** @test */
    public function audit_log_created_on_approve_actions()
    {
        $proof = PaymentProof::factory()->create([
            'invoice_id' => $this->invoice->id,
            'status' => 'UPLOADED',
        ]);

        $this->actingAs($this->financeUser);

        $this->post(route('finance.payment-proofs.review', $proof), [
            'action' => 'approve',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'actor_id' => $this->financeUser->id,
            'action' => 'payment_proof.approve',
            'auditable_type' => PaymentProof::class,
            'auditable_id' => $proof->id,
        ]);
    }

    /** @test */
    public function file_upload_validation()
    {
        $installment = Installment::factory()->create([
            'invoice_id' => $this->invoice->id,
            'installment_no' => 1,
        ]);

        $this->actingAs($this->studentUser);

        // Test invalid file type
        $response = $this->post(route('mahasiswa.payment-proofs.store'), [
            'installment_id' => $installment->id,
            'transfer_date' => now()->toDateString(),
            'amount_submitted' => 1000000,
            'file' => \Illuminate\Http\UploadedFile::fake()->create('document.docx', 1000),
        ]);

        $response->assertSessionHasErrors('file');

        // Test file too large (>2MB)
        $response = $this->post(route('mahasiswa.payment-proofs.store'), [
            'installment_id' => $installment->id,
            'transfer_date' => now()->toDateString(),
            'amount_submitted' => 1000000,
            'file' => \Illuminate\Http\UploadedFile::fake()->create('proof.jpg', 3000),
        ]);

        $response->assertSessionHasErrors('file');
    }
}
