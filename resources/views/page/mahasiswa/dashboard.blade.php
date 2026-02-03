@extends('layouts.mahasiswa')

@section('title', 'Dashboard Mahasiswa')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    :root {
        --maroon: #8B1538;
        --maroon-hover: #6B0F2A;
        --maroon-light: #FEF2F4;
    }

    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(139, 21, 56, 0.15);
    }
</style>
@endpush

@section('content')
@php
$isProfileComplete = $mahasiswa->isProfileComplete();
@endphp
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Profile Incomplete Warning --}}
    @if(!$isProfileComplete)
    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-6">
        <div class="flex items-start gap-4">
            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            <div class="flex-1">
                <h4 class="font-bold text-red-900 mb-2">Profil Belum Lengkap!</h4>
                <p class="text-sm text-red-800 mb-3">
                    Anda harus melengkapi data profil terlebih dahulu untuk dapat mengakses fitur KRS, Nilai, Jadwal, dan fitur lainnya.
                </p>
                <a href="{{ route('mahasiswa.profil.manajemen') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-semibold">
                    <i class="fas fa-user-edit mr-2"></i>
                    Lengkapi Profil Sekarang
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Welcome Card --}}
    <div class="bg-gradient-to-r from-maroon to-maroon-hover rounded-xl shadow-lg text-white p-8">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 rounded-full overflow-hidden bg-white/20 flex items-center justify-center text-3xl font-bold">
                @if(!empty($mahasiswa->foto))
                <img src="{{ asset('storage/' . $mahasiswa->foto) }}" alt="Foto {{ $mahasiswa->user->name }}" class="w-full h-full object-cover">
                @else
                <span class="text-white">{{ strtoupper(substr($mahasiswa->user->name ?? 'M', 0, 1)) }}</span>
                @endif
            </div>
            <div class="flex-1">
                <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ $mahasiswa->user->name ?? 'Mahasiswa' }}!</h1>
                @php
                $studentSemester = $mahasiswa->semester ?? $mahasiswa->getCurrentSemester();
                @endphp
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div><span class="opacity-80">NIM:</span> <span class="font-semibold">{{ $mahasiswa->nim }}</span></div>
                    <div><span class="opacity-80">Prodi:</span> <span class="font-semibold">{{ $mahasiswa->prodi ?? 'Hukum' }}</span></div>
                    <div><span class="opacity-80">Angkatan:</span> <span class="font-semibold">{{ $mahasiswa->angkatan ?? '2023' }}</span></div>
                    <div class="mt-1"><span class="opacity-80">Semester:</span> <span class="font-semibold">{{ $studentSemester }}</span></div>
                    <div>
                        <span class="opacity-80">Status:</span>
                        <span class="px-2 py-1 rounded-full text-xs font-bold ml-1 {{ $mahasiswa->status === 'aktif' ? 'bg-green-400 text-green-900' : 'bg-yellow-400 text-yellow-900' }}">
                            {{ strtoupper($mahasiswa->status ?? 'AKTIF') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pengumuman (ganti 4 kartu stat) --}}
    @php
    if (!isset($pengumuman)) {
    $pengumuman = \App\Models\Pengumuman::whereNotNull('published_at')->orderByDesc('published_at')->get();
    }
    @endphp
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800">Berita Terikini</h3>
            @php
            $pengumumanIndexUrl = Route::has('mahasiswa.pengumuman.index') ? route('mahasiswa.pengumuman.index') : '#';
            @endphp
            <a href="{{ $pengumumanIndexUrl }}" class="text-sm text-maroon hover:underline">Lihat semua</a>
        </div>

        @if(isset($pengumuman) && $pengumuman->count())
        <div class="space-y-4">
            @foreach($pengumuman->take(4) as $p)
            <div class="flex items-start gap-4 p-4 border rounded-lg hover:shadow-sm transition">
                <div class="flex-shrink-0 w-3 h-12 rounded-full bg-maroon mt-1"></div>
                <div class="flex-1">
                    @php $showUrl = Route::has('mahasiswa.pengumuman.show') ? route('mahasiswa.pengumuman.show', $p->id) : '#'; @endphp
                    <a href="{{ $showUrl }}" class="text-md font-semibold text-gray-800 hover:text-maroon">{{ $p->judul }}</a>
                    <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit(strip_tags($p->isi ?? ''), 140) }}</p>
                    <div class="text-xs text-gray-400 mt-2">{{ $p->created_at ? $p->created_at->format('d M Y') : '' }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-sm text-gray-600">Belum ada pengumuman terbaru.</div>
        @endif
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-bolt text-maroon mr-3"></i>
            Akses Cepat
            @if(!$isProfileComplete)
            <span class="ml-3 text-xs font-normal text-red-600 bg-red-100 px-2 py-1 rounded-full">
                <i class="fas fa-lock mr-1"></i> Lengkapi profil untuk mengaktifkan
            </span>
            @endif
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @if($isProfileComplete)
            <a href="{{ route('mahasiswa.krs.index') }}" class="flex items-center gap-4 p-4 border-2 border-maroon rounded-lg hover:bg-maroon-light transition group">
                <div class="w-12 h-12 bg-maroon rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                    <i class="fas fa-clipboard-list text-white text-xl"></i>
                </div>
                <div>
                    <div class="font-bold text-gray-800">Isi KRS</div>
                    <div class="text-sm text-gray-600">Status: {{ $krsStatus }}</div>
                </div>
            </a>
            @else
            <div class="flex items-center gap-4 p-4 border-2 border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed opacity-60">
                <div class="w-12 h-12 bg-gray-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-white text-xl"></i>
                </div>
                <div>
                    <div class="font-bold text-gray-500">Isi KRS</div>
                    <div class="text-sm text-gray-400"><i class="fas fa-lock mr-1"></i> Profil belum lengkap</div>
                </div>
            </div>
            @endif

            @if($isProfileComplete)
            <a href="{{ route('mahasiswa.nilai.index') }}" class="flex items-center gap-4 p-4 border-2 border-blue-500 rounded-lg hover:bg-blue-50 transition group">
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                    <i class="fas fa-graduation-cap text-white text-xl"></i>
                </div>
                <div>
                    <div class="font-bold text-gray-800">Lihat Nilai</div>
                    <div class="text-sm text-gray-600">IPK: {{ number_format($ipk, 2) }}</div>
                </div>
            </a>
            @else
            <div class="flex items-center gap-4 p-4 border-2 border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed opacity-60">
                <div class="w-12 h-12 bg-gray-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-white text-xl"></i>
                </div>
                <div>
                    <div class="font-bold text-gray-500">Lihat Nilai</div>
                    <div class="text-sm text-gray-400"><i class="fas fa-lock mr-1"></i> Profil belum lengkap</div>
                </div>
            </div>
            @endif

            @if($isProfileComplete)
            <a href="{{ route('mahasiswa.jadwal.index') }}" class="flex items-center gap-4 p-4 border-2 border-green-500 rounded-lg hover:bg-green-50 transition group">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                </div>
                <div>
                    <div class="font-bold text-gray-800">Jadwal Kuliah</div>
                    <div class="text-sm text-gray-600">Semester Ini</div>
                </div>
            </a>
            @else
            <div class="flex items-center gap-4 p-4 border-2 border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed opacity-60">
                <div class="w-12 h-12 bg-gray-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                </div>
                <div>
                    <div class="font-bold text-gray-500">Jadwal Kuliah</div>
                    <div class="text-sm text-gray-400"><i class="fas fa-lock mr-1"></i> Profil belum lengkap</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Info + PA Row (50:50) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Informasi Penting (left) --}}
        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6">
            <div class="flex items-start gap-4">
                <i class="fas fa-info-circle text-blue-600 text-2xl"></i>
                <div>
                    <h4 class="font-bold text-blue-900 mb-2">Informasi Penting</h4>
                    <p class="text-sm text-blue-800">
                        Pastikan Anda telah mengisi KRS sebelum batas waktu yang ditentukan.
                        Untuk informasi lebih lanjut, hubungi bagian akademik kampus.
                    </p>
                </div>
            </div>
        </div>

        {{-- PA Contact Box (right) --}}
        <div class="bg-white border border-red-200 rounded-lg p-4">
            <div class="bg-red-100 border border-red-200 rounded-t-lg p-4">
                <h4 class="font-bold text-red-800">Kontak Layanan dan Konsultasi (PA):</h4>
            </div>
            <div class="p-4 text-sm text-gray-700">
                @php
                $paList = $mahasiswa->dosenPa()->with('user')->get();
                @endphp

                @if($paList->isEmpty())
                <p class="text-gray-600">Kami menyediakan dosen Penasihat Akademik (PA) untuk membimbing dan melayani Anda apabila ada pertanyaan atau masalah saat melakukan pengisian KRS. Silakan menghubungi bagian akademik untuk penempatan PA.</p>
                <p class="mt-3 text-xs text-gray-500">Waktu pelayanan: <strong>Hari Kerja, JAM 09.00 - 15.00 WIB</strong></p>
                @else
                <p class="text-gray-700 mb-3">Berikut kontak PA Anda. Klik nama untuk membuka chat WhatsApp.</p>
                <div class="space-y-3">
                    @foreach($paList as $pa)
                    @php
                    $phoneRaw = $pa->phone ?? $pa->user->phone ?? $pa->user->no_hp ?? '';
                    $phoneDigits = preg_replace('/\D+/', '', $phoneRaw);
                    // Normalize Indonesian numbers: leading 0 or starting with 8
                    if (str_starts_with($phoneDigits, '0')) {
                    $waNumber = '62' . ltrim($phoneDigits, '0');
                    } elseif (str_starts_with($phoneDigits, '8')) {
                    $waNumber = '62' . $phoneDigits;
                    } else {
                    $waNumber = $phoneDigits;
                    }
                    $waLink = $waNumber ? ('https://wa.me/' . $waNumber) : null;
                    @endphp

                    <div class="p-3 border rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-semibold text-gray-800">{{ $pa->user->name ?? ($pa->nidn ?? 'PA') }}</div>
                                <div class="text-xs text-gray-500">NIDN: {{ $pa->nidn ?? '-' }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-600">{{ $pa->pendidikan ?? '-' }}</div>
                                @if($waLink)
                                <a href="{{ $waLink }}" target="_blank" rel="noopener" class="inline-flex items-center mt-2 px-3 py-1 rounded-full bg-green-50 text-green-700 border border-green-100 text-sm hover:bg-green-100">
                                    <i class="fab fa-whatsapp mr-2"></i>
                                    Chat WA
                                </a>
                                @else
                                <div class="text-xs text-gray-400 mt-2">Nomor tidak tersedia</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 text-xs text-gray-500">
                    <strong>WAKTU PELAYANAN (WA Only!):</strong><br>
                    HARI KERJA, JAM 09.00 - 15.00 WIB
                </div>
                @endif

                <div class="mt-4">
                    @if($paList->isNotEmpty())
                    <a href="#" class="text-sm text-red-700 font-semibold">Klik disini untuk melihat kontak PA (WA Only!).</a>
                    @else
                    <a href="{{ Route::has('mahasiswa.kontak.pa') ? route('mahasiswa.kontak.pa') : '#' }}" class="text-sm text-red-700 font-semibold">Klik disini untuk melihat kontak PA (WA Only!).</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    console.log('Dashboard Mahasiswa loaded');
</script>
@endpush