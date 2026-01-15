@extends('layouts.admin')
@section('title', 'Tambah User')
@section('page-title', 'Tambah User')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg border-t-4 border-indigo-600">
        <div class="p-6 border-b border-gray-200 bg-indigo-600 text-white rounded-t-xl"><h3 class="text-xl font-bold flex items-center"><i class="fas fa-user-plus mr-3 text-2xl"></i>Tambah User Baru</h3></div>
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-user text-gray-400 mr-1"></i>Nama Lengkap *</label><input type="text" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" required></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-envelope text-gray-400 mr-1"></i>Email *</label><input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" required></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-shield-alt text-gray-400 mr-1"></i>Role *</label><select name="role" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" required><option value="">Pilih Role</option><option value="admin">Admin</option><option value="dosen">Dosen</option><option value="mahasiswa">Mahasiswa</option><option value="parent">Parent</option></select></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-lock text-gray-400 mr-1"></i>Password *</label><input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" required></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-lock text-gray-400 mr-1"></i>Konfirmasi Password *</label><input type="password" name="password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" required></div>
            </div>
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t"><a href="{{ route('admin.users.index') }}" class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center"><i class="fas fa-times mr-2"></i>Batal</a><button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition flex items-center shadow-md transform hover:scale-105"><i class="fas fa-save mr-2"></i>Simpan</button></div>
        </form>
    </div>
</div>
@endsection
