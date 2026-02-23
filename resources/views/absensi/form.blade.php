@extends('layouts.blank')

@section('title', 'Isi Absensi | STIH')

@section('content')
<div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans antialiased text-gray-900" x-data="attendanceForm()">
    <!-- Main Card Container -->
    <div class="mx-auto w-full max-w-md sm:max-w-xl md:max-w-2xl lg:max-w-3xl">
        <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-200">
            
            <!-- 1. Logo STIH Header -->
            <div class="bg-[#8B1538] py-6 px-4 flex flex-col items-center justify-center text-center">
                <div class="bg-white/10 p-2 rounded-lg backdrop-blur-sm mb-3">
                    <img src="{{ asset('images/logo_stih_white.png') }}" alt="STIH Logo" class="h-16 w-auto">
                </div>
                <h2 class="text-2xl font-bold text-white tracking-wide uppercase">Daftar Hadir</h2>
                <p class="text-pink-100 text-xs mt-1 font-medium">Silakan isi data diri Anda untuk presensi</p>
            </div>

            <!-- 2. Class Details -->
            <div class="px-6 py-5 bg-gray-50 border-b border-gray-100">
                <div class="space-y-3">
                    <!-- Title -->
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-0.5">Mata Kuliah</span>
                        <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ $kelas->mataKuliah->nama_mk ?? '-' }}</h3>
                    </div>

                    <!-- Grid Info -->
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-0.5">Kelas</span>
                            <span class="font-semibold text-gray-800">{{ $kelas->nama_kelas ?? $kelas->kode_kelas ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-0.5">Ruangan</span>
                            <span class="font-semibold text-gray-800">{{ $kelas->ruang ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 text-sm">
                         <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-0.5">Dosen Pengampu</span>
                            <span class="font-semibold text-gray-800">{{ $kelas->dosen->user->name ?? $kelas->dosen->nidn ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-0.5">Waktu</span>
                            <span class="font-semibold text-gray-800">
                                {{ $kelas->hari ?? '' }}, {{ substr($kelas->jam_mulai, 0, 5) }} - {{ substr($kelas->jam_selesai, 0, 5) }}
                            </span>
                        </div>
                        @if(isset($metodePengajaran))
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-0.5">Metode Pertemuan</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold 
                                {{ $metodePengajaran === 'offline' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $metodePengajaran === 'online' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $metodePengajaran === 'asynchronous' ? 'bg-purple-100 text-purple-700' : '' }}">
                                {{ ucfirst($metodePengajaran) }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="px-6 py-6">
                <!-- GPS Status Banner -->
                <div x-show="locationError" x-cloak class="mb-5 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700" x-text="locationError"></p>
                        </div>
                    </div>
                </div>

                <div x-show="locationSuccess" x-cloak class="mb-5 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">Lokasi berhasil terdeteksi. <span x-show="withinRadius">Anda berada dalam radius kampus.</span><span x-show="!withinRadius">Anda berada di luar radius kampus.</span></p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('absensi.submit', ['token' => $token]) }}" class="space-y-5" @submit="handleSubmit">
                    @csrf

                    <!-- Hidden GPS fields -->
                    <input type="hidden" name="lat" x-model="latitude">
                    <input type="hidden" name="lng" x-model="longitude">

                    @if(! (auth()->check() && auth()->user()->mahasiswa))
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">NIM</label>
                            <input type="text" name="nim" value="{{ old('nim') }}" placeholder="Contoh: 2023001"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-[#8B1538] focus:border-transparent transition duration-150 ease-in-out text-sm">
                            @error('nim')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Sesuai KTM"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-[#8B1538] focus:border-transparent transition duration-150 ease-in-out text-sm">
                            @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Kontak (HP/WA)</label>
                            <input type="text" name="kontak" value="{{ old('kontak') }}" placeholder="0812..."
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-[#8B1538] focus:border-transparent transition duration-150 ease-in-out text-sm">
                            @error('kontak')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex items-start space-x-3">
                             <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-[#8B1538]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Login sebagai {{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">Data Anda akan terisi otomatis.</p>
                                <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                                @php $userPhone = auth()->user()->mahasiswa?->phone ?? auth()->user()->phone ?? null; @endphp
                                <input type="hidden" name="kontak" value="{{ $userPhone }}">
                            </div>
                        </div>
                    @endif

                    <div>
                        @php
                            $totalPertemuan = $totalPertemuan ?? $class?->total_pertemuan ?? 16;
                            $selectedPertemuan = old('pertemuan', request('pertemuan', $currentPertemuan ?? 1));
                        @endphp
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Pertemuan</label>
                        <select name="pertemuan" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-[#8B1538] focus:border-transparent transition duration-150 ease-in-out text-sm">
                            @for($i = 1; $i <= $totalPertemuan; $i++)
                                <option value="{{ $i }}" {{ (int)$selectedPertemuan === $i ? 'selected' : '' }}>Pertemuan {{ $i }}</option>
                            @endfor
                        </select>
                        @error('pertemuan')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Conditional Reason Fields (shown when outside radius for offline meetings) -->
                    <div x-show="showReasonFields" x-cloak class="space-y-4">
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                            <p class="text-xs text-blue-700 font-medium">Anda berada di luar radius kampus untuk pertemuan offline. Harap pilih alasan kehadiran online Anda.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Alasan Kehadiran Online <span class="text-red-500">*</span></label>
                            <select name="reason_category" x-model="reasonCategory" 
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#8B1538] focus:border-transparent transition duration-150 ease-in-out text-sm"
                                :class="{ 'border-red-500': reasonRequired && !reasonCategory }">
                                <option value="">-- Pilih Alasan --</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Kendala transportasi">Kendala transportasi</option>
                                <option value="Izin keluarga">Izin keluarga</option>
                                <option value="Keperluan penting">Keperluan penting</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            @error('reason_category')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div x-show="reasonCategory === 'Lainnya'" x-cloak>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Detail Alasan <span class="text-red-500">*</span></label>
                            <textarea name="reason_detail" rows="3" placeholder="Jelaskan alasan Anda..." x-model="reasonDetail"
                                 class="w-full px-4 py-2.5 rounded-lg border border-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-[#8B1538] focus:border-transparent transition duration-150 ease-in-out text-sm resize-none"
                                 :class="{ 'border-red-500': reasonRequired && reasonCategory === 'Lainnya' && !reasonDetail }"></textarea>
                            @error('reason_detail')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="2" placeholder="Sakit / Izin / Catatan lain..."
                             class="w-full px-4 py-2.5 rounded-lg border border-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-[#8B1538] focus:border-transparent transition duration-150 ease-in-out text-sm resize-none">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" :disabled="isSubmitting"
                            class="w-full px-6 py-3 bg-[#8B1538] hover:bg-[#72112e] text-white font-bold rounded-lg shadow-md hover:shadow-lg transition duration-200 ease-in-out transform hover:-translate-y-0.5 text-sm uppercase tracking-wider disabled:opacity-50 disabled:cursor-not-allowed"
                            :class="{ 'opacity-50 cursor-not-allowed': isSubmitting }">
                            <span x-show="!isSubmitting">Kirim Absensi</span>
                            <span x-show="isSubmitting">Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 py-3 text-center border-t border-gray-100">
                <p class="text-[10px] text-gray-400">&copy; {{ date('Y') }} STIH Dashboard System.</p>
            </div>
        </div>
    </div>
</div>

<script>
function attendanceForm() {
    return {
        latitude: null,
        longitude: null,
        locationError: null,
        locationSuccess: false,
        withinRadius: false,
        showReasonFields: false,
        reasonRequired: false,
        reasonCategory: '{{ old("reason_category") }}',
        reasonDetail: '{{ old("reason_detail") }}',
        isSubmitting: false,
        metodePengajaran: '{{ $metodePengajaran ?? "offline" }}',
        
        init() {
            // Request GPS location on page load
            this.getLocation();
        },
        
        getLocation() {
            if (!navigator.geolocation) {
                this.locationError = 'Browser Anda tidak mendukung geolocation.';
                this.handleNoLocation();
                return;
            }
            
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.latitude = position.coords.latitude;
                    this.longitude = position.coords.longitude;
                    this.locationSuccess = true;
                    this.locationError = null;
                    
                    // Calculate distance and determine if within radius
                    const distance = this.calculateDistance(
                        this.latitude,
                        this.longitude,
                        -6.311252,
                        106.811174
                    );
                    
                    this.withinRadius = distance <= 100;
                    
                    // Show reason fields if offline meeting and outside radius
                    if (this.metodePengajaran === 'offline' && !this.withinRadius) {
                        this.showReasonFields = true;
                        this.reasonRequired = true;
                    }
                },
                (error) => {
                    let errorMsg = 'Gagal mendapatkan lokasi. ';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMsg += 'Anda menolak izin lokasi.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMsg += 'Informasi lokasi tidak tersedia.';
                            break;
                        case error.TIMEOUT:
                            errorMsg += 'Request timeout.';
                            break;
                        default:
                            errorMsg += 'Error tidak diketahui.';
                    }
                    this.locationError = errorMsg;
                    this.handleNoLocation();
                }
            );
        },
        
        handleNoLocation() {
            // For offline meetings, if no GPS, treat as outside radius
            if (this.metodePengajaran === 'offline') {
                this.showReasonFields = true;
                this.reasonRequired = true;
            }
        },
        
        calculateDistance(lat1, lng1, lat2, lng2) {
            const R = 6371e3; // Earth radius in meters
            const φ1 = lat1 * Math.PI / 180;
            const φ2 = lat2 * Math.PI / 180;
            const Δφ = (lat2 - lat1) * Math.PI / 180;
            const Δλ = (lng2 - lng1) * Math.PI / 180;

            const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                    Math.cos(φ1) * Math.cos(φ2) *
                    Math.sin(Δλ/2) * Math.sin(Δλ/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

            return R * c; // Distance in meters
        },
        
        handleSubmit(event) {
            // Validate reason fields if required
            if (this.reasonRequired) {
                if (!this.reasonCategory) {
                    event.preventDefault();
                    alert('Harap pilih alasan kehadiran online.');
                    return false;
                }
                
                if (this.reasonCategory === 'Lainnya' && !this.reasonDetail) {
                    event.preventDefault();
                    alert('Harap isi detail alasan.');
                    return false;
                }
            }
            
            this.isSubmitting = true;
        }
    }
}
</script>

<style>
[x-cloak] {
    display: none !important;
}
</style>
@endsection