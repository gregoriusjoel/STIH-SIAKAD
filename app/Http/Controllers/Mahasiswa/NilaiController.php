<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

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
     * Resolve semester number for a KRS entry.
     */
    private function getSemesterNumber($krs): ?int
    {
        $number = $krs->kelasMataKuliah?->semester?->semester_number
            ?? $krs->semester?->semester_number
            ?? $krs->mataKuliah?->semester
            ?? $krs->kelasMataKuliah?->mataKuliah?->semester;

        if ($number === null || $number === '') {
            return null;
        }

        return is_numeric($number) ? (int) $number : null;
    }

    /**
     * Build semester dropdown options using mahasiswa semester numbering (1,2,3,...).
     */
    private function buildSemesterOptions($nilaiData): array
    {
        return $nilaiData
            ->map(fn($krs) => $this->getSemesterNumber($krs))
            ->filter(fn($n) => $n !== null)
            ->unique()
            ->sort()
            ->values()
            ->map(fn($n) => [
                'value' => (string) $n,
                'label' => 'Semester ' . $n,
            ])
            ->toArray();
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
        $semesterOptions = $this->buildSemesterOptions($nilaiData);

        [$nilaiPerSemester, $ipk, $totalSks, $ipsPerSemester] = $this->buildStats($nilaiData);

        return view('page.mahasiswa.nilai.index', compact(
            'mahasiswa', 'nilaiPerSemester', 'ipk', 'totalSks', 'ipsPerSemester', 'semesterOptions'
        ));
    }

    public function khs()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $nilaiData = $this->fetchNilaiData($mahasiswa);
        $semesterOptions = $this->buildSemesterOptions($nilaiData);

        [$nilaiPerSemester, $ipk, $totalSks, $ipsPerSemester] = $this->buildStats($nilaiData);

        return view('page.mahasiswa.khs.index', compact(
            'mahasiswa', 'nilaiPerSemester', 'ipk', 'totalSks', 'ipsPerSemester', 'semesterOptions'
        ));
    }

    public function print(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $nilaiData = $this->fetchNilaiData($mahasiswa);

        $selectedSemesterNumber = (int) $request->query('semester', 0);
        $isSemesterFiltered = $selectedSemesterNumber > 0;

        if ($isSemesterFiltered) {
            $nilaiData = $nilaiData
                ->filter(fn($krs) => $this->getSemesterNumber($krs) === $selectedSemesterNumber)
                ->values();
        }

        [$nilaiPerSemester, $ipk, $totalSks, $ipsPerSemester] = $this->buildStats($nilaiData);

        $selectedSemester = $isSemesterFiltered ? ('Semester ' . $selectedSemesterNumber) : '';

        $pdf = PDF::loadView('page.mahasiswa.nilai.pdf', compact(
            'mahasiswa',
            'nilaiPerSemester',
            'ipk',
            'totalSks',
            'ipsPerSemester',
            'selectedSemester',
            'isSemesterFiltered'
        ))->setPaper('a4', 'portrait');

        $nim = $mahasiswa->nim ?? 'mahasiswa';
        $filename = 'KHS-' . $nim . '.pdf';

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function printRangkuman(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $mahasiswa->loadMissing(['user', 'dosenPa.user']);

        $allNilaiData = $this->fetchNilaiData($mahasiswa);
        $selectedSemesterNumber = (int) $request->query('semester', 0);
        $isSemesterFiltered = $selectedSemesterNumber > 0;

        $nilaiData = $isSemesterFiltered
            ? $allNilaiData->filter(fn($krs) => $this->getSemesterNumber($krs) === $selectedSemesterNumber)->values()
            : $allNilaiData->values();

        $nilaiData = $nilaiData
            ->sortBy(function ($krs) {
                $mk = $krs->kelasMataKuliah?->mataKuliah ?? $krs->mataKuliah;
                $semesterNumber = $this->getSemesterNumber($krs) ?? 999;
                $kodeMk = strtoupper($mk->kode_mk ?? 'ZZZ');

                return sprintf('%03d-%s', $semesterNumber, $kodeMk);
            })
            ->values();

        $rows = $nilaiData
            ->map(function ($krs, $index) {
                $mk = $krs->kelasMataKuliah?->mataKuliah ?? $krs->mataKuliah;
                $nilai = $krs->nilai;

                if (!$mk || !$nilai) {
                    return null;
                }

                $nilaiAngka = (float) ($nilai->nilai_akhir ?? $nilai->nilai ?? 0);
                $sks = $this->getSks($krs);
                $bobotDasar = (float) ($nilai->bobot ?? $this->getBobot($nilaiAngka));
                $mutu = $bobotDasar * $sks;

                return [
                    'no' => $index + 1,
                    'kode_mk' => $mk->kode_mk ?? '-',
                    'nama_mk' => $mk->nama_mk ?? '-',
                    'sks' => $sks,
                    'nilai_huruf' => $this->resolveGrade($nilaiAngka, $nilai->grade ?? null),
                    'mutu' => $mutu,
                    'bobot' => $nilaiAngka,
                ];
            })
            ->filter()
            ->values();

        $totalSks = (int) $rows->sum('sks');
        $totalMutu = (float) $rows->sum('mutu');
        $ips = $totalSks > 0 ? round($totalMutu / $totalSks, 2) : 0;

        // Saat cetak semua semester, IPS mengikuti semester terbaru (bukan rerata kumulatif).
        if (!$isSemesterFiltered) {
            $latestSemester = $allNilaiData
                ->map(fn($krs) => $this->getSemesterNumber($krs))
                ->filter(fn($n) => $n !== null)
                ->max();

            if ($latestSemester !== null) {
                $latestSemesterData = $allNilaiData
                    ->filter(fn($krs) => $this->getSemesterNumber($krs) === (int) $latestSemester)
                    ->values();

                $latestRows = $latestSemesterData
                    ->map(function ($krs) {
                        $mk = $krs->kelasMataKuliah?->mataKuliah ?? $krs->mataKuliah;
                        $nilai = $krs->nilai;

                        if (!$mk || !$nilai) {
                            return null;
                        }

                        $nilaiAngka = (float) ($nilai->nilai_akhir ?? $nilai->nilai ?? 0);
                        $sks = $this->getSks($krs);
                        $bobotDasar = (float) ($nilai->bobot ?? $this->getBobot($nilaiAngka));

                        return [
                            'sks' => $sks,
                            'mutu' => $bobotDasar * $sks,
                        ];
                    })
                    ->filter()
                    ->values();

                $latestSks = (int) $latestRows->sum('sks');
                $latestMutu = (float) $latestRows->sum('mutu');

                if ($latestSks > 0) {
                    $ips = round($latestMutu / $latestSks, 2);
                }
            }
        }

        [, $ipk] = $this->buildStats($allNilaiData);

        $firstKrs = $nilaiData->first();
        $semester = $firstKrs?->kelasMataKuliah?->semester ?? $firstKrs?->semester;
        $currentSemester = $mahasiswa->getCurrentSemesterInfo();

        $tahunAjaran = $semester?->tahun_ajaran
            ?? $currentSemester->tahun_ajaran
            ?? '-';

        $semesterLabel = $semester?->nama_semester
            ?? ($isSemesterFiltered ? ($selectedSemesterNumber % 2 === 0 ? 'Genap' : 'Ganjil') : ($currentSemester->nama_semester ?? '-'));
        $semesterNumber = $nilaiData
            ->map(fn($krs) => $this->getSemesterNumber($krs))
            ->filter(fn($n) => $n !== null)
            ->max();
        $semesterNumber = $semesterNumber ?? ($currentSemester->semester_number ?? null);

        $prodi = $mahasiswa->prodi ?? '-';
        $dosenPa = $mahasiswa->dosenPa->first()?->user?->name ?? '-';

        $pdf = PDF::loadView('page.mahasiswa.nilai.rangkuman-pdf', [
            'mahasiswa' => $mahasiswa,
            'rows' => $rows,
            'tahunAjaran' => $tahunAjaran,
            'semesterLabel' => $semesterLabel,
            'semesterNumber' => $semesterNumber,
            'prodi' => $prodi,
            'dosenPa' => $dosenPa,
            'totalSks' => $totalSks,
            'totalMutu' => $totalMutu,
            'ips' => $ips,
            'ipk' => $ipk,
            'sksMaksimalSemesterDepan' => 24,
        ])->setPaper('a4', 'portrait');

        $nim = $mahasiswa->nim ?? 'mahasiswa';
        $filename = 'Rangkuman-Nilai-' . $nim . '.pdf';

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    private function resolveGrade(float $nilaiAngka, ?string $grade): string
    {
        if (!empty($grade)) {
            return $grade;
        }

        if ($nilaiAngka >= 80) return 'A';
        if ($nilaiAngka >= 76) return 'A-';
        if ($nilaiAngka >= 72) return 'B+';
        if ($nilaiAngka >= 68) return 'B';
        if ($nilaiAngka >= 64) return 'B-';
        if ($nilaiAngka >= 60) return 'C+';
        if ($nilaiAngka >= 56) return 'C';
        if ($nilaiAngka >= 45) return 'D';

        return 'E';
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
