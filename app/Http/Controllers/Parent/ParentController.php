<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Krs;
use App\Models\Presensi;
use App\Models\Semester;
use App\Services\ParentStudentResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{
    public function __construct(private ParentStudentResolver $resolver) {}

    // ─── HELPERS ────────────────────────────────────────────────

    /**
     * Get the mahasiswa linked to the authenticated parent.
     * Aborts 403 if no link found.
     */
    private function getMahasiswa()
    {
        return $this->resolver->resolveOrAbort(Auth::user());
    }

    private function getBobot(?string $grade): float
    {
        return [
            'A'  => 4.0, 'A-' => 3.7,
            'B+' => 3.3, 'B'  => 3.0, 'B-' => 2.7,
            'C+' => 2.3, 'C'  => 2.0, 'C-' => 1.7,
            'D'  => 1.0, 'E'  => 0.0,
        ][$grade ?? ''] ?? 0.0;
    }

    /** Compute IPK / IPS from a collection of Krs (with nilai + kelasMataKuliah.mataKuliah loaded). */
    private function calculateIpk($krsCollection): float
    {
        $totalBobot = 0.0;
        $totalSks   = 0;

        foreach ($krsCollection as $krs) {
            if (! $krs->nilai) {
                continue;
            }
            $sks = $krs->kelasMataKuliah?->mataKuliah?->sks
                ?? $krs->mataKuliah?->sks
                ?? 0;
            $totalBobot += $this->getBobot($krs->nilai->grade) * $sks;
            $totalSks   += $sks;
        }

        return $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0.0;
    }

    // ─── PAGES ──────────────────────────────────────────────────

    public function dashboard()
    {
        $mahasiswa = $this->getMahasiswa();

        // Active semester: prefer the one attached to mahasiswa, fallback to latest active
        $activeSemester = Semester::where('is_active', true)->latest()->first();

        // KRS for active semester only
        $krsQuery = Krs::where('mahasiswa_id', $mahasiswa->id)->where('status', 'approved');
        if ($activeSemester) {
            $krsQuery->where(function ($q) use ($activeSemester) {
                $q->whereHas('kelasMataKuliah', fn($q2) => $q2->where('semester_id', $activeSemester->id))
                  ->orWhere('semester_id', $activeSemester->id);
            });
        }
        $activeKrs = $krsQuery->with(['kelasMataKuliah.mataKuliah', 'mataKuliah'])->get();

        $totalSks  = $activeKrs->sum(fn($k) => $k->kelasMataKuliah?->mataKuliah?->sks ?? $k->mataKuliah?->sks ?? 0);
        $jumlahMk  = $activeKrs->count();

        // IPK cumulative (all semesters)
        $allNilaiKrs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('nilai')
            ->with(['nilai', 'kelasMataKuliah.mataKuliah', 'mataKuliah'])
            ->get();
        $ipk = $this->calculateIpk($allNilaiKrs);

        // Presensi summary (hadir/izin/sakit/alfa) for active semester
        $presensiRaw = Presensi::where('mahasiswa_id', $mahasiswa->id)
            ->when($activeSemester, fn($q) =>
                $q->whereHas('krs', fn($q2) =>
                    $q2->whereHas('kelasMataKuliah', fn($q3) => $q3->where('semester_id', $activeSemester->id))
                )
            )
            ->get();

        $presensiStats = [
            'hadir' => $presensiRaw->where('status', 'hadir')->count(),
            'izin'  => $presensiRaw->where('status', 'izin')->count(),
            'sakit' => $presensiRaw->where('status', 'sakit')->count(),
            'alfa'  => $presensiRaw->whereIn('status', ['alfa', 'alpha', 'absen'])->count(),
            'total' => $presensiRaw->count(),
        ];

        // Latest invoice status  
        $latestInvoice  = Invoice::where('student_id', $mahasiswa->id)
            ->whereIn('status', ['PUBLISHED', 'LUNAS', 'PARTIAL'])
            ->latest('published_at')
            ->first();

        $statusPembayaran = match($latestInvoice?->status) {
            'LUNAS'     => 'lunas',
            'PARTIAL'   => 'sebagian',
            'PUBLISHED' => 'belum_bayar',
            default     => 'belum_ada',
        };

        return view('page.parent.dashboard', compact(
            'mahasiswa',
            'activeSemester',
            'totalSks',
            'jumlahMk',
            'ipk',
            'statusPembayaran',
            'latestInvoice',
            'presensiStats',
        ));
    }

    public function nilai()
    {
        $mahasiswa = $this->getMahasiswa();

        $allKrs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('nilai')
            ->with([
                'nilai',
                'kelasMataKuliah.mataKuliah',
                'kelasMataKuliah.semester',
                'mataKuliah',
                'semester',
            ])
            ->get();

        // Group by semester label for display
        $nilaiData = $allKrs->groupBy(function ($item) {
            if ($item->kelasMataKuliah?->semester) {
                return $item->kelasMataKuliah->semester->nama_semester;
            }
            if ($item->semester) {
                return $item->semester->nama_semester;
            }
            $semNo = $item->mataKuliah?->semester ?? $item->kelasMataKuliah?->mataKuliah?->semester;
            return $semNo ? "Semester {$semNo}" : 'Lainnya';
        });

        // IPS per semester
        $ipsPerSemester = $nilaiData->map(fn($items) => $this->calculateIpk($items));

        // IPK cumulative
        $ipk = $this->calculateIpk($allKrs);

        return view('page.parent.nilai', compact('mahasiswa', 'nilaiData', 'ipk', 'ipsPerSemester'));
    }

    public function jadwal()
    {
        $mahasiswa = $this->getMahasiswa();

        $activeSemester = Semester::where('is_active', true)->latest()->first();

        $activeKrs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'approved')
            ->when($activeSemester, fn($q) =>
                $q->where(function ($q2) use ($activeSemester) {
                    $q2->whereHas('kelasMataKuliah', fn($q3) => $q3->where('semester_id', $activeSemester->id))
                       ->orWhere('semester_id', $activeSemester->id);
                })
            )
            ->with([
                'kelasMataKuliah.mataKuliah',
                'kelasMataKuliah.dosen.user',
                'kelasMataKuliah.semester',
                'kelas.jadwals',
                'mataKuliah',
            ])
            ->get();

        return view('page.parent.jadwal', compact('mahasiswa', 'activeKrs', 'activeSemester'));
    }

    public function presensi()
    {
        $mahasiswa = $this->getMahasiswa();

        $activeSemester = Semester::where('is_active', true)->latest()->first();

        // Correct eager-load path: presensi → krs → kelasMataKuliah → mataKuliah
        $presensiData = Presensi::where('mahasiswa_id', $mahasiswa->id)
            ->when($activeSemester, fn($q) =>
                $q->whereHas('krs', fn($q2) =>
                    $q2->whereHas('kelasMataKuliah', fn($q3) => $q3->where('semester_id', $activeSemester->id))
                )
            )
            ->with([
                'krs.kelasMataKuliah.mataKuliah',
                'krs.kelasMataKuliah.jadwal',
            ])
            ->orderByDesc('tanggal')
            ->paginate(15);

        // Per-semester attendance stats
        $presensiStats = [
            'hadir' => Presensi::where('mahasiswa_id', $mahasiswa->id)->where('status', 'hadir')->count(),
            'izin'  => Presensi::where('mahasiswa_id', $mahasiswa->id)->where('status', 'izin')->count(),
            'sakit' => Presensi::where('mahasiswa_id', $mahasiswa->id)->where('status', 'sakit')->count(),
            'alfa'  => Presensi::where('mahasiswa_id', $mahasiswa->id)->whereIn('status', ['alfa', 'alpha', 'absen'])->count(),
        ];
        $presensiStats['total'] = array_sum($presensiStats);

        return view('page.parent.presensi', compact('mahasiswa', 'presensiData', 'presensiStats', 'activeSemester'));
    }

    public function pembayaran()
    {
        $mahasiswa = $this->getMahasiswa();

        // Use Invoice model (the current payment system)
        $invoices = Invoice::where('student_id', $mahasiswa->id)
            ->whereIn('status', ['PUBLISHED', 'LUNAS', 'PARTIAL'])
            ->with(['payments', 'installments', 'paymentProofs'])
            ->orderByDesc('published_at')
            ->get();

        // Summary stats
        $totalTagihan  = $invoices->sum('total_tagihan');
        $totalTerbayar = $invoices->sum(fn($inv) => $inv->total_paid ?? 0);
        $sisaTagihan   = $totalTagihan - $totalTerbayar;

        return view('page.parent.pembayaran', compact(
            'mahasiswa',
            'invoices',
            'totalTagihan',
            'totalTerbayar',
            'sisaTagihan',
        ));
    }
}
