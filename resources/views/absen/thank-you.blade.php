@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-md py-12">
    <div class="bg-white shadow rounded p-6 text-center">
        <h2 class="text-2xl font-bold mb-3">Terima Kasih</h2>

        @if(!empty($mahasiswa))
            <div class="mb-2">Nama: <strong>{{ $mahasiswa->user->name ?? '-' }}</strong></div>
        @endif

        <div class="mb-2">Mata Kuliah / Kelas: <strong>{{ $mataKuliah ?? '-' }}</strong></div>

        @if(!empty($presensi))
            <div class="mb-2">Tanggal: <strong>{{ \Carbon\Carbon::parse($presensi->tanggal)->translatedFormat('d F Y') }}</strong></div>
            <div class="mb-2">Jam: <strong>{{ $presensi->waktu ? \Carbon\Carbon::parse($presensi->waktu)->format('H:i') : '-' }}</strong></div>
        @else
            <div class="mb-2">Tanggal: <strong>{{ now()->translatedFormat('d F Y') }}</strong></div>
            <div class="mb-2">Jam: <strong>{{ now()->format('H:i') }}</strong></div>
        @endif

        <p class="mt-4 text-green-700 font-semibold">Terima kasih, absen berhasil.</p>

        <div class="mt-6">
            <a href="/" class="text-sm text-gray-600">Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection
