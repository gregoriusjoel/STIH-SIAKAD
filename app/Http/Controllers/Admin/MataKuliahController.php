<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Prodi;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MataKuliahController extends Controller
{
    public function index()
    {
        $mataKuliahs = MataKuliah::with(['prodi', 'fakultas'])->paginate(10);
        return view('admin.mata-kuliah.index', compact('mataKuliahs'));
    }

    public function create()
    {
        $prodis = Prodi::where('status', 'aktif')->get();
        $fakultas = Fakultas::with('prodis')->where('status', 'aktif')->get();
        return view('admin.mata-kuliah.create', compact('prodis', 'fakultas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|unique:mata_kuliahs,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            'praktikum' => 'nullable|integer|min:0|max:10',
            'jenis' => 'required|in:wajib_nasional,wajib_prodi,pilihan,peminatan',
            'prodi_id' => 'required|exists:prodis,id',
            'fakultas_id' => 'required|exists:fakultas,id',
            'deskripsi' => 'nullable|string',
        ]);

        MataKuliah::create($request->all());

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan');
    }

    public function show(MataKuliah $mataKuliah)
    {
        $mataKuliah->load(['kelasMataKuliahs.dosen.user', 'prodi', 'fakultas']);
        return view('admin.mata-kuliah.show', compact('mataKuliah'));
    }

    public function edit(MataKuliah $mataKuliah)
    {
        $prodis = Prodi::where('status', 'aktif')->get();
        $fakultas = Fakultas::with('prodis')->where('status', 'aktif')->get();
        return view('admin.mata-kuliah.edit', compact('mataKuliah', 'prodis', 'fakultas'));
    }

    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $request->validate([
            'kode_mk' => 'required|unique:mata_kuliahs,kode_mk,' . $mataKuliah->id,
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            'praktikum' => 'nullable|integer|min:0|max:10',
            'jenis' => 'required|in:wajib_nasional,wajib_prodi,pilihan,peminatan',
            'prodi_id' => 'required|exists:prodis,id',
            'fakultas_id' => 'required|exists:fakultas,id',
            'deskripsi' => 'nullable|string',
        ]);

        $mataKuliah->update($request->all());

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil diupdate');
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        try {
            $mataKuliah->delete();
            return redirect()->route('admin.mata-kuliah.index')
                ->with('success', 'Mata kuliah berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');
        $header = null;
        $imported = 0;
        $updated = 0;
        $failed = 0;
        $errors = [];
        $detailedErrors = [];
        $rowNumber = 1;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 0, ',')) !== false) {
                if (!$header) {
                    $header = array_map(function ($h) {
                        return strtolower(trim($h));
                    }, $row);
                    $rowNumber++;
                    continue;
                }

                $data = [];
                foreach ($header as $i => $key) {
                    $data[$key] = isset($row[$i]) ? trim($row[$i]) : null;
                }

                // Skip if kode_mk or nama_matkul is empty
                if (empty($data['kode_mk']) || empty($data['nama_matkul'])) {
                    $failed++;
                    $detailedErrors[] = "Baris $rowNumber: kode_mk atau nama_matkul kosong";
                    $rowNumber++;
                    continue;
                }

                try {
                    // Get default prodi and fakultas (first active ones)
                    $defaultProdi = Prodi::where('status', 'aktif')->first();
                    $defaultFakultas = Fakultas::where('status', 'aktif')->first();

                    if (!$defaultProdi || !$defaultFakultas) {
                        $detailedErrors[] = "Baris $rowNumber: Prodi atau Fakultas default tidak ditemukan";
                        $failed++;
                        $rowNumber++;
                        continue;
                    }

                    // Check if mata kuliah already exists
                    $existing = MataKuliah::where('kode_mk', $data['kode_mk'])->first();

                    // Determine jenis based on kode_mk prefix
                    $jenis = 'wajib_prodi'; // default
                    $prefix = substr($data['kode_mk'], 0, 4);
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
                        'kode_mk' => $data['kode_mk'],
                        'nama_mk' => $data['nama_matkul'],
                        'sks' => !empty($data['sks']) ? (int)$data['sks'] : 2,
                        'semester' => !empty($data['semester']) ? (int)$data['semester'] : 1,
                        'praktikum' => !empty($data['praktikum']) ? (int)$data['praktikum'] : 0,
                        'jenis' => $jenis,
                        'prodi_id' => $defaultProdi->id,
                        'fakultas_id' => $defaultFakultas->id,
                    ];

                    if ($existing) {
                        $existing->update($mataKuliahData);
                        $updated++;
                    } else {
                        MataKuliah::create($mataKuliahData);
                        $imported++;
                    }

                    $rowNumber++;
                } catch (\Exception $e) {
                    $failed++;
                    $detailedErrors[] = "Baris $rowNumber: " . $e->getMessage();
                    $rowNumber++;
                    continue;
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        } finally {
            if (is_resource($handle)) fclose($handle);
        }

        $message = "Import selesai: $imported baru, $updated diperbarui, $failed gagal.";
        if (count($detailedErrors)) {
            $message .= ' Lihat detail error di bawah.';
        }

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', $message)
            ->with('import_errors', $detailedErrors);
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="mata_kuliah_template.csv"',
        ];

        $columns = ['kode_id', 'kode_mk', 'nama_matkul', 'praktikum', 'sks', 'semester'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            // Use comma as delimiter
            fputcsv($file, $columns, ',');
            // sample rows
            fputcsv($file, ['sms1', 'ADH10010', 'Ilmu Agama', '', '2', '1'], ',');
            fputcsv($file, ['sms1', 'ADH10006', 'Bahasa Indonesia Hukum', '', '2', '1'], ',');
            fputcsv($file, ['sms1', 'ADH10007', 'Pancasila & Kewargakabupatenan', '', '3', '1'], ',');
            fclose($file);
        };

        return response()->streamDownload($callback, 'mata_kuliah_template.csv', $headers);
    }
}
