@extends('layouts.app')

@section('title', 'Isi Absensi')

@section('content')
    <div class="max-w-xl mx-auto py-12">
        <div class="bg-white rounded-lg border p-6">
            <h3 class="text-lg font-bold mb-4">Form Absensi</h3>

            <p class="text-sm text-gray-600 mb-4">Kelas: <strong>{{ $kelas->nama_kelas ?? $kelas->mata_kuliah->nama_mk ?? '-' }}</strong></p>

            <form method="POST" action="{{ route('absensi.submit', ['token' => $token]) }}">
                @csrf

                @if(! (auth()->check() && auth()->user()->mahasiswa))
                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">NPM</label>
                        <input type="text" name="npm" value="{{ old('npm') }}" class="w-full border px-3 py-2 rounded">
                        @error('npm')<div class="text-xs text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border px-3 py-2 rounded">
                        @error('name')<div class="text-xs text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                @else
                    <div class="mb-3">
                        <p class="text-sm">Anda masuk sebagai: <strong>{{ auth()->user()->name }}</strong></p>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="block text-sm text-gray-700">Keterangan (opsional)</label>
                    <input type="text" name="keterangan" value="{{ old('keterangan') }}" class="w-full border px-3 py-2 rounded">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-[#8B1538] text-white rounded">Kirim</button>
                </div>
            </form>
        </div>
    </div>
@endsection
