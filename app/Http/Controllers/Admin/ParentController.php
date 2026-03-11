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

    public function getExistingData($mahasiswa_id)
    {
        $parentData = ParentModel::where('mahasiswa_id', $mahasiswa_id)->first();
        if ($parentData) {
            return response()->json([
                'success' => true,
                'data' => $parentData
            ]);
        }
        return response()->json(['success' => false, 'message' => 'No existing data found for this mahasiswa']);
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

            $parentData = [
                'user_id' => $user->id,
                'mahasiswa_id' => $request->mahasiswa_id,
                'hubungan' => $request->hubungan,
                'pekerjaan' => $request->pekerjaan,
                'phone' => $request->phone,
                'address' => $request->address,
                'tipe_wali' => $request->hubungan === 'wali' ? 'wali' : 'orang_tua',
            ];

            if ($request->hubungan === 'ayah') {
                $parentData['nama_ayah'] = $request->name;
                $parentData['handphone_ayah'] = $request->phone;
                $parentData['alamat_ayah'] = $request->address;
                $parentData['pekerjaan_ayah'] = $request->pekerjaan;
            } elseif ($request->hubungan === 'ibu') {
                $parentData['nama_ibu'] = $request->name;
                $parentData['handphone_ibu'] = $request->phone;
                $parentData['alamat_ibu'] = $request->address;
                $parentData['pekerjaan_ibu'] = $request->pekerjaan;
            } elseif ($request->hubungan === 'wali') {
                $parentData['nama_wali'] = $request->name;
                $parentData['handphone_wali'] = $request->phone;
                $parentData['alamat_wali'] = $request->address;
                $parentData['pekerjaan_wali'] = $request->pekerjaan;
            }

            ParentModel::create($parentData);

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
            'mahasiswa_id' => 'nullable|exists:mahasiswas,id',
            'hubungan' => 'required|in:ayah,ibu,wali',
            'pekerjaan' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Handle User Account Logic
            // If the parent already has a dedicated 'parent' user account, just update it.
            if ($parent->user && $parent->user->role === 'parent') {
                $userData = ['name' => $request->name, 'email' => $request->email];
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }
                $parent->user->update($userData);
            } 
            // If the parent is still tied to the 'mahasiswa' user account and the admin provides an email,
            // we should create a new dedicated 'parent' user account for them.
            elseif ($parent->user && $parent->user->role === 'mahasiswa' && $request->filled('email')) {
                // To avoid duplicate email conflicts, we'll check if the email isn't already used
                $existingUser = User::where('email', $request->email)->first();
                if(!$existingUser) {
                    $newUser = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password ?? 'parent123'),
                        'role' => 'parent',
                    ]);
                    $parent->user_id = $newUser->id;
                    $parent->save();
                } else {
                    throw new \Exception('Email sudah digunakan oleh pengguna lain.');
                }
            }

            $updateData = [
                'mahasiswa_id' => $request->mahasiswa_id,
                'hubungan' => $request->hubungan,
                'pekerjaan' => $request->pekerjaan,
                'phone' => $request->phone,
                'address' => $request->address,
                'tipe_wali' => $request->hubungan === 'wali' ? 'wali' : 'orang_tua',
            ];

            if ($request->hubungan === 'ayah') {
                $updateData['nama_ayah'] = $request->name;
                $updateData['handphone_ayah'] = $request->phone;
                $updateData['alamat_ayah'] = $request->address;
                $updateData['pekerjaan_ayah'] = $request->pekerjaan;
            } elseif ($request->hubungan === 'ibu') {
                $updateData['nama_ibu'] = $request->name;
                $updateData['handphone_ibu'] = $request->phone;
                $updateData['alamat_ibu'] = $request->address;
                $updateData['pekerjaan_ibu'] = $request->pekerjaan;
            } elseif ($request->hubungan === 'wali') {
                $updateData['nama_wali'] = $request->name;
                $updateData['handphone_wali'] = $request->phone;
                $updateData['alamat_wali'] = $request->address;
                $updateData['pekerjaan_wali'] = $request->pekerjaan;
            }

            $parent->update($updateData);

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

            // Only delete the user account if it belongs to a 'parent' role
            // (safety guard to prevent accidentally deleting mahasiswa accounts)
            if ($user && $user->role === 'parent') {
                $user->delete();
            }

            DB::commit();
            return redirect()->route('admin.parents.index')->with('success', 'Data orang tua berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
