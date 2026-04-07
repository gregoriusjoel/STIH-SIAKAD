@extends('layouts.admin')

@section('title', 'Detail Dosen — ' . $dosen->user->name)
@section('page-title', 'Detail Dosen')

@section('content')
@php
    $totalMkDiajar   = $dosen->kelasMataKuliahs->pluck('mata_kuliah_id')->unique()->count();
    $totalSemester   = $historySemesters->count();
    $mkSemesterIni   = $currentAssignments->count();
    $sksSemesterIni  = $currentAssignments->sum('sks');
@endphp

<div x-data="dosenDetail()" class="w-full space-y-6">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500 text-lg"></i>
            <span class="text-green-800 font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('warning'))
        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-xl flex items-center gap-3">
            <i class="fas fa-exclamation-triangle text-amber-500 text-lg"></i>
            <span class="text-amber-800 font-medium text-sm">{{ session('warning') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl flex items-center gap-3">
            <i class="fas fa-times-circle text-red-500 text-lg"></i>
            <span class="text-red-800 font-medium text-sm">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════ --}}
    {{-- STAT CARDS                                  --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-maroon/10 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-book-open text-maroon text-lg"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-gray-900">{{ $totalMkDiajar }}</div>
                <div class="text-xs font-semibold text-gray-500 leading-tight">Total MK<br>Diajarkan</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-history text-blue-600 text-lg"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-gray-900">{{ $totalSemester }}</div>
                <div class="text-xs font-semibold text-gray-500 leading-tight">Semester<br>Mengajar</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-violet-50 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-chalkboard-teacher text-violet-600 text-lg"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-gray-900">{{ $mkSemesterIni }}</div>
                <div class="text-xs font-semibold text-gray-500 leading-tight">MK Semester<br>Ini</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-layer-group text-emerald-600 text-lg"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-gray-900">{{ $sksSemesterIni }}</div>
                <div class="text-xs font-semibold text-gray-500 leading-tight">SKS Semester<br>Ini</div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- TAB NAVIGATION                              --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b border-gray-100 bg-gray-50/50">
            <nav class="flex">
                <button @click="activeTab = 'profil'"
                    :class="activeTab === 'profil'
                        ? 'border-maroon text-maroon bg-white shadow-sm'
                        : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="flex-1 py-4 px-4 text-center border-b-2 font-semibold text-sm transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-user-tie text-xs"></i>
                    <span>Profil</span>
                </button>
                <button @click="activeTab = 'penugasan'"
                    :class="activeTab === 'penugasan'
                        ? 'border-maroon text-maroon bg-white shadow-sm'
                        : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="flex-1 py-4 px-4 text-center border-b-2 font-semibold text-sm transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-book text-xs"></i>
                    <span>Penugasan</span>
                    @if($activeSemester)
                        <span class="hidden sm:inline-flex ml-1 text-[10px] bg-maroon text-white px-2 py-0.5 rounded-full font-bold">
                            {{ $activeSemester->nama_semester }}
                        </span>
                    @endif
                </button>
                <button @click="activeTab = 'histori'"
                    :class="activeTab === 'histori'
                        ? 'border-maroon text-maroon bg-white shadow-sm'
                        : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="flex-1 py-4 px-4 text-center border-b-2 font-semibold text-sm transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-history text-xs"></i>
                    <span>Histori</span>
                    @if($historySemesters->count() > 0)
                        <span class="hidden sm:inline-flex text-[10px] bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full font-bold">
                            {{ $historySemesters->count() }}
                        </span>
                    @endif
                </button>
            </nav>
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- TAB 1: PROFIL                               --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div x-show="activeTab === 'profil'" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
             class="p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row gap-6 sm:gap-8">
                {{-- Avatar & Quick Actions --}}
                <div class="flex flex-col items-center gap-4 flex-shrink-0">
                    <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-maroon to-red-900 flex items-center justify-center text-white font-black text-3xl shadow-lg shadow-maroon/20">
                        {{ strtoupper(substr($dosen->user->name, 0, 1)) }}
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold
                        {{ $dosen->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        <i class="fas fa-circle text-[8px] mr-1"></i>{{ ucfirst($dosen->status) }}
                    </span>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.dosen.edit', $dosen) }}"
                           class="px-4 py-2 bg-maroon text-white text-xs font-bold rounded-xl hover:bg-red-900 transition shadow-sm">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <a href="{{ route('admin.dosen.index') }}"
                           class="px-4 py-2 border border-gray-200 text-gray-600 text-xs font-bold rounded-xl hover:bg-gray-50 transition">
                            <i class="fas fa-arrow-left mr-1"></i>Kembali
                        </a>
                    </div>
                </div>

                {{-- Info Grid --}}
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nama Lengkap</p>
                        <p class="text-sm font-bold text-gray-900">{{ $dosen->user->name }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">NIDN</p>
                        <p class="text-sm font-bold font-mono text-maroon">{{ $dosen->nidn }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Email</p>
                        <p class="text-sm font-medium text-gray-700 break-all">{{ $dosen->user->email }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Telepon</p>
                        <p class="text-sm font-medium text-gray-700">{{ $dosen->phone ?? '-' }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pendidikan Terakhir</p>
                        <p class="text-sm font-medium text-gray-700">
                            @if(is_array($dosen->pendidikan_terakhir) && count($dosen->pendidikan_terakhir))
                                {{ implode(' → ', $dosen->pendidikan_terakhir) }}
                            @else
                                {{ $dosen->pendidikan ?? '-' }}
                            @endif
                        </p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Jabatan Fungsional</p>
                        <p class="text-sm font-medium text-gray-700">
                            {{ is_array($dosen->jabatan_fungsional) && count($dosen->jabatan_fungsional) ? implode(', ', $dosen->jabatan_fungsional) : '-' }}
                        </p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Program Studi</p>
                        <p class="text-sm font-medium text-gray-700">
                            {{ is_array($dosen->prodi) ? implode(', ', $dosen->prodi) : $dosen->prodi }}
                        </p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Dosen Tetap</p>
                        <p class="text-sm font-medium {{ $dosen->dosen_tetap ? 'text-green-700 font-bold' : 'text-gray-500' }}">
                            {{ $dosen->dosen_tetap ? '✓ Ya' : 'Tidak' }}
                        </p>
                    </div>
                    @if($dosen->address)
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 sm:col-span-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Alamat</p>
                        <p class="text-sm font-medium text-gray-700">{{ $dosen->address }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Kelas Aktif Summary --}}
            @if($dosen->kelasMataKuliahs && $dosen->kelasMataKuliahs->count())
            <div class="mt-8 pt-6 border-t border-gray-100">
                <h5 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="fas fa-chalkboard-teacher text-maroon"></i> Kelas yang Diampu
                </h5>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($dosen->kelasMataKuliahs as $km)
                    <div class="p-3 border border-gray-100 rounded-xl bg-gray-50 hover:bg-white hover:shadow-sm transition-all">
                        <div class="text-sm font-bold text-gray-800 truncate">{{ $km->mataKuliah->nama_mk ?? '-' }}</div>
                        <div class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                            <span class="font-mono bg-gray-200 rounded px-1.5 py-0.5">{{ $km->kode_kelas ?? '-' }}</span>
                            @if($km->hari)
                                <span>{{ $km->hari }} {{ $km->jam_mulai }}-{{ $km->jam_selesai }}</span>
                            @endif
                        </div>
                        <div class="text-xs font-bold text-maroon mt-1">{{ $km->mataKuliah->sks ?? '-' }} SKS</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- TAB 2: PENUGASAN TA AKTIF                  --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div x-show="activeTab === 'penugasan'" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
             class="p-6 sm:p-8">
            @if(!$activeSemester)
                <div class="text-center py-16 text-gray-400">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-2xl"></i>
                    </div>
                    <p class="font-semibold">Tidak ada semester aktif.</p>
                    <p class="text-sm mt-1">Silakan aktifkan semester terlebih dahulu.</p>
                </div>
            @else
                <div class="space-y-8">
                    {{-- Header + Copy button --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h5 class="text-base font-bold text-gray-900">
                                Penugasan — {{ $activeSemester->nama_semester }} {{ $activeSemester->tahun_ajaran }}
                            </h5>
                            <p class="text-sm text-gray-500 mt-0.5">
                                {{ $currentAssignments->count() }} MK · {{ $currentAssignments->sum('sks') }} SKS terpenuhi
                            </p>
                        </div>
                        @if($previousSemester && $previousAssignments->count() > 0)
                            <form method="POST" action="{{ route('admin.dosen.assignments.copy', $dosen) }}"
                                onsubmit="return confirm('Salin {{ $previousAssignments->count() }} MK dari {{ $previousSemester->nama_semester }} {{ $previousSemester->tahun_ajaran }}?')">
                                @csrf
                                <input type="hidden" name="source_semester_id" value="{{ $previousSemester->id }}">
                                <input type="hidden" name="target_semester_id" value="{{ $activeSemester->id }}">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-600/20">
                                    <i class="fas fa-copy text-xs"></i>
                                    Salin dari {{ $previousSemester->nama_semester }} {{ $previousSemester->tahun_ajaran }}
                                    <span class="bg-blue-500 text-white text-[10px] px-2 py-0.5 rounded-full">{{ $previousAssignments->count() }}</span>
                                </button>
                            </form>
                        @endif
                    </div>

                    {{-- Current Assignments --}}
                    @if($currentAssignments->count() > 0)
                        <div class="overflow-hidden rounded-2xl border border-gray-100">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">No</th>
                                        <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kode MK</th>
                                        <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Mata Kuliah</th>
                                        <th class="px-5 py-3.5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">SKS</th>
                                        <th class="px-5 py-3.5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($currentAssignments as $i => $mk)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-5 py-3.5 text-gray-500 text-xs">{{ $i + 1 }}</td>
                                        <td class="px-5 py-3.5 font-mono text-xs font-bold text-gray-700">{{ $mk->kode_mk }}</td>
                                        <td class="px-5 py-3.5 font-semibold text-gray-800">{{ $mk->nama_mk }}</td>
                                        <td class="px-5 py-3.5 text-center">
                                            <span class="font-bold text-violet-600">{{ $mk->sks }}</span>
                                        </td>
                                        <td class="px-5 py-3.5 text-center">
                                            <form method="POST"
                                                action="{{ route('admin.dosen.assignments.destroy', [$dosen, $mk]) }}"
                                                onsubmit="return confirm('Hapus penugasan {{ $mk->nama_mk }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="semester_id" value="{{ $activeSemester->id }}">
                                                <button type="submit"
                                                    class="p-1.5 text-red-400 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Hapus Penugasan">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 border-t border-gray-100">
                                    <tr>
                                        <td colspan="3" class="px-5 py-3 text-sm font-bold text-gray-600 text-right">Total SKS</td>
                                        <td class="px-5 py-3 text-center font-black text-maroon">{{ $currentAssignments->sum('sks') }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10 border-2 border-dashed border-gray-200 rounded-2xl text-gray-400">
                            <i class="fas fa-inbox text-3xl mb-3"></i>
                            <p class="text-sm font-medium">Belum ada penugasan untuk semester ini.</p>
                            <p class="text-xs mt-1">Cari dan pilih mata kuliah di bawah untuk menambahkan.</p>
                        </div>
                    @endif

                    {{-- Add Assignment Section --}}
                    <div class="pt-2">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="flex-1 h-px bg-gray-100"></div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider px-2">Tambah Penugasan</span>
                            <div class="flex-1 h-px bg-gray-100"></div>
                        </div>

                        <form method="POST" action="{{ route('admin.dosen.assignments.store', $dosen) }}" x-ref="assignForm">
                            @csrf
                            <input type="hidden" name="semester_id" value="{{ $activeSemester->id }}">

                            {{-- Search Box --}}
                            <div class="relative mb-4">
                                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400 text-sm"></i>
                                </div>
                                <input type="text"
                                    x-model="mkSearch"
                                    placeholder="Cari kode atau nama mata kuliah..."
                                    class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-2xl text-sm focus:ring-2 focus:ring-maroon/20 focus:border-maroon/50 transition bg-gray-50/50 focus:bg-white placeholder-gray-400">
                                <div x-show="mkSearch" class="absolute inset-y-0 right-4 flex items-center">
                                    <button type="button" @click="mkSearch = ''" class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-times-circle text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Available MK as Chips --}}
                            <div class="max-h-56 overflow-y-auto border border-gray-100 rounded-2xl p-4 bg-gray-50/50 mb-5">
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="mk in filteredMataKuliah" :key="mk.id">
                                        <button type="button"
                                            @click="toggleDraft(mk)"
                                            :class="draftIds.includes(mk.id)
                                                ? 'bg-maroon text-white border-maroon shadow-sm shadow-maroon/20'
                                                : 'bg-white text-gray-700 border-gray-200 hover:border-maroon/40 hover:text-maroon'"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-semibold transition-all cursor-pointer">
                                            <span x-text="mk.kode_mk" class="font-mono font-bold"></span>
                                            <span>—</span>
                                            <span x-text="mk.nama_mk"></span>
                                            <span :class="draftIds.includes(mk.id) ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500'"
                                                  class="ml-1 px-1.5 py-0.5 rounded text-[10px] font-bold" x-text="mk.sks + ' SKS'"></span>
                                            <i x-show="draftIds.includes(mk.id)" class="fas fa-check text-[9px] ml-0.5"></i>
                                        </button>
                                    </template>
                                    <div x-show="filteredMataKuliah.length === 0"
                                         class="w-full text-center py-6 text-gray-400 text-sm">
                                        <i class="fas fa-search-minus mr-2"></i>Tidak ada mata kuliah yang sesuai pencarian.
                                    </div>
                                </div>
                            </div>

                            {{-- Draft Preview --}}
                            <div x-show="draftIds.length > 0" x-transition class="mb-5">
                                <div class="p-4 bg-gradient-to-br from-maroon/5 to-red-50 border border-maroon/20 rounded-2xl">
                                    <div class="flex items-center justify-between mb-3">
                                        <h6 class="text-sm font-bold text-maroon flex items-center gap-2">
                                            <i class="fas fa-clipboard-check"></i>
                                            Draft Penugasan
                                            <span class="bg-maroon text-white text-[10px] px-2 py-0.5 rounded-full" x-text="draftIds.length + ' MK · ' + draftSks + ' SKS'"></span>
                                        </h6>
                                        <button type="button" @click="draftIds = []"
                                            class="text-xs text-red-500 hover:text-red-700 font-medium">
                                            <i class="fas fa-times mr-1"></i>Hapus Semua
                                        </button>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="id in draftIds" :key="id">
                                            <span class="inline-flex items-center gap-1.5 bg-white border border-maroon/30 rounded-full px-3 py-1.5 text-xs font-semibold text-gray-800 shadow-sm">
                                                <span x-text="getMkName(id)"></span>
                                                <button type="button" @click="draftIds = draftIds.filter(d => d !== id)"
                                                    class="w-4 h-4 rounded-full bg-red-100 hover:bg-red-200 text-red-500 flex items-center justify-center transition-colors">
                                                    <i class="fas fa-times text-[9px]"></i>
                                                </button>
                                            </span>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            {{-- Hidden inputs --}}
                            <template x-for="id in draftIds" :key="'h-'+id">
                                <input type="hidden" name="mata_kuliah_ids[]" :value="id">
                            </template>

                            {{-- Submit --}}
                            <div class="flex justify-end">
                                <button type="submit"
                                    :disabled="draftIds.length === 0"
                                    :class="draftIds.length === 0
                                        ? 'bg-gray-200 text-gray-400 cursor-not-allowed'
                                        : 'bg-maroon text-white hover:bg-red-900 shadow-lg shadow-maroon/20 hover:shadow-maroon/30'"
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm transition-all">
                                    <i class="fas fa-save"></i>
                                    <span>Simpan Penugasan</span>
                                    <span x-show="draftIds.length > 0" class="bg-white/20 px-2 py-0.5 rounded-full text-xs" x-text="draftIds.length + ' MK'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- TAB 3: HISTORI MENGAJAR                    --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div x-show="activeTab === 'histori'" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
             class="p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6">
                <h5 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-history text-maroon text-sm"></i>
                    Histori Penugasan Mengajar
                </h5>
                @if($historySemesters->count() > 0)
                    <span class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                        {{ $historySemesters->count() }} semester
                    </span>
                @endif
            </div>

            @if($historySemesters->count() > 0)
                {{-- Semester Selector --}}
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Pilih Semester</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($historySemesters as $sem)
                            @php
                                $isGanjil = stripos($sem->nama_semester, 'ganjil') !== false;
                                $isCurrent = $sem->is_current ?? false;
                            @endphp
                            <button type="button"
                                @click="selectedHistorySemester = '{{ $sem->id }}'; loadHistory()"
                                :class="selectedHistorySemester === '{{ $sem->id }}'
                                    ? '{{ $isCurrent ? 'bg-maroon text-white border-maroon' : ($isGanjil ? 'bg-blue-600 text-white border-blue-600' : 'bg-emerald-600 text-white border-emerald-600') }}'
                                    : 'bg-white text-gray-700 border-gray-200 hover:border-gray-300'"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border text-xs font-bold transition-all cursor-pointer shadow-sm">
                                @if($isCurrent)
                                    <i class="fas fa-star text-[10px]"></i>
                                @endif
                                {{ $sem->nama_semester }} {{ $sem->tahun_ajaran }}
                                <span :class="selectedHistorySemester === '{{ $sem->id }}'
                                    ? 'bg-white/20 text-white'
                                    : '{{ $isGanjil ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-600' }}'"
                                    class="px-1.5 py-0.5 rounded text-[10px] font-bold">
                                    {{ $sem->assignment_count }} MK
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Loading --}}
                <div x-show="historyLoading" class="text-center py-10">
                    <i class="fas fa-spinner fa-spin text-2xl text-maroon mb-3"></i>
                    <p class="text-sm text-gray-400">Memuat data histori...</p>
                </div>

                {{-- History Table --}}
                <div x-show="!historyLoading && historyData" x-transition>
                    {{-- Summary bar --}}
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100 mb-4">
                        <div>
                            <span class="text-xs text-gray-400 font-semibold">Semester:</span>
                            <span class="text-sm font-bold text-gray-900 ml-1" x-text="historyData?.semester?.nama_semester + ' ' + historyData?.semester?.tahun_ajaran"></span>
                        </div>
                        <div class="h-4 w-px bg-gray-200"></div>
                        <div>
                            <span class="text-xs text-gray-400 font-semibold">Mata Kuliah:</span>
                            <span class="font-black text-maroon ml-1" x-text="historyData?.assignments?.length + ' MK'"></span>
                        </div>
                        <div class="h-4 w-px bg-gray-200"></div>
                        <div>
                            <span class="text-xs text-gray-400 font-semibold">Total SKS:</span>
                            <span class="font-black text-violet-600 ml-1" x-text="historyData?.total_sks + ' SKS'"></span>
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-2xl border border-gray-100">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">No</th>
                                    <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kode MK</th>
                                    <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Mata Kuliah</th>
                                    <th class="px-5 py-3.5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">SKS</th>
                                    <th class="px-5 py-3.5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kelas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <template x-for="(mk, idx) in historyData?.assignments ?? []" :key="mk.id">
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-5 py-3.5 text-xs text-gray-400" x-text="idx + 1"></td>
                                        <td class="px-5 py-3.5 font-mono text-xs font-bold text-gray-700" x-text="mk.kode_mk"></td>
                                        <td class="px-5 py-3.5 font-semibold text-gray-800" x-text="mk.nama_mk"></td>
                                        <td class="px-5 py-3.5 text-center font-bold text-violet-600" x-text="mk.sks"></td>
                                        <td class="px-5 py-3.5 text-center">
                                            <span x-show="mk.kelas_count > 0"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700"
                                                x-text="mk.kelas_count + ' kelas'"></span>
                                            <span x-show="mk.kelas_count === 0"
                                                class="text-gray-300 text-xs">—</span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot class="bg-gray-50 border-t border-gray-100">
                                <tr>
                                    <td colspan="3" class="px-5 py-3 text-sm font-bold text-gray-600 text-right">Total SKS</td>
                                    <td class="px-5 py-3 text-center font-black text-maroon" x-text="historyData?.total_sks"></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div x-show="!historyLoading && !historyData"
                     class="text-center py-12 text-gray-400 border-2 border-dashed border-gray-200 rounded-2xl">
                    <i class="fas fa-hand-pointer text-3xl mb-3"></i>
                    <p class="text-sm font-medium">Pilih semester di atas untuk melihat histori.</p>
                </div>
            @else
                <div class="text-center py-16 text-gray-400">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-folder-open text-2xl"></i>
                    </div>
                    <p class="font-semibold">Belum ada histori penugasan mengajar.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function dosenDetail() {
    return {
        activeTab: 'profil',
        mkSearch: '',
        draftIds: [...@json($currentAssignments->pluck('id')->values())],
        selectedHistorySemester: '',
        historyData: null,
        historyLoading: false,

        allMataKuliah: {!! json_encode($availableMataKuliah->map(function($mk) {
            return [
                'id'      => $mk->id,
                'kode_mk' => $mk->kode_mk,
                'nama_mk' => $mk->nama_mk,
                'sks'     => $mk->sks,
            ];
        })->values()->toArray()) !!},

        currentIds: @json($currentAssignments->pluck('id')->values()),

        get filteredMataKuliah() {
            if (!this.mkSearch.trim()) return this.allMataKuliah;
            const q = this.mkSearch.toLowerCase();
            return this.allMataKuliah.filter(mk =>
                mk.kode_mk.toLowerCase().includes(q) ||
                mk.nama_mk.toLowerCase().includes(q)
            );
        },

        get draftSks() {
            return this.draftIds.reduce((sum, id) => {
                const mk = this.allMataKuliah.find(m => m.id === id);
                return sum + (mk ? mk.sks : 0);
            }, 0);
        },

        toggleDraft(mk) {
            const idx = this.draftIds.indexOf(mk.id);
            if (idx === -1) {
                this.draftIds.push(mk.id);
            } else {
                this.draftIds.splice(idx, 1);
            }
        },

        getMkName(id) {
            const mk = this.allMataKuliah.find(m => m.id === id);
            return mk ? mk.kode_mk + ' — ' + mk.nama_mk : 'MK #' + id;
        },

        async loadHistory() {
            if (!this.selectedHistorySemester) {
                this.historyData = null;
                return;
            }
            this.historyLoading = true;
            try {
                const url = `{{ url('admin/dosen/' . $dosen->id . '/assignments/history') }}/${this.selectedHistorySemester}`;
                const resp = await fetch(url, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                this.historyData = await resp.json();
            } catch (e) {
                console.error('Failed to load history', e);
                this.historyData = null;
            } finally {
                this.historyLoading = false;
            }
        }
    };
}
</script>
@endpush
