@extends('layouts.admin')
@section('title', 'Edit Kelas Mata Kuliah')
@section('page-title', 'Edit Kelas Mata Kuliah')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg border-t-4 border-blue-600">
        <div class="p-6 border-b border-gray-200 bg-blue-600 text-white rounded-t-xl"><h3 class="text-xl font-bold flex items-center"><i class="fas fa-chalkboard-teacher mr-3 text-2xl"></i>Edit Kelas Mata Kuliah</h3></div>
        <form action="{{ route('admin.kelas-mata-kuliah.update', $kelasMataKuliah) }}" method="POST" class="p-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-book text-gray-400 mr-1"></i>Mata Kuliah *</label><select name="mata_kuliah_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>@foreach($mataKuliahs as $mk)<option value="{{ $mk->id }}" {{ old('mata_kuliah_id', $kelasMataKuliah->mata_kuliah_id) == $mk->id ? 'selected' : '' }}>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-users text-gray-400 mr-1"></i>Nama Kelas *</label><input type="text" name="nama_kelas" value="{{ old('nama_kelas', $kelasMataKuliah->nama_kelas) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-user-tie text-gray-400 mr-1"></i>Dosen *</label><select name="dosen_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>@foreach($dosens as $d)<option value="{{ $d->id }}" {{ old('dosen_id', $kelasMataKuliah->dosen_id) == $d->id ? 'selected' : '' }}>{{ $d->user->name }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-calendar text-gray-400 mr-1"></i>Semester *</label><select name="semester_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>@foreach($semesters as $s)<option value="{{ $s->id }}" {{ old('semester_id', $kelasMataKuliah->semester_id) == $s->id ? 'selected' : '' }}>{{ $s->nama_semester }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-user-friends text-gray-400 mr-1"></i>Kuota *</label><input type="number" name="kuota" value="{{ old('kuota', $kelasMataKuliah->kuota) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-door-open text-gray-400 mr-1"></i>Ruangan</label><input type="text" name="ruangan" value="{{ old('ruangan', $kelasMataKuliah->ruangan) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"></div>
            </div>
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t"><a href="{{ route('admin.kelas-mata-kuliah.index') }}" class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center"><i class="fas fa-times mr-2"></i>Batal</a><button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition flex items-center shadow-md transform hover:scale-105"><i class="fas fa-save mr-2"></i>Update</button></div>
        </form>
    </div>
</div>
@endsection
