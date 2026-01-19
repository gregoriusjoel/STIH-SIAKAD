@extends('layouts.mahasiswa')

@section('title', 'KRS Ditutup')
@section('page-title', 'Pengisian KRS')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-lg p-8 text-center">
            <div class="mb-6">
                <div class="mx-auto w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-lock text-yellow-600 text-3xl"></i>
                </div>
                <h2 class="text-2xl font-bold mb-2">Pengisian KRS Ditutup</h2>
                <p class="text-gray-600">{{ $message }}</p>
            </div>

            @if($semesterAktif)
            <div class="mx-auto inline-block text-left bg-gray-50 p-5 rounded-lg mb-6 shadow-sm" style="min-width:200px;">
                <p class="text-xs text-gray-500">Semester</p>
                <p class="text-lg font-semibold">{{ $semesterAktif->nama_semester }}</p>
                <p class="text-xs text-gray-500 mt-2">Tahun Ajaran</p>
                <p class="text-sm font-medium">{{ $semesterAktif->tahun_ajaran }}</p>
                
                @if($semesterAktif->krs_mulai && $semesterAktif->krs_selesai)
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <p class="text-xs text-gray-500">Periode KRS</p>
                    <p class="text-sm font-medium">{{ $semesterAktif->krs_mulai->format('d M Y') }} - {{ $semesterAktif->krs_selesai->format('d M Y') }}</p>
                </div>
                @endif
            </div>
            @endif

            <div class="text-sm text-gray-500 mb-6">
                <i class="fas fa-info-circle mr-1"></i>
                Hubungi admin atau tunggu pengumuman pembukaan KRS
            </div>

            <a href="{{ route('mahasiswa.dashboard') }}" class="inline-block px-6 py-3 bg-gray-200 text-gray-800 rounded-lg font-medium hover:bg-gray-300 transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
