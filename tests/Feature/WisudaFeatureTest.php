<?php

namespace Tests\Feature;

use App\Domain\Skripsi\Enums\SkripsiStatus;
use App\Domain\Wisuda\Enums\WisudaDocumentType;
use App\Domain\Wisuda\Enums\WisudaRegistrationStatus;
use App\Domain\Wisuda\Services\WisudaEligibilityService;
use App\Domain\Wisuda\Services\WisudaService;
use App\Models\Mahasiswa;
use App\Models\SkripsiSubmission;
use App\Models\User;
use App\Models\WisudaBatch;
use App\Models\WisudaRegistration;
use App\Models\WisudaDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WisudaFeatureTest extends TestCase
{
    use RefreshDatabase;

    private WisudaEligibilityService $eligibilityService;
    private WisudaService $wisudaService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->eligibilityService = app(WisudaEligibilityService::class);
        $this->wisudaService = app(WisudaService::class);
    }

    /**
     * Test that student without eligible skripsi is not eligible.
     */
    public function test_student_without_eligible_skripsi_is_not_eligible(): void
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12345678',
            'nama' => $user->name,
            'prodi' => 'Ilmu Hukum',
            'angkatan' => '2022',
            'status' => 'aktif',
            'status_akun' => 'baru',
        ]);

        // No skripsi submission at all
        $this->assertFalse($this->eligibilityService->isEligible($mahasiswa));

        // Skripsi submission with non-eligible status
        $submission = SkripsiSubmission::create([
            'mahasiswa_id' => $mahasiswa->id,
            'judul' => 'Judul Skripsi Test',
            'status' => SkripsiStatus::BIMBINGAN_ACTIVE,
        ]);

        $this->assertFalse($this->eligibilityService->isEligible($mahasiswa));
    }

    /**
     * Test that student with REVISION_APPROVED skripsi is eligible.
     */
    public function test_student_with_revision_approved_is_eligible(): void
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12345678',
            'nama' => $user->name,
            'prodi' => 'Ilmu Hukum',
            'angkatan' => '2022',
            'status' => 'aktif',
            'status_akun' => 'baru',
        ]);

        SkripsiSubmission::create([
            'mahasiswa_id' => $mahasiswa->id,
            'judul' => 'Judul Skripsi Test',
            'status' => SkripsiStatus::REVISION_APPROVED,
        ]);

        $this->assertTrue($this->eligibilityService->isEligible($mahasiswa));
    }

    /**
     * Test that student with SKRIPSI_COMPLETED skripsi is eligible.
     */
    public function test_student_with_thesis_completed_is_eligible(): void
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12345678',
            'nama' => $user->name,
            'prodi' => 'Ilmu Hukum',
            'angkatan' => '2022',
            'status' => 'aktif',
            'status_akun' => 'baru',
        ]);

        SkripsiSubmission::create([
            'mahasiswa_id' => $mahasiswa->id,
            'judul' => 'Judul Skripsi Test',
            'status' => SkripsiStatus::SKRIPSI_COMPLETED,
        ]);

        $this->assertTrue($this->eligibilityService->isEligible($mahasiswa));
    }

    /**
     * Test that student with active wisuda registration is not eligible.
     */
    public function test_student_with_active_registration_is_not_eligible(): void
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12345678',
            'nama' => $user->name,
            'prodi' => 'Ilmu Hukum',
            'angkatan' => '2022',
            'status' => 'aktif',
            'status_akun' => 'baru',
        ]);

        $submission = SkripsiSubmission::create([
            'mahasiswa_id' => $mahasiswa->id,
            'judul' => 'Judul Skripsi Test',
            'status' => SkripsiStatus::REVISION_APPROVED,
        ]);

        // Create an active wisuda registration
        WisudaRegistration::create([
            'mahasiswa_id' => $mahasiswa->id,
            'skripsi_submission_id' => $submission->id,
            'status' => WisudaRegistrationStatus::PENDING,
            'no_hp' => '08123456789',
            'email_aktif' => 'student@test.com',
            'submitted_at' => now(),
        ]);

        $this->assertFalse($this->eligibilityService->isEligible($mahasiswa));
    }

    /**
     * Test graduation registration and file upload.
     */
    public function test_student_can_register_for_wisuda_and_uploads_documents(): void
    {
        Storage::fake('s3');

        $user = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12345678',
            'nama' => $user->name,
            'prodi' => 'Ilmu Hukum',
            'angkatan' => '2022',
            'status' => 'aktif',
            'status_akun' => 'baru',
        ]);

        $submission = SkripsiSubmission::create([
            'mahasiswa_id' => $mahasiswa->id,
            'judul' => 'Judul Skripsi Test',
            'status' => SkripsiStatus::REVISION_APPROVED,
        ]);

        $this->actingAs($user);

        // Step 1: Submit contact data to initialize registration
        $response = $this->post(route('mahasiswa.wisuda.store'), [
            'no_hp' => '08123456789',
            'email_aktif' => 'student@test.com',
        ]);

        $response->assertRedirect(route('mahasiswa.wisuda.index'));

        $registration = WisudaRegistration::where('mahasiswa_id', $mahasiswa->id)->first();
        $this->assertNotNull($registration);
        $this->assertEquals(WisudaRegistrationStatus::PENDING, $registration->status);
        $this->assertEquals('08123456789', $registration->no_hp);
        $this->assertEquals('student@test.com', $registration->email_aktif);

        // Step 2: Upload mandatory documents
        $fakePdf = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');
        $fakePhoto = UploadedFile::fake()->image('photo.jpg', 600, 600);

        foreach (WisudaDocumentType::cases() as $type) {
            $file = ($type === WisudaDocumentType::PAS_FOTO) ? $fakePhoto : $fakePdf;

            $uploadResponse = $this->from(route('mahasiswa.wisuda.index'))
                ->post(route('mahasiswa.wisuda.upload', [
                    'reg' => $registration->id,
                    'file_type' => $type->value,
                ]), [
                    'file' => $file,
                ]);

            $uploadResponse->assertRedirect(route('mahasiswa.wisuda.index'));
        }

        $this->assertEquals(4, $registration->documents()->count());

        // Step 3: Final submit pendaftaran
        $submitResponse = $this->post(route('mahasiswa.wisuda.submit', $registration->id));
        $submitResponse->assertRedirect(route('mahasiswa.wisuda.index'));

        $registration->refresh();
        $this->assertEquals(WisudaRegistrationStatus::PENDING, $registration->status);
        $this->assertNotNull($registration->submitted_at);
    }

    /**
     * Test admin approval flow.
     */
    public function test_admin_can_view_registrations_and_approve_them(): void
    {
        $adminUser = User::factory()->create(['role' => 'admin']);
        $studentUser = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa = Mahasiswa::create([
            'user_id' => $studentUser->id,
            'nim' => '12345678',
            'nama' => $studentUser->name,
            'prodi' => 'Ilmu Hukum',
            'angkatan' => '2022',
            'status' => 'aktif',
            'status_akun' => 'baru',
        ]);

        $submission = SkripsiSubmission::create([
            'mahasiswa_id' => $mahasiswa->id,
            'judul' => 'Judul Skripsi Test',
            'status' => SkripsiStatus::REVISION_APPROVED,
        ]);

        $registration = WisudaRegistration::create([
            'mahasiswa_id' => $mahasiswa->id,
            'skripsi_submission_id' => $submission->id,
            'status' => WisudaRegistrationStatus::PENDING,
            'no_hp' => '08123456789',
            'email_aktif' => 'student@test.com',
            'submitted_at' => now(),
        ]);

        $this->actingAs($adminUser);

        // Admin checks show/detail page
        $showResponse = $this->get(route('admin.wisuda.show', $registration->id));
        $showResponse->assertStatus(200);

        // Admin approves the registration
        $approveResponse = $this->post(route('admin.wisuda.approve', $registration->id));
        $approveResponse->assertRedirect(route('admin.wisuda.show', $registration->id));

        $registration->refresh();
        $this->assertEquals(WisudaRegistrationStatus::APPROVED, $registration->status);
        $this->assertNotNull($registration->reviewed_at);
        $this->assertEquals($adminUser->id, $registration->reviewed_by);
    }

    /**
     * Test admin rejection flow.
     */
    public function test_admin_can_reject_registration_with_reason(): void
    {
        $adminUser = User::factory()->create(['role' => 'admin']);
        $studentUser = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa = Mahasiswa::create([
            'user_id' => $studentUser->id,
            'nim' => '12345678',
            'nama' => $studentUser->name,
            'prodi' => 'Ilmu Hukum',
            'angkatan' => '2022',
            'status' => 'aktif',
            'status_akun' => 'baru',
        ]);

        $submission = SkripsiSubmission::create([
            'mahasiswa_id' => $mahasiswa->id,
            'judul' => 'Judul Skripsi Test',
            'status' => SkripsiStatus::REVISION_APPROVED,
        ]);

        $registration = WisudaRegistration::create([
            'mahasiswa_id' => $mahasiswa->id,
            'skripsi_submission_id' => $submission->id,
            'status' => WisudaRegistrationStatus::PENDING,
            'no_hp' => '08123456789',
            'email_aktif' => 'student@test.com',
            'submitted_at' => now(),
        ]);

        $this->actingAs($adminUser);

        // Visit show page first to set referrer
        $this->get(route('admin.wisuda.show', $registration->id));

        // Admin rejects the registration
        $rejectResponse = $this->post(route('admin.wisuda.reject', $registration->id), [
            'rejection_note' => 'Dokumen Turnitin tidak terbaca.',
        ]);
        $rejectResponse->assertRedirect(route('admin.wisuda.show', $registration->id));

        $registration->refresh();
        $this->assertEquals(WisudaRegistrationStatus::REJECTED, $registration->status);
        $this->assertEquals('Dokumen Turnitin tidak terbaca.', $registration->rejection_note);
        $this->assertNotNull($registration->reviewed_at);
        $this->assertEquals($adminUser->id, $registration->reviewed_by);
    }

    /**
     * Test admin batch management and assigning approved students.
     */
    public function test_admin_can_create_batch_and_assign_approved_students(): void
    {
        $adminUser = User::factory()->create(['role' => 'admin']);
        $studentUser = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa = Mahasiswa::create([
            'user_id' => $studentUser->id,
            'nim' => '12345678',
            'nama' => $studentUser->name,
            'prodi' => 'Ilmu Hukum',
            'angkatan' => '2022',
            'status' => 'aktif',
            'status_akun' => 'baru',
        ]);

        $submission = SkripsiSubmission::create([
            'mahasiswa_id' => $mahasiswa->id,
            'judul' => 'Judul Skripsi Test',
            'status' => SkripsiStatus::REVISION_APPROVED,
        ]);

        $registration = WisudaRegistration::create([
            'mahasiswa_id' => $mahasiswa->id,
            'skripsi_submission_id' => $submission->id,
            'status' => WisudaRegistrationStatus::APPROVED, // approved, ready for scheduling
            'no_hp' => '08123456789',
            'email_aktif' => 'student@test.com',
            'submitted_at' => now(),
        ]);

        $this->actingAs($adminUser);

        // Step 1: Create batch
        $batchResponse = $this->post(route('admin.wisuda.batches.store'), [
            'nama_batch' => 'Wisuda Gelombang I 2026',
            'tanggal' => '2026-09-15',
            'waktu_mulai' => '08:00',
            'lokasi' => 'Aula Utama Kampus A',
            'catatan' => 'Harap hadir 30 menit sebelum acara.',
        ]);

        $batch = WisudaBatch::where('nama_batch', 'Wisuda Gelombang I 2026')->first();
        $this->assertNotNull($batch);

        $batchResponse->assertRedirect(route('admin.wisuda.batches.show', $batch->id));

        // Step 2: Assign approved student to batch
        $assignResponse = $this->from(route('admin.wisuda.batches.show', $batch->id))
            ->post(route('admin.wisuda.batches.assign', $batch->id), [
                'registration_ids' => [$registration->id],
            ]);

        $assignResponse->assertRedirect(route('admin.wisuda.batches.show', $batch->id));

        $registration->refresh();
        $this->assertEquals(WisudaRegistrationStatus::SCHEDULED, $registration->status);
        $this->assertEquals($batch->id, $registration->wisuda_batch_id);

        // Step 3: Verify notification in email_outboxes
        $this->assertDatabaseHas('email_outboxes', [
            'target_email' => 'student@test.com',
            'subject' => 'Jadwal Wisuda Anda',
        ]);
    }
}
