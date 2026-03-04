<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImportService;
use App\Models\ImportLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ImportController extends Controller
{
    protected ImportService $importService;

    /**
     * Import type configurations for views
     */
    protected array $importTypes = [
        'mahasiswa' => [
            'title' => 'Import Data Mahasiswa',
            'description' => 'Import data mahasiswa dari file CSV atau XLSX',
            'icon' => 'fa-user-graduate',
            'template_columns' => ['nim', 'nama', 'email', 'prodi', 'angkatan', 'semester', 'phone', 'alamat', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'agama'],
        ],
        'dosen' => [
            'title' => 'Import Data Dosen',
            'description' => 'Import data dosen dari file CSV atau XLSX',
            'icon' => 'fa-chalkboard-teacher',
            'template_columns' => ['nidn', 'nama', 'email', 'pendidikan', 'universitas', 'prodi', 'phone', 'address', 'jabatan_fungsional', 'dosen_tetap'],
        ],
        'dosen_pa' => [
            'title' => 'Import Data Dosen PA',
            'description' => 'Import penugasan dosen pembimbing akademik ke mahasiswa',
            'icon' => 'fa-user-tie',
            'template_columns' => ['nim', 'nidn_dosen_pa'],
        ],
        'mata_kuliah' => [
            'title' => 'Import Mata Kuliah',
            'description' => 'Import data mata kuliah dari file CSV atau XLSX',
            'icon' => 'fa-book',
            'template_columns' => ['kode_mk', 'nama_matkul', 'sks', 'semester', 'jenis', 'praktikum', 'prodi_id', 'fakultas_id', 'deskripsi'],
        ],
        'ruangan' => [
            'title' => 'Import Data Ruangan',
            'description' => 'Import data ruangan kelas dari file CSV atau XLSX',
            'icon' => 'fa-door-open',
            'template_columns' => ['kode_ruangan', 'nama_ruangan', 'gedung', 'lantai', 'kapasitas', 'status'],
        ],
        'orang_tua' => [
            'title' => 'Import Data Orang Tua',
            'description' => 'Import data orang tua/wali mahasiswa dari file CSV atau XLSX',
            'icon' => 'fa-users',
            'template_columns' => ['nim_mahasiswa', 'nama_ortu', 'email', 'hubungan', 'pekerjaan', 'phone', 'address'],
        ],
    ];

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }

    /**
     * Display import index page
     */
    public function index()
    {
        $importTypes = $this->importTypes;
        $recentLogs = ImportLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.import.index', compact('importTypes', 'recentLogs'));
    }

    /**
     * Display import page for specific type
     */
    public function show(string $type)
    {
        if (!isset($this->importTypes[$type])) {
            abort(404, 'Tipe import tidak ditemukan');
        }

        $importConfig = $this->importTypes[$type];
        $importConfig['type'] = $type;

        $recentLogs = ImportLog::where('type', $type)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.import.show', compact('importConfig', 'type', 'recentLogs'));
    }

    /**
     * Preview uploaded file data
     */
    public function preview(Request $request, string $type)
    {
        if (!isset($this->importTypes[$type])) {
            return response()->json([
                'success' => false,
                'message' => 'Tipe import tidak valid',
            ], 400);
        }

        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240', // Max 10MB
        ]);

        try {
            $file = $request->file('file');
            
            // Parse file
            $data = $this->importService->parseFile($file);
            
            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File kosong atau tidak dapat dibaca',
                ], 400);
            }

            // Validate data
            $validation = $this->importService->validateData($type, $data);

            // Get preview data (max 100 rows)
            $previewData = array_slice($data, 0, 100);
            
            // Get columns from first row (filter internal columns and reindex)
            $columns = array_keys($data[0] ?? []);
            $columns = array_values(array_filter($columns, fn($col) => !str_starts_with($col, '_')));

            // Clean preview data to remove internal columns
            $cleanPreview = array_map(function($row) {
                return array_filter($row, fn($key) => !str_starts_with($key, '_'), ARRAY_FILTER_USE_KEY);
            }, $previewData);

            return response()->json([
                'success' => true,
                'data' => [
                    'columns' => $columns,
                    'preview' => array_values($cleanPreview),
                    'validation' => $validation,
                    'total_rows' => count($data),
                    'filename' => $file->getClientOriginalName(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error parsing file: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Process import
     */
    public function import(Request $request, string $type)
    {
        if (!isset($this->importTypes[$type])) {
            return response()->json([
                'success' => false,
                'message' => 'Tipe import tidak valid',
            ], 400);
        }

        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
            'skip_duplicates' => 'nullable|boolean',
        ]);

        try {
            $file = $request->file('file');
            $skipDuplicates = $request->boolean('skip_duplicates', true);
            
            // Parse file
            $data = $this->importService->parseFile($file);
            
            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File kosong atau tidak dapat dibaca',
                ], 400);
            }

            // Validate first
            $validation = $this->importService->validateData($type, $data);
            
            if (!$validation['valid'] && !empty($validation['errors'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'validation' => $validation,
                ], 422);
            }

            // Import data
            $result = $this->importService->import(
                $type,
                $validation['validated_data'],
                Auth::id(),
                $skipDuplicates
            );

            // Update log with filename
            $log = ImportLog::where('type', $type)
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($log) {
                $log->update(['filename' => $file->getClientOriginalName()]);
            }

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Import berhasil!',
                    'result' => $result,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Import gagal',
                    'result' => $result,
                ], 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error importing data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download import template
     */
    public function downloadTemplate(string $type)
    {
        if (!isset($this->importTypes[$type])) {
            abort(404, 'Tipe import tidak ditemukan');
        }

        $config = $this->importTypes[$type];
        $columns = $config['template_columns'];
        
        // Create CSV content
        $csvContent = implode(',', $columns) . "\n";
        
        // Add sample data row based on type
        $sampleData = $this->getSampleData($type, $columns);
        $csvContent .= implode(',', $sampleData) . "\n";

        $filename = "template_import_{$type}.csv";

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Get sample data for template
     */
    protected function getSampleData(string $type, array $columns): array
    {
        $samples = [
            'mahasiswa' => [
                'nim' => '2024001001',
                'nama' => 'John Doe',
                'email' => '2024001001@student.stih.ac.id',
                'prodi' => 'Ilmu Hukum',
                'angkatan' => '2024',
                'semester' => '1',
                'phone' => '081234567890',
                'alamat' => 'Jl. Contoh No. 123',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2000-01-15',
                'agama' => 'Islam',
            ],
            'dosen' => [
                'nidn' => '0123456789',
                'nama' => 'Dr. Jane Smith, S.H., M.H.',
                'email' => 'jane.smith@stih.ac.id',
                'pendidikan' => 'S3',
                'universitas' => 'Universitas Indonesia',
                'prodi' => 'Ilmu Hukum',
                'phone' => '081234567890',
                'address' => 'Jl. Dosen No. 456',
                'jabatan_fungsional' => 'Lektor Kepala',
                'dosen_tetap' => 'ya',
            ],
            'dosen_pa' => [
                'nim' => '2024001001',
                'nidn_dosen_pa' => '0123456789',
            ],
            'mata_kuliah' => [
                'kode_mk' => 'HKM101',
                'nama_matkul' => 'Pengantar Ilmu Hukum',
                'sks' => '3',
                'semester' => '1',
                'jenis' => 'wajib_prodi',
                'praktikum' => '0',
                'prodi_id' => '',
                'fakultas_id' => '',
                'deskripsi' => 'Mata kuliah dasar ilmu hukum',
            ],
            'ruangan' => [
                'kode_ruangan' => 'R101',
                'nama_ruangan' => 'Ruang 101',
                'gedung' => 'Gedung A',
                'lantai' => '1',
                'kapasitas' => '40',
                'status' => 'aktif',
            ],
            'orang_tua' => [
                'nim_mahasiswa' => '2024001001',
                'nama_ortu' => 'Budi Santoso',
                'email' => 'budi.santoso@gmail.com',
                'hubungan' => 'ayah',
                'pekerjaan' => 'PNS',
                'phone' => '081234567890',
                'address' => 'Jl. Contoh No. 123',
            ],
        ];

        $typeData = $samples[$type] ?? [];
        $result = [];

        foreach ($columns as $column) {
            $result[] = $typeData[$column] ?? '';
        }

        return $result;
    }

    /**
     * Display import history/logs
     */
    public function history(Request $request)
    {
        $query = ImportLog::with('user')
            ->orderBy('created_at', 'desc');

        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        $logs = $query->paginate(20);
        $importTypes = $this->importTypes;

        return view('admin.import.history', compact('logs', 'importTypes'));
    }

    /**
     * Show import log details
     */
    public function showLog(ImportLog $log)
    {
        return view('admin.import.log-detail', compact('log'));
    }
}
