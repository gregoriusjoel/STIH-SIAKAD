<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\SemesterLockedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttachMataKuliahRequest;
use App\Http\Requests\Admin\CarryForwardRequest;
use App\Http\Requests\Admin\RestoreMataKuliahRequest;
use App\Models\AuditLog;
use App\Models\MataKuliah;
use App\Models\Semester;
use App\Services\MataKuliahSemesterService;
use App\Services\SemesterService;
use Illuminate\Http\Request;

class MataKuliahSemesterController extends Controller
{
    public function __construct(
        protected MataKuliahSemesterService $service,
        protected SemesterService $semesterService,
    ) {}

    /* ─────────────────────────────────────────
     *  INDEX — TA Aktif + Histori (tab view)
     * ───────────────────────────────────────── */

    public function index(Request $request)
    {
        // Semua fitur sudah digabung ke halaman Mata Kuliah utama
        return redirect()->route('admin.mata-kuliah.index', [
            'tab'         => $request->input('tab', 'ta-aktif'),
            'semester_id' => $request->input('semester_id'),
        ]);
    }

    /* ─────────────────────────────────────────
     *  HISTORI (JSON endpoint for semester change)
     * ───────────────────────────────────────── */

    public function histori(Request $request)
    {
        $request->validate(['semester_id' => 'required|exists:semesters,id']);
        $semester = Semester::findOrFail($request->semester_id);

        $historyPivots = $this->service->getHistoryMKBySemester($semester->id);

        return view('admin.mata-kuliah-semester.partials.history-list', compact('historyPivots', 'semester'));
    }

    /* ─────────────────────────────────────────
     *  ATTACH MK TO SEMESTER
     * ───────────────────────────────────────── */

    public function attach(AttachMataKuliahRequest $request)
    {
        try {
            $result = $this->service->attachToSemester(
                $request->semester_id,
                $request->mata_kuliah_ids
            );

            $msg = "Berhasil menambahkan {$result['attached']} mata kuliah. ";
            if ($result['skipped'] > 0) {
                $msg .= "{$result['skipped']} sudah ada, dilewati.";
            }

            return redirect()->route('admin.mata-kuliah-semester.index', ['semester_id' => $request->semester_id])
                ->with('success', trim($msg));
        } catch (SemesterLockedException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /* ─────────────────────────────────────────
     *  DETACH (soft) MK FROM SEMESTER
     * ───────────────────────────────────────── */

    public function detach(Request $request)
    {
        $request->validate([
            'semester_id'      => 'required|exists:semesters,id',
            'mata_kuliah_ids'  => 'required|array|min:1',
            'mata_kuliah_ids.*'=> 'exists:mata_kuliahs,id',
        ]);

        try {
            $count = $this->service->detachFromSemester(
                $request->semester_id,
                $request->mata_kuliah_ids
            );

            return redirect()->route('admin.mata-kuliah-semester.index', ['semester_id' => $request->semester_id])
                ->with('success', "{$count} mata kuliah dipindahkan ke histori.");
        } catch (SemesterLockedException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /* ─────────────────────────────────────────
     *  CARRY FORWARD — PREVIEW
     * ───────────────────────────────────────── */

    public function carryForwardPreview(Request $request)
    {
        $request->validate([
            'source_semester_id' => 'required|exists:semesters,id',
            'target_semester_id' => 'required|exists:semesters,id',
        ]);

        $preview = $this->service->previewCarryForward(
            $request->source_semester_id,
            $request->target_semester_id
        );

        $source = Semester::find($request->source_semester_id);
        $target = Semester::find($request->target_semester_id);

        return response()->json([
            'to_copy'      => $preview['to_copy']->map(fn($mk) => [
                'id' => $mk->id,
                'kode_mk' => $mk->kode_mk,
                'nama_mk' => $mk->nama_mk,
                'sks' => $mk->sks,
                'prodi' => $mk->prodi?->nama_prodi,
            ]),
            'conflicts'    => $preview['conflicts']->map(fn($mk) => [
                'id' => $mk->id,
                'kode_mk' => $mk->kode_mk,
                'nama_mk' => $mk->nama_mk,
                'sks' => $mk->sks,
            ]),
            'source_total' => $preview['source_total'],
            'source_label' => $source?->display_label,
            'target_label' => $target?->display_label,
        ]);
    }

    /* ─────────────────────────────────────────
     *  CARRY FORWARD — EXECUTE
     * ───────────────────────────────────────── */

    public function carryForward(CarryForwardRequest $request)
    {
        try {
            $result = $this->service->carryForward(
                $request->source_semester_id,
                $request->target_semester_id
            );

            $msg = "Carry forward selesai: {$result['copied']} MK disalin. ";
            if ($result['skipped'] > 0) {
                $msg .= "{$result['skipped']} sudah ada di tujuan, dilewati.";
            }
            if (!empty($result['errors'])) {
                $msg .= ' Beberapa error terjadi: ' . implode(', ', $result['errors']);
                return redirect()->back()->with('error', $msg);
            }

            return redirect()->route('admin.mata-kuliah-semester.index', [
                'semester_id' => $request->target_semester_id,
            ])->with('success', trim($msg));
        } catch (SemesterLockedException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /* ─────────────────────────────────────────
     *  RESTORE FROM HISTORY
     * ───────────────────────────────────────── */

    public function restore(RestoreMataKuliahRequest $request)
    {
        try {
            $result = $this->service->restoreFromSemester(
                $request->source_semester_id,
                $request->target_semester_id,
                $request->mata_kuliah_ids
            );

            $msg = "Restored {$result['restored']} mata kuliah ke semester aktif. ";
            if ($result['skipped'] > 0) {
                $msg .= "{$result['skipped']} sudah aktif, dilewati.";
            }

            return redirect()->route('admin.mata-kuliah-semester.index', [
                'semester_id' => $request->target_semester_id,
            ])->with('success', trim($msg));
        } catch (SemesterLockedException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /* ─────────────────────────────────────────
     *  ATTACH BY KODE_MK (JSON — after import)
     * ───────────────────────────────────────── */

    public function attachByCodes(Request $request)
    {
        $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'kode_mks'    => 'required|array|min:1',
            'kode_mks.*'  => 'string|max:50',
        ]);

        try {
            $ids = MataKuliah::whereIn('kode_mk', $request->kode_mks)
                ->pluck('id')
                ->toArray();

            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada mata kuliah ditemukan dengan kode yang diberikan.',
                ], 404);
            }

            $result = $this->service->attachToSemester($request->semester_id, $ids);

            return response()->json([
                'success' => true,
                'message' => "Berhasil menambahkan {$result['attached']} MK ke semester. {$result['skipped']} sudah aktif, dilewati.",
                'result'  => $result,
            ]);
        } catch (SemesterLockedException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 403);
        }
    }

    /* ─────────────────────────────────────────
     *  SEMESTER LOCK / ACTIVATE
     * ───────────────────────────────────────── */

    public function activateSemester(Semester $semester)
    {
        $success = $this->semesterService->activateSemester($semester);

        if ($success) {
            return redirect()->route('admin.mata-kuliah-semester.index', ['semester_id' => $semester->id])
                ->with('success', "Semester \"{$semester->display_label}\" berhasil diaktifkan. Relasi MK lama dipindahkan ke histori.");
        }

        return redirect()->back()->with('error', 'Gagal mengaktifkan semester.');
    }

    public function lockSemester(Semester $semester)
    {
        $success = $this->semesterService->lockSemester($semester);

        if ($success) {
            return redirect()->route('admin.mata-kuliah-semester.index', ['semester_id' => $semester->id])
                ->with('success', "Semester \"{$semester->display_label}\" berhasil dikunci.");
        }

        return redirect()->back()->with('error', 'Gagal mengunci semester.');
    }

    public function unlockSemester(Semester $semester)
    {
        $success = $this->semesterService->unlockSemester($semester);

        if ($success) {
            return redirect()->route('admin.mata-kuliah-semester.index', ['semester_id' => $semester->id])
                ->with('success', "Semester \"{$semester->display_label}\" berhasil dibuka kuncinya.");
        }

        return redirect()->back()->with('error', 'Gagal membuka kunci semester.');
    }

    /* ─────────────────────────────────────────
     *  AUDIT LOG PAGE
     * ───────────────────────────────────────── */

    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('actor')
            ->orderBy('created_at', 'desc');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('actor_id')) {
            $query->where('actor_id', $request->actor_id);
        }
        if ($request->filled('entity_type')) {
            $query->where('auditable_type', 'like', "%{$request->entity_type}%");
        }

        $logs = $query->paginate(30)->withQueryString();

        $actions = AuditLog::distinct()->pluck('action');

        return view('admin.mata-kuliah-semester.audit-logs', compact('logs', 'actions'));
    }
}
