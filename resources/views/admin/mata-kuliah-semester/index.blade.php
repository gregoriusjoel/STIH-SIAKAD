@extends('layouts.admin')

@section('title', 'Manajemen MK per Semester')
@section('page-title', 'Manajemen Mata Kuliah per Semester')

@section('content')
<div x-data="mkSemesterPage()" x-init="init()">

    {{-- ═══════════════ HEADER ═══════════════ --}}
    <div class="mb-6 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <i class="fas fa-calendar-alt text-maroon dark:text-red-400"></i>
                Manajemen Mata Kuliah per Semester (TA)
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Best Practice: Master MK global, relasi per TA, histori dan carry-forward.
            </p>

            {{-- Semester badge --}}
            @if($activeSemester)
            <div class="mt-3 flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    Semester Aktif: {{ $activeSemester->display_label }}
                </span>
                @if($activeSemester->is_locked)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                    <i class="fas fa-lock"></i> DIKUNCI
                </span>
                @endif
            </div>
            @else
            <div class="mt-3">
                <span class="px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Tidak ada semester aktif
                </span>
            </div>
            @endif
        </div>

        {{-- Action buttons --}}
        <div class="flex flex-wrap gap-2 flex-shrink-0">
            {{-- Attach MK --}}
            @if($selectedSemester && !$selectedSemester->is_locked)
            <button @click="openAttachModal()"
                class="flex items-center gap-2 px-4 py-2 bg-maroon text-white rounded-lg hover:bg-red-900 transition text-sm font-medium shadow">
                <i class="fas fa-plus-circle"></i> Tambah MK ke Semester
            </button>
            @endif

            {{-- Carry Forward --}}
            <button @click="openCarryForwardModal()"
                class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium shadow">
                <i class="fas fa-copy"></i> Carry Forward
            </button>

            {{-- Restore from History --}}
            <button @click="openRestoreModal()"
                class="flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium shadow">
                <i class="fas fa-history"></i> Ambil dari Histori
            </button>

            {{-- Activate Semester --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-sm font-medium shadow">
                    <i class="fas fa-toggle-on"></i> Aktifkan Semester
                </button>
                <div x-show="open" @click.away="open = false"
                    x-transition
                    class="absolute right-0 top-full mt-1 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-xl border dark:border-gray-700 z-30 py-1">
                    @foreach($allSemesters as $sem)
                    <form action="{{ route('admin.mata-kuliah-semester.activate-semester', $sem) }}" method="POST"
                        onsubmit="return confirm('Aktifkan semester {{ $sem->display_label }}? Semester lama akan dipindahkan ke histori.')">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center gap-2 {{ $sem->is_active ? 'font-semibold text-green-700 dark:text-green-400' : '' }}">
                            @if($sem->is_active)<i class="fas fa-check-circle text-green-500"></i>@endif
                            {{ $sem->display_label }}
                        </button>
                    </form>
                    @endforeach
                </div>
            </div>

            {{-- Lock / Unlock --}}
            @if($activeSemester)
            @if($activeSemester->is_locked)
            <form action="{{ route('admin.mata-kuliah-semester.unlock-semester', $activeSemester) }}" method="POST"
                onsubmit="return confirm('Buka kunci semester ini? (Hanya Superadmin)')">
                @csrf @method('PATCH')
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition text-sm font-medium shadow">
                    <i class="fas fa-lock-open"></i> Buka Kunci
                </button>
            </form>
            @else
            <form action="{{ route('admin.mata-kuliah-semester.lock-semester', $activeSemester) }}" method="POST"
                onsubmit="return confirm('Kunci semester {{ $activeSemester->display_label }}? Tidak ada perubahan yang bisa dilakukan setelah dikunci.')">
                @csrf @method('PATCH')
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition text-sm font-medium shadow">
                    <i class="fas fa-lock"></i> Kunci Semester
                </button>
            </form>
            @endif
            @endif

            {{-- Audit Logs --}}
            <a href="{{ route('admin.mata-kuliah-semester.audit-logs') }}"
                class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 transition text-sm font-medium shadow">
                <i class="fas fa-clipboard-list"></i> Audit Log
            </a>
        </div>
    </div>

    {{-- ═══════════════ FLASH MESSAGES ═══════════════ --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition
        class="mb-4 flex items-center gap-3 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg">
        <i class="fas fa-check-circle text-green-500"></i>
        <span>{{ session('success') }}</span>
        <button @click="show = false" class="ml-auto text-green-600 hover:text-green-800"><i class="fas fa-times"></i></button>
    </div>
    @endif
    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-transition
        class="mb-4 flex items-center gap-3 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-300 px-4 py-3 rounded-lg">
        <i class="fas fa-exclamation-circle text-red-500"></i>
        <span>{{ session('error') }}</span>
        <button @click="show = false" class="ml-auto text-red-600 hover:text-red-800"><i class="fas fa-times"></i></button>
    </div>
    @endif

    {{-- ═══════════════ SEMESTER SELECTOR ═══════════════ --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-4 mb-5 flex flex-wrap gap-3 items-center">
        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300"><i class="fas fa-filter mr-1"></i> Pilih Semester:</label>
        <form method="GET" action="{{ route('admin.mata-kuliah-semester.index') }}" class="flex gap-2 items-center flex-wrap">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <select name="semester_id" onchange="this.form.submit()"
                class="text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-maroon">
                @foreach($allSemesters as $sem)
                <option value="{{ $sem->id }}" {{ $selectedSemester?->id == $sem->id ? 'selected' : '' }}>
                    {{ $sem->display_label }} {{ $sem->is_active ? '(Aktif)' : '' }}
                </option>
                @endforeach
            </select>
        </form>
        @if($selectedSemester)
        <div class="ml-auto flex gap-2 text-xs text-gray-500 dark:text-gray-400">
            <span>Jumlah MK Aktif: <strong class="text-maroon dark:text-red-400">{{ $activePivots->count() }}</strong></span>
            <span>|</span>
            <span>Histori: <strong>{{ $historyPivots->count() }}</strong></span>
            @if($selectedSemester->is_locked)
            <span class="ml-2 px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 font-semibold">
                <i class="fas fa-lock mr-1"></i>LOCKED
            </span>
            @endif
        </div>
        @endif
    </div>

    {{-- ═══════════════ TABS ═══════════════ --}}
    <div class="flex border-b border-gray-200 dark:border-gray-700 mb-0">
        <a href="{{ route('admin.mata-kuliah-semester.index', ['semester_id' => $selectedSemester?->id, 'tab' => 'active']) }}"
            class="px-6 py-3 text-sm font-semibold border-b-2 transition
                {{ $tab === 'active'
                    ? 'border-maroon text-maroon dark:text-red-400 dark:border-red-400'
                    : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-maroon' }}">
            <i class="fas fa-check-circle mr-1"></i> TA Aktif
            <span class="ml-1 px-2 py-0.5 rounded-full text-xs
                {{ $tab === 'active' ? 'bg-maroon text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                {{ $activePivots->count() }}
            </span>
        </a>
        <a href="{{ route('admin.mata-kuliah-semester.index', ['semester_id' => $selectedSemester?->id, 'tab' => 'history']) }}"
            class="px-6 py-3 text-sm font-semibold border-b-2 transition
                {{ $tab === 'history'
                    ? 'border-purple-600 text-purple-700 dark:text-purple-400 dark:border-purple-400'
                    : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-purple-600' }}">
            <i class="fas fa-archive mr-1"></i> Histori
            <span class="ml-1 px-2 py-0.5 rounded-full text-xs
                {{ $tab === 'history' ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                {{ $historyPivots->count() }}
            </span>
        </a>
    </div>

    {{-- ═══════════════ TAB CONTENT ═══════════════ --}}
    <div class="bg-white dark:bg-gray-800 rounded-b-xl shadow border border-t-0 border-gray-200 dark:border-gray-700 overflow-hidden">

        {{-- ── Active Tab ── --}}
        @if($tab === 'active')
        @include('admin.mata-kuliah-semester.partials.active-list', ['pivots' => $activePivots, 'semester' => $selectedSemester])

        {{-- ── History Tab ── --}}
        @else
        @include('admin.mata-kuliah-semester.partials.history-list', ['historyPivots' => $historyPivots, 'semester' => $selectedSemester])
        @endif
    </div>

</div>

{{-- ═══════════════ MODAL: ATTACH MK ═══════════════ --}}
<div x-data x-show="$store.mkSemester.attachOpen"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
    @keydown.escape.window="$store.mkSemester.attachOpen = false">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col"
        @click.away="$store.mkSemester.attachOpen = false">
        <div class="flex items-center justify-between px-6 py-4 bg-maroon text-white rounded-t-2xl">
            <h3 class="text-lg font-bold flex items-center gap-2"><i class="fas fa-plus-circle"></i> Tambah MK ke Semester</h3>
            <button @click="$store.mkSemester.attachOpen = false" class="text-white text-xl">&times;</button>
        </div>

        <form method="POST" action="{{ route('admin.mata-kuliah-semester.attach') }}" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <input type="hidden" name="semester_id" value="{{ $selectedSemester?->id }}">

            <div class="p-5 flex-1 overflow-y-auto">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Pilih mata kuliah untuk ditambahkan ke <strong>{{ $selectedSemester?->display_label }}</strong>.
                </p>

                {{-- Search --}}
                <input type="text" id="attachSearch" placeholder="Cari kode/nama mata kuliah..."
                    class="w-full mb-3 px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-maroon"
                    oninput="filterAttachList(this.value)">

                {{-- MK List --}}
                <div id="attachList" class="divide-y divide-gray-100 dark:divide-gray-700 max-h-80 overflow-y-auto border dark:border-gray-700 rounded-lg">
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
                        <span class="px-2 py-0.5 text-xs rounded-full
                            {{ $mk->jenis === 'wajib_prodi' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400' }}">
                            {{ str_replace('_', ' ', $mk->jenis) }}
                        </span>
                    </label>
                    @empty
                    <div class="text-center py-8 text-gray-400 dark:text-gray-500">
                        <i class="fas fa-check-circle text-3xl mb-2"></i>
                        <p>Semua mata kuliah sudah terdaftar di semester ini.</p>
                    </div>
                    @endforelse
                </div>

                <div class="mt-2 flex items-center gap-2">
                    <button type="button" onclick="document.querySelectorAll('#attachList input[type=checkbox]').forEach(c => c.checked = true)"
                        class="text-xs text-maroon dark:text-red-400 hover:underline">Pilih Semua</button>
                    <span class="text-gray-300">|</span>
                    <button type="button" onclick="document.querySelectorAll('#attachList input[type=checkbox]').forEach(c => c.checked = false)"
                        class="text-xs text-gray-500 hover:underline">Batal Pilih</button>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t dark:border-gray-700 flex justify-end gap-3 rounded-b-2xl">
                <button type="button" @click="$store.mkSemester.attachOpen = false"
                    class="px-5 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-maroon text-white rounded-lg hover:bg-red-900 text-sm font-semibold">
                    <i class="fas fa-plus mr-1"></i> Tambahkan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════ MODAL: CARRY FORWARD ═══════════════ --}}
<div x-data x-show="$store.mkSemester.carryOpen"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
    @keydown.escape.window="$store.mkSemester.carryOpen = false">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 bg-blue-600 text-white rounded-t-2xl">
            <h3 class="text-lg font-bold flex items-center gap-2"><i class="fas fa-copy"></i> Carry Forward MK</h3>
            <button @click="$store.mkSemester.carryOpen = false" class="text-white text-xl">&times;</button>
        </div>

        <div class="p-5 flex-1 overflow-y-auto">
            <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-sm text-blue-800 dark:text-blue-300">
                <i class="fas fa-info-circle mr-1"></i>
                Salin daftar mata kuliah dari semester lama ke semester tujuan. MK yang sudah ada di tujuan akan dilewati.
            </div>

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Semester Sumber</label>
                    <select id="cf-source" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm"
                        @change="cfPreview()">
                        @foreach($allSemesters as $sem)
                        <option value="{{ $sem->id }}">{{ $sem->display_label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Semester Tujuan</label>
                    <select id="cf-target" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm"
                        @change="cfPreview()">
                        @foreach($allSemesters as $sem)
                        <option value="{{ $sem->id }}" {{ $selectedSemester?->id == $sem->id ? 'selected' : '' }}>
                            {{ $sem->display_label }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="button" @click="cfPreview()"
                class="mb-4 px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-lg text-sm font-medium hover:bg-blue-200 transition">
                <i class="fas fa-eye mr-1"></i> Preview Carry Forward
            </button>

            {{-- Preview Result --}}
            <div id="cf-preview" class="hidden">
                <div id="cf-summary" class="mb-3 text-sm text-gray-600 dark:text-gray-400"></div>
                <div id="cf-to-copy-section" class="hidden mb-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-check text-green-500 mr-1"></i> Akan disalin:
                    </h4>
                    <div id="cf-to-copy" class="border dark:border-gray-700 rounded-lg divide-y divide-gray-100 dark:divide-gray-700 max-h-48 overflow-y-auto text-xs"></div>
                </div>
                <div id="cf-conflict-section" class="hidden">
                    <h4 class="text-sm font-semibold text-orange-700 dark:text-orange-400 mb-2">
                        <i class="fas fa-exclamation-triangle text-orange-500 mr-1"></i> Konflik (sudah ada di tujuan, dilewati):
                    </h4>
                    <div id="cf-conflicts" class="border dark:border-gray-700 rounded-lg divide-y divide-gray-100 dark:divide-gray-700 max-h-32 overflow-y-auto text-xs"></div>
                </div>
            </div>
        </div>

        <form id="cf-form" method="POST" action="{{ route('admin.mata-kuliah-semester.carry-forward') }}">
            @csrf
            <input type="hidden" id="cf-form-source" name="source_semester_id">
            <input type="hidden" id="cf-form-target" name="target_semester_id">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t dark:border-gray-700 flex justify-end gap-3 rounded-b-2xl">
                <button type="button" @click="$store.mkSemester.carryOpen = false"
                    class="px-5 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 text-sm">Batal</button>
                <button type="submit" id="cf-submit-btn" disabled
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-copy mr-1"></i> Salin Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════ MODAL: RESTORE FROM HISTORY ═══════════════ --}}
<div x-data x-show="$store.mkSemester.restoreOpen"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
    @keydown.escape.window="$store.mkSemester.restoreOpen = false">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 bg-purple-700 text-white rounded-t-2xl">
            <h3 class="text-lg font-bold flex items-center gap-2"><i class="fas fa-history"></i> Ambil dari Histori</h3>
            <button @click="$store.mkSemester.restoreOpen = false" class="text-white text-xl">&times;</button>
        </div>

        <form method="POST" action="{{ route('admin.mata-kuliah-semester.restore') }}" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <input type="hidden" name="target_semester_id" value="{{ $selectedSemester?->id }}">

            <div class="p-5 flex-1 overflow-y-auto">
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Semester Sumber (Histori)</label>
                    <select name="source_semester_id" id="restore-source-id"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm"
                        @change="loadRestoreList($event.target.value)">
                        <option value="">-- Pilih Semester Sumber --</option>
                        @foreach($allSemesters as $sem)
                        <option value="{{ $sem->id }}" {{ $sem->id !== $selectedSemester?->id ? '' : 'disabled' }}>
                            {{ $sem->display_label }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Search --}}
                <input type="text" id="restoreSearch" placeholder="Cari nama/kode MK di histori..."
                    class="w-full mb-3 px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-purple-500"
                    oninput="filterRestoreList(this.value)">

                {{-- History MK List --}}
                <div id="restore-list" class="divide-y divide-gray-100 dark:divide-gray-700 max-h-72 overflow-y-auto border dark:border-gray-700 rounded-lg">
                    @forelse($historyPivots as $pivot)
                    <label class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer restore-item"
                        data-search="{{ strtolower($pivot->mataKuliah->kode_mk . ' ' . $pivot->mataKuliah->nama_mk) }}">
                        <input type="checkbox" name="mata_kuliah_ids[]" value="{{ $pivot->mata_kuliah_id }}"
                            class="w-4 h-4 text-purple-600 border-gray-300 rounded">
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $pivot->mataKuliah->nama_mk }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $pivot->mataKuliah->kode_mk }} &bull; {{ $pivot->mataKuliah->sks }} SKS
                                @if($pivot->sourceSemester) &bull; dari {{ $pivot->sourceSemester->display_label }} @endif
                            </div>
                        </div>
                        <span class="px-2 py-0.5 text-xs rounded-full
                            {{ $pivot->status === 'history' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                            {{ $pivot->status }}
                        </span>
                    </label>
                    @empty
                    <div class="text-center py-8 text-gray-400 dark:text-gray-500">
                        <i class="fas fa-inbox text-3xl mb-2"></i>
                        <p>Pilih semester sumber untuk melihat histori MK.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t dark:border-gray-700 flex justify-end gap-3 rounded-b-2xl">
                <button type="button" @click="$store.mkSemester.restoreOpen = false"
                    class="px-5 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 text-sm">Batal</button>
                <button type="submit"
                    class="px-5 py-2 bg-purple-700 text-white rounded-lg hover:bg-purple-800 text-sm font-semibold">
                    <i class="fas fa-undo mr-1"></i> Tambahkan ke TA Aktif
                </button>
            </div>
        </form>
    </div>
</div>


@push('scripts')
<script>
    // Alpine Store
    document.addEventListener('alpine:init', () => {
        Alpine.store('mkSemester', {
            attachOpen: false,
            carryOpen: false,
            restoreOpen: false,
        });
    });

    function mkSemesterPage() {
        return {
            init() {},
            openAttachModal() { Alpine.store('mkSemester').attachOpen = true; },
            openCarryForwardModal() { Alpine.store('mkSemester').carryOpen = true; },
            openRestoreModal() { Alpine.store('mkSemester').restoreOpen = true; },
        };
    }

    // Carry Forward Preview
    async function cfPreview() {
        const source = document.getElementById('cf-source').value;
        const target = document.getElementById('cf-target').value;

        if (!source || !target || source === target) {
            document.getElementById('cf-preview').classList.add('hidden');
            return;
        }

        document.getElementById('cf-form-source').value = source;
        document.getElementById('cf-form-target').value = target;

        try {
            const res = await fetch(`{{ route('admin.mata-kuliah-semester.carry-forward-preview') }}?source_semester_id=${source}&target_semester_id=${target}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await res.json();

            document.getElementById('cf-preview').classList.remove('hidden');
            document.getElementById('cf-summary').innerHTML =
                `<strong>${data.to_copy.length}</strong> MK akan disalin dari <em>${data.source_label}</em> ke <em>${data.target_label}</em>. ` +
                `<strong class="text-orange-600">${data.conflicts.length}</strong> konflik akan dilewati.`;

            // To copy
            const toCopyEl = document.getElementById('cf-to-copy');
            if (data.to_copy.length > 0) {
                document.getElementById('cf-to-copy-section').classList.remove('hidden');
                toCopyEl.innerHTML = data.to_copy.map(mk =>
                    `<div class="px-4 py-2 flex justify-between items-center text-gray-700 dark:text-gray-300">
                        <span><span class="font-mono text-maroon dark:text-red-400">${mk.kode_mk}</span> — ${mk.nama_mk}</span>
                        <span class="text-gray-400">${mk.sks} SKS</span>
                    </div>`
                ).join('');
            } else {
                document.getElementById('cf-to-copy-section').classList.add('hidden');
            }

            // Conflicts
            const conflictsEl = document.getElementById('cf-conflicts');
            if (data.conflicts.length > 0) {
                document.getElementById('cf-conflict-section').classList.remove('hidden');
                conflictsEl.innerHTML = data.conflicts.map(mk =>
                    `<div class="px-4 py-2 flex justify-between items-center text-orange-700 dark:text-orange-400">
                        <span><span class="font-mono">${mk.kode_mk}</span> — ${mk.nama_mk}</span>
                        <span class="text-orange-400">${mk.sks} SKS</span>
                    </div>`
                ).join('');
            } else {
                document.getElementById('cf-conflict-section').classList.add('hidden');
            }

            document.getElementById('cf-submit-btn').disabled = data.to_copy.length === 0;
        } catch (e) {
            console.error(e);
        }
    }

    // Attach search filter
    function filterAttachList(q) {
        document.querySelectorAll('.attach-item').forEach(el => {
            el.style.display = el.dataset.search.includes(q.toLowerCase()) ? '' : 'none';
        });
    }

    // Restore search filter
    function filterRestoreList(q) {
        document.querySelectorAll('.restore-item').forEach(el => {
            el.style.display = el.dataset.search.includes(q.toLowerCase()) ? '' : 'none';
        });
    }

    // Load history list for restore modal
    async function loadRestoreList(semesterId) {
        if (!semesterId) return;
        const targetSemesterId = {{ $selectedSemester?->id ?? 'null' }};
        try {
            const res = await fetch(`{{ route('admin.mata-kuliah-semester.histori') }}?semester_id=${semesterId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const html = await res.text();
            document.getElementById('restore-list').innerHTML = html;
        } catch(e) {
            console.error(e);
        }
    }
</script>
@endpush

@endsection
