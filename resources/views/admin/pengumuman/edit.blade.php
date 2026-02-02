@extends('layouts.admin')

@section('title', 'Edit Pengumuman')
@section('page-title', 'Edit Pengumuman')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('admin.pengumuman.update', $pengumuman) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Judul</label>
                <input type="text" name="judul" value="{{ $pengumuman->judul }}" class="mt-1 block w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Isi</label>
                <textarea name="isi" class="mt-1 block w-full border rounded p-2" rows="6" required>{{ $pengumuman->isi }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Tanggal Publikasi (opsional)</label>
                 <input type="datetime-local" name="published_at" value="{{ $pengumuman->published_at ? \Carbon\Carbon::parse($pengumuman->published_at)->format('Y-m-d\\TH:i') : '' }}" class="mt-1 block w-full border rounded p-2">
            </div>
            <div class="flex justify-end">
                <a href="{{ route('admin.pengumuman.index') }}" class="px-4 py-2 border rounded mr-2">Batal</a>
                <button class="px-4 py-2 bg-maroon text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
@endsection
