@extends('layouts.admin')

@section('title', 'Manajemen Mata Kuliah')
@section('page-title', 'Manajemen Mata Kuliah')

@section('content')
<div x-data="mkPage()" x-init="init()">

{{-- ═══════════════ HEADER ═══════════════ --}}
<div class="mb-5 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
            <i class="fas fa-book text-maroon dark:text-red-500"></i>
            Manajemen Mata Kuliah
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Master MK global &middot; Relasi per Tahun Ajaran &middot; Histori &amp; Carry-Forward</p>

        {{-- Semester aktif badge --}}
        <div class="mt-2 flex flex-wrap items-center gap-2">
            @if($activeSemester)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    Semester Aktif: {{ $activeSemester->display_label }}
                </span>
                @if($activeSemester->is_locked)
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                    <i class="fas fa-lock"></i> DIKUNCI
                </span>
                @endif
            @else
                <span class="px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Tidak ada semester aktif
                </span>
            @endif
        </div>
    </div>

    {{-- Right-side actions --}}
    <div class="flex flex-wrap gap-2 flex-shrink-0 items-start">
        <button onclick="document.getElementById('importModal').classList.remove('hidden')"
            class="group bg-white dark:bg-gray-800 text-maroon dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10 border-2 border-maroon/10 hover:border-maroon px-4 py-2 rounded-xl transition-all duration-200 flex items-center gap-2 shadow-sm text-sm font-bold">
            <i class="fas fa-file-import text-xs"></i>
            Import Mata Kuliah
        </button>
        <a href="{{ route('admin.mata-kuliah.create') }}"
            class="bg-maroon text-white hover:bg-red-900 px-4 py-2 rounded-xl transition flex items-center gap-2 shadow-md text-sm font-medium">
            <i class="fas fa-plus"></i>
            Tambah Mata Kuliah
        </a>
    </div>
</div>

{{-- ═══════════════ FLASH MESSAGES ═══════════════ --}}
@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-transition
    class="mb-4 flex items-center gap-3 bg-green-50 dark:bg-green-900/20 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded-xl">
    <i class="fas fa-check-circle text-green-500 flex-shrink-0"></i>
    <span class="text-sm">{{ session('success') }}</span>
    <button @click="show = false" class="ml-auto text-green-600 hover:text-green-800 flex-shrink-0"><i class="fas fa-times"></i></button>
</div>
@endif
@if(session('error'))
<div x-data="{ show: true }" x-show="show" x-transition
    class="mb-4 flex items-center gap-3 bg-red-50 dark:bg-red-900/20 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-300 px-4 py-3 rounded-xl">
    <i class="fas fa-exclamation-circle text-red-500 flex-shrink-0"></i>
    <span class="text-sm">{{ session('error') }}</span>
    <button @click="show = false" class="ml-auto text-red-600 hover:text-red-800 flex-shrink-0"><i class="fas fa-times"></i></button>
</div>
@endif

{{-- ═══════════════ SEMESTER CONTROL BAR ═══════════════ --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 px-4 py-3 mb-0 flex flex-wrap gap-3 items-center">

    <form method="GET" action="{{ route('admin.mata-kuliah.index') }}" class="flex gap-2 items-center">
        <input type="hidden" name="tab" value="{{ in_array($tab, ['ta-aktif','histori']) ? $tab : 'ta-aktif' }}">
        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 whitespace-nowrap"><i class="fas fa-calendar-alt mr-1"></i>Semester:</label>
        <select name="semester_id" onchange="this.form.submit()"
            class="text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-maroon">
            @foreach($allSemesters as $sem)
            <option value="{{ $sem->id }}" {{ $selectedSemester?->id == $sem->id ? 'selected' : '' }}>
                {{ $sem->display_label }}{{ $sem->is_active ? ' (Aktif)' : '' }}
            </option>
            @endforeach
        </select>
    </form>

    <div class="h-6 w-px bg-gray-200 dark:bg-gray-600"></div>

    @if($selectedSemester && !$selectedSemester->is_locked)
    <button @click="attachOpen = true"
        class="flex items-center gap-2 px-3 py-1.5 bg-maroon text-white rounded-lg hover:bg-red-900 text-xs font-semibold transition">
        <i class="fas fa-plus-circle"></i> Tambah ke Semester
    </button>
    @endif

    <button @click="carryOpen = true"
        class="flex items-center gap-2 px-3 py-1.5 bg-maroon text-white rounded-lg hover:bg-red-900 text-xs font-semibold transition">
        <i class="fas fa-copy"></i> Carry Forward
    </button>

    <button @click="restoreOpen = true"
        class="flex items-center gap-2 px-3 py-1.5 bg-maroon text-white rounded-lg hover:bg-red-900 text-xs font-semibold transition">
        <i class="fas fa-history"></i> Ambil Histori
    </button>

    <div class="h-6 w-px bg-gray-200 dark:bg-gray-600"></div>

    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" @click.away="open = false"
            class="flex items-center gap-1.5 px-3 py-1.5 bg-maroon text-white rounded-lg hover:bg-red-900 text-xs font-semibold transition">
            <i class="fas fa-toggle-on"></i> Aktifkan Semester <i class="fas fa-chevron-down text-xs ml-1"></i>
        </button>
        <div x-show="open" x-transition
            class="absolute left-0 top-full mt-1 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-xl border dark:border-gray-700 z-30 py-1">
            @foreach($allSemesters as $sem)
            <form action="{{ route('admin.mata-kuliah-semester.activate-semester', $sem) }}" method="POST"
                onsubmit="return confirm('Aktifkan semester {{ $sem->display_label }}?')">
                @csrf @method('PATCH')
                <button type="submit"
                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center gap-2 {{ $sem->is_active ? 'font-bold text-green-700 dark:text-green-400' : 'text-gray-700 dark:text-gray-300' }}">
                    @if($sem->is_active)<i class="fas fa-check-circle text-green-500 w-4"></i>@else<span class="w-4 inline-block"></span>@endif
                    {{ $sem->display_label }}
                </button>
            </form>
            @endforeach
        </div>
    </div>

    @if($selectedSemester)
        @if($selectedSemester->is_locked)
        <form action="{{ route('admin.mata-kuliah-semester.unlock-semester', $selectedSemester) }}" method="POST"
            onsubmit="return confirm('Buka kunci semester {{ $selectedSemester->display_label }}?')">
            @csrf @method('PATCH')
            <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 bg-orange-500 text-white rounded-lg hover:bg-orange-600 text-xs font-semibold transition">
                <i class="fas fa-lock-open"></i> Buka Kunci
            </button>
        </form>
        @else
        <form action="{{ route('admin.mata-kuliah-semester.lock-semester', $selectedSemester) }}" method="POST"
            onsubmit="return confirm('Kunci semester {{ $selectedSemester->display_label }}?')">
            @csrf @method('PATCH')
            <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-700 text-white rounded-lg hover:bg-gray-800 text-xs font-semibold transition">
                <i class="fas fa-lock"></i> Kunci Semester
            </button>
        </form>
        @endif
    @endif

    <div class="ml-auto flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
        @if($selectedSemester)
        <span>MK Aktif TA: <strong class="text-maroon dark:text-red-400">{{ $activePivots->count() }}</strong></span>
        <span class="text-gray-300 dark:text-gray-600">|</span>
        <span>Histori: <strong>{{ $historyPivots->count() }}</strong></span>
        @if($selectedSemester->is_locked)
        <span class="px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 font-bold">
            <i class="fas fa-lock mr-0.5"></i>LOCKED
        </span>
        @endif
        @endif
    </div>
</div>

{{-- ═══════════════ TABS ═══════════════ --}}
<div class="flex border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
    <a href="{{ route('admin.mata-kuliah.index', ['tab' => 'master', 'semester_id' => $selectedSemester?->id]) }}"
        class="px-5 py-3 text-sm font-semibold border-b-2 transition {{ $tab === 'master' ? 'border-maroon text-maroon dark:text-red-400 dark:border-red-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-maroon' }}">
        <i class="fas fa-database mr-1.5"></i> Master MK
        <span class="ml-1 px-1.5 py-0.5 rounded-full text-xs {{ $tab === 'master' ? 'bg-maroon text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">{{ $mataKuliahs->total() }}</span>
    </a>
    <a href="{{ route('admin.mata-kuliah.index', ['tab' => 'ta-aktif', 'semester_id' => $selectedSemester?->id]) }}"
        class="px-5 py-3 text-sm font-semibold border-b-2 transition {{ $tab === 'ta-aktif' ? 'border-maroon text-maroon dark:text-red-400 dark:border-red-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-maroon' }}">
        <i class="fas fa-check-circle mr-1.5"></i> TA Aktif
        <span class="ml-1 px-1.5 py-0.5 rounded-full text-xs {{ $tab === 'ta-aktif' ? 'bg-maroon text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">{{ $activePivots->count() }}</span>
    </a>
    <a href="{{ route('admin.mata-kuliah.index', ['tab' => 'histori', 'semester_id' => $selectedSemester?->id]) }}"
        class="px-5 py-3 text-sm font-semibold border-b-2 transition {{ $tab === 'histori' ? 'border-maroon text-maroon dark:text-red-400 dark:border-red-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-maroon' }}">
        <i class="fas fa-archive mr-1.5"></i> Histori TA
        <span class="ml-1 px-1.5 py-0.5 rounded-full text-xs {{ $tab === 'histori' ? 'bg-maroon text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">{{ $historyPivots->count() }}</span>
    </a>
</div>

{{-- ═══════════════ TAB CONTENT WRAPPER ═══════════════ --}}
<div class="bg-white dark:bg-gray-800 rounded-b-xl shadow border border-t-0 border-gray-200 dark:border-gray-700 overflow-hidden">

{{-- ─────────── TAB: MASTER MK ─────────── --}}
@if($tab === 'master')
{{-- Filter Bar --}}
<div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex flex-wrap gap-3 items-center">
    <div class="flex flex-wrap gap-3 items-center w-full">
        <div class="relative flex-1 min-w-[200px] max-w-xs">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" id="master-search" placeholder="Cari kode / nama MK..."
                oninput="filterMasterTable()"
                class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon">
        </div>
        <select id="master-semester-filter" onchange="filterMasterTable()"
            class="text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-maroon">
            <option value="">Semua Semester</option>
            @for($s = 1; $s <= 8; $s++)
            <option value="{{ $s }}">Semester {{ $s }}</option>
            @endfor
        </select>
        <button type="button" onclick="document.getElementById('master-search').value='';document.getElementById('master-semester-filter').value='';filterMasterTable()"
            class="px-3 py-2 text-sm text-gray-600 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
            <i class="fas fa-times mr-1"></i> Reset
        </button>
        <span id="master-count" class="ml-auto text-xs text-gray-500 dark:text-gray-400"></span>
    </div>
</div>
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-maroon text-white">
            <tr>
                <th class="px-5 py-4 text-left text-xs font-bold uppercase">No</th>
                @php
                    $sortUrl = function($col) use ($sort, $sortDir, $selectedSemester, $search, $semesterFilter) {
                        $newDir = ($sort === $col && $sortDir === 'asc') ? 'desc' : 'asc';
                        return route('admin.mata-kuliah.index', array_filter([
                            'tab' => 'master', 'semester_id' => $selectedSemester?->id,
                            'search' => $search, 'semester_filter' => $semesterFilter,
                            'sort' => $col, 'sort_dir' => $newDir,
                        ], fn($v) => $v !== '' && $v !== null));
                    };
                    $sortIcon = function($col) use ($sort, $sortDir) {
                        if ($sort !== $col) return '<i class="fas fa-sort ml-1 opacity-40"></i>';
                        return $sortDir === 'asc' ? '<i class="fas fa-sort-up ml-1"></i>' : '<i class="fas fa-sort-down ml-1"></i>';
                    };
                @endphp
                <th class="px-5 py-4 text-left text-xs font-bold uppercase"><a href="{{ $sortUrl('kode_mk') }}" class="hover:underline">Kode MK {!! $sortIcon('kode_mk') !!}</a></th>
                <th class="px-5 py-4 text-left text-xs font-bold uppercase"><a href="{{ $sortUrl('nama_mk') }}" class="hover:underline"><i class="fas fa-book-open mr-1"></i>Nama Mata Kuliah {!! $sortIcon('nama_mk') !!}</a></th>
                <th class="px-5 py-4 text-center text-xs font-bold uppercase"><a href="{{ $sortUrl('sks') }}" class="hover:underline"><i class="fas fa-calculator mr-1"></i>SKS {!! $sortIcon('sks') !!}</a></th>
                <th class="px-5 py-4 text-center text-xs font-bold uppercase"><a href="{{ $sortUrl('jenis') }}" class="hover:underline"><i class="fas fa-tags mr-1"></i>Jenis {!! $sortIcon('jenis') !!}</a></th>
                <th class="px-5 py-4 text-left text-xs font-bold uppercase"><i class="fas fa-graduation-cap mr-1"></i>Prodi</th>
                <th class="px-5 py-4 text-center text-xs font-bold uppercase"><i class="fas fa-cog mr-1"></i>Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="master-tbody">
            @forelse($mataKuliahs as $mk)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition master-row"
                data-kode="{{ strtolower($mk->kode_mk) }}" data-nama="{{ strtolower($mk->nama_mk) }}"
                data-semester="{{ $mk->semester }}" data-sks="{{ $mk->sks }}">
                <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400 font-medium master-row-no">
                    {{ ($mataKuliahs->currentPage() - 1) * $mataKuliahs->perPage() + $loop->iteration }}
                </td>
                <td class="px-5 py-4">
                    <span class="font-mono text-sm text-maroon dark:text-red-400 font-bold">{{ $mk->kode_mk }}</span>
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-lg bg-maroon flex items-center justify-center text-white flex-shrink-0">
                            <i class="fas fa-book text-xs"></i>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $mk->nama_mk }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Semester {{ $mk->semester }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 text-center">
                    <span class="px-2.5 py-1 text-sm font-bold rounded-full bg-red-100 dark:bg-red-900/30 text-maroon dark:text-red-300">
                        {{ $mk->sks }} SKS
                    </span>
                </td>
                <td class="px-5 py-4 text-center">
                    @php
                        $jenisColors = [
                            'wajib_nasional' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300',
                            'wajib_prodi'    => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
                            'pilihan'        => 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300',
                            'peminatan'      => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
                        ];
                        $colorClass = $jenisColors[$mk->jenis] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300';
                    @endphp
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $colorClass }}">
                        <i class="fas fa-star mr-1"></i>{{ ucwords(str_replace('_', ' ', $mk->jenis)) }}
                    </span>
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $mk->prodi->jenjang === 'S1' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300'
                            : ($mk->prodi->jenjang === 'S2' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300'
                            : 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300') }}">
                            {{ $mk->prodi->jenjang }}
                        </span>
                        <div>
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $mk->prodi->nama_prodi }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $mk->fakultas->nama_fakultas }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <button type="button"
                            onclick="document.getElementById('modal-mk-{{ $mk->id }}').classList.remove('hidden')"
                            class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition" title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="{{ route('admin.mata-kuliah.edit', $mk) }}"
                            class="p-2 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded transition" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.mata-kuliah.destroy', $mk) }}" method="POST" class="inline delete-form">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>

            {{-- Detail Modal --}}
            <div id="modal-mk-{{ $mk->id }}" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-11/12 md:w-3/4 lg:w-1/2 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between px-6 py-4 bg-maroon text-white rounded-t-xl">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">Detail Mata Kuliah</h3>
                                <p class="text-xs text-white/80">{{ $mk->kode_mk }}</p>
                            </div>
                        </div>
                        <button onclick="document.getElementById('modal-mk-{{ $mk->id }}').classList.add('hidden')" class="text-white text-xl">&times;</button>
                    </div>
                    <div class="p-6 grid grid-cols-2 gap-4 text-sm">
                        <div class="col-span-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Nama Mata Kuliah</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $mk->nama_mk }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Kode MK</p>
                            <p class="font-mono font-bold text-maroon dark:text-red-400">{{ $mk->kode_mk }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">SKS</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $mk->sks }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Semester</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $mk->semester }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Jenis</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ ucwords(str_replace('_', ' ', $mk->jenis)) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Prodi</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $mk->prodi->nama_prodi }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Fakultas</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $mk->fakultas->nama_fakultas }}</p>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 flex justify-end gap-3 rounded-b-xl">
                        <a href="{{ route('admin.mata-kuliah.edit', $mk) }}" class="bg-maroon text-white px-4 py-2 rounded-lg text-sm shadow">Edit</a>
                        <button onclick="document.getElementById('modal-mk-{{ $mk->id }}').classList.add('hidden')"
                            class="px-4 py-2 border dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 text-sm">Tutup</button>
                    </div>
                </div>
            </div>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-16 text-center text-gray-400 dark:text-gray-500">
                    <i class="fas fa-inbox text-5xl mb-4"></i>
                    <p class="text-lg font-medium">Belum ada mata kuliah</p>
                    <p class="text-sm mt-1">Tambahkan atau import mata kuliah terlebih dahulu</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($mataKuliahs->hasPages())
<div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
    {{ $mataKuliahs->links() }}
</div>
@endif

{{-- ─────────── TAB: TA AKTIF ─────────── --}}
@elseif($tab === 'ta-aktif')
@if($activePivots->count() > 0)
{{-- Filter Bar TA Aktif --}}
<div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex flex-wrap gap-3 items-center">
    <div class="relative flex-1 min-w-[200px] max-w-xs">
        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
        <input type="text" id="active-search" placeholder="Cari kode / nama MK..."
            oninput="filterActiveTable()"
            class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon">
    </div>
    <select id="active-semester-filter" onchange="filterActiveTable()"
        class="text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-maroon">
        <option value="">Semua Semester</option>
        @for($s = 1; $s <= 8; $s++)
        <option value="{{ $s }}">Semester {{ $s }}</option>
        @endfor
    </select>
    <button type="button" onclick="document.getElementById('active-search').value='';document.getElementById('active-semester-filter').value='';filterActiveTable()"
        class="px-3 py-2 text-sm text-gray-600 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
        <i class="fas fa-times mr-1"></i> Reset
    </button>
    <span id="active-count" class="ml-auto text-xs text-gray-500 dark:text-gray-400"></span>
</div>
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="active-table">
        <thead class="bg-maroon text-white">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase w-8">
                    <input type="checkbox" id="select-all-active" class="w-4 h-4 rounded border-white/30"
                        onchange="document.querySelectorAll('.active-mk-checkbox').forEach(c => c.checked = this.checked)">
                </th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase">No</th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase cursor-pointer select-none" onclick="sortActiveTable('kode_mk')">Kode MK <i class="fas fa-sort ml-1 opacity-40" id="active-sort-icon-kode_mk"></i></th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase cursor-pointer select-none" onclick="sortActiveTable('nama_mk')">Nama Mata Kuliah <i class="fas fa-sort ml-1 opacity-40" id="active-sort-icon-nama_mk"></i></th>
                <th class="px-4 py-3 text-center text-xs font-bold uppercase cursor-pointer select-none" onclick="sortActiveTable('sks')">SKS <i class="fas fa-sort ml-1 opacity-40" id="active-sort-icon-sks"></i></th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase">Prodi</th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase cursor-pointer select-none" onclick="sortActiveTable('semester')">Semester <i class="fas fa-sort ml-1 opacity-40" id="active-sort-icon-semester"></i></th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase">Asal</th>
                <th class="px-4 py-3 text-center text-xs font-bold uppercase">Aktif Sejak</th>
                <th class="px-4 py-3 text-center text-xs font-bold uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="active-tbody">
            @foreach($activePivots as $i => $pivot)
            @php $mk = $pivot->mataKuliah; @endphp
            <tr class="hover:bg-red-50 dark:hover:bg-red-900/10 transition active-row"
                data-kode="{{ strtolower($mk->kode_mk) }}" data-nama="{{ strtolower($mk->nama_mk) }}"
                data-semester="{{ $mk->semester }}" data-sks="{{ $mk->sks }}">
                <td class="px-4 py-3">
                    <input type="checkbox" value="{{ $mk->id }}" class="active-mk-checkbox w-4 h-4 rounded border-gray-300 text-maroon">
                </td>
                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 active-row-no">{{ $i + 1 }}</td>
                <td class="px-4 py-3">
                    <span class="font-mono text-xs font-bold text-maroon dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-2 py-0.5 rounded">{{ $mk->kode_mk }}</span>
                </td>
                <td class="px-4 py-3">
                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $mk->nama_mk }}</div>
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-red-100 dark:bg-red-900/30 text-maroon dark:text-red-300">{{ $mk->sks }} SKS</span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $mk->prodi?->nama_prodi }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-maroon dark:text-red-300">Semester {{ $mk->semester }}</span>
                </td>
                <td class="px-4 py-3">
                    @if($pivot->source_semester_id)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                        <i class="fas fa-copy text-xs"></i> {{ $pivot->sourceSemester?->display_label }}
                    </span>
                    @else
                    <span class="text-xs text-gray-400 dark:text-gray-500">Manual</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-center text-xs text-gray-500 dark:text-gray-400">
                    {{ $pivot->activated_at ? $pivot->activated_at->format('d/m/Y') : '-' }}
                </td>
                <td class="px-4 py-3 text-center">
                    @if($selectedSemester && !$selectedSemester->is_locked)
                    <form method="POST" action="{{ route('admin.mata-kuliah-semester.detach') }}"
                        onsubmit="return confirm('Pindah ke histori?')">
                        @csrf
                        <input type="hidden" name="semester_id" value="{{ $selectedSemester->id }}">
                        <input type="hidden" name="mata_kuliah_ids[]" value="{{ $mk->id }}">
                        <button type="submit" class="p-1.5 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded transition text-xs" title="Pindah ke Histori">
                            <i class="fas fa-archive"></i>
                        </button>
                    </form>
                    @else
                    <i class="fas fa-lock text-gray-300 dark:text-gray-600 text-xs"></i>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@if($selectedSemester && !$selectedSemester->is_locked)
<div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-t dark:border-gray-700">
    <form method="POST" action="{{ route('admin.mata-kuliah-semester.detach') }}"
        onsubmit="return confirm('Pindahkan MK terpilih ke histori?')" id="bulk-detach-form">
        @csrf
        <input type="hidden" name="semester_id" value="{{ $selectedSemester->id }}">
        <div id="bulk-detach-hidden"></div>
        <button type="submit" onclick="prepareBulkDetach(event)"
            class="px-4 py-1.5 text-xs bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition flex items-center gap-1">
            <i class="fas fa-archive"></i> Pindah Histori (Terpilih)
        </button>
    </form>
</div>
@endif
@else
<div class="text-center py-16 text-gray-400 dark:text-gray-500">
    <i class="fas fa-book-open text-5xl mb-4"></i>
    <p class="text-lg font-medium">Belum ada MK aktif untuk semester ini</p>
    <p class="text-sm mt-1">Gunakan <strong>Tambah ke Semester</strong> atau <strong>Carry Forward</strong> di atas.</p>
</div>
@endif

{{-- ─────────── TAB: HISTORI TA ─────────── --}}
@else
@if($historyPivots->count() > 0)
{{-- Filter Bar Histori --}}
<div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex flex-wrap gap-3 items-center">
    <div class="relative flex-1 min-w-[200px] max-w-xs">
        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
        <input type="text" id="history-search" placeholder="Cari kode / nama MK..."
            oninput="filterHistoryTable()"
            class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon">
    </div>
    <select id="history-semester-filter" onchange="filterHistoryTable()"
        class="text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-maroon">
        <option value="">Semua Semester</option>
        @for($s = 1; $s <= 8; $s++)
        <option value="{{ $s }}">Semester {{ $s }}</option>
        @endfor
    </select>
    <button type="button" onclick="document.getElementById('history-search').value='';document.getElementById('history-semester-filter').value='';filterHistoryTable()"
        class="px-3 py-2 text-sm text-gray-600 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
        <i class="fas fa-times mr-1"></i> Reset
    </button>
    <span id="history-count" class="ml-auto text-xs text-gray-500 dark:text-gray-400"></span>
</div>
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="history-table">
        <thead class="bg-maroon text-white">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase">No</th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase cursor-pointer select-none" onclick="sortHistoryTable('kode_mk')">Kode MK <i class="fas fa-sort ml-1 opacity-40" id="history-sort-icon-kode_mk"></i></th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase cursor-pointer select-none" onclick="sortHistoryTable('nama_mk')">Nama Mata Kuliah <i class="fas fa-sort ml-1 opacity-40" id="history-sort-icon-nama_mk"></i></th>
                <th class="px-4 py-3 text-center text-xs font-bold uppercase cursor-pointer select-none" onclick="sortHistoryTable('sks')">SKS <i class="fas fa-sort ml-1 opacity-40" id="history-sort-icon-sks"></i></th>
                <th class="px-4 py-3 text-center text-xs font-bold uppercase cursor-pointer select-none" onclick="sortHistoryTable('semester')">Semester <i class="fas fa-sort ml-1 opacity-40" id="history-sort-icon-semester"></i></th>
                <th class="px-4 py-3 text-center text-xs font-bold uppercase">Status</th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase">Asal Semester</th>
                <th class="px-4 py-3 text-center text-xs font-bold uppercase">Non-aktif Sejak</th>
                <th class="px-4 py-3 text-center text-xs font-bold uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="history-tbody">
            @foreach($historyPivots as $i => $pivot)
            @php $mk = $pivot->mataKuliah; @endphp
            <tr class="hover:bg-red-50 dark:hover:bg-red-900/10 transition history-row {{ $pivot->status === 'archived' ? 'opacity-60' : '' }}"
                data-kode="{{ strtolower($mk->kode_mk) }}" data-nama="{{ strtolower($mk->nama_mk) }}"
                data-semester="{{ $mk->semester }}" data-sks="{{ $mk->sks }}">
                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 history-row-no">{{ $i + 1 }}</td>
                <td class="px-4 py-3">
                    <span class="font-mono text-xs font-bold text-maroon dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-2 py-0.5 rounded">{{ $mk->kode_mk }}</span>
                </td>
                <td class="px-4 py-3">
                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $mk->nama_mk }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $mk->sks }} SKS &bull; Semester {{ $mk->semester }}</div>
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">{{ $mk->sks }} SKS</span>
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-maroon dark:text-red-300">Semester {{ $mk->semester }}</span>
                </td>
                <td class="px-4 py-3 text-center">
                    @if($pivot->status === 'history')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">
                        <i class="fas fa-clock text-xs"></i> Histori
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                        <i class="fas fa-archive text-xs"></i> Arsip
                    </span>
                    @endif
                </td>
                <td class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400">{{ $pivot->sourceSemester?->display_label ?? '—' }}</td>
                <td class="px-4 py-3 text-center text-xs text-gray-500 dark:text-gray-400">
                    {{ $pivot->deactivated_at ? $pivot->deactivated_at->format('d/m/Y') : '-' }}
                </td>
                <td class="px-4 py-3 text-center">
                    @if($activeSemester && !$activeSemester->is_locked)
                    <form method="POST" action="{{ route('admin.mata-kuliah-semester.restore') }}"
                        onsubmit="return confirm('Pulihkan MK ini ke semester aktif?')">
                        @csrf
                        <input type="hidden" name="source_semester_id" value="{{ $selectedSemester->id }}">
                        <input type="hidden" name="target_semester_id" value="{{ $activeSemester->id }}">
                        <input type="hidden" name="mata_kuliah_ids[]" value="{{ $mk->id }}">
                        <button type="submit"
                            class="px-2.5 py-1 text-xs bg-red-100 dark:bg-red-900/30 text-maroon dark:text-red-400 hover:bg-red-200 rounded-lg transition font-medium">
                            <i class="fas fa-undo mr-1"></i> Pulihkan
                        </button>
                    </form>
                    @else
                    <i class="fas fa-lock text-gray-300 dark:text-gray-600 text-xs"></i>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="text-center py-16 text-gray-400 dark:text-gray-500">
    <i class="fas fa-archive text-5xl mb-4"></i>
    <p class="text-lg font-medium">Tidak ada histori MK untuk semester ini</p>
    <p class="text-sm mt-1">Histori muncul saat semester baru diaktifkan.</p>
</div>
@endif
@endif

</div>{{-- end tab content wrapper --}}


{{-- ═══════════════════════════════════════
     MODALS
═══════════════════════════════════════ --}}

{{-- MODAL: TAMBAH MK KE SEMESTER --}}
<div x-show="attachOpen" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
    @keydown.escape.window="attachOpen = false">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col"
        @click.away="attachOpen = false">
        <div class="flex items-center justify-between px-6 py-4 bg-maroon text-white rounded-t-2xl">
            <h3 class="text-lg font-bold flex items-center gap-2"><i class="fas fa-plus-circle"></i> Tambah MK ke Semester</h3>
            <button @click="attachOpen = false" class="text-white text-xl">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.mata-kuliah-semester.attach') }}" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <input type="hidden" name="semester_id" value="{{ $selectedSemester?->id }}">
            <div class="p-5 flex-1 overflow-y-auto">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Pilih mata kuliah dari master untuk ditambahkan ke semester <strong>{{ $selectedSemester?->display_label }}</strong>.
                </p>
                <input type="text" placeholder="Cari nama / kode MK..."
                    class="w-full mb-3 px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-maroon"
                    oninput="filterAttachList(this.value)">
                <div id="attachList" class="border dark:border-gray-700 rounded-xl divide-y divide-gray-100 dark:divide-gray-700 max-h-80 overflow-y-auto">
                    @forelse($unattachedMK as $mk)
                    <label class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer attach-item"
                        data-search="{{ strtolower($mk->kode_mk . ' ' . $mk->nama_mk) }}">
                        <input type="checkbox" name="mata_kuliah_ids[]" value="{{ $mk->id }}"
                            class="w-4 h-4 text-maroon border-gray-300 rounded">
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $mk->nama_mk }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $mk->kode_mk }} &bull; {{ $mk->sks }} SKS &bull; {{ $mk->prodi?->nama_prodi }}
                            </div>
                        </div>
                        <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 flex-shrink-0">Semester {{ $mk->semester }}</span>
                    </label>
                    @empty
                    <div class="text-center py-8 text-gray-400 dark:text-gray-500">
                        <i class="fas fa-check-circle text-3xl mb-2 text-green-400"></i>
                        <p>Semua MK sudah aktif di semester ini.</p>
                    </div>
                    @endforelse
                </div>
                <div class="mt-2 flex gap-3 text-xs">
                    <button type="button" onclick="document.querySelectorAll('#attachList input[type=checkbox]').forEach(c=>c.checked=true)" class="text-maroon dark:text-red-400 hover:underline">Pilih Semua</button>
                    <span class="text-gray-300">|</span>
                    <button type="button" onclick="document.querySelectorAll('#attachList input[type=checkbox]').forEach(c=>c.checked=false)" class="text-gray-500 hover:underline">Batal Pilih</button>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t dark:border-gray-700 flex justify-end gap-3 rounded-b-2xl">
                <button type="button" @click="attachOpen = false" class="px-5 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-700 dark:text-gray-300">Batal</button>
                <button type="submit" class="px-5 py-2 bg-maroon text-white rounded-lg text-sm font-semibold hover:bg-red-900">
                    <i class="fas fa-plus mr-1"></i> Tambahkan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: CARRY FORWARD --}}
<div x-show="carryOpen" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
    @keydown.escape.window="carryOpen = false">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 bg-maroon text-white rounded-t-2xl">
            <h3 class="text-lg font-bold flex items-center gap-2"><i class="fas fa-copy"></i> Carry Forward MK</h3>
            <button @click="carryOpen = false" class="text-white text-xl">&times;</button>
        </div>
        <div class="p-5 flex-1 overflow-y-auto">
            <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg text-sm text-maroon dark:text-red-300">
                <i class="fas fa-info-circle mr-1"></i>
                Salin daftar MK dari semester lama ke semester tujuan. Konflik otomatis dilewati.
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Semester Sumber</label>
                    <select id="cf-source" onchange="cfPreview()"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm">
                        @foreach($allSemesters as $sem)
                        <option value="{{ $sem->id }}">{{ $sem->display_label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Semester Tujuan</label>
                    <select id="cf-target" onchange="cfPreview()"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm">
                        @foreach($allSemesters as $sem)
                        <option value="{{ $sem->id }}" {{ $selectedSemester?->id == $sem->id ? 'selected' : '' }}>{{ $sem->display_label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="button" onclick="cfPreview()"
                class="mb-4 px-4 py-2 bg-red-100 dark:bg-red-900/30 text-maroon dark:text-red-300 rounded-lg text-sm font-medium hover:bg-red-200 transition">
                <i class="fas fa-eye mr-1"></i> Preview
            </button>
            <div id="cf-preview" class="hidden space-y-3">
                <p id="cf-summary" class="text-sm text-gray-600 dark:text-gray-400"></p>
                <div id="cf-to-copy-section" class="hidden">
                    <p class="text-xs font-semibold text-green-700 dark:text-green-400 mb-1"><i class="fas fa-check mr-1"></i> Akan disalin:</p>
                    <div id="cf-to-copy" class="border dark:border-gray-700 rounded-lg divide-y divide-gray-100 dark:divide-gray-700 max-h-44 overflow-y-auto text-xs"></div>
                </div>
                <div id="cf-conflict-section" class="hidden">
                    <p class="text-xs font-semibold text-orange-600 dark:text-orange-400 mb-1"><i class="fas fa-exclamation-triangle mr-1"></i> Konflik (dilewati):</p>
                    <div id="cf-conflicts" class="border dark:border-gray-700 rounded-lg divide-y divide-gray-100 dark:divide-gray-700 max-h-28 overflow-y-auto text-xs"></div>
                </div>
            </div>
        </div>
        <form id="cf-form" method="POST" action="{{ route('admin.mata-kuliah-semester.carry-forward') }}">
            @csrf
            <input type="hidden" id="cf-form-source" name="source_semester_id">
            <input type="hidden" id="cf-form-target" name="target_semester_id">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t dark:border-gray-700 flex justify-end gap-3 rounded-b-2xl">
                <button type="button" @click="carryOpen = false" class="px-5 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-700 dark:text-gray-300">Batal</button>
                <button type="submit" id="cf-submit-btn" disabled
                    class="px-5 py-2 bg-maroon text-white rounded-lg text-sm font-semibold hover:bg-red-900 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-copy mr-1"></i> Salin Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: RESTORE FROM HISTORY --}}
<div x-show="restoreOpen" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
    @keydown.escape.window="restoreOpen = false">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 bg-maroon text-white rounded-t-2xl">
            <h3 class="text-lg font-bold flex items-center gap-2"><i class="fas fa-history"></i> Ambil dari Histori</h3>
            <button @click="restoreOpen = false" class="text-white text-xl">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.mata-kuliah-semester.restore') }}" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <input type="hidden" name="target_semester_id" value="{{ $activeSemester?->id }}">
            <div class="p-5 flex-1 overflow-y-auto">
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Pilih Semester Histori Sumber</label>
                    <select name="source_semester_id"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm">
                        <option value="">-- Pilih Semester --</option>
                        @foreach($allSemesters as $sem)
                        <option value="{{ $sem->id }}" {{ $sem->id === $selectedSemester?->id ? 'selected' : '' }}>{{ $sem->display_label }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="text" placeholder="Cari nama / kode MK..."
                    class="w-full mb-3 px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-maroon"
                    oninput="filterRestoreList(this.value)">
                <div id="restore-list" class="border dark:border-gray-700 rounded-xl divide-y divide-gray-100 dark:divide-gray-700 max-h-72 overflow-y-auto">
                    @forelse($historyPivots as $pivot)
                    <label class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer restore-item"
                        data-search="{{ strtolower($pivot->mataKuliah->kode_mk . ' ' . $pivot->mataKuliah->nama_mk) }}">
                        <input type="checkbox" name="mata_kuliah_ids[]" value="{{ $pivot->mata_kuliah_id }}"
                            class="w-4 h-4 text-maroon border-gray-300 rounded">
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $pivot->mataKuliah->nama_mk }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $pivot->mataKuliah->kode_mk }} &bull; {{ $pivot->mataKuliah->sks }} SKS
                            </div>
                        </div>
                        <span class="px-2 py-0.5 text-xs rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">{{ $pivot->status }}</span>
                    </label>
                    @empty
                    <div class="text-center py-8 text-gray-400 dark:text-gray-500">
                        <i class="fas fa-inbox text-3xl mb-2"></i>
                        <p>Pilih semester sumber untuk melihat histori.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t dark:border-gray-700 flex justify-end gap-3 rounded-b-2xl">
                <button type="button" @click="restoreOpen = false" class="px-5 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-700 dark:text-gray-300">Batal</button>
                <button type="submit" class="px-5 py-2 bg-maroon text-white rounded-lg text-sm font-semibold hover:bg-red-900">
                    <i class="fas fa-undo mr-1"></i> Tambahkan ke TA Aktif
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: IMPORT MATA KULIAH --}}
<div id="importModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[92vh] flex flex-col">
        {{-- Header --}}
        <div class="bg-maroon text-white px-6 py-4 flex justify-between items-center rounded-t-2xl flex-shrink-0">
            <h3 class="text-lg font-bold flex items-center gap-2">
                <i class="fas fa-file-import"></i> Import Mata Kuliah
            </h3>
            <button type="button" onclick="closeImportModal()" class="text-white/80 hover:text-white text-2xl leading-none">&times;</button>
        </div>

        {{-- Body --}}
        <div class="flex-1 min-h-0 overflow-y-auto p-6 space-y-5" style="-webkit-overflow-scrolling: touch;">

            {{-- Template download --}}
            <div class="flex items-center gap-3 flex-wrap">
                <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">Download Template:</span>
                <a href="{{ route('admin.import.template', ['type' => 'mata_kuliah', 'format' => 'csv']) }}"
                    data-no-loader
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-900/50 transition">
                    <i class="fas fa-file-csv"></i> CSV
                </a>
                <a href="{{ route('admin.import.template', ['type' => 'mata_kuliah', 'format' => 'xlsx']) }}"
                    data-no-loader
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-900/50 transition">
                    <i class="fas fa-file-excel"></i> XLSX
                </a>
            </div>

            {{-- Kolom yang diperlukan --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900/30 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-2 flex items-center gap-2">
                    <i class="fas fa-columns"></i> Kolom Template
                </h4>
                <div class="flex flex-wrap gap-2 text-xs">
                    @foreach(['kode_mk', 'nama_matkul', 'sks', 'semester'] as $col)
                        <span class="px-2 py-1 rounded-full bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 font-semibold">{{ $col }} *</span>
                    @endforeach
                    @foreach(['kode_id', 'jenis', 'praktikum', 'prodi_id', 'fakultas_id', 'deskripsi'] as $col)
                        <span class="px-2 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">{{ $col }}</span>
                    @endforeach
                </div>
                <p class="text-xs text-blue-700 dark:text-blue-400 mt-2"><span class="text-red-600 font-bold">*</span> wajib &bull; <code class="bg-blue-100 dark:bg-blue-900/40 px-1 rounded">jenis</code> otomatis dari prefix kode_mk (ADH1=nasional, ADH2=prodi, ADH3=pilihan, ADH4=peminatan)</p>
            </div>

            {{-- Drop zone --}}
            <div id="imp-dropzone"
                class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center cursor-pointer transition-all hover:border-maroon dark:hover:border-red-400 hover:bg-maroon/5 dark:hover:bg-red-900/10"
                onclick="document.getElementById('imp-file-input').click()"
                ondragover="event.preventDefault();this.classList.add('border-maroon','bg-maroon/5')"
                ondragleave="this.classList.remove('border-maroon','bg-maroon/5')"
                ondrop="handleImportDrop(event)">
                <input type="file" id="imp-file-input" class="hidden" accept=".csv,.xlsx,.xls" onchange="handleImportFileSelect(this.files[0])">
                <div id="imp-dz-idle">
                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                    <p class="text-gray-600 dark:text-gray-400 font-medium">Drag &amp; drop file di sini atau klik untuk memilih</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Format: CSV, XLSX &bull; Maksimal: 10MB</p>
                </div>
                <div id="imp-dz-selected" class="hidden">
                    <div class="flex items-center justify-center gap-3">
                        <i id="imp-file-icon" class="fas fa-file-csv text-4xl text-green-500"></i>
                        <div class="text-left">
                            <p id="imp-filename" class="text-sm font-semibold text-gray-700 dark:text-gray-300"></p>
                            <p id="imp-filesize" class="text-xs text-gray-500 dark:text-gray-400"></p>
                        </div>
                        <button type="button" onclick="event.stopPropagation();clearImportFile()"
                            class="ml-3 text-red-400 hover:text-red-600 text-xl"><i class="fas fa-times-circle"></i></button>
                    </div>
                </div>
            </div>

            {{-- Options --}}
            <div class="space-y-3">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" id="imp-skip-dup" checked
                        class="rounded border-gray-300 dark:border-gray-600 text-maroon focus:ring-maroon dark:focus:ring-red-500">
                    <span class="text-sm text-gray-700 dark:text-gray-300">
                        Lewati data duplikat <span class="text-gray-400 dark:text-gray-500">(kode_mk yang sudah ada tidak diperbarui)</span>
                    </span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" id="imp-attach-semester"
                        onchange="toggleImportSemesterSelect(this.checked)"
                        class="rounded border-gray-300 dark:border-gray-600 text-maroon focus:ring-maroon dark:focus:ring-red-500">
                    <span class="text-sm text-gray-700 dark:text-gray-300">
                        Tambahkan ke <strong>TA Aktif</strong> setelah import
                    </span>
                </label>

                <div id="imp-semester-select-wrap" class="hidden pl-8">
                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Pilih semester tujuan:</label>
                    <select id="imp-semester-id"
                        class="w-full text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-maroon">
                        @foreach($allSemesters as $sem)
                            <option value="{{ $sem->id }}"
                                {{ ($activeSemester && $sem->id === $activeSemester->id) ? 'selected' : '' }}>
                                {{ $sem->display_label }}{{ $sem->status === 'active' ? ' (Aktif)' : ($sem->status === 'locked' ? ' 🔒' : '') }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Semester yang dikunci tidak dapat diubah.
                    </p>
                </div>
            </div>

            {{-- Progress --}}
            <div id="imp-progress" class="hidden">
                <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                    <span id="imp-progress-label">Memproses...</span>
                    <span id="imp-progress-pct">0%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                    <div id="imp-progress-bar" class="bg-maroon h-2 rounded-full transition-all duration-300" style="width:0%"></div>
                </div>
            </div>

            {{-- Validation errors --}}
            <div id="imp-val-errors" class="hidden bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                <h4 class="text-sm font-bold text-red-800 dark:text-red-300 flex items-center gap-2 mb-2">
                    <i class="fas fa-exclamation-triangle"></i> Error Validasi
                </h4>
                <ul id="imp-val-error-list" class="text-xs text-red-700 dark:text-red-400 list-disc list-inside space-y-1 max-h-36 overflow-y-auto"></ul>
            </div>

            {{-- Preview table --}}
            <div id="imp-preview" class="hidden">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-sm font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-table text-maroon dark:text-red-400"></i> Preview Data
                    </h4>
                    <div id="imp-preview-stats" class="text-xs text-gray-500 dark:text-gray-400 flex gap-3"></div>
                </div>
                <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                    <div class="overflow-x-auto max-h-56">
                        <table class="min-w-full text-xs">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr id="imp-preview-head"></tr>
                            </thead>
                            <tbody id="imp-preview-body" class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800"></tbody>
                        </table>
                    </div>
                    <div id="imp-preview-pagination" class="px-3 py-2 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30 hidden">
                        <div class="flex items-center justify-center gap-2 text-xs" id="imp-preview-pagination-inner"></div>
                    </div>
                    <p id="imp-preview-more" class="hidden text-xs text-gray-400 dark:text-gray-500 text-center p-2 border-t border-gray-100 dark:border-gray-700"></p>
                </div>
            </div>

            {{-- Import result --}}
            <div id="imp-result" class="hidden rounded-xl p-5 border">
                <h4 id="imp-result-title" class="font-bold flex items-center gap-2 text-base mb-3"></h4>
                <div id="imp-result-summary" class="grid grid-cols-3 gap-3 mb-3"></div>
                <div id="imp-result-detail" class="text-xs text-red-700 dark:text-red-400 space-y-1 max-h-32 overflow-y-auto"></div>
            </div>

            {{-- Attach-to-semester result --}}
            <div id="imp-attach-result" class="hidden rounded-xl p-4 border bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800">
                <h4 class="text-sm font-bold text-green-800 dark:text-green-300 flex items-center gap-2 mb-1">
                    <i class="fas fa-check-circle"></i> Ditambahkan ke TA Aktif
                </h4>
                <p id="imp-attach-result-msg" class="text-xs text-green-700 dark:text-green-400"></p>
            </div>

        </div>{{-- /body --}}

        {{-- Footer --}}
        <div class="flex-shrink-0 border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between gap-3">
            <p class="text-xs text-gray-400 dark:text-gray-500">Import ke Master MK &bull; opsional: tambahkan ke TA Aktif</p>
            <div class="flex gap-3">
                <button type="button" id="imp-btn-preview" onclick="runImportPreview()" disabled
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 dark:disabled:bg-gray-700 disabled:cursor-not-allowed text-white text-sm rounded-lg transition flex items-center gap-1.5">
                    <i class="fas fa-eye"></i> Preview
                </button>
                <button type="button" id="imp-btn-import" onclick="runImport()" disabled
                    class="px-4 py-2 bg-maroon hover:bg-red-900 disabled:bg-gray-300 dark:disabled:bg-gray-700 disabled:cursor-not-allowed text-white text-sm rounded-lg transition flex items-center gap-1.5">
                    <i class="fas fa-file-import"></i> Import
                </button>
                <button type="button" onclick="closeImportModal()"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

</div>{{-- end x-data mkPage --}}

@push('scripts')
<script>
function mkPage() {
    return {
        attachOpen: false,
        carryOpen: false,
        restoreOpen: false,
        init() {},
    };
}

function filterAttachList(q) {
    document.querySelectorAll('.attach-item').forEach(el => {
        el.style.display = el.dataset.search.includes(q.toLowerCase()) ? '' : 'none';
    });
}

// ─── Client-side Filter & Sort for TA Aktif / Histori ─────────────────────────
let _activeSortCol = null, _activeSortDir = 'asc';
let _historySortCol = null, _historySortDir = 'asc';

function _filterTable(rows, searchEl, semesterEl, countEl, noClass) {
    const q = (document.getElementById(searchEl)?.value || '').toLowerCase();
    const sem = document.getElementById(semesterEl)?.value || '';
    let visible = 0;
    rows.forEach(row => {
        const matchSearch = !q || row.dataset.kode.includes(q) || row.dataset.nama.includes(q);
        const matchSem = !sem || row.dataset.semester === sem;
        const show = matchSearch && matchSem;
        row.style.display = show ? '' : 'none';
        if (show) { visible++; row.querySelector('.' + noClass).textContent = visible; }
    });
    const el = document.getElementById(countEl);
    if (el) el.textContent = q || sem ? `Menampilkan ${visible} dari ${rows.length} MK` : '';
}

function _sortTable(tbodyId, rowClass, col, prefix) {
    const tbody = document.getElementById(tbodyId);
    if (!tbody) return;
    const rows = Array.from(tbody.querySelectorAll('.' + rowClass));
    // Determine direction
    let sortCol, sortDir;
    if (prefix === 'active') {
        if (_activeSortCol === col) _activeSortDir = _activeSortDir === 'asc' ? 'desc' : 'asc';
        else { _activeSortCol = col; _activeSortDir = 'asc'; }
        sortCol = _activeSortCol; sortDir = _activeSortDir;
    } else {
        if (_historySortCol === col) _historySortDir = _historySortDir === 'asc' ? 'desc' : 'asc';
        else { _historySortCol = col; _historySortDir = 'asc'; }
        sortCol = _historySortCol; sortDir = _historySortDir;
    }
    // Update icons
    ['kode_mk','nama_mk','sks','semester'].forEach(c => {
        const icon = document.getElementById(`${prefix}-sort-icon-${c}`);
        if (icon) icon.className = c === col
            ? `fas fa-sort-${sortDir === 'asc' ? 'up' : 'down'} ml-1`
            : 'fas fa-sort ml-1 opacity-40';
    });
    // Sort
    const dataKey = col === 'kode_mk' ? 'kode' : col === 'nama_mk' ? 'nama' : col;
    const isNum = col === 'sks' || col === 'semester';
    rows.sort((a, b) => {
        let va = a.dataset[dataKey], vb = b.dataset[dataKey];
        if (isNum) { va = parseInt(va) || 0; vb = parseInt(vb) || 0; }
        let cmp = isNum ? va - vb : va.localeCompare(vb);
        return sortDir === 'desc' ? -cmp : cmp;
    });
    rows.forEach(r => tbody.appendChild(r));
    // Re-number visible rows
    let num = 0;
    rows.forEach(r => { if (r.style.display !== 'none') { num++; r.querySelector(`.${prefix}-row-no`).textContent = num; } });
}

function filterMasterTable() { _filterTable(document.querySelectorAll('.master-row'), 'master-search', 'master-semester-filter', 'master-count', 'master-row-no'); }
function filterActiveTable() { _filterTable(document.querySelectorAll('.active-row'), 'active-search', 'active-semester-filter', 'active-count', 'active-row-no'); }
function sortActiveTable(col) { _sortTable('active-tbody', 'active-row', col, 'active'); filterActiveTable(); }
function filterHistoryTable() { _filterTable(document.querySelectorAll('.history-row'), 'history-search', 'history-semester-filter', 'history-count', 'history-row-no'); }
function sortHistoryTable(col) { _sortTable('history-tbody', 'history-row', col, 'history'); filterHistoryTable(); }

function filterRestoreList(q) {
    document.querySelectorAll('.restore-item').forEach(el => {
        el.style.display = el.dataset.search.includes(q.toLowerCase()) ? '' : 'none';
    });
}

async function cfPreview() {
    const source = document.getElementById('cf-source').value;
    const target = document.getElementById('cf-target').value;
    if (!source || !target || source === target) {
        document.getElementById('cf-preview').classList.add('hidden');
        document.getElementById('cf-submit-btn').disabled = true;
        return;
    }
    document.getElementById('cf-form-source').value = source;
    document.getElementById('cf-form-target').value = target;
    try {
        const res = await fetch(
            `/admin/mata-kuliah-semester/carry-forward/preview?source_semester_id=${source}&target_semester_id=${target}`,
            { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } }
        );
        const data = await res.json();
        document.getElementById('cf-preview').classList.remove('hidden');
        document.getElementById('cf-summary').innerHTML =
            `<strong class="text-green-700">${data.to_copy.length} MK</strong> akan disalin dari <em>${data.source_label}</em> ke <em>${data.target_label}</em>. ` +
            `<strong class="text-orange-600">${data.conflicts.length} konflik</strong> akan dilewati.`;
        if (data.to_copy.length > 0) {
            document.getElementById('cf-to-copy-section').classList.remove('hidden');
            document.getElementById('cf-to-copy').innerHTML = data.to_copy.map(mk =>
                `<div class="px-4 py-2 flex justify-between text-gray-700 dark:text-gray-300">
                    <span><span class="font-mono text-maroon dark:text-red-400 font-bold">${mk.kode_mk}</span> &mdash; ${mk.nama_mk}</span>
                    <span class="text-gray-400 text-xs">${mk.sks} SKS</span>
                </div>`
            ).join('');
        } else { document.getElementById('cf-to-copy-section').classList.add('hidden'); }
        if (data.conflicts.length > 0) {
            document.getElementById('cf-conflict-section').classList.remove('hidden');
            document.getElementById('cf-conflicts').innerHTML = data.conflicts.map(mk =>
                `<div class="px-4 py-2 text-orange-600 dark:text-orange-400">
                    <span class="font-mono font-bold">${mk.kode_mk}</span> &mdash; ${mk.nama_mk}
                </div>`
            ).join('');
        } else { document.getElementById('cf-conflict-section').classList.add('hidden'); }
        document.getElementById('cf-submit-btn').disabled = data.to_copy.length === 0;
    } catch(e) { console.error(e); }
}

function prepareBulkDetach(e) {
    const checked = document.querySelectorAll('.active-mk-checkbox:checked');
    if (checked.length === 0) { e.preventDefault(); alert('Pilih minimal satu mata kuliah.'); return; }
    const container = document.getElementById('bulk-detach-hidden');
    container.innerHTML = '';
    checked.forEach(c => {
        const input = document.createElement('input');
        input.type = 'hidden'; input.name = 'mata_kuliah_ids[]'; input.value = c.value;
        container.appendChild(input);
    });
}

document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Hapus mata kuliah ini?',
            text: 'Data akan dihapus permanen dari master.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#7a1621',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
        }).then(r => { if (r.isConfirmed) form.submit(); });
    });
});

// ─── Import Modal ─────────────────────────────────────────────────────────────
let _impFile = null;
let _impKodeMKs = []; // kode_mk values collected during preview

function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
    clearImportFile();
    document.getElementById('imp-preview').classList.add('hidden');
    document.getElementById('imp-val-errors').classList.add('hidden');
    document.getElementById('imp-result').classList.add('hidden');
    document.getElementById('imp-attach-result').classList.add('hidden');
    document.getElementById('imp-progress').classList.add('hidden');
    document.getElementById('imp-attach-semester').checked = false;
    toggleImportSemesterSelect(false);
}

function toggleImportSemesterSelect(show) {
    document.getElementById('imp-semester-select-wrap').classList.toggle('hidden', !show);
}

function handleImportDrop(event) {
    event.preventDefault();
    document.getElementById('imp-dropzone').classList.remove('border-maroon', 'bg-maroon/5');
    const file = event.dataTransfer.files[0];
    if (file) handleImportFileSelect(file);
}

function handleImportFileSelect(file) {
    if (!file) return;
    const allowed = ['.csv', '.xlsx', '.xls'];
    const ext = '.' + file.name.split('.').pop().toLowerCase();
    if (!allowed.includes(ext)) {
        Swal.fire({ icon: 'error', title: 'Format tidak didukung', text: 'Gunakan file CSV atau XLSX.', confirmButtonColor: '#7a1621' });
        return;
    }
    const maxSize = 10 * 1024 * 1024;
    if (file.size > maxSize) {
        Swal.fire({ icon: 'error', title: 'File terlalu besar', text: 'Maksimal ukuran file adalah 10MB.', confirmButtonColor: '#7a1621' });
        return;
    }
    _impFile = file;
    _impKodeMKs = [];
    document.getElementById('imp-dz-idle').classList.add('hidden');
    document.getElementById('imp-dz-selected').classList.remove('hidden');
    document.getElementById('imp-filename').textContent = file.name;
    document.getElementById('imp-filesize').textContent = (file.size / 1024).toFixed(1) + ' KB';
    const icon = document.getElementById('imp-file-icon');
    icon.className = ext === '.csv'
        ? 'fas fa-file-csv text-4xl text-green-500'
        : 'fas fa-file-excel text-4xl text-blue-500';
    document.getElementById('imp-btn-preview').disabled = false;
    document.getElementById('imp-btn-import').disabled = true;
    document.getElementById('imp-preview').classList.add('hidden');
    document.getElementById('imp-val-errors').classList.add('hidden');
    document.getElementById('imp-result').classList.add('hidden');
    document.getElementById('imp-attach-result').classList.add('hidden');
}

function clearImportFile() {
    _impFile = null;
    _impKodeMKs = [];
    document.getElementById('imp-file-input').value = '';
    document.getElementById('imp-dz-idle').classList.remove('hidden');
    document.getElementById('imp-dz-selected').classList.add('hidden');
    document.getElementById('imp-btn-preview').disabled = true;
    document.getElementById('imp-btn-import').disabled = true;
    document.getElementById('imp-preview').classList.add('hidden');
    document.getElementById('imp-val-errors').classList.add('hidden');
    document.getElementById('imp-result').classList.add('hidden');
    document.getElementById('imp-attach-result').classList.add('hidden');
    document.getElementById('imp-progress').classList.add('hidden');
}

function _impProgress(label, pct) {
    const c = document.getElementById('imp-progress');
    c.classList.remove('hidden');
    document.getElementById('imp-progress-label').textContent = label;
    document.getElementById('imp-progress-pct').textContent = pct + '%';
    document.getElementById('imp-progress-bar').style.width = pct + '%';
}

async function runImportPreview() {
    if (!_impFile) return;
    document.getElementById('imp-val-errors').classList.add('hidden');
    document.getElementById('imp-preview').classList.add('hidden');
    document.getElementById('imp-result').classList.add('hidden');
    document.getElementById('imp-attach-result').classList.add('hidden');
    document.getElementById('imp-btn-preview').disabled = true;
    _impProgress('Membaca file…', 30);

    const fd = new FormData();
    fd.append('file', _impFile);
    fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    try {
        _impProgress('Memvalidasi…', 60);
        const res = await fetch('{{ route('admin.import.preview', ['type' => 'mata_kuliah']) }}', {
            method: 'POST', body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const json = await res.json();
        _impProgress('Selesai', 100);
        setTimeout(() => document.getElementById('imp-progress').classList.add('hidden'), 600);

        if (!json.success) {
            _showImpErrors([{ row: 0, errors: [json.message] }]);
            return;
        }

        const d = json.data;

        // Collect kode_mk values from preview for later attach-to-semester
        _impKodeMKs = Array.from(new Set(d.preview
            .map(row => row['kode_mk'] ?? row['kode_mk_'] ?? null)
            .filter(Boolean)));

        // Client-side paginated preview (10 rows per page)
        window._impPreviewData = d.preview || [];
        const _impPerPage = 10;
        let _impCurrentPage = 1;

        const head = document.getElementById('imp-preview-head');
        const body = document.getElementById('imp-preview-body');
        const paginationWrap = document.getElementById('imp-preview-pagination');
        const paginationInner = document.getElementById('imp-preview-pagination-inner');

        window.renderPreviewPage = function(page = 1) {
            _impCurrentPage = page;
            const total = window._impPreviewData.length;
            const totalPages = Math.max(1, Math.ceil(total / _impPerPage));
            const start = (page - 1) * _impPerPage;
            const slice = window._impPreviewData.slice(start, start + _impPerPage);

            // Header
            head.innerHTML = d.columns.map(c => `<th class="px-3 py-2 text-left text-gray-600 dark:text-gray-400 font-semibold whitespace-nowrap">${c}</th>`).join('');

            // Body rows
            body.innerHTML = slice.map(row => {
                const cells = d.columns.map(c => `<td class="px-3 py-1.5 text-gray-700 dark:text-gray-300 whitespace-nowrap">${row[c] ?? ''}</td>`).join('');
                return `<tr class="even:bg-gray-50 dark:even:bg-gray-700/30">${cells}</tr>`;
            }).join('');

            // Pagination UI
            if (totalPages > 1) {
                paginationWrap.classList.remove('hidden');
                const pages = [];
                // Prev
                pages.push(`<button class="px-2 py-1 rounded ${page===1? 'opacity-50 cursor-not-allowed':'bg-white dark:bg-gray-700'}" onclick="renderPreviewPage(${Math.max(1,page-1)})">Prev</button>`);
                // Page numbers (limit displayed pages)
                const startPage = Math.max(1, page - 3);
                const endPage = Math.min(totalPages, page + 3);
                for (let p = startPage; p <= endPage; p++) {
                    pages.push(`<button class="px-2 py-1 rounded ${p===page? 'bg-maroon text-white':'bg-white dark:bg-gray-700'}" onclick="renderPreviewPage(${p})">${p}</button>`);
                }
                // Next
                pages.push(`<button class="px-2 py-1 rounded ${page===totalPages? 'opacity-50 cursor-not-allowed':'bg-white dark:bg-gray-700'}" onclick="renderPreviewPage(${Math.min(totalPages,page+1)})">Next</button>`);

                paginationInner.innerHTML = pages.join('');
            } else {
                paginationWrap.classList.add('hidden');
                paginationInner.innerHTML = '';
            }

            // Preview-more text (showing items in preview slice and total rows/parity with server)
            const moreEl = document.getElementById('imp-preview-more');
            if (d.total_rows > window._impPreviewData.length) {
                moreEl.textContent = `Menampilkan ${Math.min(window._impPreviewData.length, _impPerPage)} dari ${d.total_rows} baris (preview). Semua baris akan diimport.`;
                moreEl.classList.remove('hidden');
            } else {
                moreEl.classList.add('hidden');
            }
        }

        // Initial render
        window.renderPreviewPage(1);

        // Stats
        const v = d.validation;
        document.getElementById('imp-preview-stats').innerHTML =
            `<span class="text-green-600 dark:text-green-400 font-semibold">${v.valid_rows} valid</span>`
            + (v.error_rows ? `<span class="text-red-600 dark:text-red-400 font-semibold">${v.error_rows} error</span>` : '')
            + (v.duplicate_rows ? `<span class="text-yellow-600 dark:text-yellow-400 font-semibold">${v.duplicate_rows} duplikat</span>` : '');

        if (d.total_rows > 100) {
            const moreEl = document.getElementById('imp-preview-more');
            moreEl.textContent = `Menampilkan 100 dari ${d.total_rows} baris. Semua ${d.total_rows} baris akan diimport dan ditambahkan ke TA Aktif.`;
            moreEl.classList.remove('hidden');
        } else {
            document.getElementById('imp-preview-more').classList.add('hidden');
        }
        document.getElementById('imp-preview').classList.remove('hidden');

        if (v.errors && v.errors.length > 0) {
            _showImpErrors(v.errors);
        }

        document.getElementById('imp-btn-preview').disabled = false;
        document.getElementById('imp-btn-import').disabled = v.valid_rows === 0;
    } catch(e) {
        document.getElementById('imp-progress').classList.add('hidden');
        document.getElementById('imp-btn-preview').disabled = false;
        Swal.fire({ icon: 'error', title: 'Gagal', text: e.message, confirmButtonColor: '#7a1621' });
    }
}

async function runImport() {
    if (!_impFile) return;
    document.getElementById('imp-result').classList.add('hidden');
    document.getElementById('imp-attach-result').classList.add('hidden');
    document.getElementById('imp-btn-import').disabled = true;
    document.getElementById('imp-btn-preview').disabled = true;
    _impProgress('Mengimport data ke Master MK…', 40);

    const fd = new FormData();
    fd.append('file', _impFile);
    fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    fd.append('skip_duplicates', document.getElementById('imp-skip-dup').checked ? '1' : '0');

    try {
        _impProgress('Menyimpan…', 75);
        const res = await fetch('{{ route('admin.import.import', ['type' => 'mata_kuliah']) }}', {
            method: 'POST', body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const json = await res.json();
        _impProgress('Import selesai', 90);

        const rc = document.getElementById('imp-result');
        const success = json.success;
        const s = json.result?.summary ?? {};

        rc.className = success
            ? 'rounded-xl p-5 border bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800'
            : 'rounded-xl p-5 border bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800';

        document.getElementById('imp-result-title').innerHTML = success
            ? `<i class="fas fa-check-circle text-green-600 dark:text-green-400"></i> <span class="text-green-800 dark:text-green-300">Import Master MK Selesai</span>`
            : `<i class="fas fa-times-circle text-red-600 dark:text-red-400"></i> <span class="text-red-800 dark:text-red-300">Import Gagal</span>`;

        document.getElementById('imp-result-summary').innerHTML = [
            { label: 'Total', val: s.total ?? 0, cls: 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' },
            { label: 'Berhasil', val: s.success ?? 0, cls: 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' },
            { label: 'Dilewati', val: s.skipped ?? 0, cls: 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' },
            { label: 'Gagal', val: s.failed ?? 0, cls: 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' },
        ].map(x =>
            `<div class="rounded-lg p-3 text-center ${x.cls}"><div class="text-xl font-bold">${x.val}</div><div class="text-xs">${x.label}</div></div>`
        ).join('');

        const failed = json.result?.results?.failed ?? [];
        if (failed.length > 0) {
            document.getElementById('imp-result-detail').innerHTML =
                failed.map(f => `<div>Baris ${f.row}: ${f.error}</div>`).join('');
        } else {
            document.getElementById('imp-result-detail').innerHTML = '';
        }
        rc.classList.remove('hidden');
        document.getElementById('imp-btn-preview').disabled = false;

        // If "Tambahkan ke TA Aktif" is checked and import succeeded
        const attachChecked = document.getElementById('imp-attach-semester').checked;
        const semesterId = document.getElementById('imp-semester-id')?.value;

        if (success && attachChecked && semesterId && _impKodeMKs.length > 0) {
            _impProgress('Menambahkan ke TA Aktif…', 95);
            await _attachImportedToSemester(semesterId, _impKodeMKs);
        }

        _impProgress('Selesai', 100);
        setTimeout(() => document.getElementById('imp-progress').classList.add('hidden'), 500);

        if (success && (s.success ?? 0) > 0) {
            setTimeout(() => window.location.reload(), 2500);
        }
    } catch(e) {
        document.getElementById('imp-progress').classList.add('hidden');
        document.getElementById('imp-btn-preview').disabled = false;
        document.getElementById('imp-btn-import').disabled = false;
        Swal.fire({ icon: 'error', title: 'Gagal', text: e.message, confirmButtonColor: '#7a1621' });
    }
}

async function _attachImportedToSemester(semesterId, kodeMKs) {
    const fd = new FormData();
    fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    fd.append('semester_id', semesterId);
    kodeMKs.forEach(kode => fd.append('kode_mks[]', kode));

    try {
        const res = await fetch('{{ route('admin.mata-kuliah-semester.attach-by-codes') }}', {
            method: 'POST', body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const json = await res.json();
        const el = document.getElementById('imp-attach-result');
        const msg = document.getElementById('imp-attach-result-msg');

        if (json.success) {
            el.className = 'rounded-xl p-4 border bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800';
            el.querySelector('h4').innerHTML = '<i class="fas fa-check-circle"></i> Ditambahkan ke TA Aktif';
            msg.textContent = json.message;
        } else {
            el.className = 'rounded-xl p-4 border bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800';
            el.querySelector('h4').innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-600"></i> <span class="text-yellow-800 dark:text-yellow-300">Perhatian — TA Aktif</span>';
            msg.textContent = json.message;
        }
        el.classList.remove('hidden');
    } catch(e) {
        console.error('Attach to semester failed:', e);
    }
}

function _showImpErrors(errors) {
    const list = document.getElementById('imp-val-error-list');
    list.innerHTML = errors.map(e =>
        (e.errors ?? [e]).map(msg => `<li>Baris ${e.row}: ${msg}</li>`).join('')
    ).join('');
    document.getElementById('imp-val-errors').classList.remove('hidden');
}
</script>
@endpush

@endsection
