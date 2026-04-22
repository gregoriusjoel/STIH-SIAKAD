@extends('layouts.guest')

@section('page-title', 'Absen Mahasiswa')

@section('content')

    <div class="max-w-md w-full bg-white dark:bg-[#1a1d2e] rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-slate-800"
        x-data="attendanceLoginForm()">
        <div class="p-8">
            <div class="text-center mb-6">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/logo_stih_white-clear.png') }}"
                        class="h-20 w-auto block dark:hidden filter brightness-0" alt="Logo STIH">
                    <img src="{{ asset('images/logo_stih_white-clear.png') }}" class="h-20 w-auto hidden dark:block"
                        alt="Logo STIH">
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Student Site Absensi
                </h3>
            </div>

            <!-- GPS Status Banner -->
            <div x-show="locationError" x-cloak class="mb-4 bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs text-yellow-700 font-medium" x-text="locationError"></p>
                    </div>
                </div>
            </div>

            <div x-show="locationSuccess" x-cloak class="mb-4 bg-green-50 border-l-4 border-green-400 p-3 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs text-green-700 font-medium">
                            Lokasi terdeteksi.
                            <span x-show="withinRadius">Anda dalam radius kampus.</span>
                            <span x-show="!withinRadius">Anda di luar radius kampus.</span>
                        </p>
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('absen.login.post') }}" class="space-y-6" @submit="handleSubmit">
                @csrf
                <input type="hidden" name="token" value="{{ $token ?? '' }}">
                <input type="hidden" name="lat" x-model="latitude">
                <input type="hidden" name="lng" x-model="longitude">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NIM atau Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input name="identifier" type="text"
                            class="pl-10 w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-[#8B1538] focus:border-[#8B1538] block px-4 py-3 placeholder-gray-400 dark:placeholder-gray-500 transition-colors"
                            placeholder="Masukkan NIM atau Email" required>
                    </div>
                </div>

                <div x-data="{ show: false }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                    <div class="relative text-gray-400 focus-within:text-gray-600 dark:focus-within:text-gray-300">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input name="password" :type="show ? 'text' : 'password'"
                            class="pl-10 pr-10 w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-[#8B1538] focus:border-[#8B1538] block px-4 py-3 placeholder-gray-400 dark:placeholder-gray-500 transition-colors"
                            placeholder="Masukkan Password" required>
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer focus:outline-none text-gray-400 hover:text-[#8B1538] transition-colors">
                            <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                @if(!empty($kelas))
                    <div
                        class="bg-gray-50 dark:bg-slate-800/50 rounded-xl p-4 border border-gray-100 dark:border-slate-700 space-y-3">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#8B1538]/10 flex items-center justify-center text-[#8B1538]">
                                    <i class="fas fa-book text-xs"></i>
                                </div>
                            </div>
                            <div class="text-sm">
                                <p class="text-gray-500 dark:text-gray-400 font-medium text-xs uppercase tracking-wider mb-0.5">
                                    Mata Kuliah</p>
                                <div class="font-bold text-gray-900 dark:text-white">{{ $kelas->mataKuliah->nama_mk ?? '-' }}
                                </div>
                                <div class="text-gray-500 text-xs mt-0.5">Kelas {{ $kelas->kode_kelas ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#8B1538]/10 flex items-center justify-center text-[#8B1538]">
                                    <i class="fas fa-chalkboard-teacher text-xs"></i>
                                </div>
                            </div>
                            <div class="text-sm">
                                <p class="text-gray-500 dark:text-gray-400 font-medium text-xs uppercase tracking-wider mb-0.5">
                                    Dosen</p>
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $kelas->dosen->nama ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#8B1538]/10 flex items-center justify-center text-[#8B1538]">
                                    <i class="fas fa-clock text-xs"></i>
                                </div>
                            </div>
                            <div class="text-sm">
                                <p class="text-gray-500 dark:text-gray-400 font-medium text-xs uppercase tracking-wider mb-0.5">
                                    Waktu</p>
                                <div class="font-semibold text-gray-900 dark:text-white">
                                    {{ $kelas->hari ?? '-' }}, {{ substr($kelas->jam_mulai, 0, 5) }} -
                                    {{ substr($kelas->jam_selesai, 0, 5) }}
                                </div>
                            </div>
                        </div>

                        @if(isset($metodePengajaran))
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-8 h-8 rounded-full bg-[#8B1538]/10 flex items-center justify-center text-[#8B1538]">
                                        <i class="fas fa-graduation-cap text-xs"></i>
                                    </div>
                                </div>
                                <div class="text-sm">
                                    <p class="text-gray-500 dark:text-gray-400 font-medium text-xs uppercase tracking-wider mb-0.5">
                                        Metode</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold 
                                            {{ $metodePengajaran === 'offline' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $metodePengajaran === 'online' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $metodePengajaran === 'asynchronous' ? 'bg-purple-100 text-purple-700' : '' }}">
                                        {{ ucfirst($metodePengajaran) }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Conditional Reason Fields -->
                <div x-show="showReasonFields" x-cloak class="space-y-4">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded-r-lg">
                        <p class="text-xs text-blue-700 font-medium">Anda berada di luar radius kampus untuk pertemuan
                            offline. Harap pilih alasan.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alasan <span
                                class="text-red-500">*</span></label>
                        <select name="reason_category" x-model="reasonCategory"
                            class="w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-[#8B1538] focus:border-[#8B1538] px-4 py-3">
                            <option value="">-- Pilih Alasan --</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Kendala transportasi">Kendala transportasi</option>
                            <option value="Izin keluarga">Izin keluarga</option>
                            <option value="Keperluan penting">Keperluan penting</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div x-show="reasonCategory === 'Lainnya'" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Detail Alasan <span
                                class="text-red-500">*</span></label>
                        <textarea name="reason_detail" rows="2" placeholder="Jelaskan alasan Anda..." x-model="reasonDetail"
                            class="w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-[#8B1538] focus:border-[#8B1538] px-4 py-3 resize-none"></textarea>
                    </div>
                </div>

                <button type="submit" :disabled="isSubmitting"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-br from-[#8B1538] to-[#6D1029] hover:from-[#7A1230] hover:to-[#5E0D22] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8B1538] transform transition-all duration-200 hover:scale-[1.01] disabled:opacity-50 disabled:cursor-not-allowed"
                    :class="{ 'opacity-50 cursor-not-allowed': isSubmitting }">
                    <span x-show="!isSubmitting">Login & Absen</span>
                    <span x-show="isSubmitting">Memproses...</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        function attendanceLoginForm() {
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
                hasError: {{ (old('reason_category') || $errors->has('reason_category')) ? 'true' : 'false' }},

                init() {
                    if (this.hasError) {
                        this.showReasonFields = true;
                        this.reasonRequired = true;
                    }
                    this.getLocation();
                },

                getLocation() {
                    if (!navigator.geolocation) {
                        this.locationError = 'Browser atau koneksi tidak mendukung fitur lokasi.';
                        this.handleNoLocation();
                        return;
                    }

                    try {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                this.latitude = position.coords.latitude;
                                this.longitude = position.coords.longitude;
                                this.locationSuccess = true;
                                this.locationError = null;

                                const distance = this.calculateDistance(
                                    this.latitude,
                                    this.longitude,
                                    -6.311252,
                                    106.811174
                                );

                                this.withinRadius = distance <= 100;

                                if (this.metodePengajaran === 'offline' && !this.withinRadius) {
                                    this.showReasonFields = true;
                                    this.reasonRequired = true;
                                }
                            },
                            (error) => {
                                let errorMsg = 'Gagal mendapatkan lokasi. ';
                                switch (error.code) {
                                    case error.PERMISSION_DENIED:
                                        errorMsg += 'Izin lokasi ditolak.';
                                        break;
                                    case error.POSITION_UNAVAILABLE:
                                        errorMsg += 'Sinyal GPS tidak tersedia.';
                                        break;
                                    case error.TIMEOUT:
                                        errorMsg += 'Waktu pencarian lokasi habis (timeout).';
                                        break;
                                    default:
                                        errorMsg += 'Error tidak diketahui.';
                                }
                                this.locationError = errorMsg;
                                this.handleNoLocation();
                            },
                            {
                                enableHighAccuracy: true,
                                timeout: 5000,
                                maximumAge: 0
                            }
                        );
                    } catch (err) {
                        this.locationError = 'Terjadi kesalahan sistem saat mencari lokasi.';
                        this.handleNoLocation();
                    }
                },

                handleNoLocation() {
                    if (this.metodePengajaran === 'offline') {
                        this.showReasonFields = true;
                        this.reasonRequired = true;
                    }
                },

                calculateDistance(lat1, lng1, lat2, lng2) {
                    const R = 6371e3;
                    const φ1 = lat1 * Math.PI / 180;
                    const φ2 = lat2 * Math.PI / 180;
                    const Δφ = (lat2 - lat1) * Math.PI / 180;
                    const Δλ = (lng2 - lng1) * Math.PI / 180;

                    const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                        Math.cos(φ1) * Math.cos(φ2) *
                        Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                    return R * c;
                },

                handleSubmit(event) {
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