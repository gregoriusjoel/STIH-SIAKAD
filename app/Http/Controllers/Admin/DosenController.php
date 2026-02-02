<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function index()
    {
        $dosens = Dosen::with('user', 'kelasMataKuliahs.mataKuliah')->paginate(10);
        return view('admin.dosen.index', compact('dosens'));
    }

    public function create()
    {
        $mataKuliahs = \App\Models\MataKuliah::orderBy('nama_mk')->get();
        return view('admin.dosen.create', compact('mataKuliahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|min:6',
            'nidn' => 'required|digits_between:1,10|unique:dosens,nidn',
            'pendidikan' => 'required|string',
            'prodi' => 'required|array|min:1',
            'prodi.*' => 'string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'mata_kuliah_ids' => 'nullable|array',
            'mata_kuliah_ids.*' => 'exists:mata_kuliahs,id',
        ]);

        DB::beginTransaction();
        try {
            $plainPassword = $request->filled('password') ? $request->password : 'dosen123';
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($plainPassword),
                'role' => 'dosen',
            ]);

            $dosen = Dosen::create([
                'user_id' => $user->id,
                'nidn' => $request->nidn,
                'pendidikan' => $request->pendidikan,
                'prodi' => $request->prodi,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => 'aktif',
            ]);

            // store mata_kuliah ids directly on dosens table as JSON
            if ($request->filled('mata_kuliah_ids') && $dosen) {
                $dosen->update(['mata_kuliah_ids' => array_values($request->mata_kuliah_ids)]);
            }

            DB::commit();
            return redirect()->route('admin.dosen.index')
                ->with('success', 'Data dosen berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Dosen $dosen)
    {
        $dosen->load('user', 'kelasMataKuliahs.mataKuliah');

        $assignedMataKuliahs = collect();

        // If mata_kuliah_ids stored as JSON, use them
        if (!empty($dosen->mata_kuliah_ids) && is_array($dosen->mata_kuliah_ids) && count($dosen->mata_kuliah_ids) > 0) {
            $assignedMataKuliahs = \App\Models\MataKuliah::whereIn('id', $dosen->mata_kuliah_ids)->get();
        } else {
            // Fallback: try to read from a pivot relation if the pivot table exists
            try {
                $assigned = $dosen->mataKuliahs()->get();
                if ($assigned && $assigned->count()) {
                    $assignedMataKuliahs = $assigned;
                }
            } catch (\Throwable $e) {
                // pivot table likely doesn't exist — ignore
            }
        }

        return view('admin.dosen.show', compact('dosen', 'assignedMataKuliahs'));
    }

    public function edit(Dosen $dosen)
    {
        $mataKuliahs = \App\Models\MataKuliah::orderBy('nama_mk')->get();
        return view('admin.dosen.edit', compact('dosen', 'mataKuliahs'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $dosen->user_id,
            'nidn' => 'required|digits_between:1,10|unique:dosens,nidn,' . $dosen->id,
            'pendidikan' => 'required|string',
            'prodi' => 'required|array|min:1',
            'prodi.*' => 'string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'required|in:aktif,non-aktif',
            'mata_kuliah_ids' => 'nullable|array',
            'mata_kuliah_ids.*' => 'exists:mata_kuliahs,id',
        ]);

        DB::beginTransaction();
        try {
            $dosen->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $dosen->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            $dosen->update([
                'nidn' => $request->nidn,
                'pendidikan' => $request->pendidikan,
                'prodi' => $request->prodi,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => $request->status,
            ]);

            // update mata_kuliah_ids JSON column if provided
            if ($request->has('mata_kuliah_ids')) {
                $dosen->update(['mata_kuliah_ids' => array_values($request->mata_kuliah_ids ?? [])]);
            }

            DB::commit();
            return redirect()->route('admin.dosen.index')
                ->with('success', 'Data dosen berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Dosen $dosen)
    {
        try {
            $dosen->user->delete();
            return redirect()->route('admin.dosen.index')
                ->with('success', 'Data dosen berhasil dihapus');
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
        $path = $file->getRealPath();

        $handle = fopen($path, 'r');
        if ($handle === false) {
            return back()->with('error', 'Gagal membuka file.');
        }

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

                // minimal required: nidn and name and email
                if (empty($data['nidn']) || empty($data['name']) || empty($data['email'])) {
                    $failed++;
                    $errors[] = 'Baris dengan NIDN/name/email kosong diabaikan.';
                    continue;
                }

                $mkIds = [];
                try {
                    // check existing user by email
                        $user = User::where('email', $data['email'])->first();

                    if ($user) {
                        // update user name if different
                        if ($user->name !== $data['name']) {
                            $user->name = $data['name'];
                            $user->save();
                        }

                        // find or create dosen record
                        $dosen = Dosen::where('user_id', $user->id)->orWhere('nidn', $data['nidn'])->first();
                                if ($dosen) {
                                    $updateData = [
                                        'nidn' => $data['nidn'],
                                        'pendidikan' => $data['pendidikan'] ?? $dosen->pendidikan,
                                        'phone' => $data['phone'] ?? $dosen->phone,
                                        'prodi' => isset($data['prodi']) ? array_map('trim', explode('|', $data['prodi'])) : $dosen->prodi,
                                        'status' => $data['status'] ?? $dosen->status,
                                    ];

                                    // handle mata_kuliah_kode -> convert to ids and store
                                    if (!empty($data['mata_kuliah_kode'])) {
                                        $codes = preg_split('/[|,;]+/', $data['mata_kuliah_kode']);
                                        $codes = array_filter(array_map('trim', $codes));
                                        if (count($codes)) {
                                            $mks = \App\Models\MataKuliah::whereIn('kode_mk', $codes)->get();
                                            $mkIds = $mks->pluck('id')->toArray();
                                            $foundCodes = $mks->pluck('kode_mk')->toArray();
                                            $missing = array_values(array_diff($codes, $foundCodes));
                                            if (count($missing)) {
                                                $detailedErrors[] = "Baris $rowNumber: Kode mata kuliah tidak ditemukan: " . implode(', ', $missing);
                                            }
                                            $updateData['mata_kuliah_ids'] = $mkIds;
                                        }
                                    }

                                    $dosen->update($updateData);

                                    // try syncing pivot if exists
                                    try {
                                        if (!empty($mkIds) && method_exists($dosen, 'mataKuliahs')) {
                                            $dosen->mataKuliahs()->sync($mkIds);
                                        }
                                    } catch (\Throwable $e) {
                                        // ignore if pivot table missing
                                    }

                                    $updated++;
                                } else {
                                    $createData = [
                                        'user_id' => $user->id,
                                        'nidn' => $data['nidn'],
                                        'pendidikan' => $data['pendidikan'] ?? null,
                                        'prodi' => isset($data['prodi']) ? array_map('trim', explode('|', $data['prodi'])) : [],
                                        'phone' => $data['phone'] ?? null,
                                        'address' => $data['address'] ?? null,
                                        'status' => $data['status'] ?? 'aktif',
                                    ];

                                    if (!empty($data['mata_kuliah_kode'])) {
                                        $codes = preg_split('/[|,;]+/', $data['mata_kuliah_kode']);
                                        $codes = array_filter(array_map('trim', $codes));
                                        if (count($codes)) {
                                            $mks = \App\Models\MataKuliah::whereIn('kode_mk', $codes)->get();
                                            $mkIds = $mks->pluck('id')->toArray();
                                            $foundCodes = $mks->pluck('kode_mk')->toArray();
                                            $missing = array_values(array_diff($codes, $foundCodes));
                                            if (count($missing)) {
                                                $detailedErrors[] = "Baris $rowNumber: Kode mata kuliah tidak ditemukan: " . implode(', ', $missing);
                                            }
                                            $createData['mata_kuliah_ids'] = $mkIds;
                                        }
                                    }

                                    $dosen = Dosen::create($createData);

                                    try {
                                        if (!empty($mkIds) && method_exists($dosen, 'mataKuliahs')) {
                                            $dosen->mataKuliahs()->sync($mkIds);
                                        }
                                    } catch (\Throwable $e) {
                                        // ignore if pivot table missing
                                    }

                                    $imported++;
                                }
                        } else {
                        // create new user and dosen (use default password and role)
                        $plainPassword = 'dosen123';
                        $user = User::create([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'password' => Hash::make($plainPassword),
                            'role' => 'dosen',
                        ]);
                        
                        $createData = [
                            'user_id' => $user->id,
                            'nidn' => $data['nidn'],
                            'pendidikan' => $data['pendidikan'] ?? null,
                            'prodi' => isset($data['prodi']) ? array_map('trim', explode('|', $data['prodi'])) : [],
                            'phone' => $data['phone'] ?? null,
                            'address' => $data['address'] ?? null,
                            'status' => $data['status'] ?? 'aktif',
                        ];

                        if (!empty($data['mata_kuliah_kode'])) {
                            $codes = preg_split('/[|,;]+/', $data['mata_kuliah_kode']);
                            $codes = array_filter(array_map('trim', $codes));
                            if (count($codes)) {
                                $mks = \App\Models\MataKuliah::whereIn('kode_mk', $codes)->get();
                                $mkIds = $mks->pluck('id')->toArray();
                                $foundCodes = $mks->pluck('kode_mk')->toArray();
                                $missing = array_values(array_diff($codes, $foundCodes));
                                if (count($missing)) {
                                    $detailedErrors[] = "Baris $rowNumber: Kode mata kuliah tidak ditemukan: " . implode(', ', $missing);
                                }
                                $createData['mata_kuliah_ids'] = $mkIds;
                            }
                        }

                        $dosen = Dosen::create($createData);

                        try {
                            if (!empty($mkIds) && method_exists($dosen, 'mataKuliahs')) {
                                $dosen->mataKuliahs()->sync($mkIds);
                            }
                        } catch (\Throwable $e) {
                            // ignore if pivot table missing
                        }

                        $imported++;
                    }
                    $rowNumber++;
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = $e->getMessage();
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
        if (count($errors) || count($detailedErrors)) {
            $message .= ' Beberapa baris bermasalah atau ada peringatan.';
        }

        return redirect()->route('admin.dosen.index')
            ->with('success', $message)
            ->with('import_errors', $detailedErrors);
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="dosen_import_template.csv"',
        ];

        $columns = ['nidn', 'name', 'email', 'pendidikan', 'phone', 'prodi', 'status', 'address', 'mata_kuliah_kode'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            // sample rows demonstrating multiple kode MK separators
            fputcsv($file, ['123456', 'Budi Santoso', 'budi@example.com', 'S2', '08123456789', 'Teknik Informatika|Sistem Informasi', 'aktif', 'Jalan Merdeka 1', 'KD001|KD002']);
            fputcsv($file, ['234567', 'Siti Nurjanah', 'siti@example.com', 'S2', '081298765432', 'Hukum Bisnis', 'aktif', 'Jl. Contoh 2', 'KD001,KD003']);
            fputcsv($file, ['345678', 'Ahmad Fauzi', 'ahmad@example.com', 'S2', '081212345678', 'Hukum Pidana', 'aktif', 'Jl. Contoh 3', 'KD002;KD004']);
            fclose($file);
        };

        return response()->streamDownload($callback, 'dosen_import_template.csv', $headers);
    }
}
