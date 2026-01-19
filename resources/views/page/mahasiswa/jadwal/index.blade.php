@extends('layouts.mahasiswa')

@section('title', 'Jadwal Kuliah')
@section('page-title', 'Jadwal Kuliah')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    {{-- Header Card --}}
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-1">Jadwal Kuliah</h2>
                <p class="text-gray-600">Semester: <span class="font-semibold">{{ $semesterAktif->nama_semester ?? 'Tidak ada semester aktif' }}</span></p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                    <i class="fas fa-print"></i>
                    <span>Print</span>
                </button>
                <button onclick="downloadICS()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                    <i class="fas fa-download"></i>
                    <span>Export</span>
                </button>
            </div>
        </div>
    </div>

    @if($krsData->isEmpty())
    <div class="bg-white rounded-xl shadow-lg p-12">
        <div class="text-center">
            <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Tidak Ada Jadwal</h3>
            <p class="text-gray-500 mb-4">KRS Anda belum disetujui atau belum ada mata kuliah yang diambil.</p>
            <a href="{{ route('mahasiswa.krs.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-maroon text-white rounded-lg hover:bg-maroon-hover transition">
                <i class="fas fa-file-alt"></i>
                Lihat KRS
            </a>
        </div>
    </div>
    @else
    {{-- Jadwal per Hari --}}
    <div class="grid grid-cols-1 gap-6">
        @foreach($jadwalPerHari as $hari => $jadwals)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            {{-- Day Header --}}
            <div class="bg-gradient-to-r from-maroon to-red-800 text-white px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-day text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">{{ $hari }}</h3>
                            <p class="text-sm opacity-90">{{ count($jadwals) }} Mata Kuliah</p>
                        </div>
                    </div>
                    @if(count($jadwals) > 0)
                    <div class="text-right text-sm opacity-90">
                        <p>{{ substr($jadwals[0]['jam_mulai'], 0, 5) }} - {{ substr(end($jadwals)['jam_selesai'], 0, 5) }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Schedule Cards --}}
            @if(count($jadwals) > 0)
            <div class="p-6 space-y-4">
                @foreach($jadwals as $jadwal)
                <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-all hover:border-maroon">
                    <div class="flex items-start justify-between gap-4">
                        {{-- Left: Time --}}
                        <div class="flex-shrink-0 text-center">
                            <div class="bg-maroon/10 rounded-lg px-4 py-3">
                                <i class="fas fa-clock text-maroon text-xl mb-2"></i>
                                <p class="font-bold text-gray-800">{{ substr($jadwal['jam_mulai'], 0, 5) }}</p>
                                <p class="text-xs text-gray-500">-</p>
                                <p class="font-bold text-gray-800">{{ substr($jadwal['jam_selesai'], 0, 5) }}</p>
                            </div>
                        </div>

                        {{-- Middle: Course Info --}}
                        <div class="flex-grow">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-800 mb-1">{{ $jadwal['mata_kuliah'] }}</h4>
                                    <p class="text-sm text-gray-600">{{ $jadwal['kode_mk'] }} • {{ $jadwal['sks'] }} SKS</p>
                                </div>
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold">
                                    {{ $jadwal['kelas'] }}
                                </span>
                            </div>

                            <div class="flex items-center gap-6 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-chalkboard-teacher text-maroon"></i>
                                    <span>{{ $jadwal['dosen'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-door-open text-maroon"></i>
                                    <span>{{ $jadwal['ruangan'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-8 text-center text-gray-400">
                <i class="fas fa-calendar-times text-4xl mb-2"></i>
                <p>Tidak ada jadwal untuk hari ini</p>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Summary Card --}}
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Ringkasan Jadwal</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Total MK</p>
                <p class="text-2xl font-bold text-blue-600">{{ $krsData->count() }}</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Total SKS</p>
                <p class="text-2xl font-bold text-green-600">{{ $krsData->sum(function($krs) { return $krs->kelasMataKuliah->mataKuliah->sks ?? 0; }) }}</p>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Hari Kuliah</p>
                <p class="text-2xl font-bold text-purple-600">{{ count(array_filter($jadwalPerHari, function($j) { return count($j) > 0; })) }}</p>
            </div>
            <div class="text-center p-4 bg-orange-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Semester</p>
                <p class="text-2xl font-bold text-orange-600">{{ $semesterAktif->nama_semester ?? '-' }}</p>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    function downloadICS() {
        alert('Fitur export ke kalender akan segera tersedia!');
    }
</script>
@endpush
