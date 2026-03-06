<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    // ── Shared query ────────────────────────────────────────────────────────────

    /**
     * Fetch all published nilai KRS for the current mahasiswa.
     * Loads both kelasMataKuliah path AND direct mataKuliah + semester
     * so internship conversion KRS (where kelasMataKuliah is null) are handled correctly.
     */
    private function fetchNilaiData($mahasiswa)
    {
        return $mahasiswa->krs()
            ->with([
                'kelasMataKuliah.mataKuliah',
                'kelasMataKuliah.semester',
                'mataKuliah',           // ← direct link for internship conversion
                'semester',             // ← direct semester for internship conversion (krs.semester_id)
                'nilai' => fn($q) => $q->where('is_published', true),
                'nilai.bobotPenilaian',
            ])
            ->whereHas('nilai', fn($q) => $q->where('is_published', true))
            ->get();
    }

    /**
     * Resolve semester key for groupBy.
     *
     * Priority:
     * 1. kelasMataKuliah.semester.nama_semester  (normal KRS)
     * 2. krs.semester.nama_semester              (internship conversion with semester_id set)
     * 3. "Semester X"                            (fallback from mataKuliah.semester number)
     */
    private function semesterKey($krs): string
    {
        // Normal KRS path
        if ($krs->kelasMataKuliah && $krs->kelasMataKuliah->semester) {
            return $krs->kelasMataKuliah->semester->nama_semester;
        }

        // Internship conversion path — krs.semester_id set by InternshipKrsService
        if ($krs->semester) {
            return $krs->semester->nama_semester;
        }

        // Last fallback: use MK semester number
        $semNo = $krs->mataKuliah->semester
              ?? $krs->kelasMataKuliah?->mataKuliah?->semester;

        return $semNo ? "Semester {$semNo}" : 'Lainnya';
    }

    /**
     * Resolve SKS for a KRS entry.
     * Normal: kelasMataKuliah.mataKuliah.sks
     * Internship: mataKuliah.sks (direct)
     */
    private function getSks($krs): int
    {
        return $krs->kelasMataKuliah?->mataKuliah?->sks
            ?? $krs->mataKuliah?->sks
            ?? 0;
    }

    /**
     * Build IPS per semester and IPK from a collection of nilai data.
     */
    private function buildStats($nilaiData): array
    {
        $nilaiPerSemester = $nilaiData->groupBy(fn($item) => $this->semesterKey($item));

        $totalBobot = 0;
        $totalSks   = 0;

        foreach ($nilaiData as $krs) {
            if ($krs->nilai) {
                $bobot = $krs->nilai->bobot ?? $this->getBobot($krs->nilai->nilai_akhir ?? 0);
                $sks   = $this->getSks($krs);
                $totalBobot += ($bobot * $sks);
                $totalSks   += $sks;
            }
        }

        $ipk = $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;

        $ipsPerSemester = [];
        foreach ($nilaiPerSemester as $semesterNama => $nilaiList) {
            $semBobot = 0;
            $semSks   = 0;
            foreach ($nilaiList as $krs) {
                if ($krs->nilai) {
                    $bobot   = $krs->nilai->bobot ?? $this->getBobot($krs->nilai->nilai_akhir ?? 0);
                    $sks     = $this->getSks($krs);
                    $semBobot += ($bobot * $sks);
                    $semSks   += $sks;
                }
            }
            $ipsPerSemester[$semesterNama] = [
                'ips' => $semSks > 0 ? round($semBobot / $semSks, 2) : 0,
                'sks' => $semSks,
            ];
        }

        return [$nilaiPerSemester, $ipk, $totalSks, $ipsPerSemester];
    }

    // ── Actions ─────────────────────────────────────────────────────────────────

    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $nilaiData = $this->fetchNilaiData($mahasiswa);

        [$nilaiPerSemester, $ipk, $totalSks, $ipsPerSemester] = $this->buildStats($nilaiData);

        return view('page.mahasiswa.nilai.index', compact(
            'mahasiswa', 'nilaiPerSemester', 'ipk', 'totalSks', 'ipsPerSemester'
        ));
    }

    public function khs()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $nilaiData = $this->fetchNilaiData($mahasiswa);

        [$nilaiPerSemester, $ipk, $totalSks, $ipsPerSemester] = $this->buildStats($nilaiData);

        return view('page.mahasiswa.khs.index', compact(
            'mahasiswa', 'nilaiPerSemester', 'ipk', 'totalSks', 'ipsPerSemester'
        ));
    }

    public function print()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $nilaiData = $this->fetchNilaiData($mahasiswa);

        [$nilaiPerSemester, $ipk, $totalSks, $ipsPerSemester] = $this->buildStats($nilaiData);

        return view('page.mahasiswa.nilai.print', compact(
            'mahasiswa', 'nilaiPerSemester', 'ipk', 'totalSks', 'ipsPerSemester'
        ));
    }

    public function getBobot($nilai)
    {
        if ($nilai >= 80) return 4.00;
        if ($nilai >= 76) return 3.67;
        if ($nilai >= 72) return 3.33;
        if ($nilai >= 68) return 3.00;
        if ($nilai >= 64) return 2.67;
        if ($nilai >= 60) return 2.33;
        if ($nilai >= 56) return 2.00;
        if ($nilai >= 45) return 1.00;
        return 0;
    }

    public function getGrade($nilai)
    {
        if ($nilai >= 80) return 'A';
        if ($nilai >= 76) return 'A-';
        if ($nilai >= 72) return 'B+';
        if ($nilai >= 68) return 'B';
        if ($nilai >= 64) return 'B-';
        if ($nilai >= 60) return 'C+';
        if ($nilai >= 56) return 'C';
        if ($nilai >= 45) return 'D';
        return 'E';
    }
}
