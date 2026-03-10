<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = (string) $request->get('q', '');
        $results = [];
        $featureResults = [];

        if ($q === '') {
            return view('admin.search.results', ['q' => $q, 'results' => $results, 'features' => $featureResults]);
        }

        $term = '%' . $q . '%';

        // logical table definitions with common variants (singular/plural)
        $tables = [
            'users' => ['tables' => ['users'], 'columns' => ['name', 'email']],
            'dosens' => ['tables' => ['dosens', 'dosen'], 'columns' => ['nama', 'nidn', 'email']],
            'mahasiswas' => ['tables' => ['mahasiswas', 'mahasiswa'], 'columns' => ['nama', 'nrp', 'nim', 'email']],
            'mata_kuliahs' => ['tables' => ['mata_kuliahs', 'mata_kuliah', 'mata_kulias'], 'columns' => ['nama', 'kode']],
            'kelas_mata_kuliahs' => ['tables' => ['kelas_mata_kuliahs', 'kelas_mata_kuliah'], 'columns' => ['nama', 'kode']],
            'parents' => ['tables' => ['parents', 'parent'], 'columns' => ['nama', 'email']],
            'jadwals' => ['tables' => ['jadwals', 'jadwal'], 'columns' => ['hari', 'ruang', 'keterangan']],
            'semesters' => ['tables' => ['semesters', 'semester'], 'columns' => ['nama_semester', 'tahun_ajaran']],
            'krs' => ['tables' => ['krs', 'krs_items'], 'columns' => ['id']],
            'ruangans' => ['tables' => ['ruangans', 'ruangan'], 'columns' => ['nama_ruang', 'kode']],
            'prodis' => ['tables' => ['prodis', 'prodi'], 'columns' => ['nama_prodi', 'kode_prodi']],
            'pengumumans' => ['tables' => ['pengumumans', 'pengumuman'], 'columns' => ['judul']],
        ];

        // Static admin features (label and route name)
        $features = [
            ['key' => 'mahasiswa', 'label' => 'Data Mahasiswa', 'route' => 'admin.mahasiswa.index'],
            ['key' => 'dosen', 'label' => 'Data Dosen', 'route' => 'admin.dosen.index'],
            ['key' => 'mata_kuliah', 'label' => 'Mata Kuliah', 'route' => 'admin.mata-kuliah.index'],
            ['key' => 'kelas', 'label' => 'Kelas Mata Kuliah', 'route' => 'admin.kelas-mata-kuliah.index'],
            ['key' => 'jadwal', 'label' => 'Jadwal Perkuliahan', 'route' => 'admin.jadwal.index'],
            ['key' => 'parents', 'label' => 'Data Orang Tua', 'route' => 'admin.parents.index'],
            ['key' => 'users', 'label' => 'Manajemen User', 'route' => 'admin.users.index'],
            ['key' => 'semester', 'label' => 'Semester & Tahun Ajaran', 'route' => 'admin.semester.index'],
            ['key' => 'krs', 'label' => 'Manajemen KRS', 'route' => 'admin.krs.index'],
            ['key' => 'ruangan', 'label' => 'Manajemen Ruangan', 'route' => 'admin.ruangan.index'],
            ['key' => 'jam_perkuliahan', 'label' => 'Jam Perkuliahan', 'route' => 'admin.jam-perkuliahan.index'],
            ['key' => 'prodi', 'label' => 'Program Studi', 'route' => 'admin.prodi.index'],
            ['key' => 'fakultas', 'label' => 'Fakultas', 'route' => 'admin.fakultas.index'],
            ['key' => 'magang', 'label' => 'Bimbingan Magang', 'route' => 'admin.magang.index'],
            ['key' => 'kalender', 'label' => 'Kalender Akademik', 'route' => 'admin.kalender.index'],
            ['key' => 'dosen_pa', 'label' => 'Dosen Pembimbing Akademik', 'route' => 'admin.dosen-pa.index'],
            ['key' => 'pengajuan', 'label' => 'Pengajuan Surat', 'route' => 'admin.pengajuan.index'],
            ['key' => 'jadwal_generator', 'label' => 'Auto Generate Jadwal', 'route' => 'admin.jadwal_generator.index'],
            ['key' => 'jadwal_admin_approval', 'label' => 'Persetujuan Jadwal', 'route' => 'admin.jadwal_admin_approval.index'],
            ['key' => 'availability', 'label' => 'Ketersediaan Dosen', 'route' => 'admin.availability.index'],
            ['key' => 'import', 'label' => 'Import Data', 'route' => 'admin.import.index'],
            ['key' => 'pengumuman', 'label' => 'Pengumuman', 'route' => 'admin.pengumuman.index'],
            ['key' => 'absensi_dosen', 'label' => 'Absensi Dosen', 'route' => 'admin.absensi_dosen.index'],
        ];

        // search database tables (try common table name variants)
        foreach ($tables as $logical => $cfg) {
            $foundTable = null;
            foreach ($cfg['tables'] as $candidate) {
                if (Schema::hasTable($candidate)) {
                    $foundTable = $candidate;
                    break;
                }
            }
            if (!$foundTable) {
                continue;
            }

            // only use columns that actually exist in the table
            $availableCols = [];
            foreach ($cfg['columns'] as $col) {
                if (Schema::hasColumn($foundTable, $col)) {
                    $availableCols[] = $col;
                }
            }
            if (empty($availableCols)) {
                continue;
            }

            $query = DB::table($foundTable)->select(array_merge(['id'], $availableCols));
            $query->where(function ($w) use ($availableCols, $term) {
                foreach ($availableCols as $col) {
                    $w->orWhere($col, 'like', $term);
                }
            });

            $items = $query->limit(15)->get();
            if ($items->isNotEmpty()) {
                $results[$logical] = $items;
            }
        }

        // search features
        foreach ($features as $f) {
            if (stripos($f['label'], $q) !== false || stripos($f['key'], $q) !== false) {
                $featureResults[] = $f;
            }
        }

        // If request expects JSON (AJAX typeahead), return compact JSON
        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            // map results to lightweight arrays
            $routeMap = [
                'users' => 'users',
                'dosens' => 'dosen',
                'mahasiswas' => 'mahasiswa',
                'mata_kuliahs' => 'mata-kuliah',
                'kelas_mata_kuliahs' => 'kelas-mata-kuliah',
                'parents' => 'parents',
                'jadwals' => 'jadwal',
                'semesters' => 'semester',
                'krs' => 'krs',
                'ruangans' => 'ruangan',
                'prodis' => 'prodi',
                'pengumumans' => 'pengumuman',
            ];

            $outResults = [];
            foreach ($results as $table => $items) {
                $list = [];
                foreach ($items->take(5) as $item) {
                    // choose display
                    $display = null;
                    foreach (['name','nama','nama_semester','title','email','nrp','kode','nidn','ruang','hari'] as $c) {
                        if (isset($item->$c) && $item->$c) { $display = $item->$c; break; }
                    }
                    if (!$display) { $display = 'ID: ' . ($item->id ?? '-'); }

                    $url = null;
                    if (isset($routeMap[$table])) {
                        $url = url('/admin/' . $routeMap[$table] . '/' . ($item->id ?? ''));
                    }

                    $list[] = ['id' => $item->id ?? null, 'display' => $display, 'url' => $url];
                }
                $outResults[$table] = $list;
            }

            $outFeatures = [];
            foreach ($featureResults as $f) {
                $url = null;
                if (!empty($f['route']) && \Route::has($f['route'])) {
                    $url = route($f['route']);
                }
                $outFeatures[] = ['label' => $f['label'], 'route' => $f['route'] ?? null, 'url' => $url, 'key' => $f['key']];
            }

            return response()->json(['q' => $q, 'features' => $outFeatures, 'results' => $outResults]);
        }

        // pass feature results under a dedicated key for html view
        return view('admin.search.results', ['q' => $q, 'results' => $results, 'features' => $featureResults]);
    }

}
