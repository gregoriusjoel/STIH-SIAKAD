@extends('layouts.admin')
@section('title', 'Jadwal Perkuliahan')
@section('page-title', 'Jadwal Perkuliahan')

@push('styles')
<style>
    .tab-btn.active { border-color: #8B1538; color: #8B1538; background-color: #FEF2F2; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
</style>
@endpush

@section('content')
<div class="space-y-6">
    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
    @endif

    {{-- Cards Layout: Pending, Waiting Room, Active --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Left Column (30%): Form Tambah Kelas --}}
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-maroon text-white">
                    <div class="font-semibold text-lg flex items-center"><i class="fas fa-plus-circle mr-2"></i>Tambah Jadwal Baru</div>
                </div>
                <form action="{{ route('admin.kelas-mata-kuliah.store') }}" method="POST" class="p-4">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fas fa-book text-gray-400 mr-1"></i>Mata Kuliah <span class="text-red-500">*</span></label>
                            <select name="mata_kuliah_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm" required>
                                <option value="">Pilih Mata Kuliah</option>
                                @foreach($mataKuliahs as $mk)
                                <option value="{{ $mk->id }}">{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fas fa-users text-gray-400 mr-1"></i>Nama Kelas <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_kelas" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm" placeholder="A, B, C..." required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fas fa-user-tie text-gray-400 mr-1"></i>Dosen <span class="text-red-500">*</span></label>
                            <select name="dosen_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm" required>
                                <option value="">Pilih Dosen</option>
                                @foreach($dosens as $d)
                                <option value="{{ $d->id }}">{{ $d->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fas fa-user-friends text-gray-400 mr-1"></i>Kuota <span class="text-red-500">*</span></label>
                                <input type="number" name="kuota" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm" placeholder="40" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fas fa-door-open text-gray-400 mr-1"></i>Ruangan</label>
                                <input type="text" name="ruangan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm" placeholder="R.101">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fas fa-calendar-day text-gray-400 mr-1"></i>Hari</label>
                            <select name="hari" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm">
                                <option value="">Pilih Hari</option>
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                                <option value="{{ $h }}">{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fas fa-clock text-gray-400 mr-1"></i>Mulai</label>
                                <input type="time" name="jam_mulai" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fas fa-clock text-gray-400 mr-1"></i>Selesai</label>
                                <input type="time" name="jam_selesai" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-maroon text-white px-4 py-2 rounded-lg hover:bg-maroon-700 transition flex items-center justify-center gap-2 text-sm font-semibold shadow-md mt-4">
                            <i class="fas fa-save"></i> Simpan Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right Column (70%): Cards & Table --}}
        <div class="lg:col-span-9 space-y-6">
            {{-- Top Row: Pending & Waiting Room (Side by Side) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Card: Pending Approval --}}
                <div class="bg-white rounded-2xl shadow-xl border-t-4 border-amber-400 overflow-hidden flex flex-col h-full min-h-[520px]">
                    <div class="p-6 border-b border-gray-200 flex items-center justify-between bg-amber-50">
                        <div class="font-semibold text-gray-800 text-lg"><i class="fas fa-hourglass-half mr-2 text-amber-600"></i>Menunggu Approval</div>
                        <div class="text-lg text-gray-600">@if($pendingJadwals->count() > 0)<span class="ml-2 px-2 py-0.5 bg-amber-500 text-white text-xs rounded-full">{{ $pendingJadwals->count() }}</span>@endif</div>
                    </div>
                    <div class="p-6 flex-1 overflow-y-auto max-h-[480px]">
                        @if($pendingJadwals->count() > 0 || (!empty($pendingReschedules) && $pendingReschedules->count() > 0) || (!empty($pendingKelasReschedules) && $pendingKelasReschedules->count() > 0))
                        <div class="space-y-4">
                            @foreach($pendingJadwals as $jadwal)
                            <div class="border border-amber-200 bg-white rounded-lg p-3 shadow-sm relative">
                                <div class="pr-20">
                                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                                        <h4 class="font-bold text-gray-800 text-sm">{{ $jadwal->kelas->mataKuliah->nama_mk }}</h4>
                                        <span class="bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded text-[10px] font-semibold">{{ $jadwal->kelas->mataKuliah->kode_mk }}</span>
                                        <span class="bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded text-[10px] font-semibold">{{ $jadwal->kelas->mataKuliah->sks }} SKS</span>
                                    </div>
                                    <div class="text-xs text-gray-600 space-y-0.5">
                                        <p><i class="fas fa-user-tie text-maroon mr-1"></i>{{ $jadwal->kelas->dosen->name ?? 'N/A' }}</p>
                                        <p><i class="fas fa-calendar-day text-maroon mr-1"></i>{{ $jadwal->hari }}, {{ substr($jadwal->jam_mulai, 0, 5) }}-{{ substr($jadwal->jam_selesai, 0, 5) }}</p>
                                        @if($jadwal->catatan_dosen)
                                        <p class="italic text-gray-500 mt-1"><i class="fas fa-comment text-maroon mr-1"></i>"{{ $jadwal->catatan_dosen }}"</p>
                                        @endif
                                        <p class="text-[10px] text-gray-400 mt-1">Diajukan {{ $jadwal->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="absolute top-3 right-3 flex flex-col gap-2">
                                    <form action="{{ route('admin.jadwal.approve', $jadwal) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 rounded-full bg-green-500 text-white hover:bg-green-600 transition flex items-center justify-center shadow-sm" onclick="return confirm('Setujui jadwal ini?')" title="Setujui">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="w-8 h-8 rounded-full bg-red-500 text-white hover:bg-red-600 transition flex items-center justify-center shadow-sm" onclick="openRejectModal({{ $jadwal->id }})" title="Tolak">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                                                       {{-- Pending Reschedule Requests (Detailed) --}}
                             @if(!empty($pendingReschedules) && $pendingReschedules->count() > 0)
                             <div class="mt-4">
                                <h4 class="text-sm font-bold text-gray-700 mb-3 pl-1 border-l-4 border-yellow-500">Permintaan Reschedule Dosen</h4>
                                <div class="space-y-3">
                                    @foreach($pendingReschedules as $r)
                                    <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-3 relative">
                                        <div class="pr-20"> {{-- Space for buttons --}}
                                            <div class="font-bold text-gray-800 text-sm mb-1">{{ $r->jadwal->kelas->mataKuliah->nama_mk ?? '-' }}</div>
                                            <div class="text-xs text-gray-600 mb-1"><i class="fas fa-user-tie text-maroon mr-1"></i>{{ $r->dosen->name ?? '-' }}</div>
                                            
                                            <div class="grid grid-cols-2 gap-2 text-xs mb-2">
                                                <div class="bg-white/50 p-1.5 rounded border border-yellow-100">
                                                    <span class="block text-gray-500 text-[10px] uppercase font-semibold">Jadwal Asli</span>
                                                    <div class="font-medium text-gray-700">
                                                        {{ $r->old_hari }} <span class="mx-1">•</span> {{ substr($r->old_jam_mulai,0,5) }}-{{ substr($r->old_jam_selesai,0,5) }}
                                                    </div>
                                                </div>
                                                <div class="bg-white p-1.5 rounded border border-yellow-200">
                                                    <span class="block text-green-600 text-[10px] uppercase font-semibold">Jadwal Baru</span>
                                                    <div class="font-bold text-gray-800">
                                                        {{ $r->new_hari }} <span class="mx-1">•</span> {{ substr($r->new_jam_mulai,0,5) }}-{{ substr($r->new_jam_selesai,0,5) }}
                                                    </div>
                                                </div>
                                            </div>

                                            @if($r->catatan)
                                            <div class="text-xs bg-white/60 p-2 rounded italic text-gray-600 border border-yellow-100">
                                                <i class="fas fa-quote-left text-yellow-400 mr-1"></i>{{ $r->catatan }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="absolute top-3 right-3 flex flex-col gap-2">
                                            <form action="{{ route('admin.jadwal.reschedules.approve', $r->id) }}" method="POST">
                                                @csrf
                                                <button class="w-8 h-8 rounded-full bg-green-500 text-white hover:bg-green-600 transition flex items-center justify-center shadow-sm" title="Setujui">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="w-8 h-8 rounded-full bg-red-500 text-white hover:bg-red-600 transition flex items-center justify-center shadow-sm" onclick="openRejectModalReschedule({{ $r->id }})" title="Tolak">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                             </div>
                             @endif

                             {{-- Pending Weekly Reschedules Requests (Detailed) --}}
                             @if(!empty($pendingKelasReschedules) && $pendingKelasReschedules->count() > 0)
                             <div class="mt-4">
                                <h4 class="text-sm font-bold text-gray-700 mb-3 pl-1 border-l-4 border-amber-500">Permintaan Reschedule Mingguan</h4>
                                <div class="space-y-3">
                                    @foreach($pendingKelasReschedules as $kr)
                                    <div class="border border-amber-200 bg-amber-50 rounded-lg p-3 relative">
                                        <div class="pr-20"> {{-- Space for buttons --}}
                                            <div class="font-bold text-gray-800 text-sm mb-1">{{ $kr->kelasMataKuliah->mataKuliah->nama_mk ?? '-' }}</div>
                                            <div class="flex items-center gap-2 text-xs text-gray-600 mb-2">
                                                <span><i class="fas fa-user-tie text-maroon mr-1"></i>{{ $kr->dosen->user->name ?? '-' }}</span>
                                                <span class="bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded text-[10px]">{{ $kr->kelasMataKuliah->kode_kelas }}</span>
                                            </div>
                                            
                                            <div class="grid grid-cols-2 gap-2 text-xs mb-2">
                                                <div class="bg-white/50 p-1.5 rounded border border-amber-100">
                                                    <span class="block text-gray-500 text-[10px] uppercase font-semibold">Jadwal Sebelumnya</span>
                                                    <div class="font-medium text-gray-700">
                                                        {{ $kr->old_hari }}<span class="mx-1">|</span>{{ $kr->old_jam_mulai ? substr($kr->old_jam_mulai,0,5) : '-' }}-{{ $kr->old_jam_selesai ? substr($kr->old_jam_selesai,0,5) : '-' }}
                                                    </div>
                                                </div>
                                                <div class="bg-white p-1.5 rounded border border-amber-200">
                                                    <span class="block text-green-600 text-[10px] uppercase font-semibold">Jadwal Baru</span>
                                                    <div class="font-bold text-gray-800">
                                                        {{ $kr->new_hari }}<span class="mx-1">|</span>{{ substr($kr->new_jam_mulai,0,5) }}-{{ substr($kr->new_jam_selesai,0,5) }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-[10px] text-blue-600 mb-2 font-medium">
                                                <i class="fas fa-calendar-week mr-1"></i>Berlaku: {{ $kr->week_start->format('d M') }} - {{ $kr->week_end->format('d M Y') }}
                                            </div>

                                            @if($kr->catatan_dosen)
                                            <div class="text-xs bg-white/60 p-2 rounded italic text-gray-600 border border-amber-100">
                                                <i class="fas fa-quote-left text-amber-400 mr-1"></i>{{ $kr->catatan_dosen }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="absolute top-3 right-3 flex flex-col gap-2">
                                            <form action="{{ route('admin.kelas.reschedules.approve', $kr->id) }}" method="POST">
                                                @csrf
                                                <button class="w-8 h-8 rounded-full bg-green-500 text-white hover:bg-green-600 transition flex items-center justify-center shadow-sm" title="Setujui">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="w-8 h-8 rounded-full bg-red-500 text-white hover:bg-red-600 transition flex items-center justify-center shadow-sm" onclick="openRejectModalWeekly({{ $kr->id }})" title="Tolak">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                             </div>
                             @endif
                        </div>
                        @else
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-check-circle text-3xl mb-2"></i>
                            <p class="text-xs">Semua aman</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Card: Waiting for Room --}}
                <div class="bg-white rounded-xl shadow-lg border-t-4 border-blue-400 overflow-hidden flex flex-col h-full">
                    <div class="p-6 border-b border-gray-200 flex items-center justify-between bg-blue-50">
                        <div class="font-semibold text-gray-800 text-lg"><i class="fas fa-door-open mr-2 text-blue-600"></i>Menunggu Ruangan</div>
                        <div class="text-lg text-gray-600">@if($approvedJadwals->count() > 0)<span class="ml-2 px-2 py-0.5 bg-blue-500 text-white text-xs rounded-full">{{ $approvedJadwals->count() }}</span>@endif</div>
                    </div>
                    <div class="p-6 flex-1 overflow-y-auto max-h-[480px]">
                        @if($approvedJadwals->count() > 0 || (!empty($approvedKelasReschedules) && $approvedKelasReschedules->count() > 0))
                        <div class="space-y-4">
                            {{-- Approved Jadwals --}}
                            @foreach($approvedJadwals as $jadwal)
                            <div class="border border-blue-200 bg-white rounded-lg p-3 shadow-sm">
                                <div class="flex flex-col gap-2">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                                            <h4 class="font-bold text-gray-800 text-sm">{{ $jadwal->kelas->mataKuliah->nama_mk }}</h4>
                                            <span class="bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded text-[10px] font-semibold">Approved</span>
                                        </div>
                                        <div class="text-xs text-gray-600 space-y-0.5">
                                            <p><i class="fas fa-user-tie text-maroon mr-1"></i>{{ $jadwal->kelas->dosen->name ?? 'N/A' }}</p>
                                            <p><i class="fas fa-clock text-maroon mr-1"></i>{{ $jadwal->hari }}, {{ substr($jadwal->jam_mulai, 0, 5) }}</p>
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.jadwal.assignRoom', $jadwal) }}" method="POST" class="mt-1">
                                        @csrf
                                        <div class="flex gap-1">
                                            <input type="text" name="section" placeholder="Kls" required class="flex-1 px-2 py-1 border border-gray-300 rounded text-xs w-10">
                                            <input type="text" name="ruangan" placeholder="Ruang" required class="flex-1 px-2 py-1 border border-gray-300 rounded text-xs">
                                            <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded text-xs hover:bg-blue-700 transition"><i class="fas fa-save"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endforeach

                            {{-- Approved Kelas Reschedules waiting for room --}}
                            @if(!empty($approvedKelasReschedules) && $approvedKelasReschedules->count() > 0)
                            <div class="mt-4 border-gray-200">
                                 <p class="text-xs font-bold text-blue-700 mb-2">Reschedule (Butuh Kelas & Ruang):</p>
                                 @foreach($approvedKelasReschedules as $kr)
                                 <div class="border border-blue-200 bg-blue-50 rounded p-2 text-xs mb-2">
                                     <div class="font-semibold">{{ $kr->kelasMataKuliah->mataKuliah->nama_mk ?? '-' }}</div>
                                     <div class="text-gray-500">{{ $kr->new_hari }} {{ substr($kr->new_jam_mulai,0,5) }} - {{ substr($kr->new_jam_selesai,0,5) }}</div>
                                     <div class="text-[10px] text-blue-600 mb-1">
                                         <i class="fas fa-calendar-week mr-1"></i>{{ $kr->week_start->format('d M') }} - {{ $kr->week_end->format('d M Y') }}
                                     </div>
                                     <form action="{{ route('admin.kelas.reschedules.assignRoom', $kr->id) }}" method="POST" class="mt-1 flex gap-1">
                                         @csrf
                                         <input type="text" name="new_kelas" placeholder="Kelas" required class="flex-1 px-2 py-1 border border-gray-300 rounded text-xs">
                                         <input type="text" name="new_ruang" placeholder="Ruang" required class="flex-1 px-2 py-1 border border-gray-300 rounded text-xs">
                                         <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded text-xs"><i class="fas fa-save"></i></button>
                                     </form>
                                 </div>
                                 @endforeach
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-check-circle text-3xl mb-2"></i>
                            <p class="text-xs">Semua aman</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Card: Active Schedules (Full Width inside Right Column) --}}
            <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
                <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                    <div class="font-semibold text-gray-800 text-lg"><i class="fas fa-check-circle mr-2 text-maroon-600"></i>Jadwal Aktif</div>
                    <div class="text-lg text-gray-600">@if($kelasMataKuliahs->count() > 0)<span class="ml-2 px-2 py-0.5 bg-maroon text-white text-xs rounded-full">{{ $kelasMataKuliahs->total() }}</span>@endif</div>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-base">
                            <thead class="bg-maroon text-white">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold">Hari/Jam</th>
                                    <th class="px-4 py-3 text-left font-semibold">Mata Kuliah</th>
                                    <th class="px-4 py-3 text-left font-semibold">Dosen</th>
                                    <th class="px-4 py-3 text-left font-semibold">Ruang</th>
                                    <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($kelasMataKuliahs as $k)
                                <tr class="hover:bg-maroon-50 transition duration-200">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-gray-900">{{ $k->hari ?: '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $k->jam_mulai ? substr($k->jam_mulai, 0, 5) : '-' }} - {{ $k->jam_selesai ? substr($k->jam_selesai, 0, 5) : '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-bold">{{ $k->mataKuliah->nama_mk }}</div>
                                        <div class="text-xs text-gray-500">{{ $k->kode_kelas }} • {{ $k->mataKuliah->sks }} SKS</div>
                                    </td>
                                    <td class="px-4 py-3">{{ $k->dosen->user->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 font-medium">{{ $k->ruang ?: '-' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center space-x-1">
                                            <a href="{{ route('admin.kelas-mata-kuliah.edit', $k) }}" class="bg-yellow-500 text-white p-1.5 rounded hover:bg-yellow-600 transition" title="Edit"><i class="fas fa-edit text-xs"></i></a>
                                            <form action="{{ route('admin.kelas-mata-kuliah.destroy', $k) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">@csrf @method('DELETE')<button type="submit" class="bg-red-500 text-white p-1.5 rounded hover:bg-red-600 transition" title="Hapus"><i class="fas fa-trash text-xs"></i></button></form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada jadwal aktif</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($kelasMataKuliahs->hasPages())
                    <div class="mt-4">{{ $kelasMataKuliahs->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-times-circle text-red-500 mr-2"></i>Tolak Jadwal</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea name="catatan" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Jelaskan alasan penolakan..."></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">Tolak Jadwal</button>
            </div>
        </form>
    </div>
</div>

{{-- Reject Modal for Kelas Reschedule (Weekly) --}}
<div id="rejectModalKelas" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center" style="display: none;">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-times-circle text-red-500 mr-2"></i>Tolak Reschedule Mingguan</h3>
        <form id="rejectFormKelas" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea name="catatan_admin" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Jelaskan alasan penolakan..."></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeRejectModalWeekly()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition" onclick="return confirm('Yakin ingin menolak permintaan reschedule ini?')">Tolak</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
        });
    });

    // Reject modal for jadwal
    function openRejectModal(jadwalId) {
        document.getElementById('rejectForm').action = '/admin/jadwal/' + jadwalId + '/reject';
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    // Reject modal for jadwal-reschedules (permintaan reschedule biasa)
    window.openRejectModalReschedule = function(rescheduleId) {
        document.getElementById('rejectForm').action = '/admin/jadwal-reschedules/' + rescheduleId + '/reject';
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    // Reject modal for kelas reschedule (weekly)
    window.openRejectModalWeekly = function(rescheduleId) {
        console.log('Opening reject modal for id:', rescheduleId);
        document.getElementById('rejectFormKelas').action = '/admin/kelas-reschedules/' + rescheduleId + '/reject';
        const modal = document.getElementById('rejectModalKelas');
        if(modal) {
            modal.style.display = 'flex';
        } else {
            console.error('Reject modal not found!');
        }
    }
    
    window.closeRejectModalWeekly = function() {
        const modal = document.getElementById('rejectModalKelas');
        if(modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endpush
@endsection