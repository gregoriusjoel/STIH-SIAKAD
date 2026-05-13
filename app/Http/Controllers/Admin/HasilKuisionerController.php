<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KuesionerAktivasi;
use App\Models\KuesionerMahasiswaBaru;
use App\Models\Semester;
use App\Models\Prodi;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\SurveyQuestion;
use App\Services\ExportKuisionerExcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HasilKuisionerController extends Controller
{
    protected $excelService;

    public function __construct(ExportKuisionerExcelService $excelService)
    {
        $this->excelService = $excelService;
    }

    public function index()
    {
        $currentSemester = Semester::where('is_active', true)->first();
        $types = [
            [
                'id' => 'mahasiswa_baru',
                'name' => 'Kuesioner Mahasiswa Baru',
                'total_respondents' => KuesionerMahasiswaBaru::count(),
                'status' => 'Aktif',
                'tahun_ajaran' => date('Y'),
                'created_at' => KuesionerMahasiswaBaru::min('created_at') ?? now(),
            ],
            [
                'id' => 'aktivasi_semester',
                'name' => 'Kuesioner Aktivasi Semester',
                'total_respondents' => KuesionerAktivasi::count(),
                'status' => 'Aktif',
                'tahun_ajaran' => $currentSemester ? $currentSemester->tahun_ajaran : '-',
                'created_at' => KuesionerAktivasi::min('created_at') ?? now(),
            ],
        ];

        return view('page.admin.hasil-kuisioner.index', compact('types'));
    }

    public function show(Request $request, $type)
    {
        $semesters = Semester::orderBy('tahun_ajaran', 'desc')->orderBy('nama_semester', 'desc')->get();
        $prodis = Prodi::all();
        $dosens = Dosen::all();
        $mataKuliahs = MataKuliah::all();

        $query = $this->getQuery($type, $request);
        $results = $query->get();

        $angkatans = [];
        if ($type === 'mahasiswa_baru') {
            $angkatans = KuesionerMahasiswaBaru::whereNotNull('angkatan')->distinct()->pluck('angkatan')->sortDesc();
        } else {
            // For other types, try to get from related students or just a standard range
            $angkatans = KuesionerAktivasi::whereNotNull('angkatan')->distinct()->pluck('angkatan')->sortDesc();
        }

        $stats = $this->calculateStats($type, $results);

        return view('page.admin.hasil-kuisioner.show', compact('type', 'results', 'stats', 'semesters', 'prodis', 'dosens', 'mataKuliahs', 'angkatans'));
    }

    public function exportExcel(Request $request, $type)
    {
        $query = $this->getQuery($type, $request);
        $results = $query->get();
        $stats = $this->calculateStats($type, $results);

        return $this->excelService->export($type, $results, $stats);
    }

    protected function getQuery($type, Request $request)
    {
        if ($type === 'mahasiswa_baru') {
            $query = KuesionerMahasiswaBaru::with('mahasiswa.user', 'mahasiswa.prodiData');
        } else {
            $query = KuesionerAktivasi::with(['mahasiswa.user', 'mahasiswa.prodiData', 'semester']);
        }

        if ($request->filled('semester_id') && $type === 'aktivasi_semester') {
            $query->where('semester_id', $request->semester_id);
        }

        if ($request->filled('prodi_id')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('prodi_id', $request->prodi_id);
            });
        }

        if ($request->filled('angkatan')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('angkatan', $request->angkatan);
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        return $query;
    }

    protected function calculateStats($type, $results)
    {
        $questions = $this->getQuestions($type);
        $totalRespondents = $results->count();
        $rekap = [];

        foreach ($questions as $q) {
            $key = $q['key'];
            $scores = $results->pluck($key)->filter()->toArray();
            $count = count($scores);
            $avg = $count > 0 ? array_sum($scores) / $count : 0;

            // Frequency for Chart.js
            $freq = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
            foreach ($scores as $s) {
                if (isset($freq[(int) $s])) {
                    $freq[(int) $s]++;
                }
            }

            $rekap[$key] = [
                'text' => $q['text'],
                'avg' => round($avg, 2),
                'count' => $count,
                'freq' => $freq,
            ];
        }

        $period = 'Semua Periode';
        if ($results->isNotEmpty()) {
            if ($type === 'aktivasi_semester') {
                $semesters = $results->pluck('semester.display_label')->unique();
                $period = $semesters->count() === 1 ? $semesters->first() : 'Multi Semester';
            } else {
                $angkatans = $results->pluck('mahasiswa.angkatan')->unique();
                $period = $angkatans->count() === 1 ? 'Angkatan ' . $angkatans->first() : 'Semua Angkatan';
            }
        }

        return [
            'total_respondents' => $totalRespondents,
            'questions' => $questions,
            'rekap' => $rekap,
            'suggestions' => $results->filter(fn($r) => !empty($r->saran))->map(function ($r) {
                return [
                    'name' => $r->mahasiswa?->user?->name ?? 'Anonim',
                    'nim' => $r->mahasiswa?->nim ?? '-',
                    'text' => $r->saran,
                ];
            })->toArray(),
            'period' => $period,
        ];
    }

    protected function getQuestions($type)
    {
        if ($type === 'mahasiswa_baru') {
            return SurveyQuestion::all();
        }

        return [
            ['key' => 'fasilitas_kampus', 'text' => 'Kepuasan terhadap Fasilitas Kampus'],
            ['key' => 'sistem_akademik', 'text' => 'Kepuasan terhadap Sistem Akademik'],
            ['key' => 'kualitas_dosen', 'text' => 'Kepuasan terhadap Kualitas Dosen'],
            ['key' => 'layanan_administrasi', 'text' => 'Kepuasan terhadap Layanan Administrasi'],
            ['key' => 'kepuasan_keseluruhan', 'text' => 'Kepuasan Keseluruhan'],
        ];
    }
}
