@extends('layouts.mahasiswa')

@section('title', 'Aktivasi Semester')
@section('page-title', 'Aktivasi Semester')

@push('styles')
    <style>
        .rating-input {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            flex-wrap: nowrap;
        }

        .rating-input input[type="radio"] {
            appearance: none;
            -webkit-appearance: none;
            width: 1.5rem;
            height: 1.5rem;
            border: 2px solid #d1d5db;
            border-radius: 50%;
            cursor: pointer;
            background-color: white;
            transition: all 0.2s;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .rating-input input[type="radio"]:hover {
            border-color: #991b1b;
            background-color: #fef2f2;
        }

        .rating-input input[type="radio"]:checked {
            background-color: #991b1b;
            border-color: #991b1b;
            color: white;
        }

        .rating-input label {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="">

        {{-- Header Warning --}}
        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6 mb-6">
            <div class="flex items-start gap-4">
                <i class="fas fa-info-circle text-blue-600 text-3xl"></i>
                <div>
                    <h3 class="font-bold text-blue-900 text-lg mb-2">Aktivasi Semester Baru</h3>
                    <p class="text-blue-800 text-sm">
                        Anda telah naik ke <strong>Semester {{ $mahasiswa->semester }}</strong>. 
                        Sebelum melanjutkan, silakan isi kuesioner kepuasan ini untuk membantu kami meningkatkan kualitas layanan.
                    </p>
                </div>
            </div>
        </div>

        {{-- Main Form Card --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-red-900 to-red-950 text-white p-6">
                <h2 class="text-2xl font-bold mb-2">Kuesioner Kepuasan Semester {{ $mahasiswa->semester }}</h2>
                <p class="text-sm opacity-90">
                    Tahun Ajaran: <strong>{{ $currentSemester->tahun_ajaran }}</strong> 
                    ({{ $currentSemester->nama_semester }})
                </p>
            </div>

            <form action="{{ route('mahasiswa.semester-aktivasi.store') }}" method="POST" class="p-8 space-y-8">
                @csrf

                {{-- Info Mahasiswa --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex flex-col lg:flex-row lg:justify-between text-sm gap-4">
                        <div class="flex flex-col gap-2">
                            <div><span class="text-gray-600">NIM:</span> <span
                                    class="font-semibold text-gray-800">{{ $mahasiswa->nim }}</span></div>
                            <div><span class="text-gray-600">Prodi:</span> <span
                                    class="font-semibold text-gray-800">{{ $mahasiswa->prodi ?? 'Hukum' }}</span></div>
                        </div>
                        <div class="flex flex-col gap-2 lg:text-right">
                            <div><span class="text-gray-600">Nama:</span> <span
                                    class="font-semibold text-gray-800">{{ $mahasiswa->user->name }}</span></div>
                            <div><span class="text-gray-600">Semester Saat Ini:</span> <span
                                    class="font-semibold text-blue-600 text-lg">{{ $mahasiswa->semester }}</span></div>
                        </div>
                    </div>
                </div>

                {{-- Rating Questions --}}
                <div class="space-y-6">
                    <p class="text-sm text-gray-600 text-center">
                        <strong>Petunjuk:</strong> Pilih rating dari 1 (Sangat Tidak Puas) hingga 5 (Sangat Puas)
                    </p>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- 1. Fasilitas Kampus --}}
                    <div class="border border-gray-200 rounded-lg p-6">
                        <label class="block text-gray-800 font-semibold mb-4 text-center">
                            1. Bagaimana penilaian Anda terhadap fasilitas kampus di semester ini?
                        </label>
                        <div class="flex items-center justify-center gap-2 mt-4">
                            <span class="text-sm text-gray-600">Sangat Tidak Puas</span>
                            <div class="rating-input">
                                <input type="radio" name="fasilitas_kampus" id="fasilitas_1" value="1" required>
                                <input type="radio" name="fasilitas_kampus" id="fasilitas_2" value="2">
                                <input type="radio" name="fasilitas_kampus" id="fasilitas_3" value="3">
                                <input type="radio" name="fasilitas_kampus" id="fasilitas_4" value="4">
                                <input type="radio" name="fasilitas_kampus" id="fasilitas_5" value="5">
                            </div>
                            <span class="text-sm text-gray-600">Sangat Puas</span>
                        </div>
                        @error('fasilitas_kampus')
                            <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 2. Sistem Akademik --}}
                    <div class="border border-gray-200 rounded-lg p-6">
                        <label class="block text-gray-800 font-semibold mb-4 text-center">
                            2. Bagaimana penilaian Anda terhadap sistem akademik?
                        </label>
                        <div class="flex items-center justify-center gap-2 mt-4">
                            <span class="text-sm text-gray-600">Sangat Tidak Puas</span>
                            <div class="rating-input">
                                <input type="radio" name="sistem_akademik" id="sistem_1" value="1" required>
                                <input type="radio" name="sistem_akademik" id="sistem_2" value="2">
                                <input type="radio" name="sistem_akademik" id="sistem_3" value="3">
                                <input type="radio" name="sistem_akademik" id="sistem_4" value="4">
                                <input type="radio" name="sistem_akademik" id="sistem_5" value="5">
                            </div>
                            <span class="text-sm text-gray-600">Sangat Puas</span>
                        </div>
                        @error('sistem_akademik')
                            <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 3. Kualitas Dosen --}}
                    <div class="border border-gray-200 rounded-lg p-6">
                        <label class="block text-gray-800 font-semibold mb-4 text-center">
                            3. Bagaimana penilaian Anda terhadap kualitas dosen?
                        </label>
                        <div class="flex items-center justify-center gap-2 mt-4">
                            <span class="text-sm text-gray-600">Sangat Tidak Puas</span>
                            <div class="rating-input">
                                <input type="radio" name="kualitas_dosen" id="dosen_1" value="1" required>
                                <input type="radio" name="kualitas_dosen" id="dosen_2" value="2">
                                <input type="radio" name="kualitas_dosen" id="dosen_3" value="3">
                                <input type="radio" name="kualitas_dosen" id="dosen_4" value="4">
                                <input type="radio" name="kualitas_dosen" id="dosen_5" value="5">
                            </div>
                            <span class="text-sm text-gray-600">Sangat Puas</span>
                        </div>
                        @error('kualitas_dosen')
                            <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 4. Layanan Administrasi --}}
                    <div class="border border-gray-200 rounded-lg p-6">
                        <label class="block text-gray-800 font-semibold mb-4 text-center">
                            4. Bagaimana penilaian Anda terhadap layanan administrasi?
                        </label>
                        <div class="flex items-center justify-center gap-2 mt-4">
                            <span class="text-sm text-gray-600">Sangat Tidak Puas</span>
                            <div class="rating-input">
                                <input type="radio" name="layanan_administrasi" id="admin_1" value="1" required>
                                <input type="radio" name="layanan_administrasi" id="admin_2" value="2">
                                <input type="radio" name="layanan_administrasi" id="admin_3" value="3">
                                <input type="radio" name="layanan_administrasi" id="admin_4" value="4">
                                <input type="radio" name="layanan_administrasi" id="admin_5" value="5">
                            </div>
                            <span class="text-sm text-gray-600">Sangat Puas</span>
                        </div>
                        @error('layanan_administrasi')
                            <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 5. Kepuasan Keseluruhan --}}
                    <div class="border border-gray-200 rounded-lg p-6">
                        <label class="block text-gray-800 font-semibold mb-4 text-center">
                            5. Secara keseluruhan, bagaimana tingkat kepuasan Anda?
                        </label>
                        <div class="flex items-center justify-center gap-2 mt-4">
                            <span class="text-sm text-gray-600">Sangat Tidak Puas</span>
                            <div class="rating-input">
                                <input type="radio" name="kepuasan_keseluruhan" id="kepuasan_1" value="1" required>
                                <input type="radio" name="kepuasan_keseluruhan" id="kepuasan_2" value="2">
                                <input type="radio" name="kepuasan_keseluruhan" id="kepuasan_3" value="3">
                                <input type="radio" name="kepuasan_keseluruhan" id="kepuasan_4" value="4">
                                <input type="radio" name="kepuasan_keseluruhan" id="kepuasan_5" value="5">
                            </div>
                            <span class="text-sm text-gray-600">Sangat Puas</span>
                        </div>
                        @error('kepuasan_keseluruhan')
                            <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Saran --}}
                    <div class="border border-gray-200 rounded-lg p-6 col-span-1 lg:col-span-2">
                        <label class="block text-gray-800 font-semibold mb-4">
                            Saran dan Masukan Anda
                        </label>
                        <textarea 
                            name="saran" 
                            rows="5"
                            placeholder="Tuliskan saran atau masukan untuk perbaikan layanan..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        ></textarea>
                        @error('saran')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="flex gap-4 justify-center pt-4">
                    <button 
                        type="submit" 
                        class="px-8 py-3 bg-red-900 text-white font-semibold rounded-lg hover:bg-red-950 transition-colors flex items-center gap-2"
                    >
                        Kirim Kuesioner & Lanjutkan
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
