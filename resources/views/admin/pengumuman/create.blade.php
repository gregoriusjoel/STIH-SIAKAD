@extends('layouts.admin')

@section('title', 'Buat Pengumuman')
@section('page-title', 'Buat Pengumuman')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('admin.pengumuman.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Judul</label>
                <input type="text" name="judul" class="mt-1 block w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Isi</label>
                <textarea name="isi" class="mt-1 block w-full border rounded p-2" rows="6" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Tanggal Publikasi (opsional)</label>
                <input type="datetime-local" name="published_at" class="mt-1 block w-full border rounded p-2">
            </div>
            <div class="flex justify-end">
                <a href="{{ route('admin.pengumuman.index') }}" class="px-4 py-2 border rounded mr-2">Batal</a>
                <button class="px-4 py-2 bg-maroon text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
@endsection
