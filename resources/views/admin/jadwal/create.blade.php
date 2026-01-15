@extends('layouts.admin')
@section('title', 'Tambah Jadwal')
@section('page-title', 'Tambah Jadwal')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg border-t-4 border-green-600">
        <div class="p-6 border-b border-gray-200 bg-green-600 text-white rounded-t-xl"><h3 class="text-xl font-bold flex items-center"><i class="fas fa-clock mr-3 text-2xl"></i>Tambah Jadwal Perkuliahan</h3></div>
        <form action="{{ route('admin.jadwal.store') }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-chalkboard-teacher text-gray-400 mr-1"></i>Kelas Mata Kuliah *</label><select name="kelas_mata_kuliah_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition" required><option value="">Pilih Kelas</option>@foreach($kelasMatKul as $k)<option value="{{ $k->id }}">{{ $k->mataKuliah->nama_mk }} (Kelas {{ $k->nama_kelas }}) - {{ $k->dosen->user->name }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-calendar-day text-gray-400 mr-1"></i>Hari *</label><select name="hari" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition" required><option value="">Pilih Hari</option><option value="Senin">Senin</option><option value="Selasa">Selasa</option><option value="Rabu">Rabu</option><option value="Kamis">Kamis</option><option value="Jumat">Jumat</option><option value="Sabtu">Sabtu</option></select></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-door-open text-gray-400 mr-1"></i>Ruangan *</label><input type="text" name="ruangan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition" placeholder="R.101" required></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-clock text-gray-400 mr-1"></i>Jam Mulai *</label><input type="time" name="jam_mulai" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition" required></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-clock text-gray-400 mr-1"></i>Jam Selesai *</label><input type="time" name="jam_selesai" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition" required></div>
            </div>
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t"><a href="{{ route('admin.jadwal.index') }}" class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center"><i class="fas fa-times mr-2"></i>Batal</a><button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition flex items-center shadow-md transform hover:scale-105"><i class="fas fa-save mr-2"></i>Simpan</button></div>
        </form>
    </div>
</div>
@endsection
