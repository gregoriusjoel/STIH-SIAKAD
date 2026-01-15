@extends('layouts.admin')

@section('title', 'Detail KRS')
@section('page-title', 'Detail KRS')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-file-alt mr-3 text-maroon"></i>
                Detail KRS
            </h2>
            <p class="text-gray-600 text-sm mt-1">Informasi detail Kartu Rencana Studi</p>
        </div>
        <a href="{{ route('admin.krs.index') }}" 
            class="px-4 py-2 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Mahasiswa -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md border-t-4 border-maroon">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-user-graduate text-maroon mr-2"></i>
                    Informasi Mahasiswa
                </h3>
            </div>
            <div class="p-6">
                <div class="flex items-start space-x-4">
                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-maroon to-red-700 flex items-center justify-center text-white font-bold text-3xl">
                        {{ strtoupper(substr($krs->mahasiswa->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xl font-bold text-gray-800">{{ $krs->mahasiswa->user->name }}</h4>
                        <div class="mt-3 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">
                                    <i class="fas fa-id-card mr-1"></i>
                                    NPM
                                </p>
                                <p class="font-semibold text-gray-800">{{ $krs->mahasiswa->npm }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">
                                    <i class="fas fa-book-open mr-1"></i>
                                    Program Studi
                                </p>
                                <p class="font-semibold text-gray-800">{{ $krs->mahasiswa->prodi }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Angkatan
                                </p>
                                <p class="font-semibold text-gray-800">{{ $krs->mahasiswa->angkatan }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">
                                    <i class="fas fa-envelope mr-1"></i>
                                    Email
                                </p>
                                <p class="font-semibold text-gray-800 text-sm">{{ $krs->mahasiswa->user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status KRS -->
        <div class="bg-white rounded-xl shadow-md border-t-4 border-maroon">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-info-circle text-maroon mr-2"></i>
                    Status KRS
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-xs text-gray-500 mb-2">
                        <i class="fas fa-toggle-on mr-1"></i>
                        Status Pengajuan
                    </p>
                    @if($krs->status == 'pending')
                        <span class="px-4 py-2 inline-flex text-sm font-bold rounded-full bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-2"></i>Pending
                        </span>
                    @elseif($krs->status == 'disetujui')
                        <span class="px-4 py-2 inline-flex text-sm font-bold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-2"></i>Disetujui
                        </span>
                    @else
                        <span class="px-4 py-2 inline-flex text-sm font-bold rounded-full bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-2"></i>Ditolak
                        </span>
                    @endif
                </div>

                <div>
                    <p class="text-xs text-gray-500 mb-2">
                        <i class="fas fa-calendar-check mr-1"></i>
                        Tanggal Pengajuan
                    </p>
                    <p class="font-semibold text-gray-800">{{ $krs->created_at->format('d F Y H:i') }}</p>
                </div>

                @if($krs->status == 'pending')
                    <div class="pt-4 border-t space-y-2">
                        <form action="{{ route('admin.krs.updateStatus', $krs) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="disetujui">
                            <button type="submit" 
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center justify-center font-semibold">
                                <i class="fas fa-check mr-2"></i>
                                Setujui KRS
                            </button>
                        </form>
                        <form action="{{ route('admin.krs.updateStatus', $krs) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="ditolak">
                            <button type="submit" 
                                onclick="return confirm('Yakin ingin menolak KRS ini?')"
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center justify-center font-semibold">
                                <i class="fas fa-times mr-2"></i>
                                Tolak KRS
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detail Mata Kuliah -->
    <div class="mt-6 bg-white rounded-xl shadow-md border-t-4 border-maroon">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-book text-maroon mr-2"></i>
                Detail Mata Kuliah
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Mata Kuliah Info -->
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                        <div class="h-12 w-12 rounded-lg bg-blue-600 flex items-center justify-center text-white mr-4">
                            <i class="fas fa-book text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Nama Mata Kuliah</p>
                            <p class="font-bold text-gray-800">{{ $krs->kelasMataKuliah->mataKuliah->nama_mk }}</p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-purple-50 rounded-lg">
                        <div class="h-12 w-12 rounded-lg bg-purple-600 flex items-center justify-center text-white mr-4">
                            <i class="fas fa-barcode text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Kode Mata Kuliah</p>
                            <p class="font-bold text-gray-800">{{ $krs->kelasMataKuliah->mataKuliah->kode_mk }}</p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-green-50 rounded-lg">
                        <div class="h-12 w-12 rounded-lg bg-green-600 flex items-center justify-center text-white mr-4">
                            <i class="fas fa-calculator text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Jumlah SKS</p>
                            <p class="font-bold text-gray-800">{{ $krs->kelasMataKuliah->mataKuliah->sks }} SKS</p>
                        </div>
                    </div>
                </div>

                <!-- Kelas & Semester Info -->
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-indigo-50 rounded-lg">
                        <div class="h-12 w-12 rounded-lg bg-indigo-600 flex items-center justify-center text-white mr-4">
                            <i class="fas fa-door-open text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Kelas</p>
                            <p class="font-bold text-gray-800">{{ $krs->kelasMataKuliah->nama_kelas }}</p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-orange-50 rounded-lg">
                        <div class="h-12 w-12 rounded-lg bg-orange-600 flex items-center justify-center text-white mr-4">
                            <i class="fas fa-calendar-alt text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Semester</p>
                            <p class="font-bold text-gray-800">{{ $krs->semester->nama_semester }}</p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-pink-50 rounded-lg">
                        <div class="h-12 w-12 rounded-lg bg-pink-600 flex items-center justify-center text-white mr-4">
                            <i class="fas fa-chalkboard-teacher text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Dosen Pengampu</p>
                            <p class="font-bold text-gray-800">{{ $krs->kelasMataKuliah->dosen->user->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($krs->kelasMataKuliah->mataKuliah->deskripsi)
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 mb-2">
                        <i class="fas fa-align-left mr-1"></i>
                        Deskripsi Mata Kuliah
                    </p>
                    <p class="text-gray-700">{{ $krs->kelasMataKuliah->mataKuliah->deskripsi }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
