<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ParentController extends Controller
{
    public function index()
    {
        $parents = ParentModel::with(['user', 'mahasiswa.user'])->paginate(10);
        return view('admin.parents.index', compact('parents'));
    }

    public function create()
    {
        $mahasiswas = Mahasiswa::with('user')->get();
        return view('admin.parents.create', compact('mahasiswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'hubungan' => 'required|in:ayah,ibu,wali',
            'pekerjaan' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'parent',
            ]);

            ParentModel::create([
                'user_id' => $user->id,
                'mahasiswa_id' => $request->mahasiswa_id,
                'hubungan' => $request->hubungan,
                'pekerjaan' => $request->pekerjaan,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            DB::commit();
            return redirect()->route('admin.parents.index')->with('success', 'Data orang tua berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function edit(ParentModel $parent)
    {
        $mahasiswas = Mahasiswa::with('user')->get();
        return view('admin.parents.edit', compact('parent', 'mahasiswas'));
    }

    public function update(Request $request, ParentModel $parent)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $parent->user_id,
            'password' => 'nullable|min:6',
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'hubungan' => 'required|in:ayah,ibu,wali',
            'pekerjaan' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $userData = ['name' => $request->name, 'email' => $request->email];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $parent->user->update($userData);

            $parent->update([
                'mahasiswa_id' => $request->mahasiswa_id,
                'hubungan' => $request->hubungan,
                'pekerjaan' => $request->pekerjaan,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            DB::commit();
            return redirect()->route('admin.parents.index')->with('success', 'Data orang tua berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(ParentModel $parent)
    {
        DB::beginTransaction();
        try {
            $user = $parent->user;
            $parent->delete();
            $user->delete();
            DB::commit();
            return redirect()->route('admin.parents.index')->with('success', 'Data orang tua berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
