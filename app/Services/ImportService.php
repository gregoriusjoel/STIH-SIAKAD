<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ImportLog;

class ImportService
{
    /**
     * Supported import types with their configurations
     */
    protected array $importTypes = [
        'mahasiswa' => [
            'model' => \App\Models\Mahasiswa::class,
            'required_columns' => ['nim', 'nama', 'prodi', 'angkatan'],
            'column_mapping' => [
                'nim' => 'nim',
                'nama' => 'name', // will be used for User
                'email' => 'email',
                'prodi' => 'prodi',
                'angkatan' => 'angkatan',
                'semester' => 'semester',
                'phone' => 'phone',
                'no_hp' => 'no_hp',
                'alamat' => 'alamat',
                'address' => 'address',
                'jenis_kelamin' => 'jenis_kelamin',
                'tempat_lahir' => 'tempat_lahir',
                'tanggal_lahir' => 'tanggal_lahir',
                'agama' => 'agama',
            ],
            'unique_column' => 'nim',
            'has_user' => true,
        ],
        'dosen' => [
            'model' => \App\Models\Dosen::class,
            'required_columns' => ['nidn', 'nama'],
            'column_mapping' => [
                'nidn' => 'nidn',
                'nama' => 'name', // for User
                'email' => 'email',
                'pendidikan' => 'pendidikan',
                'pendidikan_terakhir' => 'pendidikan_terakhir',
                'universitas' => 'universitas',
                'phone' => 'phone',
                'address' => 'address',
                'prodi' => 'prodi',
                'jabatan_fungsional' => 'jabatan_fungsional',
                'dosen_tetap' => 'dosen_tetap',
            ],
            'unique_column' => 'nidn',
            'has_user' => true,
        ],
        'dosen_pa' => [
            'model' => \App\Models\Mahasiswa::class, // We're updating mahasiswa with dosen_pa_id
            'required_columns' => ['nim', 'nidn_dosen_pa'],
            'column_mapping' => [
                'nim' => 'nim',
                'nidn_dosen_pa' => 'nidn_dosen_pa',
            ],
            'unique_column' => 'nim',
            'has_user' => false,
            'is_relation_update' => true,
        ],
        'mata_kuliah' => [
            'model' => \App\Models\MataKuliah::class,
            'required_columns' => ['kode_mk', 'nama_matkul', 'sks', 'semester'],
            'column_mapping' => [
                'kode_mk' => 'kode_mk',
                'kode_id' => 'kode_id',
                // accept common header `nama_matkul` and alias `nama_mk`, map to DB field `nama_mk`
                'nama_matkul' => 'nama_mk',
                'nama_mk' => 'nama_mk',
                'sks' => 'sks',
                'semester' => 'semester',
                'jenis' => 'jenis',
                'praktikum' => 'praktikum',
                'prodi_id' => 'prodi_id',
                'fakultas_id' => 'fakultas_id',
                'deskripsi' => 'deskripsi',
            ],
            'unique_column' => 'kode_mk',
            'has_user' => false,
        ],
        'ruangan' => [
            'model' => \App\Models\Ruangan::class,
            'required_columns' => ['kode_ruangan', 'nama_ruangan', 'kapasitas'],
            'column_mapping' => [
                'kode_ruangan' => 'kode_ruangan',
                'nama_ruangan' => 'nama_ruangan',
                'gedung' => 'gedung',
                'lantai' => 'lantai',
                'kapasitas' => 'kapasitas',
                'kategori' => 'kategori',
                'kategori_nama' => 'kategori_nama',
                'kategori_id' => 'kategori_id',
                'status' => 'status',
            ],
            'unique_column' => 'kode_ruangan',
            'has_user' => false,
        ],
        'orang_tua' => [
            'model' => \App\Models\ParentModel::class,
            'required_columns' => ['nim_mahasiswa', 'nama_ortu', 'hubungan'],
            'column_mapping' => [
                'nim_mahasiswa' => 'nim_mahasiswa',
                'nama_ortu' => 'nama_ortu',
                'email' => 'email',
                'hubungan' => 'hubungan',
                'pekerjaan' => 'pekerjaan',
                'phone' => 'phone',
                'address' => 'address',
            ],
            'unique_column' => 'nim_mahasiswa',
            'has_user' => true,
        ],
    ];

    /**
     * Parse uploaded file and return array of data
     */
    public function parseFile(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        if ($extension === 'csv' || $extension === 'txt') {
            return $this->parseCsv($file);
        } elseif (in_array($extension, ['xlsx', 'xls'])) {
            return $this->parseExcel($file);
        }
        
        throw new \InvalidArgumentException('Format file tidak didukung. Gunakan CSV atau XLSX.');
    }

    /**
     * Parse CSV file
     */
    protected function parseCsv(UploadedFile $file): array
    {
        $data = [];
        $handle = fopen($file->getPathname(), 'r');
        
        // Detect delimiter
        $firstLine = fgets($handle);
        rewind($handle);
        
        $delimiter = ',';
        if (substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
            $delimiter = ';';
        }
        
        $header = null;
        $rowNumber = 0;
        
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $rowNumber++;
            
            // Skip empty rows
            if (count($row) === 1 && empty($row[0])) {
                continue;
            }
            
            if ($header === null) {
                // Normalize header names
                $header = array_map(function ($col) {
                    return strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '_', $col)));
                }, $row);
                continue;
            }
            
            // Map row to header
            $rowData = [];
            foreach ($header as $index => $column) {
                $rowData[$column] = isset($row[$index]) ? trim($row[$index]) : null;
            }
            $rowData['_row_number'] = $rowNumber;
            $data[] = $rowData;
        }
        
        fclose($handle);
        return $data;
    }

    /**
     * Parse Excel file (XLSX/XLS)
     */
    protected function parseExcel(UploadedFile $file): array
    {
        // Simple XLSX parsing using built-in PHP
        $zip = new \ZipArchive();
        $filePath = $file->getPathname();
        
        if ($zip->open($filePath) !== true) {
            throw new \RuntimeException('Tidak dapat membuka file Excel.');
        }
        
        // Read shared strings
        $sharedStrings = [];
        $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedStringsXml) {
            $xml = simplexml_load_string($sharedStringsXml);
            foreach ($xml->si as $si) {
                $sharedStrings[] = (string)$si->t;
            }
        }
        
        // Read the first sheet
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        if (!$sheetXml) {
            $zip->close();
            throw new \RuntimeException('Tidak dapat membaca sheet Excel.');
        }
        
        $xml = simplexml_load_string($sheetXml);
        $data = [];
        $header = null;
        $rowNumber = 0;
        
        foreach ($xml->sheetData->row as $row) {
            $rowNumber++;
            $rowData = [];
            $cellIndex = 0;
            
            foreach ($row->c as $cell) {
                $value = '';
                
                // Check if cell uses shared string
                if (isset($cell['t']) && (string)$cell['t'] === 's') {
                    $stringIndex = (int)$cell->v;
                    $value = $sharedStrings[$stringIndex] ?? '';
                } else {
                    $value = (string)$cell->v;
                }
                
                // Get cell reference to determine column
                $cellRef = (string)$cell['r'];
                preg_match('/^([A-Z]+)/', $cellRef, $matches);
                $colLetter = $matches[1] ?? 'A';
                $colIndex = $this->columnLetterToIndex($colLetter);
                
                // Fill gaps
                while ($cellIndex < $colIndex) {
                    $rowData[] = null;
                    $cellIndex++;
                }
                
                $rowData[] = trim($value);
                $cellIndex++;
            }
            
            // Skip empty rows
            if (empty(array_filter($rowData))) {
                continue;
            }
            
            if ($header === null) {
                $header = array_map(function ($col) {
                    return strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '_', $col ?? '')));
                }, $rowData);
                continue;
            }
            
            // Map row to header
            $mappedRow = [];
            foreach ($header as $index => $column) {
                if (!empty($column)) {
                    $mappedRow[$column] = $rowData[$index] ?? null;
                }
            }
            $mappedRow['_row_number'] = $rowNumber;
            $data[] = $mappedRow;
        }
        
        $zip->close();
        return $data;
    }

    /**
     * Convert Excel column letter to index
     */
    protected function columnLetterToIndex(string $letter): int
    {
        $letter = strtoupper($letter);
        $result = 0;
        for ($i = 0; $i < strlen($letter); $i++) {
            $result = $result * 26 + (ord($letter[$i]) - ord('A') + 1);
        }
        return $result - 1;
    }

    /**
     * Validate data before import
     */
    public function validateData(string $type, array $data): array
    {
        if (!isset($this->importTypes[$type])) {
            return [
                'valid' => false,
                'errors' => [
                    [
                        'row' => 0,
                        'errors' => ['Tipe import tidak valid: ' . $type],
                    ]
                ],
                'duplicates' => [],
                'validated_data' => [],
                'total_rows' => 0,
                'valid_rows' => 0,
                'error_rows' => 0,
                'duplicate_rows' => 0,
            ];
        }

        $config = $this->importTypes[$type];
        $errors = [];
        $validatedData = [];
        $duplicates = [];

        // Check for required columns in first row
        if (!empty($data)) {
            $firstRow = $data[0];
            $missingColumns = [];
            
            foreach ($config['required_columns'] as $required) {
                $found = false;
                foreach (array_keys($firstRow) as $column) {
                    if (strpos($column, $required) !== false || $required === $column) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $missingColumns[] = $required;
                }
            }
            
            if (!empty($missingColumns)) {
                return [
                    'valid' => false,
                    'errors' => [
                        [
                            'row' => 1,
                            'errors' => ['Kolom wajib tidak ditemukan: ' . implode(', ', $missingColumns)],
                        ]
                    ],
                    'duplicates' => [],
                    'validated_data' => [],
                    'total_rows' => count($data),
                    'valid_rows' => 0,
                    'error_rows' => count($data),
                    'duplicate_rows' => 0,
                ];
            }
        }

        $uniqueColumn = $config['unique_column'];
        $seenValues = [];

        foreach ($data as $index => $row) {
            $rowNumber = $row['_row_number'] ?? ($index + 2);
            $rowErrors = [];

            // Check required fields
            foreach ($config['required_columns'] as $required) {
                $value = $this->getColumnValue($row, $required);
                if (empty($value)) {
                    $rowErrors[] = "Kolom '{$required}' wajib diisi";
                }
            }

            // Check for duplicates in file
            $uniqueValue = $this->getColumnValue($row, $uniqueColumn);
            if (!empty($uniqueValue)) {
                if (isset($seenValues[$uniqueValue])) {
                    $rowErrors[] = "Duplikat '{$uniqueColumn}': {$uniqueValue} (baris {$seenValues[$uniqueValue]})";
                }
                $seenValues[$uniqueValue] = $rowNumber;
            }

            // Check for existing data in database
            if (!empty($uniqueValue) && empty($rowErrors)) {
                $existsInDb = $this->checkExistsInDatabase($type, $uniqueColumn, $uniqueValue);
                if ($existsInDb) {
                    $duplicates[] = [
                        'row' => $rowNumber,
                        'value' => $uniqueValue,
                        'column' => $uniqueColumn,
                    ];
                }
            }

            if (!empty($rowErrors)) {
                $errors[] = [
                    'row' => $rowNumber,
                    'errors' => $rowErrors,
                ];
            } else {
                $validatedData[] = array_merge($row, ['_row_number' => $rowNumber]);
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'duplicates' => $duplicates,
            'validated_data' => $validatedData,
            'total_rows' => count($data),
            'valid_rows' => count($validatedData),
            'error_rows' => count($errors),
            'duplicate_rows' => count($duplicates),
        ];
    }

    /**
     * Get column value with fallback for similar column names
     */
    protected function getColumnValue(array $row, string $column): ?string
    {
        if (isset($row[$column]) && !empty($row[$column])) {
            return $row[$column];
        }
        
        // Try to find similar column
        foreach ($row as $key => $value) {
            if (strpos($key, $column) !== false || strpos($column, $key) !== false) {
                return $value;
            }
        }
        
        return null;
    }

    /**
     * Check if value exists in database
     */
    protected function checkExistsInDatabase(string $type, string $column, string $value): bool
    {
        $config = $this->importTypes[$type];
        $model = $config['model'];
        
        return $model::where($column, $value)->exists();
    }

    /**
     * Import data to database
     */
    public function import(string $type, array $data, int $userId = null, bool $skipDuplicates = true): array
    {
        if (!isset($this->importTypes[$type])) {
            throw new \InvalidArgumentException('Tipe import tidak valid: ' . $type);
        }

        $config = $this->importTypes[$type];
        $results = [
            'success' => [],
            'failed' => [],
            'skipped' => [],
        ];

        DB::beginTransaction();
        
        try {
            foreach ($data as $row) {
                $rowNumber = $row['_row_number'] ?? 0;
                
                try {
                    $result = $this->importRow($type, $row, $config, $skipDuplicates);
                    
                    if ($result['status'] === 'success') {
                        $results['success'][] = $rowNumber;
                    } elseif ($result['status'] === 'skipped') {
                        $results['skipped'][] = [
                            'row' => $rowNumber,
                            'reason' => $result['reason'],
                        ];
                    }
                } catch (\Exception $e) {
                    $results['failed'][] = [
                        'row' => $rowNumber,
                        'error' => $e->getMessage(),
                    ];
                }
            }

            DB::commit();

            // Log the import
            $this->logImport($type, $results, $userId);

            return [
                'success' => true,
                'message' => 'Import selesai',
                'results' => $results,
                'summary' => [
                    'total' => count($data),
                    'success' => count($results['success']),
                    'failed' => count($results['failed']),
                    'skipped' => count($results['skipped']),
                ],
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import error: ' . $e->getMessage(), [
                'type' => $type,
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Import gagal: ' . $e->getMessage(),
                'results' => $results,
            ];
        }
    }

    /**
     * Import a single row
     */
    protected function importRow(string $type, array $row, array $config, bool $skipDuplicates): array
    {
        $uniqueColumn = $config['unique_column'];
        $uniqueValue = $this->getColumnValue($row, $uniqueColumn);
        
        // Check for duplicates - for mata_kuliah, we handle update instead of skip
        $existing = null;
        if ($this->checkExistsInDatabase($type, $uniqueColumn, $uniqueValue)) {
            if ($skipDuplicates && $type !== 'mata_kuliah') {
                return [
                    'status' => 'skipped',
                    'reason' => "Data dengan {$uniqueColumn} = {$uniqueValue} sudah ada",
                ];
            }
            // Get existing record for update
            $model = $config['model'];
            $existing = $model::where($uniqueColumn, $uniqueValue)->first();
        }

        // Map columns
        $mappedData = $this->mapColumns($row, $config['column_mapping']);
        
        // Handle special types
        switch ($type) {
            case 'mahasiswa':
                return $this->importMahasiswa($mappedData, $row);
            case 'dosen':
                return $this->importDosen($mappedData, $row);
            case 'dosen_pa':
                return $this->importDosenPa($row);
            case 'mata_kuliah':
                return $this->importMataKuliah($mappedData, $row, $existing);
            case 'ruangan':
                return $this->importRuangan($mappedData, $row);
            case 'orang_tua':
                return $this->importOrangTua($mappedData, $row);
            default:
                throw new \InvalidArgumentException('Handler tidak tersedia untuk tipe: ' . $type);
        }
    }

    /**
     * Map columns based on configuration
     */
    protected function mapColumns(array $row, array $mapping): array
    {
        $mapped = [];
        
        foreach ($mapping as $sourceCol => $targetCol) {
            $value = $this->getColumnValue($row, $sourceCol);
            if ($value !== null) {
                $mapped[$targetCol] = $value;
            }
        }
        
        return $mapped;
    }

    /**
     * Import Mahasiswa
     */
    protected function importMahasiswa(array $data, array $row): array
    {
        $name = $data['name'] ?? $this->getColumnValue($row, 'nama_lengkap') ?? $this->getColumnValue($row, 'nama');
        $email = $data['email'] ?? strtolower($data['nim']) . '@student.stih.ac.id';
        
        // Create user
        $user = \App\Models\User::create([
            'name' => $name,
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make('mahasiswa123'),
            'role' => 'mahasiswa',
        ]);

        // Create mahasiswa
        \App\Models\Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $data['nim'],
            'prodi' => $data['prodi'] ?? null,
            'angkatan' => $data['angkatan'] ?? date('Y'),
            'semester' => $data['semester'] ?? 1,
            'phone' => $data['phone'] ?? $data['no_hp'] ?? null,
            'address' => $data['address'] ?? $data['alamat'] ?? null,
            'no_hp' => $data['no_hp'] ?? $data['phone'] ?? null,
            'alamat' => $data['alamat'] ?? $data['address'] ?? null,
            'jenis_kelamin' => $data['jenis_kelamin'] ?? null,
            'tempat_lahir' => $data['tempat_lahir'] ?? null,
            'tanggal_lahir' => $this->parseDate($data['tanggal_lahir'] ?? null),
            'agama' => $data['agama'] ?? null,
            'status' => 'aktif',
            'status_akun' => 'aktif',
        ]);

        return ['status' => 'success'];
    }

    /**
     * Import Dosen
     */
    protected function importDosen(array $data, array $row): array
    {
        $name = $data['name'] ?? $this->getColumnValue($row, 'nama_lengkap') ?? $this->getColumnValue($row, 'nama');
        $email = $data['email'] ?? 'dosen' . $data['nidn'] . '@stih.ac.id';
        
        // Create user
        $user = \App\Models\User::create([
            'name' => $name,
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make('dosen123'),
            'role' => 'dosen',
        ]);

        // Parse arrays
        $pendidikanTerakhir = $data['pendidikan_terakhir'] ?? null;
        if (is_string($pendidikanTerakhir) && !empty($pendidikanTerakhir)) {
            $pendidikanTerakhir = [$pendidikanTerakhir];
        }

        $universitas = $data['universitas'] ?? null;
        if (is_string($universitas) && !empty($universitas)) {
            $universitas = [$universitas];
        }

        $prodi = $data['prodi'] ?? null;
        if (is_string($prodi) && !empty($prodi)) {
            $prodi = [$prodi];
        }

        $jabatanFungsional = $data['jabatan_fungsional'] ?? null;
        if (is_string($jabatanFungsional) && !empty($jabatanFungsional)) {
            $jabatanFungsional = [$jabatanFungsional];
        }

        // Dosen tetap handling
        $dosenTetap = false;
        if (isset($data['dosen_tetap'])) {
            $val = strtolower($data['dosen_tetap']);
            $dosenTetap = in_array($val, ['ya', 'yes', '1', 'true', 'tetap']);
        }

        // Create dosen
        \App\Models\Dosen::create([
            'user_id' => $user->id,
            'nidn' => $data['nidn'],
            'pendidikan' => $data['pendidikan'] ?? (is_array($pendidikanTerakhir) ? end($pendidikanTerakhir) : null),
            'pendidikan_terakhir' => $pendidikanTerakhir,
            'universitas' => $universitas,
            'prodi' => $prodi,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'jabatan_fungsional' => $jabatanFungsional,
            'dosen_tetap' => $dosenTetap,
            'status' => 'aktif',
        ]);

        return ['status' => 'success'];
    }

    /**
     * Import Dosen PA assignments
     */
    protected function importDosenPa(array $row): array
    {
        $nim = $this->getColumnValue($row, 'nim');
        $nidnDosenPa = $this->getColumnValue($row, 'nidn_dosen_pa');
        
        // Find mahasiswa
        $mahasiswa = \App\Models\Mahasiswa::where('nim', $nim)->first();
        if (!$mahasiswa) {
            throw new \Exception("Mahasiswa dengan NIM {$nim} tidak ditemukan");
        }
        
        // Find dosen
        $dosen = \App\Models\Dosen::where('nidn', $nidnDosenPa)->first();
        if (!$dosen) {
            throw new \Exception("Dosen dengan NIDN {$nidnDosenPa} tidak ditemukan");
        }
        
        // Check if dosen_pa relation exists
        $existingPa = DB::table('dosen_pa')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('dosen_id', $dosen->id)
            ->first();
            
        if (!$existingPa) {
            DB::table('dosen_pa')->insert([
                'mahasiswa_id' => $mahasiswa->id,
                'dosen_id' => $dosen->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return ['status' => 'success'];
    }

    /**
     * Import Mata Kuliah
     * Matches existing MataKuliahController import logic
     */
    protected function importMataKuliah(array $data, array $row, $existing = null): array
    {
        // Get default prodi and fakultas (first active ones) - matches existing backend
        $defaultProdi = \App\Models\Prodi::where('status', 'aktif')->first();
        $defaultFakultas = \App\Models\Fakultas::where('status', 'aktif')->first();

        if (!$defaultProdi || !$defaultFakultas) {
            return [
                'status' => 'skipped',
                'reason' => 'Prodi atau Fakultas default tidak ditemukan',
            ];
        }

        // Determine jenis based on kode_mk prefix - matches existing backend logic
        $jenis = 'wajib_prodi'; // default
        $kodeMk = $data['kode_mk'] ?? '';
        $prefix = substr($kodeMk, 0, 4);
        switch ($prefix) {
            case 'ADH1':
                $jenis = 'wajib_nasional';
                break;
            case 'ADH2':
                $jenis = 'wajib_prodi';
                break;
            case 'ADH3':
                $jenis = 'pilihan';
                break;
            case 'ADH4':
                $jenis = 'peminatan';
                break;
            case 'ADD2':
                $jenis = 'wajib_prodi';
                break;
        }

        $mataKuliahData = [
            'kode_id' => $data['kode_id'] ?? null,
            'kode_mk' => $kodeMk,
            'nama_mk' => $data['nama_mk'],
            'sks' => !empty($data['sks']) ? (int)$data['sks'] : 2,
            'semester' => !empty($data['semester']) ? (int)$data['semester'] : 1,
            'praktikum' => !empty($data['praktikum']) ? (int)$data['praktikum'] : 0,
            'jenis' => $jenis,
            'prodi_id' => $defaultProdi->id,
            'fakultas_id' => $defaultFakultas->id,
        ];

        if ($existing) {
            $existing->update($mataKuliahData);
            return ['status' => 'success', 'action' => 'updated'];
        } else {
            \App\Models\MataKuliah::create($mataKuliahData);
            return ['status' => 'success', 'action' => 'created'];
        }
    }

    /**
     * Import Ruangan
     */
    protected function importRuangan(array $data, array $row): array
    {
        $kategoriId = null;

        // Resolve kategori_id if provided
        if (!empty($data['kategori_id'])) {
            $kategoriId = (int) $data['kategori_id'];
        } elseif (!empty($data['kategori_nama'])) {
            // Try to find kategori by name
            $kategori = \App\Models\KategoriRuangan::where('nama_kategori', $data['kategori_nama'])
                ->first();
            if ($kategori) {
                $kategoriId = $kategori->id;
            }
        } elseif (!empty($data['kategori'])) {
            // Try to find kategori by name (alias)
            $kategori = \App\Models\KategoriRuangan::where('nama_kategori', $data['kategori'])
                ->first();
            if ($kategori) {
                $kategoriId = $kategori->id;
            }
        }

        \App\Models\Ruangan::create([
            'kode_ruangan' => $data['kode_ruangan'],
            'nama_ruangan' => $data['nama_ruangan'],
            'gedung' => $data['gedung'] ?? null,
            'lantai' => (int) ($data['lantai'] ?? 1),
            'kapasitas' => (int) $data['kapasitas'],
            'kategori_id' => $kategoriId,
            'status' => $data['status'] ?? 'aktif',
        ]);

        return ['status' => 'success'];
    }

    /**
     * Import Orang Tua/Wali
     */
    protected function importOrangTua(array $data, array $row): array
    {
        $nimMahasiswa = $data['nim_mahasiswa'] ?? $this->getColumnValue($row, 'nim_mahasiswa');
        $namaOrtu = $data['nama_ortu'] ?? $this->getColumnValue($row, 'nama_ortu');
        $hubungan = $data['hubungan'] ?? 'wali';

        // Find mahasiswa
        $mahasiswa = \App\Models\Mahasiswa::where('nim', $nimMahasiswa)->first();
        if (!$mahasiswa) {
            throw new \Exception("Mahasiswa dengan NIM {$nimMahasiswa} tidak ditemukan");
        }

        // Generate email if not provided
        $email = $data['email'] ?? null;
        if (empty($email)) {
            $email = 'ortu.' . strtolower(str_replace(' ', '', $nimMahasiswa)) . '@parent.stih.ac.id';
        }

        // Check if user with this email already exists
        $existingUser = \App\Models\User::where('email', $email)->first();
        if ($existingUser) {
            // Check if parent record already exists for this user+mahasiswa
            $existingParent = \App\Models\ParentModel::where('user_id', $existingUser->id)
                ->where('mahasiswa_id', $mahasiswa->id)
                ->first();
            if ($existingParent) {
                return [
                    'status' => 'skipped',
                    'reason' => "Data orang tua untuk mahasiswa NIM {$nimMahasiswa} sudah ada",
                ];
            }
        }

        // Create user
        $user = $existingUser ?? \App\Models\User::create([
            'name' => $namaOrtu,
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make('orangtua123'),
            'role' => 'parent',
        ]);

        // Create parent record
        \App\Models\ParentModel::create([
            'user_id' => $user->id,
            'mahasiswa_id' => $mahasiswa->id,
            'hubungan' => strtolower($hubungan),
            'pekerjaan' => $data['pekerjaan'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        return ['status' => 'success'];
    }

    /**
     * Parse date from various formats
     */
    protected function parseDate(?string $date): ?string
    {
        if (empty($date)) {
            return null;
        }

        // Try common formats
        $formats = ['Y-m-d', 'd-m-Y', 'd/m/Y', 'Y/m/d', 'd.m.Y'];
        
        foreach ($formats as $format) {
            $parsed = \DateTime::createFromFormat($format, $date);
            if ($parsed !== false) {
                return $parsed->format('Y-m-d');
            }
        }

        return null;
    }

    /**
     * Log import activity
     */
    protected function logImport(string $type, array $results, ?int $userId): void
    {
        try {
            ImportLog::create([
                'user_id' => $userId,
                'type' => $type,
                'total_rows' => count($results['success']) + count($results['failed']) + count($results['skipped']),
                'success_count' => count($results['success']),
                'failed_count' => count($results['failed']),
                'skipped_count' => count($results['skipped']),
                'details' => json_encode($results),
                'imported_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log import: ' . $e->getMessage());
        }
    }

    /**
     * Get import configuration
     */
    public function getImportConfig(string $type): ?array
    {
        return $this->importTypes[$type] ?? null;
    }

    /**
     * Get all supported import types
     */
    public function getSupportedTypes(): array
    {
        return array_keys($this->importTypes);
    }

    /**
     * Generate template columns for a type
     */
    public function getTemplateColumns(string $type): array
    {
        if (!isset($this->importTypes[$type])) {
            return [];
        }

        $config = $this->importTypes[$type];
        return array_keys($config['column_mapping']);
    }
}
