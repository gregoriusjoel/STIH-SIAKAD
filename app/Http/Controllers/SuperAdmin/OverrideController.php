<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Krs;
use App\Models\Invoice;
use App\Models\Internship;
use App\Models\Mahasiswa;
use App\Models\SkripsiSubmission;
use App\Models\WisudaRegistration;
use App\Models\AuditLog;
use App\Services\SuperAdmin\AcademicOverrideService;
use App\Services\SuperAdmin\FinancialOverrideService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OverrideController extends Controller
{
    // ════════════════════════════════════════════════════════════════════════
    // OVERRIDE CENTER PAGES (GET)
    // ════════════════════════════════════════════════════════════════════════

    public function academicCenter(Request $request)
    {
        $query = Nilai::with(['krs.mahasiswa.user', 'krs.mataKuliah'])
            ->whereHas('krs');

        if ($request->filled('nim_filter')) {
            $nim = $request->nim_filter;
            $query->whereHas('krs.mahasiswa', fn($q) => $q->where('nim', $nim));
        }

        if ($request->filled('grade_filter')) {
            $query->where('grade', $request->grade_filter);
        }

        $nilais = $query->orderByDesc('updated_at')->paginate(20);

        $krsList = Krs::with(['mahasiswa.user', 'mataKuliah'])
            ->orderByDesc('updated_at')
            ->paginate(15, ['*'], 'krs_page');

        $mahasiswas     = Mahasiswa::with('user')->orderBy('nim')->get();
        $totalMahasiswa = Mahasiswa::count();
        $totalKrs       = Krs::count();
        $totalNilai     = Nilai::whereNotNull('nilai_akhir')->count();
        $nilaiE         = Nilai::where('grade', 'E')->count();

        return view('super-admin.override.academic-center', compact(
            'nilais', 'krsList', 'mahasiswas',
            'totalMahasiswa', 'totalKrs', 'totalNilai', 'nilaiE'
        ));
    }

    public function financialCenter(Request $request)
    {
        $query = Invoice::with(['student.user'])->orderByDesc('created_at');

        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('student', fn($q2) => $q2->where('nim', 'like', "%{$s}%"))
                  ->orWhereHas('student.user', fn($q2) => $q2->where('name', 'like', "%{$s}%"));
            });
        }

        $invoices = $query->paginate(25);

        $stats = [
            'draft'          => Invoice::where('status', 'DRAFT')->count(),
            'published'      => Invoice::where('status', 'PUBLISHED')->count(),
            'in_installment' => Invoice::where('status', 'IN_INSTALLMENT')->count(),
            'lunas'          => Invoice::where('status', 'LUNAS')->count(),
            'total_tagihan'  => Invoice::whereIn('status', ['PUBLISHED', 'IN_INSTALLMENT'])->sum('total_tagihan'),
        ];

        return view('super-admin.override.financial-center', compact('invoices', 'stats'));
    }

    public function internshipCenter(Request $request)
    {
        $internships = Internship::with(['mahasiswa.user'])
            ->orderByDesc('created_at')
            ->paginate(25);
        return view('super-admin.override.internship-center', compact('internships'));
    }

    public function thesisCenter(Request $request)
    {
        $query = SkripsiSubmission::with(['mahasiswa.user', 'approvedSupervisor.user'])
            ->orderByDesc('updated_at');

        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }

        $submissions = $query->paginate(25);
        $dosens      = \App\Models\Dosen::with('user')->orderBy('id')->get();

        return view('super-admin.override.thesis-center', compact('submissions', 'dosens'));
    }

    public function graduationCenter(Request $request)
    {
        $query = WisudaRegistration::with(['mahasiswa.user', 'batch', 'documents'])
            ->orderByDesc('created_at');

        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }
        if ($request->filled('batch_filter')) {
            $query->where('wisuda_batch_id', $request->batch_filter);
        }

        $registrations = $query->paginate(25);

        $stats = [
            'pending'   => WisudaRegistration::where('status', 'pending')->count(),
            'approved'  => WisudaRegistration::where('status', 'approved')->count(),
            'rejected'  => WisudaRegistration::where('status', 'rejected')->count(),
            'scheduled' => WisudaRegistration::where('status', 'scheduled')->count(),
        ];

        $batches = \App\Models\WisudaBatch::orderByDesc('tanggal')->get();

        return view('super-admin.override.graduation-center', compact('registrations', 'stats', 'batches'));
    }

    // ════════════════════════════════════════════════════════════════════════
    // OVERRIDE POST ENDPOINTS
    // ════════════════════════════════════════════════════════════════════════

    public function overrideGrade(Request $request, Nilai $nilai)
    {
        $request->validate([
            'nilai_akhir'     => 'required|numeric|min:0|max:100',
            'override_reason' => 'required|string|min:10|max:500',
        ]);

        $service = app(AcademicOverrideService::class);
        $result  = $service->overrideGrade(
            $nilai,
            (float) $request->nilai_akhir,
            $request->override_reason
        );

        $ipkInfo = $result['ipk_estimate'] !== null
            ? " | Estimasi IPK baru: {$result['ipk_estimate']}"
            : '';

        return back()->with('success', "Nilai berhasil di-override ke {$result['after']['nilai_akhir']} (Grade: {$result['after']['grade']}){$ipkInfo}");
    }

    public function overrideKrs(Request $request, Krs $krs)
    {
        $request->validate([
            'status'          => 'required|in:draft,sudah submit,approved,rejected',
            'override_reason' => 'required|string|min:10|max:500',
        ]);

        $service = app(AcademicOverrideService::class);
        $service->overrideKrsStatus($krs, $request->status, $request->override_reason);

        return back()->with('success', "Status KRS berhasil di-override ke '{$request->status}'.");
    }

    public function overrideInvoice(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status'          => 'required|in:DRAFT,PUBLISHED,IN_INSTALLMENT,LUNAS',
            'override_reason' => 'required|string|min:10|max:500',
        ]);

        $service = app(FinancialOverrideService::class);
        $result  = $service->overrideStatus(
            $invoice,
            $request->status,
            $request->override_reason,
            auth()->id()
        );

        $paymentMsg = $result['payment_created'] ? ' (Payment record dibuat otomatis)' : '';

        return back()->with('success', "Invoice berhasil di-override ke '{$request->status}'{$paymentMsg}.");
    }

    public function overrideInternship(Request $request, Internship $internship)
    {
        $request->validate([
            'status'          => 'required|string',
            'override_reason' => 'required|string|min:10|max:500',
        ]);

        // Super Admin bypasses normal state machine — can override to ANY valid status
        $validStatuses = array_keys(Internship::STATUS_LABELS);
        if (!in_array($request->status, $validStatuses)) {
            return back()->with('error', 'Status magang tidak valid.');
        }

        $oldData = $internship->only(['status', 'admin_note']);

        DB::transaction(function () use ($internship, $oldData, $request) {
            $internship->update([
                'status' => $request->status,
                'admin_note' => $request->override_reason
            ]);

            AuditLog::log(
                action: 'internship.override',
                auditable: $internship,
                meta: ['reason' => $request->override_reason, 'mahasiswa_id' => $internship->mahasiswa_id],
                before: $oldData,
                after: [
                    'status' => $request->status,
                    'admin_note' => $request->override_reason
                ]
            );
        });

        return back()->with('success', 'Status Magang berhasil di-override.');
    }

    public function overrideSkripsi(Request $request, SkripsiSubmission $skripsi)
    {
        $request->validate([
            'status'          => 'required|string|in:' . implode(',', array_column(\App\Domain\Skripsi\Enums\SkripsiStatus::cases(), 'value')),
            'admin_note'      => 'nullable|string|max:1000',
            'override_reason' => 'required|string|min:10|max:500',
        ]);

        $oldData = [
            'status' => $skripsi->status instanceof \BackedEnum ? $skripsi->status->value : $skripsi->status,
            'admin_note' => $skripsi->admin_note,
        ];

        DB::transaction(function () use ($skripsi, $oldData, $request) {
            $adminNote = $request->admin_note ?? $request->override_reason;
            DB::table('skripsi_submissions')
                ->where('id', $skripsi->id)
                ->update([
                    'status' => $request->status,
                    'admin_note' => $adminNote,
                    'updated_at' => now()
                ]);

            AuditLog::log(
                action: 'skripsi.override',
                auditable: $skripsi,
                meta: ['reason' => $request->override_reason],
                before: $oldData,
                after: [
                    'status' => $request->status,
                    'admin_note' => $adminNote,
                ]
            );
        });

        return back()->with('success', 'Status Skripsi berhasil di-override.');
    }

    public function overrideWisuda(Request $request, WisudaRegistration $wisuda)
    {
        $request->validate([
            'status'          => 'required|in:pending,approved,rejected,scheduled',
            'wisuda_batch_id' => 'required|exists:wisuda_batches,id',
            'override_reason' => 'required|string|min:10|max:500',
        ]);

        $oldData = $wisuda->only(['status', 'wisuda_batch_id', 'rejection_note']);

        DB::transaction(function () use ($wisuda, $oldData, $request) {
            DB::table('wisuda_registrations')->where('id', $wisuda->id)->update([
                'status'          => $request->status,
                'wisuda_batch_id' => $request->wisuda_batch_id,
                'rejection_note'  => $request->override_reason,
                'reviewed_by'     => auth()->id(),
                'reviewed_at'     => now(),
            ]);

            AuditLog::log(
                action: 'wisuda.override',
                auditable: $wisuda,
                meta: ['reason' => $request->override_reason],
                before: $oldData,
                after: [
                    'status'          => $request->status,
                    'wisuda_batch_id' => $request->wisuda_batch_id,
                ]
            );
        });

        return back()->with('success', 'Status & Batch Wisuda berhasil di-override.');
    }
}
