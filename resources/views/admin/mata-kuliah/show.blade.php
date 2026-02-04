@extends('layouts.admin')

@section('title', 'Detail Mata Kuliah')
@section('page-title', 'Detail Mata Kuliah')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon">
        <div class="p-6 border-b border-gray-200 bg-maroon text-white rounded-t-xl">
            <h3 class="text-xl font-bold flex items-center">
                <i class="fas fa-eye mr-3 text-2xl"></i>
                Detail Data Mata Kuliah
            </h3>
            <p class="text-sm mt-1 text-white text-opacity-90">Informasi lengkap mata kuliah {{ $mataKuliah->nama_mk }}</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">
                        <i class="fas fa-barcode text-gray-400 mr-1"></i>
                        Kode Mata Kuliah
                    </h4>
                    <p class="text-lg font-bold text-gray-900">{{ $mataKuliah->kode_mk }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">
                        <i class="fas fa-calculator text-gray-400 mr-1"></i>
                        SKS
                    </h4>
                    <p class="text-lg font-bold text-gray-900">{{ $mataKuliah->sks }} SKS</p>
                </div>

                <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">
                        <i class="fas fa-book-open text-gray-400 mr-1"></i>
                        Nama Mata Kuliah
                    </h4>
                    <p class="text-lg font-bold text-gray-900">{{ $mataKuliah->nama_mk }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">
                        <i class="fas fa-tags text-gray-400 mr-1"></i>
                        Jenis Mata Kuliah
                    </h4>
                    @php
                        $jenisLabels = [
                            'wajib_nasional' => 'Wajib Nasional',
                            'wajib_prodi' => 'Wajib Prodi',
                            'pilihan' => 'Pilihan',
                            'peminatan' => 'Peminatan'
                        ];
                        $jenisColors = [
                            'wajib_nasional' => 'bg-red-100 text-red-800',
                            'wajib_prodi' => 'bg-blue-100 text-blue-800',
                            'pilihan' => 'bg-green-100 text-green-800',
                            'peminatan' => 'bg-yellow-100 text-yellow-800'
                        ];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $jenisColors[$mataKuliah->jenis] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $jenisLabels[$mataKuliah->jenis] ?? ucfirst($mataKuliah->jenis) }}
                    </span>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">
                        <i class="fas fa-layer-group text-gray-400 mr-1"></i>
                        Semester
                    </h4>
                    <p class="text-lg font-bold text-gray-900">Semester {{ $mataKuliah->semester }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">
                        <i class="fas fa-building text-gray-400 mr-1"></i>
                        Fakultas
                    </h4>
                    <p class="text-lg font-bold text-gray-900">
                        @if($mataKuliah->fakultas)
                            {{ $mataKuliah->fakultas->nama_fakultas }}
                        @else
                            <span class="text-red-500">Tidak ada</span>
                        @endif
                    </p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">
                        <i class="fas fa-university text-gray-400 mr-1"></i>
                        Program Studi
                    </h4>
                    <p class="text-lg font-bold text-gray-900">
                        @if($mataKuliah->prodi)
                            {{ $mataKuliah->prodi->nama_prodi }}
                        @else
                            <span class="text-red-500">Tidak ada</span>
                        @endif
                    </p>
                </div>

                @if($mataKuliah->praktikum)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">
                        <i class="fas fa-flask text-gray-400 mr-1"></i>
                        SKS Praktikum
                    </h4>
                    <p class="text-lg font-bold text-gray-900">{{ $mataKuliah->praktikum }} SKS</p>
                </div>
                @endif

                @if($mataKuliah->deskripsi)
                <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">
                        <i class="fas fa-align-left text-gray-400 mr-1"></i>
                        Deskripsi
                    </h4>
                    <p class="text-gray-900">{{ $mataKuliah->deskripsi }}</p>
                </div>
                @endif
            </div>

            @if($mataKuliah->kelasMataKuliahs->count() > 0)
            <div class="mt-8">
                <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-chalkboard-teacher text-maroon mr-2"></i>
                    Kelas yang Mengajar
                </h4>
                <div class="space-y-4">
                    @foreach($mataKuliah->kelasMataKuliahs as $kelas)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h5 class="font-semibold text-blue-900">{{ $kelas->kelas->nama_kelas }}</h5>
                                <p class="text-sm text-blue-700 mt-1">
                                    Dosen: {{ $kelas->dosen->user->name ?? 'Belum ditentukan' }}
                                </p>
                            </div>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ $kelas->kelas->semester }} Semester
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="flex justify-between items-center p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
            <div class="text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Dibuat: {{ $mataKuliah->created_at->format('d/m/Y H:i') }}
                @if($mataKuliah->updated_at != $mataKuliah->created_at)
                | Diupdate: {{ $mataKuliah->updated_at->format('d/m/Y H:i') }}
                @endif
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('admin.mata-kuliah.index') }}" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <a href="{{ route('admin.mata-kuliah.edit', $mataKuliah) }}" 
                    class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
            </div>
        </div>
    </div>
</div>
@endsection