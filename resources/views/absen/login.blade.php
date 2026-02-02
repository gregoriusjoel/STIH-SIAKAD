@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-md py-12">
    <div class="bg-white shadow rounded p-6">
        <h2 class="text-xl font-bold mb-4">Login Absen Mahasiswa</h2>

        @if($errors->any())
            <div class="mb-4 text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('absen.login.post') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token ?? '' }}">

            <div class="mb-3">
                <label class="block text-sm font-semibold">NIM atau Email</label>
                <input name="identifier" class="w-full border rounded px-3 py-2" placeholder="Masukkan NIM atau Email" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-semibold">Password</label>
                <input name="password" type="password" class="w-full border rounded px-3 py-2" placeholder="Password" required>
            </div>

            @if(!empty($kelas))
                <div class="mb-4 text-sm text-gray-700">
                    <div><strong>Kelas:</strong> {{ $kelas->kode_kelas ?? ($kelas->mataKuliah->nama ?? '-') }}</div>
                    <div><strong>Mata Kuliah:</strong> {{ $kelas->mataKuliah->nama ?? '-' }}</div>
                </div>
            @endif

            <button type="submit" class="w-full bg-red-700 text-white rounded py-2">Login & Absen</button>
        </form>
    </div>
</div>
@endsection
