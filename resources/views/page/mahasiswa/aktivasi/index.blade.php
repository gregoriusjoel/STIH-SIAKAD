@extends('layouts.mahasiswa')

@section('title', 'Aktivasi Akun Mahasiswa')
@section('page-title', 'Aktivasi Akun')

@push('styles')
<style>
    .rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .rating-input input[type="radio"] {
        display: none;
    }
    
    .rating-input label {
        cursor: pointer;
        font-size: 2rem;
        color: #d1d5db;
        transition: color 0.2s;
    }
    
    .rating-input input[type="radio"]:checked ~ label,
    .rating-input label:hover,
    .rating-input label:hover ~ label {
        color: #f59e0b;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    
    {{-- Header Warning --}}
    <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-6 mb-6">
        <div class="flex items-start gap-4">
            <i class="fas fa-exclamation-triangle text-yellow-600 text-3xl"></i>
            <div>
                <h3 class="font-bold text-yellow-900 text-lg mb-2">Aktivasi Akun Diperlukan</h3>
                <p class="text-yellow-800 text-sm">
                    Akun Anda saat ini dalam status <strong>tidak aktif</strong>. 
                    Untuk dapat mengakses sistem akademik, Anda harus mengisi kuesioner kepuasan berikut terlebih dahulu.
                </p>
            </div>
        </div>
    </div>

    {{-- Main Form Card --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-maroon to-maroon-hover text-white p-6">
            <h2 class="text-2xl font-bold mb-2">Kuesioner Kepuasan Mahasiswa</h2>
            <p class="text-sm opacity-90">Silakan berikan penilaian Anda terhadap layanan kampus kami</p>
        </div>

        <form action="{{ route('mahasiswa.aktivasi.store') }}" method="POST" class="p-8 space-y-8">
            @csrf

            {{-- Info Mahasiswa --}}
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-600">NIM:</span> <span class="font-semibold text-gray-800">{{ $mahasiswa->nim }}</span></div>
                    <div><span class="text-gray-600">Nama:</span> <span class="font-semibold text-gray-800">{{ $mahasiswa->user->name }}</span></div>
                    <div><span class="text-gray-600">Prodi:</span> <span class="font-semibold text-gray-800">{{ $mahasiswa->prodi ?? 'Hukum' }}</span></div>
                    <div><span class="text-gray-600">Angkatan:</span> <span class="font-semibold text-gray-800">{{ $mahasiswa->angkatan ?? '-' }}</span></div>
                </div>
            </div>

            {{-- Rating Questions --}}
            <div class="space-y-6">
                <p class="text-sm text-gray-600 text-center">
                    <strong>Petunjuk:</strong> Pilih rating dari 1 (Sangat Tidak Puas) hingga 5 (Sangat Puas)
                </p>

                {{-- 1. Fasilitas Kampus --}}
                <div class="border border-gray-200 rounded-lg p-6">
                    <label class="block text-gray-800 font-semibold mb-4 text-center">
                        1. Bagaimana penilaian Anda terhadap fasilitas kampus?
                    </label>
                    <div class="rating-input">
                        <input type="radio" name="fasilitas_kampus" id="fasilitas_5" value="5" required>
                        <label for="fasilitas_5">⭐</label>
                        <input type="radio" name="fasilitas_kampus" id="fasilitas_4" value="4">
                        <label for="fasilitas_4">⭐</label>
                        <input type="radio" name="fasilitas_kampus" id="fasilitas_3" value="3">
                        <label for="fasilitas_3">⭐</label>
                        <input type="radio" name="fasilitas_kampus" id="fasilitas_2" value="2">
                        <label for="fasilitas_2">⭐</label>
                        <input type="radio" name="fasilitas_kampus" id="fasilitas_1" value="1">
                        <label for="fasilitas_1">⭐</label>
                    </div>
                    @error('fasilitas_kampus')
                        <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 2. Sistem Akademik --}}
                <div class="border border-gray-200 rounded-lg p-6">
                    <label class="block text-gray-800 font-semibold mb-4 text-center">
                        2. Bagaimana penilaian Anda terhadap sistem akademik (SIAKAD)?
                    </label>
                    <div class="rating-input">
                        <input type="radio" name="sistem_akademik" id="sistem_5" value="5" required>
                        <label for="sistem_5">⭐</label>
                        <input type="radio" name="sistem_akademik" id="sistem_4" value="4">
                        <label for="sistem_4">⭐</label>
                        <input type="radio" name="sistem_akademik" id="sistem_3" value="3">
                        <label for="sistem_3">⭐</label>
                        <input type="radio" name="sistem_akademik" id="sistem_2" value="2">
                        <label for="sistem_2">⭐</label>
                        <input type="radio" name="sistem_akademik" id="sistem_1" value="1">
                        <label for="sistem_1">⭐</label>
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
                    <div class="rating-input">
                        <input type="radio" name="kualitas_dosen" id="dosen_5" value="5" required>
                        <label for="dosen_5">⭐</label>
                        <input type="radio" name="kualitas_dosen" id="dosen_4" value="4">
                        <label for="dosen_4">⭐</label>
                        <input type="radio" name="kualitas_dosen" id="dosen_3" value="3">
                        <label for="dosen_3">⭐</label>
                        <input type="radio" name="kualitas_dosen" id="dosen_2" value="2">
                        <label for="dosen_2">⭐</label>
                        <input type="radio" name="kualitas_dosen" id="dosen_1" value="1">
                        <label for="dosen_1">⭐</label>
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
                    <div class="rating-input">
                        <input type="radio" name="layanan_administrasi" id="admin_5" value="5" required>
                        <label for="admin_5">⭐</label>
                        <input type="radio" name="layanan_administrasi" id="admin_4" value="4">
                        <label for="admin_4">⭐</label>
                        <input type="radio" name="layanan_administrasi" id="admin_3" value="3">
                        <label for="admin_3">⭐</label>
                        <input type="radio" name="layanan_administrasi" id="admin_2" value="2">
                        <label for="admin_2">⭐</label>
                        <input type="radio" name="layanan_administrasi" id="admin_1" value="1">
                        <label for="admin_1">⭐</label>
                    </div>
                    @error('layanan_administrasi')
                        <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 5. Kepuasan Keseluruhan --}}
                <div class="border border-gray-200 rounded-lg p-6">
                    <label class="block text-gray-800 font-semibold mb-4 text-center">
                        5. Secara keseluruhan, seberapa puas Anda dengan kampus STIH?
                    </label>
                    <div class="rating-input">
                        <input type="radio" name="kepuasan_keseluruhan" id="overall_5" value="5" required>
                        <label for="overall_5">⭐</label>
                        <input type="radio" name="kepuasan_keseluruhan" id="overall_4" value="4">
                        <label for="overall_4">⭐</label>
                        <input type="radio" name="kepuasan_keseluruhan" id="overall_3" value="3">
                        <label for="overall_3">⭐</label>
                        <input type="radio" name="kepuasan_keseluruhan" id="overall_2" value="2">
                        <label for="overall_2">⭐</label>
                        <input type="radio" name="kepuasan_keseluruhan" id="overall_1" value="1">
                        <label for="overall_1">⭐</label>
                    </div>
                    @error('kepuasan_keseluruhan')
                        <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Saran --}}
            <div>
                <label class="block text-gray-800 font-semibold mb-2">
                    Saran dan Masukan
                </label>
                <textarea name="saran" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent" placeholder="Tuliskan saran atau masukan Anda untuk perbaikan kampus..."></textarea>
                @error('saran')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-center pt-4">
                <button type="submit" class="px-8 py-4 bg-maroon text-white font-bold rounded-lg hover:bg-maroon-hover transition shadow-lg hover:shadow-xl flex items-center gap-3 text-lg">
                    <i class="fas fa-check-circle"></i>
                    Aktivasi Akun Sekarang
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('input[type="radio"]').forEach(input => {
        input.addEventListener('change', function() {
            console.log('Rating selected:', this.value);
        });
    });
</script>
@endpush
