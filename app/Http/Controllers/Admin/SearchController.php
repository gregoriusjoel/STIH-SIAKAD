<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

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

        // Dynamically get admin features from the sidebar blade template
        if (app()->environment('local')) {
            $features = $this->getDynamicFeatures();
        } else {
            $features = Cache::remember('admin_sidebar_features', 86400, function () {
                return $this->getDynamicFeatures();
            });
        }

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
            $match = stripos($f['label'], $q) !== false 
                || stripos($f['key'], $q) !== false 
                || (!empty($f['route']) && stripos($f['route'], $q) !== false);
            if ($match) {
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
                if (!empty($f['route']) && Route::has($f['route'])) {
                    $url = route($f['route']);
                } elseif (!empty($f['url'])) {
                    $url = url($f['url']);
                }
                $outFeatures[] = ['label' => $f['label'], 'route' => $f['route'] ?? null, 'url' => $url, 'key' => $f['key']];
            }

            return response()->json(['q' => $q, 'features' => $outFeatures, 'results' => $outResults]);
        }

        // pass feature results under a dedicated key for html view
        return view('admin.search.results', ['q' => $q, 'results' => $results, 'features' => $featureResults]);
    }

    /**
     * Parses the admin sidebar blade template to extract available features.
     *
     * @return array
     */
    private function getDynamicFeatures()
    {
        $path = resource_path('views/admin/sidebar-admin.blade.php');
        if (!file_exists($path)) {
            return [];
        }

        $content = file_get_contents($path);
        
        // Match all <a> tags that have class containing 'sidebar-link'
        preg_match_all('/<a\s+[^>]*class="[^"]*sidebar-link[^"]*"[^>]*>.*?<\/a>/is', $content, $matches);

        $features = [];
        $seenKeys = [];

        foreach (($matches[0] ?? []) as $anchorBlock) {
            // Find span with class matching 'text-sm'
            if (preg_match('/<span[^>]*class="[^"]*text-sm[^"]*"[^>]*>(.*?)<\/span>/is', $anchorBlock, $spanMatch)) {
                $label = trim(strip_tags($spanMatch[1]));
                $lowerLabel = strtolower($label);
                
                // Exclude logout and generic/empty elements
                if ($lowerLabel === 'logout' || $lowerLabel === '') {
                    continue;
                }

                // Extract route name from the block
                $route = null;
                if (preg_match('/route\(\'([^\'\s]+)\'\)/', $anchorBlock, $routeMatch)) {
                    $route = $routeMatch[1];
                }

                // Extract url fallback from the block
                $url = null;
                if (preg_match('/url\(\'([^\'\s]+)\'\)/', $anchorBlock, $urlMatch)) {
                    $url = $urlMatch[1];
                }

                // Generate slug/key
                $key = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', trim($label)));
                if (empty($key)) {
                    continue;
                }

                if (!$route && !$url) {
                    continue;
                }

                if (isset($seenKeys[$key])) {
                    $idx = $seenKeys[$key];
                    if ($route && !$features[$idx]['route']) {
                        $features[$idx]['route'] = $route;
                    }
                    if ($url && !$features[$idx]['url']) {
                        $features[$idx]['url'] = $url;
                    }
                } else {
                    $features[] = [
                        'key' => $key,
                        'label' => $label,
                        'route' => $route,
                        'url' => $url,
                    ];
                    $seenKeys[$key] = count($features) - 1;
                }
            }
        }

        return $features;
    }

}
