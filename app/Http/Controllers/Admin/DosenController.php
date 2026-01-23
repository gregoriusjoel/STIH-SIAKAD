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
            'password' => 'required|min:6',
            'nidn' => 'required|unique:dosens,nidn',
            'pendidikan' => 'required|string',
            'prodi' => 'required|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'mata_kuliah_ids' => 'nullable|array',
            'mata_kuliah_ids.*' => 'exists:mata_kuliahs,id',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
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
            'nidn' => 'required|unique:dosens,nidn,' . $dosen->id,
            'pendidikan' => 'required|string',
            'prodi' => 'required|string',
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
}
