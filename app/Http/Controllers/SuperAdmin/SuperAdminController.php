<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Internship;
use App\Models\Krs;
use App\Models\Invoice;
use App\Models\SkripsiSubmission;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{

    /**
     * Global Search
     */
    public function globalSearch(Request $request)
    {
        $query = $request->input('query');
        $results = [];

        if ($query) {
            $results['mahasiswa'] = Mahasiswa::with('user')
                ->where('nim', 'like', "%{$query}%")
                ->orWhereHas('user', function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })
                ->take(10)->get();

            $results['dosen'] = Dosen::with('user')
                ->where('nidn', 'like', "%{$query}%")
                ->orWhereHas('user', function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })
                ->take(10)->get();

            $results['users'] = User::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->take(10)->get();

            $results['invoices'] = Invoice::with('student.user')
                ->where(function ($q) use ($query) {
                    if (is_numeric($query)) {
                        $q->where('id', $query);
                    }
                    $q->orWhereHas('student.user', function ($uq) use ($query) {
                        $uq->where('name', 'like', "%{$query}%");
                    })->orWhereHas('student', function ($sq) use ($query) {
                        $sq->where('nim', 'like', "%{$query}%");
                    });
                })
                ->take(10)->get();

            $results['krs'] = Krs::with(['mahasiswa', 'mataKuliah'])
                ->where('id', $query)
                ->orWhere('tahun_ajaran', 'like', "%{$query}%")
                ->take(10)->get();

            $results['magang'] = Internship::with('mahasiswa')
                ->where('id', $query)
                ->orWhere('instansi', 'like', "%{$query}%")
                ->take(10)->get();

            $results['skripsi'] = SkripsiSubmission::with('mahasiswa')
                ->where('id', $query)
                ->orWhere('judul', 'like', "%{$query}%")
                ->take(10)->get();
        }

        return view('super-admin.search', compact('results', 'query'));
    }

    /**
     * Student 360 View
     */
    public function student360(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load([
            'user', 'parents', 'invoices', 
            'krs.kelas.mataKuliah', 'krs.nilai', 'internships', 
            'prestasis', 'wisudaRegistrations', 'prodiData'
        ]);

        $skripsi = SkripsiSubmission::where('mahasiswa_id', $mahasiswa->id)->get();
        
        $activities = AuditLog::where('actor_id', $mahasiswa->user_id)
            ->orderBy('created_at', 'desc')
            ->take(20)->get();

        return view('super-admin.student-360', compact('mahasiswa', 'skripsi', 'activities'));
    }

    /**
     * Audit Trail Logs — full paginated view (Super Admin layout)
     */
    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('actor')->orderBy('created_at', 'desc');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%")
                  ->orWhereHas('actor', fn($u) => $u->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $logs = $query->paginate(20)->withQueryString();
        
        $actions = AuditLog::distinct()->whereNotNull('action')->pluck('action')->toArray();
        $modules = AuditLog::distinct()->whereNotNull('module')->pluck('module')->toArray();

        return view('super-admin.audit-logs', compact('logs', 'actions', 'modules'));
    }

    /**
     * Export Audit Trail Logs to CSV
     */
    public function exportAuditLogs(Request $request)
    {
        $query = AuditLog::with('actor')->orderBy('created_at', 'desc');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%")
                  ->orWhereHas('actor', fn($u) => $u->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $logs = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="audit_logs_' . now()->format('Ymd_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM for UTF-8 Excel
            
            fputcsv($file, ['ID', 'Waktu', 'Aktor ID', 'Nama Aktor', 'Email Aktor', 'Role Aktor', 'Aksi', 'Modul', 'Tipe Target', 'ID Target', 'Meta Detail', 'User Agent', 'IP Address']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->toDateTimeString(),
                    $log->actor_id,
                    $log->actor?->name ?? 'System',
                    $log->actor?->email ?? '-',
                    $log->actor_role,
                    $log->action,
                    $log->module ?? '-',
                    $log->auditable_type ?? '-',
                    $log->auditable_id ?? '-',
                    json_encode($log->meta),
                    $log->user_agent,
                    $log->ip_address
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * User Management — list all system users (Super Admin layout)
     */
    public function userManagement(Request $request)
    {
        $query = User::with('roles')->orderBy('created_at', 'desc');

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(25)->withQueryString();
        $roles = \Spatie\Permission\Models\Role::all();

        return view('super-admin.user-management', compact('users', 'roles'));
    }

    /**
     * Student 360 Search — landing page to search for a student
     */
    public function student360Search(Request $request)
    {
        $mahasiswa = null;
        $query     = $request->input('q');

        if ($query) {
            $mahasiswa = Mahasiswa::with('user')
                ->where('nim', 'like', "%{$query}%")
                ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%"))
                ->take(10)->get();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($mahasiswa ?? []);
        }

        return view('super-admin.student-360-search', compact('mahasiswa', 'query'));
    }

    /**
     * Activity Monitor — real-time user activity overview
     */
    public function activityMonitor()
    {
        $loginHariIni   = AuditLog::where('action', 'user.login')->whereDate('created_at', today())->with('actor')->latest('created_at')->take(50)->get();
        $logoutHariIni  = AuditLog::where('action', 'user.logout')->whereDate('created_at', today())->count();
        $failedLogins   = AuditLog::where('action', 'user.login_failed')->whereDate('created_at', today())->count();
        $activeImpersonations = session()->has('impersonator_id') ? 1 : 0;
        $overrideHariIni = AuditLog::where('action', 'like', '%.override%')->whereDate('created_at', today())->count();
        $recentActivity  = AuditLog::with('actor')->orderByDesc('created_at')->take(30)->get();

        return view('super-admin.activity-monitor', compact(
            'loginHariIni', 'logoutHariIni', 'failedLogins',
            'activeImpersonations', 'overrideHariIni', 'recentActivity'
        ));
    }

    /**
     * System Health — server & application health metrics
     */
    public function systemHealth()
    {
        // PHP-based health checks (no shell access required)
        $health = [
            'database'  => $this->checkDatabase(),
            'cache'     => $this->checkCache(),
            'storage'   => $this->checkStorage(),
            'queue'     => $this->checkQueue(),
        ];

        return view('super-admin.system-health', compact('health'));
    }

    private function checkDatabase(): array
    {
        try {
            \DB::connection()->getPdo();
            return ['status' => 'healthy', 'message' => 'Database connected'];
        } catch (\Exception $e) {
            return ['status' => 'critical', 'message' => $e->getMessage()];
        }
    }

    private function checkCache(): array
    {
        try {
            \Cache::put('health_check', true, 10);
            $ok = \Cache::get('health_check') === true;
            return $ok ? ['status' => 'healthy', 'message' => 'Cache berfungsi normal'] : ['status' => 'warning', 'message' => 'Cache tidak dapat dibaca'];
        } catch (\Exception $e) {
            return ['status' => 'warning', 'message' => $e->getMessage()];
        }
    }

    private function checkStorage(): array
    {
        $path = storage_path('app');
        $free = disk_free_space($path);
        $total = disk_total_space($path);
        $usedPct = $total > 0 ? round((1 - ($free / $total)) * 100, 1) : 0;
        $status = $usedPct > 90 ? 'critical' : ($usedPct > 75 ? 'warning' : 'healthy');
        return ['status' => $status, 'used_pct' => $usedPct, 'free_gb' => round($free / 1024 / 1024 / 1024, 2), 'total_gb' => round($total / 1024 / 1024 / 1024, 2)];
    }

    private function checkQueue(): array
    {
        try {
            $failed = \DB::table('failed_jobs')->count();
            $status = $failed > 10 ? 'warning' : 'healthy';
            return ['status' => $status, 'failed_jobs' => $failed];
        } catch (\Exception $e) {
            return ['status' => 'warning', 'message' => 'Tidak dapat memeriksa queue'];
        }
    }

    /**
     * System Configuration page
     */
    public function systemConfig()
    {
        $settings = \App\Models\SystemSetting::pluck('value', 'key')->toArray();
        $semesters = \App\Models\Semester::orderByDesc('created_at')->get();
        return view('super-admin.system-config', compact('settings', 'semesters'));
    }

    /**
     * Update system configuration
     */
    public function systemConfigUpdate(Request $request)
    {
        $allowed = [
            'site_name', 'site_logo', 'maintenance_mode', 'semester_aktif_id',
            'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption', 'smtp_from_address', 'smtp_from_name',
            'wa_gateway_url', 'wa_api_key'
        ];

        foreach ($allowed as $key) {
            if ($request->has($key)) {
                \App\Models\SystemSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $request->input($key)]
                );
            }
        }

        AuditLog::log('system.config_updated', auth()->user() ?? new \App\Models\User(), [
            'updated_keys' => array_intersect($allowed, array_keys($request->all())),
        ]);

        return back()->with('success', 'Konfigurasi sistem berhasil diperbarui.');
    }

    /**
     * Semester & Tahun Akademik config
     */
    public function semesterConfig()
    {
        $semesters = \App\Models\Semester::orderByDesc('created_at')->get();
        return view('super-admin.semester-config', compact('semesters'));
    }

    /**
     * Backup & Recovery page
     */
    public function backup()
    {
        $backupDir = 'backups';
        $files = [];
        
        if (\Illuminate\Support\Facades\Storage::exists($backupDir)) {
            $allFiles = \Illuminate\Support\Facades\Storage::files($backupDir);
            foreach ($allFiles as $filePath) {
                $filename = basename($filePath);
                $files[] = [
                    'filename' => $filename,
                    'size' => $this->formatBytes(\Illuminate\Support\Facades\Storage::size($filePath)),
                    'created_at' => date('d M Y H:i:s', \Illuminate\Support\Facades\Storage::lastModified($filePath)),
                    'raw_timestamp' => \Illuminate\Support\Facades\Storage::lastModified($filePath)
                ];
            }
            
            // Sort by latest created first
            usort($files, fn($a, $b) => $b['raw_timestamp'] - $a['raw_timestamp']);
        }
        
        return view('super-admin.backup', compact('files'));
    }

    /**
     * Create database backup
     */
    public function createBackup()
    {
        try {
            $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
            $dbName = \Illuminate\Support\Facades\DB::connection()->getDatabaseName();
            $key = "Tables_in_" . $dbName;
            
            $sql = "-- SIAKAD Backup\n";
            $sql .= "-- Generated: " . now()->toDateTimeString() . "\n\n";
            
            foreach ($tables as $tableObj) {
                $table = $tableObj->$key;
                
                // Get Create Table statement
                $create = \Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE `{$table}`")[0];
                $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
                $sql .= $create->{'Create Table'} . ";\n\n";
                
                // Get data
                $rows = \Illuminate\Support\Facades\DB::table($table)->get();
                foreach ($rows as $row) {
                    $rowArray = (array) $row;
                    $columns = array_keys($rowArray);
                    $escapedValues = array_map(function($val) {
                        if ($val === null) return 'NULL';
                        return \Illuminate\Support\Facades\DB::getPdo()->quote($val);
                    }, array_values($rowArray));
                    
                    $sql .= "INSERT INTO `{$table}` (`" . implode("`, `", $columns) . "`) VALUES (" . implode(", ", $escapedValues) . ");\n";
                }
                $sql .= "\n\n";
            }
            
            $filename = "backup_" . now()->format('Ymd_His') . ".sql";
            \Illuminate\Support\Facades\Storage::makeDirectory('backups');
            \Illuminate\Support\Facades\Storage::put("backups/{$filename}", $sql);
            
            AuditLog::log('system.backup_created', auth()->user() ?? new \App\Models\User(), [
                'filename' => $filename,
                'size_bytes' => strlen($sql)
            ]);
            
            return back()->with('success', "Backup database berhasil dibuat: {$filename}");
        } catch (\Exception $e) {
            return back()->with('error', "Gagal membuat backup: " . $e->getMessage());
        }
    }

    /**
     * Download backup file
     */
    public function downloadBackup($filename)
    {
        $path = "backups/{$filename}";
        
        if (!\Illuminate\Support\Facades\Storage::exists($path)) {
            abort(404, 'File backup tidak ditemukan.');
        }
        
        AuditLog::log('system.backup_downloaded', auth()->user() ?? new \App\Models\User(), [
            'filename' => $filename
        ]);

        return \Illuminate\Support\Facades\Storage::download($path);
    }

    /**
     * Delete backup file
     */
    public function deleteBackup($filename)
    {
        $path = "backups/{$filename}";
        
        if (!\Illuminate\Support\Facades\Storage::exists($path)) {
            return back()->with('error', 'File backup tidak ditemukan.');
        }
        
        \Illuminate\Support\Facades\Storage::delete($path);
        
        AuditLog::log('system.backup_deleted', auth()->user() ?? new \App\Models\User(), [
            'filename' => $filename
        ]);

        return back()->with('success', "File backup '{$filename}' berhasil dihapus.");
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

