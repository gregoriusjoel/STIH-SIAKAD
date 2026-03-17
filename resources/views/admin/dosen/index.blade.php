@extends('layouts.admin')

@section('title', 'Manajemen Dosen')
@section('page-title', 'Manajemen Dosen')

@section('content')
<div x-data="quickAssign()" @keydown.escape.window="closeDrawer()" class="relative">

{{-- ═══════════════ HEADER ═══════════════ --}}
<div class="mb-5 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
            <i class="fas fa-chalkboard-teacher text-maroon dark:text-red-500"></i>
            Manajemen Dosen
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Relasi dosen pengajar per Tahun Ajaran &middot; Histori &amp; Carry-Forward</p>
        <div class="mt-2 flex flex-wrap items-center gap-2">
            @if($activeSemester)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    TA Aktif: {{ $activeSemester->display_label }}
                </span>
            @else
                <span class="px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Tidak ada semester aktif
                </span>
            @endif
        </div>
    </div>
    <div class="flex flex-wrap gap-2 flex-shrink-0 items-start">
        <button type="button" onclick="document.getElementById('modal-import-dosen').classList.remove('hidden')"
            class="bg-white dark:bg-gray-800 text-maroon dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10 border-2 border-maroon/10 hover:border-maroon px-4 py-2 rounded-xl transition-all flex items-center gap-2 shadow-sm text-sm font-bold">
            <i class="fas fa-file-import text-xs"></i> Import Data Dosen
        </button>
        <a href="{{ route('admin.dosen.create') }}"
            class="bg-maroon text-white hover:bg-red-900 px-4 py-2 rounded-xl transition flex items-center gap-2 shadow-md text-sm font-medium">
            <i class="fas fa-plus"></i> Tambah Dosen
        </a>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-transition class="mb-4 flex items-center gap-3 bg-green-50 dark:bg-green-900/20 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded-xl">
    <i class="fas fa-check-circle text-green-500 flex-shrink-0"></i>
    <span class="text-sm">{{ session('success') }}</span>
    <button @click="show = false" class="ml-auto text-green-600 hover:text-green-800"><i class="fas fa-times"></i></button>
</div>
@endif
@if(session('warning'))
<div x-data="{ show: true }" x-show="show" x-transition class="mb-4 flex items-center gap-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-300 text-yellow-800 dark:text-yellow-300 px-4 py-3 rounded-xl">
    <i class="fas fa-exclamation-triangle text-yellow-500 flex-shrink-0"></i>
    <span class="text-sm">{{ session('warning') }}</span>
    <button @click="show = false" class="ml-auto"><i class="fas fa-times"></i></button>
</div>
@endif
@if(session('error'))
<div x-data="{ show: true }" x-show="show" x-transition class="mb-4 flex items-center gap-3 bg-red-50 dark:bg-red-900/20 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-300 px-4 py-3 rounded-xl">
    <i class="fas fa-exclamation-circle text-red-500 flex-shrink-0"></i>
    <span class="text-sm">{{ session('error') }}</span>
    <button @click="show = false" class="ml-auto"><i class="fas fa-times"></i></button>
</div>
@endif

{{-- ═══════════════ CONTROL BAR ═══════════════ --}}
<div class="bg-white dark:bg-gray-800 rounded-t-xl shadow border border-gray-200 dark:border-gray-700 px-4 py-3 flex flex-wrap gap-3 items-center">

    {{-- Semester selector --}}
    <form method="GET" action="{{ route('admin.dosen.index') }}" class="flex gap-2 items-center">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <input type="hidden" name="search" value="{{ $search }}">
        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 whitespace-nowrap"><i class="fas fa-calendar-alt mr-1"></i>Semester:</label>
        <select name="semester_id" onchange="this.form.submit()"
            class="text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-maroon">
            @foreach($allSemesters as $sem)
            <option value="{{ $sem->id }}" {{ $selectedSemester?->id == $sem->id ? 'selected' : '' }}>
                {{ $sem->display_label }}{{ $sem->is_active ? ' ✓' : '' }}
            </option>
            @endforeach
        </select>
    </form>

    <div class="h-6 w-px bg-gray-200 dark:bg-gray-600"></div>

    {{-- Tambah Dosen ke TA --}}
    @if($selectedSemester)
    <button type="button" onclick="document.getElementById('modal-tambah-dosen-ta').classList.remove('hidden')"
        class="flex items-center gap-2 px-3 py-1.5 bg-maroon text-white rounded-lg hover:bg-red-900 text-xs font-semibold transition">
        <i class="fas fa-user-plus"></i> Tambah Dosen ke TA
    </button>
    @endif

    {{-- Carry Forward --}}
    @if($previousSemester && $selectedSemester)
    <button type="button" onclick="document.getElementById('modal-carry-forward').classList.remove('hidden')"
        class="flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-gray-700 text-maroon dark:text-red-400 border border-maroon/30 rounded-lg hover:bg-maroon hover:text-white text-xs font-semibold transition">
        <i class="fas fa-copy"></i> Carry Forward
    </button>
    @endif

    <div class="ml-auto flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
        <span>Dosen aktif TA: <strong class="text-maroon dark:text-red-400">{{ $dosenAktifCount }}</strong></span>
    </div>
</div>

{{-- ═══════════════ TABS ═══════════════ --}}
<div class="flex border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 border-t-0 border-l border-r border-gray-200 dark:border-gray-700">
    <a href="{{ route('admin.dosen.index', ['tab' => 'master', 'semester_id' => $selectedSemester?->id]) }}"
        class="px-5 py-3 text-sm font-semibold border-b-2 transition {{ $tab === 'master' ? 'border-maroon text-maroon dark:text-red-400 dark:border-red-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-maroon' }}">
        <i class="fas fa-list mr-1.5"></i> Master Dosen
    </a>
    <a href="{{ route('admin.dosen.index', ['tab' => 'dosen-aktif', 'semester_id' => $selectedSemester?->id]) }}"
        class="px-5 py-3 text-sm font-semibold border-b-2 transition {{ $tab === 'dosen-aktif' ? 'border-maroon text-maroon dark:text-red-400 dark:border-red-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-maroon' }}">
        <i class="fas fa-users mr-1.5"></i> Dosen Aktif
        <span class="ml-1 px-1.5 py-0.5 rounded-full text-xs {{ $tab === 'dosen-aktif' ? 'bg-maroon text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">{{ $dosenAktifCount }}</span>
    </a>
    <a href="{{ route('admin.dosen.index', ['tab' => 'histori', 'semester_id' => $selectedSemester?->id]) }}"
        class="px-5 py-3 text-sm font-semibold border-b-2 transition {{ $tab === 'histori' ? 'border-maroon text-maroon dark:text-red-400 dark:border-red-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-maroon' }}">
        <i class="fas fa-archive mr-1.5"></i> Histori Dosen
    </a>
</div>

{{-- ═══════════════ TAB CONTENT WRAPPER ═══════════════ --}}
<div class="bg-white dark:bg-gray-800 rounded-b-xl shadow border border-t-0 border-gray-200 dark:border-gray-700 overflow-hidden">

@if($tab === 'master')
{{-- ─────────────────────────────────────────────── --}}
{{-- TAB: MASTER DOSEN                              --}}
{{-- ─────────────────────────────────────────────── --}}

{{-- Filter bar --}}
<div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
    <div class="flex flex-wrap gap-3 items-center">
        <div class="relative flex-1 min-w-[200px] max-w-sm">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" id="searchInput" placeholder="Cari nama / NIDN dosen..."
                class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon">
        </div>
    </div>
</div>

@if($dosens->isEmpty())
<div class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
    <i class="fas fa-user-slash text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
    <p class="text-lg font-semibold">Data dosen tidak ditemukan</p>
</div>
@else
<div class="overflow-x-auto">
<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="masterDosenTable">
    <thead class="bg-maroon text-white">
        <tr>
            <th class="px-5 py-4 text-left text-xs font-bold uppercase whitespace-nowrap">No</th>
            <th class="px-5 py-4 text-left text-xs font-bold uppercase whitespace-nowrap">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'nidn', 'sort_direction' => ($sortBy === 'nidn' && $sortDir === 'asc') ? 'desc' : 'asc']) }}" class="hover:text-red-200 transition-colors flex items-center gap-1">
                    <i class="fas fa-id-card mr-1"></i>NIDN
                    <i class="fas {{ $sortBy === 'nidn' ? ($sortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort' }} opacity-50"></i>
                </a>
            </th>
            <th class="px-5 py-4 text-left text-xs font-bold uppercase whitespace-nowrap">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_direction' => ($sortBy === 'name' && $sortDir === 'asc') ? 'desc' : 'asc']) }}" class="hover:text-red-200 transition-colors flex items-center gap-1">
                    <i class="fas fa-user mr-1"></i>Nama Dosen
                    <i class="fas {{ $sortBy === 'name' ? ($sortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort' }} opacity-50"></i>
                </a>
            </th>
            <th class="px-5 py-4 text-left text-xs font-bold uppercase whitespace-nowrap">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'pendidikan', 'sort_direction' => ($sortBy === 'pendidikan' && $sortDir === 'asc') ? 'desc' : 'asc']) }}" class="hover:text-red-200 transition-colors flex items-center gap-1">
                    <i class="fas fa-graduation-cap mr-1"></i>Pendidikan
                    <i class="fas {{ $sortBy === 'pendidikan' ? ($sortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort' }} opacity-50"></i>
                </a>
            </th>
            <th class="px-5 py-4 text-center text-xs font-bold uppercase whitespace-nowrap">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'mk_aktif', 'sort_direction' => ($sortBy === 'mk_aktif' && $sortDir === 'asc') ? 'desc' : 'asc']) }}" class="hover:text-red-200 transition-colors flex items-center justify-center gap-1">
                    MK Aktif
                    <i class="fas {{ $sortBy === 'mk_aktif' ? ($sortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort' }} opacity-50"></i>
                </a>
            </th>
            <th class="px-5 py-4 text-center text-xs font-bold uppercase whitespace-nowrap">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_direction' => ($sortBy === 'status' && $sortDir === 'asc') ? 'desc' : 'asc']) }}" class="hover:text-red-200 transition-colors flex items-center justify-center gap-1">
                    Status
                    <i class="fas {{ $sortBy === 'status' ? ($sortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort' }} opacity-50"></i>
                </a>
            </th>
            <th class="px-5 py-4 text-center text-xs font-bold uppercase whitespace-nowrap">Aksi</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
        @foreach($dosens as $i => $dosen)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition searchable-row">
            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400 font-medium whitespace-nowrap">
                {{ ($dosens->currentPage() - 1) * $dosens->perPage() + $loop->iteration }}
            </td>
            <td class="px-5 py-4 whitespace-nowrap search-nidn">
                <span class="text-sm font-mono text-maroon dark:text-red-400 font-bold">{{ $dosen->nidn ?: '-' }}</span>
            </td>
            <td class="px-5 py-4 search-nama">
                <div class="flex items-center gap-3 min-w-[200px]">
                    <div class="h-9 w-9 rounded-full bg-maroon flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($dosen->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <a href="{{ route('admin.dosen.show', $dosen) }}"
                            class="text-sm font-semibold text-gray-900 dark:text-gray-100 hover:text-maroon dark:hover:text-red-400 transition-colors">
                            {{ $dosen->user->name }}
                        </a>
                        <div class="text-xs text-gray-400 dark:text-gray-500 truncate max-w-[150px]">{{ $dosen->user->email }}</div>
                    </div>
                </div>
            </td>
            <td class="px-5 py-4 whitespace-nowrap">
                @if($dosen->pendidikan)
                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                    <i class="fas fa-graduation-cap mr-1"></i>{{ $dosen->pendidikan }}
                </span>
                @else
                <span class="text-xs text-gray-400">—</span>
                @endif
            </td>
            <td class="px-5 py-4 text-center whitespace-nowrap">
                @php
                    $mkAktifCount = $activeSemester
                        ? $dosen->kelasMataKuliahs->filter(fn($km) => $km->semester_id == $activeSemester->id)->pluck('mata_kuliah_id')->unique()->count()
                        : 0;
                @endphp
                @if($mkAktifCount > 0)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300">
                        <i class="fas fa-book-open text-[10px]"></i> {{ $mkAktifCount }}
                    </span>
                @else
                    <span class="text-xs text-gray-400 dark:text-gray-500">—</span>
                @endif
            </td>
            <td class="px-5 py-4 text-center whitespace-nowrap">
                <form action="{{ route('admin.dosen.toggle-status', $dosen) }}" method="POST" id="toggle-status-master-{{ $dosen->id }}">
                    @csrf
                    <button type="button" onclick="confirmToggleStatus('{{ $dosen->id }}', '{{ $dosen->status }}', '{{ addslashes($dosen->user->name) }}')"
                        class="px-3 py-1 text-xs font-semibold rounded-full cursor-pointer hover:shadow-md transition-all
                            {{ $dosen->status == 'aktif' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 hover:bg-green-200' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 hover:bg-red-200' }}">
                        <i class="fas {{ $dosen->status == 'aktif' ? 'fa-check-circle' : 'fa-times-circle' }} text-xs mr-1"></i>
                        {{ ucfirst($dosen->status) }}
                    </button>
                </form>
            </td>
            <td class="px-5 py-4 text-center">
                <div class="flex items-center justify-center gap-1">
                    <button type="button" @click="openDrawer({{ $dosen->id }})"
                        class="text-violet-600 hover:text-violet-900 transition p-2 hover:bg-violet-50 dark:hover:bg-violet-900/20 rounded" title="Quick Assign MK">
                        <i class="fas fa-bolt"></i>
                    </button>
                    <a href="{{ route('admin.dosen.show', $dosen) }}"
                        class="text-blue-600 hover:text-blue-900 transition p-2 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded" title="Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.dosen.edit', $dosen) }}"
                        class="text-amber-600 hover:text-amber-900 transition p-2 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.dosen.destroy', $dosen) }}" method="POST" class="inline delete-form">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 transition p-2 hover:bg-red-50 dark:hover:bg-red-900/20 rounded" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

<div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
    {{ $dosens->links() }}
</div>
@endif

@elseif($tab === 'dosen-aktif')
{{-- ─────────────────────────────────────────────── --}}
{{-- TAB: DOSEN AKTIF TA                            --}}
{{-- ─────────────────────────────────────────────── --}}

{{-- Filter bar --}}
<div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
    <div class="flex flex-wrap gap-3 items-center">
        <div class="relative flex-1 min-w-[200px] max-w-sm">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" id="searchInput2" placeholder="Cari nama / NIDN dosen..."
                class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon">
        </div>
    </div>
</div>

@if($dosenAktif->isEmpty())
<div class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
    <i class="fas fa-user-slash text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
    <p class="text-lg font-semibold">Belum ada dosen di semester ini</p>
    <p class="text-sm mt-1">Gunakan "Tambah Dosen ke TA" atau "Carry Forward" untuk memulai.</p>
</div>
@else
<div class="overflow-x-auto">
<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
    <thead class="bg-maroon text-white">
        <tr>
            <th class="px-5 py-4 text-left text-xs font-bold uppercase">No</th>
            <th class="px-5 py-4 text-left text-xs font-bold uppercase">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'nidn', 'sort_direction' => ($sortBy === 'nidn' && $sortDir === 'asc') ? 'desc' : 'asc']) }}" class="hover:text-red-200 transition-colors flex items-center gap-1">
                    <i class="fas fa-id-card mr-1"></i>NIDN
                    <i class="fas {{ $sortBy === 'nidn' ? ($sortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort' }} opacity-50"></i>
                </a>
            </th>
            <th class="px-5 py-4 text-left text-xs font-bold uppercase">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_direction' => ($sortBy === 'name' && $sortDir === 'asc') ? 'desc' : 'asc']) }}" class="hover:text-red-200 transition-colors flex items-center gap-1">
                    <i class="fas fa-user mr-1"></i>Nama Dosen
                    <i class="fas {{ $sortBy === 'name' ? ($sortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort' }} opacity-50"></i>
                </a>
            </th>
            <th class="px-5 py-4 text-left text-xs font-bold uppercase">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'pendidikan', 'sort_direction' => ($sortBy === 'pendidikan' && $sortDir === 'asc') ? 'desc' : 'asc']) }}" class="hover:text-red-200 transition-colors flex items-center gap-1">
                    <i class="fas fa-graduation-cap mr-1"></i>Pendidikan
                    <i class="fas {{ $sortBy === 'pendidikan' ? ($sortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort' }} opacity-50"></i>
                </a>
            </th>
            <th class="px-5 py-4 text-left text-xs font-bold uppercase">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'mk_aktif', 'sort_direction' => ($sortBy === 'mk_aktif' && $sortDir === 'asc') ? 'desc' : 'asc']) }}" class="hover:text-red-200 transition-colors flex items-center gap-1">
                    <i class="fas fa-book-open mr-1"></i>Mata Kuliah Diampu
                    <i class="fas {{ $sortBy === 'mk_aktif' ? ($sortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort' }} opacity-50"></i>
                </a>
            </th>
            <th class="px-5 py-4 text-center text-xs font-bold uppercase whitespace-nowrap"><i class="fas fa-chart-pie mr-1"></i>Total SKS</th>
            <th class="px-5 py-4 text-center text-xs font-bold uppercase">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_direction' => ($sortBy === 'status' && $sortDir === 'asc') ? 'desc' : 'asc']) }}" class="hover:text-red-200 transition-colors flex items-center justify-center gap-1">
                    Status
                    <i class="fas {{ $sortBy === 'status' ? ($sortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort' }} opacity-50"></i>
                </a>
            </th>
            <th class="px-5 py-4 text-center text-xs font-bold uppercase">Aksi</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
        @foreach($dosenAktif as $i => $dosen)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition searchable-row-2">
            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400 font-medium">{{ $i + 1 }}</td>
            <td class="px-5 py-4 search-nidn-2">
                <span class="text-sm font-mono text-maroon dark:text-red-400 font-bold">{{ $dosen->nidn }}</span>
            </td>
            <td class="px-5 py-4 search-nama-2">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-maroon flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($dosen->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <a href="{{ route('admin.dosen.show', $dosen) }}"
                            class="text-sm font-semibold text-gray-900 dark:text-gray-100 hover:text-maroon dark:hover:text-red-400 transition-colors">
                            {{ $dosen->user->name }}
                        </a>
                        <div class="text-xs text-gray-400 dark:text-gray-500">{{ $dosen->user->email }}</div>
                    </div>
                </div>
            </td>
            <td class="px-5 py-4">
                @if($dosen->pendidikan)
                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                    <i class="fas fa-graduation-cap mr-1"></i>{{ $dosen->pendidikan }}
                </span>
                @else
                <span class="text-xs text-gray-400">—</span>
                @endif
            </td>
            <td class="px-5 py-4">
                @if($dosen->mataKuliahs->isEmpty())
                    <span class="text-xs text-gray-400 dark:text-gray-500">—</span>
                @else
                    <div class="flex flex-col gap-1">
                        @foreach($dosen->mataKuliahs->take(3) as $mk)
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-[10px] text-gray-400 dark:text-gray-500 font-bold w-16 flex-shrink-0">{{ $mk->kode_mk }}</span>
                            <span class="text-xs text-gray-700 dark:text-gray-300 truncate max-w-[200px]">{{ $mk->nama_mk }}</span>
                        </div>
                        @endforeach
                        @if($dosen->mataKuliahs->count() > 3)
                        <span class="text-[10px] text-gray-400 dark:text-gray-500 font-semibold">+{{ $dosen->mataKuliahs->count() - 3 }} lainnya</span>
                        @endif
                    </div>
                @endif
            </td>
            <td class="px-5 py-4 text-center whitespace-nowrap">
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300">
                    <i class="fas fa-graduation-cap text-[10px]"></i> {{ $dosen->mataKuliahs->sum('sks') }}
                </span>
            </td>
            <td class="px-5 py-4 text-center">
                <form action="{{ route('admin.dosen.toggle-status', $dosen) }}" method="POST" id="toggle-status-{{ $dosen->id }}">
                    @csrf
                    <button type="button" onclick="confirmToggleStatus('{{ $dosen->id }}', '{{ $dosen->status }}', '{{ addslashes($dosen->user->name) }}')"
                        class="px-3 py-1 text-xs font-semibold rounded-full cursor-pointer hover:shadow-md transition-all
                            {{ $dosen->status == 'aktif' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 hover:bg-green-200' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 hover:bg-red-200' }}">
                        <i class="fas {{ $dosen->status == 'aktif' ? 'fa-check-circle' : 'fa-times-circle' }} text-xs mr-1"></i>
                        {{ ucfirst($dosen->status) }}
                    </button>
                </form>
            </td>
            <td class="px-5 py-4 text-center">
                <div class="flex items-center justify-center gap-1">
                    <button type="button" @click="openDrawer({{ $dosen->id }})"
                        class="text-violet-600 hover:text-violet-900 transition p-2 hover:bg-violet-50 dark:hover:bg-violet-900/20 rounded" title="Quick Assign MK">
                        <i class="fas fa-bolt"></i>
                    </button>
                    <a href="{{ route('admin.dosen.show', $dosen) }}"
                        class="text-blue-600 hover:text-blue-900 transition p-2 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded" title="Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.dosen.edit', $dosen) }}"
                        class="text-amber-600 hover:text-amber-900 transition p-2 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.dosen.destroy', $dosen) }}" method="POST" class="inline delete-form">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 transition p-2 hover:bg-red-50 dark:hover:bg-red-900/20 rounded" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endif

@elseif($tab === 'histori')
{{-- ─────────────────────────────────────────────── --}}
{{-- TAB: HISTORI DOSEN                             --}}
{{-- ─────────────────────────────────────────────── --}}

{{-- Filter bar --}}
<div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
    <div class="flex flex-wrap gap-3 items-center">
        <div class="relative flex-1 min-w-[200px] max-w-sm">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" id="searchInput3" placeholder="Cari nama / NIDN dosen..."
                class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon">
        </div>
        <span class="ml-auto text-xs text-gray-400 dark:text-gray-500">{{ $historiDosen->count() }} dosen ditemukan</span>
    </div>
</div>

@if($historiDosen->isEmpty())
<div class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
    <i class="fas fa-archive text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
    <p class="text-lg font-semibold">Belum ada histori pengajaran</p>
    <p class="text-sm mt-1">Data histori akan muncul setelah dosen melakukan penugasan di semester manapun.</p>
</div>
@else
<div class="p-6 space-y-5">
    @foreach($historiDosen as $dosenData)
    {{-- Card Dosen --}}
    <div x-data="{ open: true }" class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm searchable-row-3">
        {{-- Dosen Header --}}
        <button type="button" @click="open = !open"
            class="w-full flex items-center justify-between px-5 py-4 bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition text-left">
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-full bg-maroon flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr($dosenData['name'], 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100 search-nama-3">{{ $dosenData['name'] }}</span>
                        <span class="font-mono text-xs text-gray-400 dark:text-gray-500 search-nidn-3">{{ $dosenData['nidn'] }}</span>
                        @if($dosenData['pendidikan'])
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                            {{ $dosenData['pendidikan'] }}
                        </span>
                        @endif
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $dosenData['status'] === 'aktif' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300' }}">
                            {{ ucfirst($dosenData['status']) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            <i class="fas fa-calendar-alt mr-1"></i>{{ $dosenData['total_ta'] }} Semester
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            <i class="fas fa-book-open mr-1"></i>{{ $dosenData['total_mk'] }} Mata Kuliah
                        </span>
                    </div>
                </div>
            </div>
            <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
        </button>

        {{-- Semester Groups --}}
        <div x-show="open" x-transition>
            <div class="divide-y divide-gray-100 dark:divide-gray-700/50">
                @foreach($dosenData['semesters'] as $semData)
                <div class="px-5 py-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar text-maroon dark:text-red-400 text-xs"></i>
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                {{ $semData['label'] }}
                            </span>
                        </div>
                        @if($semData['is_active'])
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Aktif
                        </span>
                        @endif
                        <span class="ml-auto text-[10px] font-bold text-gray-400 dark:text-gray-500">
                            {{ count($semData['matakuliah']) }} MK
                        </span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 ml-5">
                        @foreach($semData['matakuliah'] as $mk)
                        <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 dark:bg-gray-700/40 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="font-mono text-[10px] font-bold text-maroon dark:text-red-400 flex-shrink-0">{{ $mk['kode_mk'] }}</span>
                            <span class="text-xs text-gray-700 dark:text-gray-300 truncate flex-1" title="{{ $mk['nama_mk'] }}">{{ $mk['nama_mk'] }}</span>
                            <span class="text-[10px] font-bold text-gray-400 flex-shrink-0">{{ $mk['sks'] }} SKS</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endif {{-- end tab --}}

</div> {{-- end tab content wrapper --}}

{{-- ═══════════════ MODAL: TAMBAH DOSEN KE TA ═══════════════ --}}
@if($selectedSemester)
<div id="modal-tambah-dosen-ta"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden"
    x-data="{
        selectedDosenId: '',
        selectedMkIds: [],
        mkSearch: '',
        selectedSemester: '',
        allMk: {{ Js::from($availableMataKuliah->map(fn($m) => ['id'=>$m->id,'kode_mk'=>$m->kode_mk,'nama_mk'=>$m->nama_mk,'sks'=>$m->sks, 'semester'=>$m->semester])->values()) }},
        get filteredMk() {
            let filtered = this.allMk;
            if (this.selectedSemester) {
                filtered = filtered.filter(m => m.semester == this.selectedSemester);
            }
            if (this.mkSearch.trim()) {
                const q = this.mkSearch.toLowerCase();
                filtered = filtered.filter(m => m.kode_mk.toLowerCase().includes(q) || m.nama_mk.toLowerCase().includes(q));
            }
            return filtered;
        },
        formAction: '',
        setDosen(val) {
            this.selectedDosenId = val;
            this.formAction = '/admin/dosen/' + val + '/assignments';
        }
    }">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-11/12 md:w-3/4 lg:w-1/2 max-h-[85vh] flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 bg-maroon text-white rounded-t-2xl">
            <div>
                <h3 class="text-lg font-bold">Tambah Dosen ke TA</h3>
                <p class="text-xs text-red-100">{{ $selectedSemester->display_label }}</p>
            </div>
            <button onclick="document.getElementById('modal-tambah-dosen-ta').classList.add('hidden')" class="text-white text-2xl leading-none">&times;</button>
        </div>
        <form :action="formAction" method="POST" @submit.prevent="if(!selectedDosenId || selectedMkIds.length === 0) { alert('Pilih dosen dan minimal 1 MK'); return false; } $el.submit();" class="flex-1 overflow-y-auto p-6 space-y-5">
            @csrf
            <input type="hidden" name="semester_id" value="{{ $selectedSemester->id }}">

            <div>
                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2">Pilih Dosen</label>
                <select name="dosen_select" required @change="setDosen($event.target.value)"
                    class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-maroon">
                    <option value="">-- Pilih Dosen --</option>
                    @foreach($availableDosens as $d)
                    <option value="{{ $d->id }}">{{ $d->user->name }} ({{ $d->nidn }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2">Cari &amp; Pilih Mata Kuliah</label>
                <div class="flex gap-2 mb-3">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
                        <input type="text" x-model="mkSearch" placeholder="Cari kode / nama MK..."
                            class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon">
                    </div>
                    <select x-model="selectedSemester" class="w-1/3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-2 py-2 text-sm focus:ring-2 focus:ring-maroon">
                        <option value="">Semua Sem.</option>
                        @foreach(range(1, 8) as $s)
                        <option value="{{ $s }}">Sem. {{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col gap-2 max-h-52 overflow-y-auto pr-1">
                    <template x-for="mk in filteredMk" :key="mk.id">
                        <label class="flex items-center gap-3 p-3 border border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                            :class="selectedMkIds.includes(mk.id) ? 'border-maroon bg-red-50 dark:bg-red-900/20' : ''">
                            <input type="checkbox" name="mata_kuliah_ids[]" :value="mk.id"
                                @change="selectedMkIds.includes(mk.id) ? selectedMkIds.splice(selectedMkIds.indexOf(mk.id),1) : selectedMkIds.push(mk.id)"
                                :checked="selectedMkIds.includes(mk.id)"
                                class="rounded border-gray-300 text-maroon focus:ring-maroon">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-xs font-bold text-maroon dark:text-red-400" x-text="mk.kode_mk"></span>
                                    <span class="text-[10px] font-bold text-gray-400 bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded" x-text="mk.sks + ' SKS'"></span>
                                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 dark:bg-blue-900/20 px-1.5 py-0.5 rounded" x-text="'Sem. ' + mk.semester"></span>
                                </div>
                                <span class="text-sm text-gray-700 dark:text-gray-300 line-clamp-1" x-text="mk.nama_mk"></span>
                            </div>
                        </label>
                    </template>
                    <div x-show="filteredMk.length === 0" class="text-center py-6 text-xs text-gray-400">
                        Tidak ada MK yang cocok
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-2" x-show="selectedMkIds.length > 0">
                    <span x-text="selectedMkIds.length"></span> MK dipilih
                </p>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                <button type="button" onclick="document.getElementById('modal-tambah-dosen-ta').classList.add('hidden')"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Batal
                </button>
                <button type="submit"
                    :class="(!selectedDosenId || selectedMkIds.length === 0) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-red-900'"
                    class="px-5 py-2 bg-maroon text-white rounded-xl text-sm font-bold transition shadow-md">
                    <i class="fas fa-user-plus mr-1"></i> Tambahkan
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ═══════════════ MODAL: CARRY FORWARD ═══════════════ --}}
@if($previousSemester && $selectedSemester)
<div id="modal-carry-forward" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-11/12 md:w-2/5">
        <div class="flex items-center justify-between px-6 py-4 bg-maroon text-white rounded-t-2xl">
            <div>
                <h3 class="text-lg font-bold"><i class="fas fa-copy mr-2"></i>Carry Forward Penugasan</h3>
                <p class="text-xs text-red-100">Salin semua penugasan dosen ke semester baru</p>
            </div>
            <button onclick="document.getElementById('modal-carry-forward').classList.add('hidden')" class="text-white text-2xl leading-none">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.dosen.carry-forward-all') }}" class="p-6 space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Sumber (Copy From)</p>
                    <p class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ $previousSemester->display_label }}</p>
                    <input type="hidden" name="source_semester_id" value="{{ $previousSemester->id }}">
                </div>
                <div class="bg-maroon/5 dark:bg-red-900/20 rounded-xl p-4 border border-maroon/20">
                    <p class="text-[10px] font-bold text-maroon dark:text-red-400 uppercase tracking-widest mb-1">Tujuan (Paste To)</p>
                    <p class="text-sm font-bold text-maroon dark:text-red-300">{{ $selectedSemester->display_label }}</p>
                    <input type="hidden" name="target_semester_id" value="{{ $selectedSemester->id }}">
                </div>
            </div>
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4">
                <p class="text-xs text-yellow-800 dark:text-yellow-300 font-semibold flex items-start gap-2">
                    <i class="fas fa-info-circle mt-0.5 flex-shrink-0"></i>
                    Hanya penugasan yang belum ada di semester tujuan yang akan disalin. Data yang sudah ada tidak akan digandakan.
                </p>
            </div>
            <div class="flex justify-end gap-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                <button type="button" onclick="document.getElementById('modal-carry-forward').classList.add('hidden')"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Batal
                </button>
                <button type="submit"
                    onclick="return confirm('Yakin ingin menyalin semua penugasan dosen ke semester tujuan?')"
                    class="px-5 py-2 bg-maroon hover:bg-red-900 text-white rounded-xl text-sm font-bold transition shadow-md">
                    <i class="fas fa-copy mr-1"></i> Jalankan Carry Forward
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ═══════════════ QUICK ASSIGN DRAWER ═══════════════ --}}
<div x-show="isOpen"
     class="fixed inset-0 z-[100] flex justify-end"
     role="dialog" aria-modal="true"
     style="display: none;">
    <div x-show="isOpen"
         x-transition:enter="ease-in-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in-out duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeDrawer()"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div x-show="isOpen"
         x-transition:enter="transform transition ease-in-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="pointer-events-auto relative w-screen max-w-lg shadow-2xl bg-white dark:bg-gray-800 flex flex-col h-full">
        <div class="flex items-center justify-between px-6 py-4 bg-maroon text-white shrink-0">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center font-bold text-lg" x-text="dosenInitials"></div>
                <div>
                    <h2 class="text-base font-bold leading-tight" x-text="dosenName"></h2>
                    <p class="text-xs text-red-100 font-mono" x-text="'NIDN: ' + dosenNidn"></p>
                </div>
            </div>
            <button type="button" @click="closeDrawer()" class="text-white/70 hover:text-white transition-colors focus:outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div x-show="isLoading" class="flex-1 flex flex-col items-center justify-center text-maroon">
            <i class="fas fa-spinner fa-spin text-3xl mb-3"></i>
            <span class="text-sm font-semibold">Memuat data...</span>
        </div>
        <div x-show="!isLoading && isReady" class="flex-1 overflow-y-auto px-6 py-5 space-y-6">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 border border-gray-100 dark:border-gray-600">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">TA Aktif Saat Ini</h4>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-gray-800 dark:text-gray-200" x-text="activeSemester ? activeSemester.label : 'Tidak Ada'"></span>
                    <span class="bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300 text-xs font-bold px-2 py-0.5 rounded-full" x-text="currentIds.length + ' MK'"></span>
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pencarian & Filter</label>
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>
                        <input type="text" x-model="searchQuery" placeholder="Ketik nama atau kode MK..."
                               class="w-full pl-9 pr-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-maroon/20 focus:border-maroon bg-white dark:bg-gray-700">
                    </div>
                    <select x-model="selectedSemester" class="w-32 py-2 px-3 border border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-maroon/20 focus:border-maroon bg-white dark:bg-gray-700">
                        <option value="">Semester</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                    </select>
                </div>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pilih Mata Kuliah</p>
                <div class="flex flex-col gap-2 max-h-[400px] pr-1 overflow-y-auto">
                    <template x-for="mk in filteredMkList" :key="mk.id">
                        <button type="button" @click="toggleDraft(mk)"
                            :class="[
                                draftIds.includes(mk.id) ? 'bg-maroon text-white border-maroon shadow-md' :
                                'bg-white dark:bg-gray-700 hover:border-maroon/50 hover:bg-maroon/5 text-gray-700 dark:text-gray-200 border-gray-200 dark:border-gray-600',
                                'border px-3 py-2 rounded-xl text-left transition-all w-full relative group block pr-12'
                            ]">
                            <div class="flex justify-between items-center w-full mb-1">
                                <span class="font-mono text-xs font-bold truncate" x-text="mk.kode_mk" :class="draftIds.includes(mk.id) ? 'text-red-100' : 'text-gray-500 dark:text-gray-400'"></span>
                                <div class="flex gap-1 shrink-0 ml-2">
                                    <span x-show="currentIds.includes(mk.id)" class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-green-500/20 text-green-600">Aktif</span>
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded" :class="draftIds.includes(mk.id) ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300'" x-text="mk.sks + ' SKS'"></span>
                                </div>
                            </div>
                            <span class="text-sm font-semibold leading-tight line-clamp-2" x-text="mk.nama_mk"></span>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center justify-center">
                                <i x-show="draftIds.includes(mk.id)" class="fas fa-check-circle text-white text-lg"></i>
                                <i x-show="!draftIds.includes(mk.id)" class="fas fa-plus-circle text-maroon/20 group-hover:text-maroon/60 text-lg transition-colors"></i>
                            </div>
                            <div x-show="historicIds.includes(mk.id) && !currentIds.includes(mk.id) && !draftIds.includes(mk.id)"
                                 class="mt-1.5 inline-flex items-center gap-1 text-[9px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded">
                                <i class="fas fa-history"></i> Pernah Diampu
                            </div>
                        </button>
                    </template>
                    <div x-show="filteredMkList.length === 0" class="text-center w-full py-4 text-xs text-gray-400">
                        Tidak ada MK yang cocok
                    </div>
                </div>
            </div>
        </div>
        <div x-show="!isLoading && isReady" class="shrink-0 p-6 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div x-show="draftIds.length > 0" x-transition class="mb-4">
                <div class="flex justify-between items-center text-xs mb-2">
                    <span class="font-bold text-maroon"><i class="fas fa-clipboard-check mr-1"></i>Akan Disimpan (<span x-text="draftIds.length"></span>)</span>
                    <button type="button" @click="draftIds = []" class="text-red-500 hover:text-red-700 font-semibold text-[10px]">Hapus Semua</button>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="button" @click="closeDrawer()" class="flex-1 px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400 rounded-xl text-sm font-bold hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    Batal
                </button>
                <button type="button" @click="saveAssignments()" :disabled="draftIds.length === 0 || isSaving"
                    :class="draftIds.length === 0 || isSaving ? 'opacity-50 cursor-not-allowed bg-gray-200 dark:bg-gray-700 text-gray-500' : 'bg-maroon hover:bg-red-900 text-white shadow-lg shadow-maroon/20'"
                    class="flex-[2] px-4 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center">
                    <span x-show="!isSaving">Simpan &rarr;</span>
                    <span x-show="isSaving"><i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
</div>

</div> {{-- Close x-data quickAssign wrapper --}}

{{-- ═══════════════ IMPORT MODAL ═══════════════ --}}
<div id="modal-import-dosen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-11/12 md:w-3/4 lg:w-1/2 max-h-[85vh] flex flex-col overflow-hidden">
        <div class="flex-none flex items-center justify-between px-8 py-6 bg-gradient-to-r from-maroon to-red-900 text-white">
            <div class="flex items-center space-x-4">
                <div class="h-12 w-12 rounded-xl bg-white/10 flex items-center justify-center">
                    <i class="fas fa-cloud-upload-alt text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold">Import Data Dosen</h3>
                    <p class="text-sm text-red-100/90">Unggah file CSV untuk menambahkan data dosen secara massal</p>
                </div>
            </div>
            <button onclick="document.getElementById('modal-import-dosen').classList.add('hidden')" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
        </div>
        <form action="{{ route('admin.dosen.import') }}" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto">
            @csrf
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                        <span class="w-1 h-4 bg-maroon rounded-full"></span> Upload File CSV
                    </label>
                    <div id="drop-area" class="flex-1 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-8 bg-gray-50/50 dark:bg-gray-700/30 hover:bg-red-50/30 hover:border-maroon/50 transition-all cursor-pointer group min-h-[200px]">
                        <div class="w-16 h-16 bg-white dark:bg-gray-700 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-file-csv text-3xl text-maroon/80"></i>
                        </div>
                        <p id="drop-text" class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center mb-1">Klik untuk upload atau drag &amp; drop</p>
                        <p class="text-xs text-gray-400 text-center">Hanya file .csv, maksimal 5MB</p>
                    </div>
                    <input id="file-input" type="file" name="file" accept=".csv" required class="hidden" />
                </div>
                <div class="flex flex-col gap-3">
                    <label class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                        <span class="w-1 h-4 bg-blue-600 rounded-full"></span> Petunjuk Import
                    </label>
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-900/30 rounded-xl p-5 flex-1">
                        <ul class="text-xs text-blue-800 dark:text-blue-300 space-y-2 list-disc list-inside leading-relaxed">
                            <li>File harus berformat <strong>.CSV</strong></li>
                            <li>Kolom wajib: <strong>nidn, name, email</strong></li>
                            <li>Untuk banyak prodi, pisahkan dengan <code>|</code></li>
                        </ul>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/30 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Gunakan template agar data terbaca dengan benar.</p>
                        <a href="{{ route('admin.dosen.import-template') }}"
                            class="flex items-center justify-center gap-2 w-full py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg text-sm font-semibold hover:bg-gray-50 hover:text-maroon hover:border-maroon/30 transition-all shadow-sm">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-8 pb-8 border-t border-gray-100 dark:border-gray-700 pt-4">
                <button type="button" onclick="document.getElementById('modal-import-dosen').classList.add('hidden')"
                    class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 font-semibold text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-maroon text-white rounded-xl font-semibold text-sm shadow-lg hover:bg-red-900 transition-all flex items-center gap-2">
                    <i class="fas fa-cloud-upload-alt"></i> Proses Import
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('quickAssign', () => ({
        isOpen: false, isLoading: false, isReady: false, isSaving: false,
        dosenId: null, dosenName: '', dosenNidn: '', dosenInitials: '',
        activeSemester: null, currentIds: [], historicIds: [], availableMk: [],
        searchQuery: '', selectedSemester: '', draftIds: [],

        init() {
            this.$watch('isOpen', value => {
                document.body.classList.toggle('overflow-hidden', value);
            });
        },

        openDrawer(id) {
            this.dosenId = id; this.isOpen = true; this.isLoading = true;
            this.isReady = false; this.searchQuery = ''; this.selectedSemester = ''; this.draftIds = [];
            fetch(`/admin/dosen/${id}/quick-assign`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                this.dosenName = data.dosen.name;
                this.dosenNidn = data.dosen.nidn;
                this.dosenInitials = data.dosen.name.substring(0, 1).toUpperCase();
                this.activeSemester = data.active_semester;
                this.currentIds = data.current_ids || [];
                this.draftIds = [...this.currentIds];
                this.historicIds = data.historic_mk_ids || [];
                this.availableMk = data.available_mk || [];
                this.isLoading = false; this.isReady = true;
            })
            .catch(() => {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat data dosen.' });
                this.closeDrawer();
            });
        },

        closeDrawer() {
            this.isOpen = false;
            setTimeout(() => { this.isReady = false; this.draftIds = []; this.searchQuery = ''; this.selectedSemester = ''; }, 300);
        },

        get filteredMkList() {
            let result = this.availableMk;
            
            if (this.selectedSemester !== '') {
                result = result.filter(mk => String(mk.semester) === String(this.selectedSemester));
            }
            
            if (this.searchQuery.trim() !== '') {
                const q = this.searchQuery.toLowerCase();
                result = result.filter(mk =>
                    mk.kode_mk.toLowerCase().includes(q) || mk.nama_mk.toLowerCase().includes(q)
                );
            }
            
            return result;
        },

        toggleDraft(mk) {
            const idx = this.draftIds.indexOf(mk.id);
            idx === -1 ? this.draftIds.push(mk.id) : this.draftIds.splice(idx, 1);
        },

        get hasChanges() {
            if (this.currentIds.length !== this.draftIds.length) return true;
            return this.draftIds.some(id => !this.currentIds.includes(id));
        },

        saveAssignments() {
            if (!this.activeSemester || !this.hasChanges) return;
            this.isSaving = true;
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('semester_id', this.activeSemester.id);
            this.draftIds.forEach(id => formData.append('mata_kuliah_ids[]', id));
            fetch(`/admin/dosen/${this.dosenId}/assignments`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(() => {
                this.isSaving = false;
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Penugasan berhasil disimpan.', timer: 1500, showConfirmButton: false })
                    .then(() => window.location.reload());
                this.closeDrawer();
            })
            .catch(() => {
                this.isSaving = false;
                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat menyimpan.' });
            });
        }
    }));
});
</script>
<script>
// Drag-drop for import modal
document.addEventListener('DOMContentLoaded', function () {
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('file-input');
    const dropText  = document.getElementById('drop-text');
    if (!dropArea || !fileInput) return;

    ['dragenter','dragover'].forEach(e => dropArea.addEventListener(e, ev => {
        ev.preventDefault(); ev.stopPropagation();
        dropArea.classList.add('border-maroon', 'bg-red-50/50');
    }));
    ['dragleave','drop'].forEach(e => dropArea.addEventListener(e, ev => {
        ev.preventDefault(); ev.stopPropagation();
        dropArea.classList.remove('border-maroon', 'bg-red-50/50');
    }));
    dropArea.addEventListener('click', () => fileInput.click());
    dropArea.addEventListener('drop', e => {
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            if (dropText) dropText.textContent = e.dataTransfer.files[0].name;
        }
    });
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length && dropText) dropText.textContent = fileInput.files[0].name;
    });
});

// Delete confirm
document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Hapus dosen?',
            text: 'Data dosen akan dihapus secara permanen!',
            icon: 'warning', iconColor: '#7a1621',
            showCancelButton: true,
            confirmButtonColor: '#7a1621', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
        }).then(r => { if (r.isConfirmed) form.submit(); });
    });
});

// Toggle status confirm
function confirmToggleStatus(id, currentStatus, name) {
    const isActive = currentStatus === 'aktif';
    Swal.fire({
        title: isActive ? 'Nonaktifkan Dosen?' : 'Aktifkan Dosen?',
        html: `Apakah Anda yakin ingin ${isActive ? 'menonaktifkan' : 'mengaktifkan'} dosen <strong>${name}</strong>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: isActive ? '#d33' : '#1d7f35',
        cancelButtonColor: '#6c757d',
        confirmButtonText: isActive ? 'Ya, Nonaktifkan!' : 'Ya, Aktifkan!',
        cancelButtonText: 'Batal'
    }).then(r => {
        if (r.isConfirmed) document.getElementById('toggle-status-' + id).submit();
    });
}

@if(session('success'))
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ addslashes(session('success')) }}", showConfirmButton: false, timer: 2500, timerProgressBar: true });
});
@endif
@if(session('error'))
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ addslashes(session('error')) }}", confirmButtonColor: '#7a1621' });
});
@endif
@if(session('import_errors'))
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'warning', title: 'Peringatan Import',
        html: '<div class="text-left text-sm max-h-60 overflow-y-auto"><ul class="list-disc list-inside space-y-1">@foreach(session("import_errors") as $error)<li>{{ $error }}</li>@endforeach</ul></div>',
        confirmButtonColor: '#7a1621'
    });
});
@endif

// Client-Side Search (Instant Filter)
document.addEventListener('DOMContentLoaded', function() {
    function setupRealtimeSearch(inputId, rowClass, nameClass, nidnClass) {
        const searchInput = document.getElementById(inputId);
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase();
                const rows = document.querySelectorAll(rowClass);
                
                rows.forEach(row => {
                    const nama = row.querySelector(nameClass)?.textContent.toLowerCase() || '';
                    const nidn = row.querySelector(nidnClass)?.textContent.toLowerCase() || '';
                    
                    if (nama.includes(query) || nidn.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    }

    setupRealtimeSearch('searchInput', '.searchable-row', '.search-nama', '.search-nidn');
    setupRealtimeSearch('searchInput2', '.searchable-row-2', '.search-nama-2', '.search-nidn-2');
    setupRealtimeSearch('searchInput3', '.searchable-row-3', '.search-nama-3', '.search-nidn-3');
});
</script>
@endpush
@endsection
