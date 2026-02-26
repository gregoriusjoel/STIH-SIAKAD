<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\TugasSubmission;
use App\Models\Kelas;
use App\Models\Krs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class NilaiTugasController extends Controller
{
    // ─── Helpers ────────────────────────────────────────────────────────
    private function kelasDosen(int $kelasId): Kelas
    {
        $dosen = Auth::user()->dosen;
        $kelas = Kelas::with('mataKuliah', 'dosen.user')->findOrFail($kelasId);

        // Dosen hanya bisa mengelola kelas miliknya sendiri
        abort_if($kelas->dosen_id !== $dosen?->id, 403, 'Anda tidak berhak mengakses kelas ini.');

        return $kelas;
    }

    /** Ambil daftar mahasiswa aktif di kelas ini (dari KRS approved) */
    private function mahasiswaKelas(int $kelasId): \Illuminate\Support\Collection
    {
        return Krs::with(['mahasiswa.user'])
            ->where('kelas_id', $kelasId)
            ->whereIn('status', ['approved', 'disetujui'])
            ->get()
            ->map(fn($krs) => $krs->mahasiswa)
            ->filter()
            ->sortBy(fn($m) => $m->user?->name ?? '')
            ->values();
    }

    // ─── Halaman Utama: Daftar Tugas ───────────────────────────────────
    public function index(int $kelasId)
    {
        $kelas = $this->kelasDosen($kelasId);

        // Ambil semua tugas untuk kelas ini, diurutkan per pertemuan
        $tugasList = Tugas::where(function($q) use ($kelasId, $kelas) {
                $q->where('kelas_id', $kelasId)
                  ->orWhere('mata_kuliah_id', $kelas->mata_kuliah_id);
            })
            ->orderBy('pertemuan')
            ->orderBy('created_at')
            ->get();

        // Group tugas by pertemuan untuk tampilan yang lebih rapi
        $tugasGrouped = $tugasList->groupBy('pertemuan');

        return view('page.dosen.nilai-tugas.index', compact('kelas', 'tugasList', 'tugasGrouped'));
    }

    // ─── Input Nilai untuk Tugas Tertentu ──────────────────────────────
    public function inputNilai(int $kelasId, int $tugasId)
    {
        $kelas = $this->kelasDosen($kelasId);
        $tugas = Tugas::where(function($q) use ($kelasId, $kelas) {
                $q->where('kelas_id', $kelasId)
                  ->orWhere('mata_kuliah_id', $kelas->mata_kuliah_id);
            })->findOrFail($tugasId);
        
        // Set default max_score if null
        if (!$tugas->max_score) {
            $tugas->max_score = 100;
        }

        $mahasiswas = $this->mahasiswaKelas($kelasId);

        // Ambil submissions yang sudah ada
        $submissions = TugasSubmission::where('tugas_id', $tugasId)
            ->get()
            ->keyBy('mahasiswa_id');

        // Daftar semua tugas (untuk dropdown "ganti tugas")
        $tugasList = Tugas::where(function($q) use ($kelasId, $kelas) {
                $q->where('kelas_id', $kelasId)
                  ->orWhere('mata_kuliah_id', $kelas->mata_kuliah_id);
            })
            ->orderBy('pertemuan')
            ->orderBy('created_at')
            ->get();

        return view('page.dosen.nilai-tugas.input-nilai', compact(
            'kelas', 'tugas', 'mahasiswas', 'submissions', 'tugasList'
        ));
    }

    // ─── Simpan Nilai (Bulk Update) ─────────────────────────────────────
    public function simpanNilai(Request $request, int $kelasId, int $tugasId)
    {
        $kelas = $this->kelasDosen($kelasId);
        $tugas = Tugas::where(function($q) use ($kelasId, $kelas) {
                $q->where('kelas_id', $kelasId)
                  ->orWhere('mata_kuliah_id', $kelas->mata_kuliah_id);
            })->findOrFail($tugasId);
        $dosen = Auth::user()->dosen;
        
        // Set default max_score if null
        $maxScore = $tugas->max_score ?? 100;

        $request->validate([
            'scores'                => 'required|array',
            'scores.*.mahasiswa_id' => 'required|exists:mahasiswas,id',
            'scores.*.score'        => ['nullable', 'numeric', 'min:0', "max:{$maxScore}"],
            'scores.*.comments'     => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $tugas, $dosen) {
            foreach ($request->input('scores', []) as $row) {
                // Skip jika score kosong/null
                if ($row['score'] === '' || $row['score'] === null) {
                    // Hapus submission jika ada (reset nilai)
                    TugasSubmission::where('tugas_id', $tugas->id)
                        ->where('mahasiswa_id', $row['mahasiswa_id'])
                        ->delete();
                    continue;
                }

                TugasSubmission::updateOrCreate(
                    [
                        'tugas_id'      => $tugas->id,
                        'mahasiswa_id'  => $row['mahasiswa_id'],
                    ],
                    [
                        'score'      => $row['score'],
                        'comments'   => $row['comments'] ?? null,
                        'graded_by'  => $dosen?->id,
                        'graded_at'  => now(),
                        'file_path'  => TugasSubmission::where('tugas_id', $tugas->id)
                            ->where('mahasiswa_id', $row['mahasiswa_id'])
                            ->value('file_path'), // Keep existing file if any
                    ]
                );
            }
        });

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Nilai berhasil disimpan.']);
        }

        return back()->with('success', 'Nilai berhasil disimpan.');
    }

    // ─── Reset Semua Nilai Tugas ────────────────────────────────────────
    public function resetNilai(int $kelasId, int $tugasId)
    {
        $kelas = $this->kelasDosen($kelasId);
        $tugas = Tugas::where(function($q) use ($kelasId, $kelas) {
                $q->where('kelas_id', $kelasId)
                  ->orWhere('mata_kuliah_id', $kelas->mata_kuliah_id);
            })->findOrFail($tugasId);

        // Hapus semua submissions (atau hanya reset score kolom)
        TugasSubmission::where('tugas_id', $tugasId)
            ->update(['score' => null, 'graded_by' => null, 'graded_at' => null, 'comments' => null]);

        return back()->with('success', 'Nilai berhasil direset.');
    }

    // ─── Rekap Nilai Semua Tugas ────────────────────────────────────────
    public function rekap(int $kelasId)
    {
        $kelas       = $this->kelasDosen($kelasId);
        $filterPertemuan = request('filter_pertemuan'); // Optional filter by pertemuan

        $tugasQuery = Tugas::where('kelas_id', $kelasId)
            ->orderBy('pertemuan')
            ->orderBy('created_at');

        if ($filterPertemuan) {
            $tugasQuery->where('pertemuan', $filterPertemuan);
        }

        $tugasList = $tugasQuery->get();
        $mahasiswas = $this->mahasiswaKelas($kelasId);

        // Ambil semua submission sekaligus untuk efisiensi
        $allSubmissions = TugasSubmission::whereIn('tugas_id', $tugasList->pluck('id'))
            ->get()
            ->groupBy('mahasiswa_id');

        // Susun matrix: mahasiswa → [tugas_id => score]
        $rekapRows = $mahasiswas->map(function ($m) use ($tugasList, $allSubmissions) {
            $submissions = $allSubmissions->get($m->id, collect())->keyBy('tugas_id');
            $nilaiPerTugas = $tugasList->mapWithKeys(fn($t) => [
                $t->id => $submissions->get($t->id)?->score,
            ]);

            // Hitung total & rata-rata
            $filled = $nilaiPerTugas->filter(fn($v) => $v !== null);
            $total  = $filled->sum();
            $avg    = $filled->count() > 0 ? round($filled->avg(), 2) : null;

            return [
                'mahasiswa' => $m,
                'nilai'     => $nilaiPerTugas,
                'total'     => round($total, 2),
                'avg'       => $avg,
            ];
        });

        // Sort
        $sort = request('sort', 'nama');
        $rekapRows = match($sort) {
            'total_desc' => $rekapRows->sortByDesc('total'),
            'total_asc'  => $rekapRows->sortBy('total'),
            default      => $rekapRows->sortBy(fn($r) => $r['mahasiswa']->user?->name ?? ''),
        };

        return view('page.dosen.nilai-tugas.rekap', compact(
            'kelas', 'tugasList', 'rekapRows'
        ));
    }

    // ─── Export CSV/Excel ────────────────────────────────────────────────
    public function export(int $kelasId)
    {
        $kelas       = $this->kelasDosen($kelasId);
        $format      = request('format', 'csv');

        $tugasList = Tugas::where('kelas_id', $kelasId)
            ->orderBy('pertemuan')
            ->orderBy('created_at')
            ->get();

        $mahasiswas = $this->mahasiswaKelas($kelasId);

        $allSubmissions = TugasSubmission::whereIn('tugas_id', $tugasList->pluck('id'))
            ->get()
            ->groupBy('mahasiswa_id');

        if ($format === 'csv') {
            return $this->exportCsv($kelas, $tugasList, $mahasiswas, $allSubmissions);
        } else {
            return $this->exportExcel($kelas, $tugasList, $mahasiswas, $allSubmissions);
        }
    }

    private function exportCsv($kelas, $tugasList, $mahasiswas, $allSubmissions)
    {
        $filename = 'Rekap_Nilai_Tugas_' . str_replace([' ', '/'], '_', $kelas->mataKuliah->kode_mk) . '_' . date('Ymd_His') . '.csv';

        return Response::stream(function () use ($kelas, $tugasList, $mahasiswas, $allSubmissions) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header metadata
            fputcsv($handle, ['Rekap Nilai Tugas']);
            fputcsv($handle, ['Mata Kuliah', $kelas->mataKuliah->nama_mk ?? $kelas->mataKuliah->nama ?? '-']);
            fputcsv($handle, ['Kode', $kelas->mataKuliah->kode_mk ?? '-']);
            fputcsv($handle, ['Kelas', $kelas->section ?? $kelas->kode_kelas ?? '-']);
            fputcsv($handle, ['Dosen', $kelas->dosen?->nama ?? '-']);
            fputcsv($handle, ['Tanggal Export', now()->format('d M Y H:i')]);
            fputcsv($handle, []);

            // Header kolom
            $headers = ['No', 'NIM', 'Nama', 'Prodi'];
            foreach ($tugasList as $t) {
                $headers[] = "P{$t->pertemuan}: " . ($t->title ?? "Tugas {$t->pertemuan}");
            }
            $headers[] = 'Total';
            $headers[] = 'Rata-rata';
            fputcsv($handle, $headers);

            // Data rows
            $no = 1;
            foreach ($mahasiswas as $m) {
                $submissions = $allSubmissions->get($m->id, collect())->keyBy('tugas_id');
                $row = [
                    $no++,
                    $m->nim ?? '-',
                    $m->user?->name ?? $m->nama ?? '-',
                    $m->prodi ?? '-',
                ];

                $total = 0;
                $count = 0;
                foreach ($tugasList as $t) {
                    $score = $submissions->get($t->id)?->score;
                    $row[] = $score ?? '-';
                    if ($score !== null) {
                        $total += $score;
                        $count++;
                    }
                }

                $row[] = $total > 0 ? number_format($total, 2) : '-';
                $row[] = $count > 0 ? number_format($total / $count, 2) : '-';

                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function exportExcel($kelas, $tugasList, $mahasiswas, $allSubmissions)
    {
        $filename = 'Rekap_Nilai_Tugas_' . str_replace([' ', '/'], '_', $kelas->mataKuliah->kode_mk) . '_' . date('Ymd_His') . '.xls';

        $html = '<html><head><meta charset="utf-8"></head><body>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">';

        // Header metadata
        $html .= '<tr><td colspan="' . (4 + $tugasList->count() + 2) . '" style="font-weight: bold; font-size: 16px; text-align: center;">Rekap Nilai Tugas</td></tr>';
        $html .= '<tr><td><b>Mata Kuliah</b></td><td colspan="' . (3 + $tugasList->count() + 2) . '">' . htmlspecialchars($kelas->mataKuliah->nama_mk ?? '-') . '</td></tr>';
        $html .= '<tr><td><b>Kode</b></td><td colspan="' . (3 + $tugasList->count() + 2) . '">' . htmlspecialchars($kelas->mataKuliah->kode_mk ?? '-') . '</td></tr>';
        $html .= '<tr><td><b>Kelas</b></td><td colspan="' . (3 + $tugasList->count() + 2) . '">' . htmlspecialchars($kelas->section ?? '-') . '</td></tr>';
        $html .= '<tr><td><b>Dosen</b></td><td colspan="' . (3 + $tugasList->count() + 2) . '">' . htmlspecialchars($kelas->dosen?->nama ?? '-') . '</td></tr>';
        $html .= '<tr><td><b>Tanggal Export</b></td><td colspan="' . (3 + $tugasList->count() + 2) . '">' . now()->format('d M Y H:i') . '</td></tr>';
        $html .= '<tr><td colspan="' . (4 + $tugasList->count() + 2) . '">&nbsp;</td></tr>';

        // Header kolom
        $html .= '<tr style="background-color: #f0f0f0; font-weight: bold; text-align: center;">';
        $html .= '<td>No</td><td>NIM</td><td>Nama</td><td>Prodi</td>';
        foreach ($tugasList as $t) {
            $html .= '<td>P' . $t->pertemuan . '<br>' . htmlspecialchars($t->title ?? "Tugas {$t->pertemuan}") . '</td>';
        }
        $html .= '<td>Total</td><td>Rata-rata</td>';
        $html .= '</tr>';

        // Data rows
        $no = 1;
        foreach ($mahasiswas as $m) {
            $submissions = $allSubmissions->get($m->id, collect())->keyBy('tugas_id');
            $html .= '<tr>';
            $html .= '<td style="text-align: center;">' . $no++ . '</td>';
            $html .= '<td>' . htmlspecialchars($m->nim ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($m->user?->name ?? $m->nama ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($m->prodi ?? '-') . '</td>';

            $total = 0;
            $count = 0;
            foreach ($tugasList as $t) {
                $score = $submissions->get($t->id)?->score;
                $html .= '<td style="text-align: center;">' . ($score !== null ? number_format($score, 0) : '-') . '</td>';
                if ($score !== null) {
                    $total += $score;
                    $count++;
                }
            }

            $html .= '<td style="text-align: center; font-weight: bold;">' . ($total > 0 ? number_format($total, 2) : '-') . '</td>';
            $html .= '<td style="text-align: center; font-weight: bold;">' . ($count > 0 ? number_format($total / $count, 2) : '-') . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table></body></html>';

        return Response::make($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
