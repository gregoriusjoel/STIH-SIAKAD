<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\Semester;
use App\Models\Mahasiswa;
use App\Models\AcademicEvent;
use Illuminate\Http\Request;

class KrsController extends Controller
{
    public function index(Request $request)
    {
        $query = Krs::with(['mahasiswa.user', 'kelas.mataKuliah', 'kelas.dosen', 'kelas.jadwals']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $krsData = $query->orderBy('created_at', 'desc')->paginate(10);
        $semesterAktif = Semester::where('status', 'aktif')->first();
        if (! $semesterAktif) {
            $semesterAktif = Semester::where('is_active', true)->first();
        }
        if (! $semesterAktif) {
            $semesterAktif = Semester::whereNotNull('krs_mulai')->orWhereNotNull('krs_selesai')
                ->orderBy('tanggal_mulai', 'desc')
                ->first();
        }
        if (! $semesterAktif) {
            $semesterAktif = Semester::latest()->first();
        }
        // If semester exists but doesn't have KRS dates, try to find a KRS AcademicEvent for it
        if ($semesterAktif) {
            if (empty($semesterAktif->krs_mulai) || empty($semesterAktif->krs_selesai)) {
                // Try semester-scoped KRS event first
                $krsEvent = AcademicEvent::where('semester_id', $semesterAktif->id)
                    ->where('event_type', 'krs')
                    ->orderBy('id', 'desc')
                    ->first();
                // If no semester-scoped event found, fall back to the most recent global KRS event
                if (! $krsEvent) {
                    $krsEvent = AcademicEvent::where('event_type', 'krs')
                        ->orderBy('id', 'desc')
                        ->first();
                }
                if ($krsEvent) {
                    // Set on the model instance so the view can display them (no DB write)
                    $semesterAktif->krs_mulai = $semesterAktif->krs_mulai ?? $krsEvent->start_date;
                    $semesterAktif->krs_selesai = $semesterAktif->krs_selesai ?? $krsEvent->end_date;
                }
            }
        }

        return view('admin.krs.index', compact('krsData', 'semesterAktif'));
    }

    public function updateStatus(Request $request, Krs $kr)
    {
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak',
            'keterangan' => 'nullable|string',
        ]);

        $kr->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Status KRS berhasil diupdate');
    }

    public function destroy(Krs $kr)
    {
        try {
            $kr->delete();
            return redirect()->route('admin.krs.index')
                ->with('success', 'Data KRS berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reopen KRS for a mahasiswa so they can edit their KRS again.
     */
    public function reopenForStudent(Request $request, Mahasiswa $mahasiswa)
    {
        try {
            // Set all KRS records for this mahasiswa back to 'draft' so they can edit
            Krs::where('mahasiswa_id', $mahasiswa->id)->update([
                'status' => 'draft',
                'keterangan' => null,
            ]);

            return back()->with('success', 'KRS berhasil dibuka kembali untuk mahasiswa.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuka KRS: ' . $e->getMessage());
        }
    }
}
