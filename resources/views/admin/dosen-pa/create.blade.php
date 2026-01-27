@extends('layouts.admin')

@section('title', 'Tambah Dosen PA')
@section('page-title', 'Tambah Dosen PA')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon">
        <div class="p-6 border-b border-gray-200 bg-maroon text-white rounded-t-xl">
            <h3 class="text-xl font-bold flex items-center">
                <i class="fas fa-user-tie mr-3 text-2xl"></i>
                Form Tambah Dosen PA
            </h3>
            <p class="text-sm mt-1 text-white text-opacity-90">Tetapkan Dosen Pembimbing Akademik untuk Mahasiswa</p>
        </div>

        <form action="{{ route('admin.dosen-pa.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="space-y-6">
                <!-- Info Validasi -->
                <!-- <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                        <div class="text-sm text-blue-700">
                            <p class="font-semibold mb-1">Aturan Penetapan Dosen PA:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Setiap Dosen PA dapat membimbing maksimal <strong>10 mahasiswa</strong>.</li>
                                <li>Setiap Mahasiswa hanya dapat memiliki <strong>1 Dosen PA</strong>.</li>
                            </ul>
                        </div>
                    </div>
                </div> -->

                <!-- Data Pemilihan -->
                <div class="">
                    <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-clipboard-check text-maroon mr-2"></i>
                        Pilih Data
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Dropdown Dosen -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-chalkboard-teacher text-gray-400 mr-1"></i>
                                Pilih Dosen PA *
                            </label>
                            <select name="dosen_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition @error('dosen_id') border-red-500 @enderror" 
                                required>
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosens as $dosen)
                                    @php
                                        $count = $dosen->mahasiswa_pa_count;
                                        $isFull = $count >= 10;
                                    @endphp
                                    <option value="{{ $dosen->id }}" 
                                        {{ $isFull ? 'disabled' : '' }}
                                        {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}
                                        class="{{ $isFull ? 'text-gray-400' : '' }}">
                                        {{ $dosen->user->name }} ({{ $count }}/10){{ $isFull ? ' - PENUH' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dosen_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Dosen dengan status "PENUH" sudah mencapai batas 10 mahasiswa.</p>
                        </div>

                        <!-- Dropdown Mahasiswa -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user-graduate text-gray-400 mr-1"></i>
                                Pilih Mahasiswa *
                            </label>
                            <select name="mahasiswa_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition @error('mahasiswa_id') border-red-500 @enderror" 
                                required>
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($mahasiswas as $mahasiswa)
                                    <option value="{{ $mahasiswa->id }}" {{ old('mahasiswa_id') == $mahasiswa->id ? 'selected' : '' }}>
                                        {{ $mahasiswa->user->name }} ({{ $mahasiswa->npm }})
                                    </option>
                                @endforeach
                            </select>
                            @error('mahasiswa_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Hanya menampilkan mahasiswa yang belum memiliki Dosen PA.</p>
                            @if($mahasiswas->isEmpty())
                                <p class="text-yellow-600 text-xs mt-1 font-semibold">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Semua mahasiswa sudah memiliki Dosen PA.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t">
                <a href="{{ route('admin.dosen-pa.index') }}" 
                    class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" 
                    class="bg-maroon text-white px-6 py-3 rounded-lg hover:bg-opacity-90 transition flex items-center shadow-md transform hover:scale-105"
                    {{ $mahasiswas->isEmpty() ? 'disabled' : '' }}>
                    <i class="fas fa-save mr-2"></i>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
