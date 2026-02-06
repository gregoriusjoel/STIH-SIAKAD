@extends('layouts.admin')
@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('content')
    <div class="w-full">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-t-4 border-maroon">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-maroon text-white rounded-t-xl">
                <h3 class="text-xl font-bold flex items-center"><i class="fas fa-user-edit mr-3 text-2xl"></i>Edit User</h3>
            </div>
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><i
                                class="fas fa-user text-gray-400 dark:text-gray-500 mr-1"></i>Nama Lengkap *</label><input type="text"
                            name="name" value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition"
                            required></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><i
                                class="fas fa-envelope text-gray-400 dark:text-gray-500 mr-1"></i>Email *</label><input type="email"
                            name="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition"
                            required></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><i
                                class="fas fa-shield-alt text-gray-400 dark:text-gray-500 mr-1"></i>Role *</label><select name="role"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition"
                            required>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="dosen" {{ old('role', $user->role) == 'dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="mahasiswa" {{ old('role', $user->role) == 'mahasiswa' ? 'selected' : '' }}>
                                Mahasiswa</option>
                            <option value="parent" {{ old('role', $user->role) == 'parent' ? 'selected' : '' }}>Parent
                            </option>
                            <option value="keuangan" {{ old('role', $user->role) == 'keuangan' ? 'selected' : '' }}>Keuangan
                            </option>
                            <option value="perpustakaan" {{ old('role', $user->role) == 'perpustakaan' ? 'selected' : '' }}>
                                Perpustakaan</option>
                        </select></div>
                    <div class="md:col-span-2 bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-900/30 rounded-lg p-4">
                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-3"><i
                                class="fas fa-info-circle text-maroon mr-1"></i><strong>Password:</strong> Kosongkan jika
                            tidak ingin mengubah password</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><i
                                        class="fas fa-lock text-gray-400 dark:text-gray-500 mr-1"></i>Password Baru</label><input
                                    type="password" name="password"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition">
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><i
                                        class="fas fa-lock text-gray-400 dark:text-gray-500 mr-1"></i>Konfirmasi Password</label><input
                                    type="password" name="password_confirmation"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t dark:border-gray-700"><a href="{{ route('admin.users.index') }}"
                        class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition flex items-center"><i
                            class="fas fa-times mr-2"></i>Batal</a><button type="submit"
                        class="bg-maroon text-white px-6 py-3 rounded-lg hover:bg-red-800 transition flex items-center shadow-md transform hover:scale-105"><i
                            class="fas fa-save mr-2"></i>Update</button></div>
            </form>
        </div>
    </div>
@endsection