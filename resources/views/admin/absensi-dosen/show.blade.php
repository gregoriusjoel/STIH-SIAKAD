@extends('layouts.admin')

@section('title', 'Detail Absensi Dosen')
@section('page-title', 'Detail Absensi Dosen')

@section('content')
    @section('navbar_breadcrumb')
        <nav class="flex items-center gap-2 text-sm text-[#616889]">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a href="{{ route('admin.absensi_dosen.index') }}" class="hover:text-primary transition-colors">Absensi Dosen</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-[#111218] font-medium">Detail Absensi</span>
        </nav>
    @endsection

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-maroon text-white flex items-center justify-center shadow-lg shadow-maroon/20">
                <span class="material-symbols-outlined text-[20px]">assignment_ind</span>
            </div>
            Detail Absensi Dosen
        </h1>
        <p class="text-gray-500 mt-1 ml-[52px]">
            {{ $dosen->user->name ?? 'Dosen' }} &mdash; {{ $kelasMataKuliah->mataKuliah->nama_mk ?? 'Mata Kuliah' }}
            <span class="mx-2">•</span> Kelas {{ $kelasMataKuliah->kode_kelas ?? '-' }}
        </p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium flex items-center gap-3 animate-fade-in-down">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    {{-- Info Cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Dosen Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group hover:shadow-md transition-shadow duration-300">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                 <span class="material-symbols-outlined text-[120px] text-maroon">person</span>
            </div>
            
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-maroon text-[18px]">badge</span> 
                Informasi Dosen
            </h3>
            
            <div class="flex items-center gap-5 relative z-10">
                <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-maroon to-red-900 flex items-center justify-center text-white text-2xl font-bold shrink-0 shadow-lg shadow-maroon/20">
                    {{ strtoupper(substr($dosen->user->name ?? 'D', 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-gray-900 text-lg mb-1">{{ $dosen->user->name ?? 'Dosen ' . $dosen->id }}</p>
                    <div class="space-y-1">
                        <p class="text-sm text-gray-500 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-gray-400">id_card</span>
                            NIDN: <span class="font-medium text-gray-700">{{ $dosen->nidn ?? '-' }}</span>
                        </p>
                        <p class="text-sm text-gray-500 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-gray-400">mail</span>
                            {{ $dosen->user->email ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kelas Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden hover:shadow-md transition-shadow duration-300">
             <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-maroon text-[18px]">school</span> 
                Informasi Kelas
            </h3>
            
            <dl class="grid grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <dt class="text-xs text-gray-400 mb-1">Mata Kuliah</dt>
                    <dd class="font-bold text-gray-900 text-sm truncate" title="{{ $kelasMataKuliah->mataKuliah->nama_mk ?? '-' }}">
                        {{ $kelasMataKuliah->mataKuliah->nama_mk ?? '-' }}
                    </dd>
                </div>
                <div class="text-right">
                    <dt class="text-xs text-gray-400 mb-1">Kode MK</dt>
                    <dd class="font-bold text-gray-900 font-mono text-sm">{{ $kelasMataKuliah->mataKuliah->kode_mk ?? '-' }}</dd>
                </div>
                
                <div>
                    <dt class="text-xs text-gray-400 mb-1">Kelas</dt>
                    <dd class="font-bold text-gray-900 text-sm flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-700 border border-gray-200">
                            {{ $kelasMataKuliah->kode_kelas ?? '-' }}
                        </span>
                    </dd>
                </div>
                <div class="text-right">
                    <dt class="text-xs text-gray-400 mb-1">SKS</dt>
                    <dd class="font-bold text-gray-900 text-sm">{{ $kelasMataKuliah->mataKuliah->sks ?? '-' }} SKS</dd>
                </div>

                <div class="col-span-2 pt-4 border-t border-dashed border-gray-200 mt-2 flex items-center justify-between">
                     <dt class="text-sm font-medium text-gray-600">Total Pertemuan Hadir</dt>
                     <dd>
                        <span class="inline-flex items-center justify-center px-3 py-1 bg-red-50 text-maroon rounded-lg text-sm font-bold border border-red-100">
                            {{ $attendances->count() }} Pertemuan
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Attendance Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-maroon">list_alt</span>
                Absensi Per Pertemuan
            </h3>
            
             <a href="{{ route('admin.absensi_dosen.index') }}"
                class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-200 text-gray-600 rounded-lg text-xs font-bold hover:bg-gray-50 hover:text-gray-800 transition-all">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span> Kembali
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-bold">
                        <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-6 py-4 text-center">Pertemuan Ke-</th>
                        <th class="px-6 py-4 text-center">Metode</th>
                        <th class="px-6 py-4 text-center">Jam Kelas</th>
                        <th class="px-6 py-4">Waktu Absen</th>
                        <th class="px-6 py-4 text-center">IP Address</th>
                        <th class="px-6 py-4 text-right">Lokasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($attendances as $i => $att)
                        @php
                            $metodeBadge = match($att->metode_pengajaran) {
                                'online'        => 'bg-blue-50 text-blue-700 border-blue-200',
                                'asynchronous'  => 'bg-orange-50 text-orange-700 border-orange-200',
                                default         => 'bg-gray-100 text-gray-700 border-gray-200',
                            };
                            $metodeIcon = match($att->metode_pengajaran) {
                                'online'        => 'videocam',
                                'asynchronous'  => 'schedule',
                                default         => 'location_on',
                            };
                            $metodeLabel = match($att->metode_pengajaran) {
                                'online'        => 'Daring (Online)',
                                'asynchronous'  => 'Asynchronous',
                                default         => 'Tatap Muka',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                            <td class="px-6 py-4 text-center text-sm text-gray-400 font-medium group-hover:text-gray-600">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $tipePT = $att->pertemuan->tipe_pertemuan ?? 'kuliah';
                                    $tipeBadgePT = match($tipePT) {
                                        'uts' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'uas' => 'bg-red-50 text-red-700 border-red-200',
                                        default => 'bg-gray-100 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center justify-center min-w-[2.5rem] h-10 px-2 {{ $tipeBadgePT }} rounded-xl text-sm font-bold shadow-sm border group-hover:border-gray-300 transition-colors">
                                    {{ $att->pertemuan->display_label ?? ($att->pertemuan->nomor_pertemuan ?? '-') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $metodeBadge }}">
                                    <span class="material-symbols-outlined text-[14px]">{{ $metodeIcon }}</span>
                                    {{ $metodeLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-lg bg-gray-50 border border-gray-100 text-xs font-mono text-gray-600">
                                    {{ $att->jam_kelas_mulai ? substr($att->jam_kelas_mulai, 0, 5) : '-' }} - {{ substr($att->jam_kelas_selesai, 0, 5) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-800">
                                        {{ \Carbon\Carbon::parse($att->jam_absen_dosen)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                    </span>
                                    <span class="text-xs text-gray-500 font-mono flex items-center gap-1 mt-0.5">
                                        <span class="material-symbols-outlined text-[12px]">schedule</span>
                                        {{ \Carbon\Carbon::parse($att->jam_absen_dosen)->format('H:i') }} WIB
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-mono text-gray-500 bg-gray-50 px-2 py-1 rounded border border-gray-100">{{ $att->ip_address ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @php
                                    $coords = $att->lokasi_dosen ? explode(',', $att->lokasi_dosen) : [];
                                    $hasCoords = count($coords) === 2 && is_numeric(trim($coords[0])) && is_numeric(trim($coords[1]));
                                @endphp
                                @if($hasCoords)
                                    <a href="https://www.google.com/maps?q={{ trim($coords[0]) }},{{ trim($coords[1]) }}"
                                       target="_blank" rel="noopener"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 text-gray-600 rounded-lg text-xs font-bold hover:bg-green-50 hover:text-green-700 hover:border-green-200 transition-all shadow-sm group-hover:shadow-md">
                                        <span class="material-symbols-outlined text-[16px] text-red-500 group-hover:animate-bounce">location_on</span>
                                        Lihat Peta
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 italic">Lokasi tidak tersedia</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <span class="material-symbols-outlined text-3xl opacity-50">event_busy</span>
                                    </div>
                                    <p class="font-bold text-gray-600">Belum ada data absensi</p>
                                    <p class="text-xs mt-1">Dosen belum melakukan absensi untuk mata kuliah ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
