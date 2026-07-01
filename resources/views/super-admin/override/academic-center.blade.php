@extends('layouts.super-admin')

@section('title', 'Academic Override Center')
@section('page-title', 'Academic Override Center')

@section('content')
<div class="space-y-6"
     x-data="{
         search: '',
         activeStudent: null,
         gradeModal: null,
         krsModal: null,
         loading: false,
         results: [],

         async searchMahasiswa() {
             if (!this.search || this.search.length < 2) {
                 this.results = [];
                 return;
             }
             this.loading = true;
             try {
                 const res = await fetch(`{{ route('super-admin.student-360-search') }}?q=${encodeURIComponent(this.search)}`, {
                     headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                 });
                 if (res.ok) {
                     this.results = await res.json();
                 }
             } catch(e) {}
             this.loading = false;
         }
     }">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-500">school</span>
                Academic Override Center
            </h2>
            <p class="text-sm text-slate-500 mt-0.5">Override nilai, KRS, dan status akademik mahasiswa</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('super-admin.student-360-search') }}"
               class="btn-secondary px-4 py-2 rounded-xl text-sm font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">manage_search</span>
                Student 360°
            </a>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="glass-card p-4 hover:scale-[1.02] transition-transform duration-200 cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#7a1621]/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#7a1621] text-lg">people</span>
                </div>
                <div>
                    <p class="text-xl font-black text-slate-800">{{ $totalMahasiswa }}</p>
                    <p class="text-xs text-slate-500">Total Mahasiswa</p>
                </div>
            </div>
        </div>
        <div class="glass-card p-4 hover:scale-[1.02] transition-transform duration-200 cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-sky-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-sky-600 text-lg">assignment</span>
                </div>
                <div>
                    <p class="text-xl font-black text-slate-800">{{ $totalKrs }}</p>
                    <p class="text-xs text-slate-500">Total KRS</p>
                </div>
            </div>
        </div>
        <div class="glass-card p-4 hover:scale-[1.02] transition-transform duration-200 cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-emerald-600 text-lg">grade</span>
                </div>
                <div>
                    <p class="text-xl font-black text-slate-800">{{ $totalNilai }}</p>
                    <p class="text-xs text-slate-500">Total Nilai Masuk</p>
                </div>
            </div>
        </div>
        <div class="glass-card p-4 hover:scale-[1.02] transition-transform duration-200 cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-rose-600 text-lg">cancel</span>
                </div>
                <div>
                    <p class="text-xl font-black text-slate-800">{{ $nilaiE }}</p>
                    <p class="text-xs text-slate-500">Nilai E (Tidak Lulus)</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Action: Cari Mahasiswa --}}
    <div class="glass-card p-6">
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[#7a1621]">search</span>
            Pilih Mahasiswa untuk Override
        </h3>
        <div class="flex gap-3">
            <div class="flex-1 relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text"
                    id="searchInput"
                    x-model="search"
                    @input.debounce.300ms="searchMahasiswa()"
                    placeholder="Ketik NIM atau nama mahasiswa..."
                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#7a1621] focus:border-[#7a1621] text-sm text-slate-800 transition">
            </div>
        </div>
        {{-- Search Results dropdown --}}
        <div id="searchResults" 
             x-show="search.length >= 2"
             class="mt-3 border border-[#7a1621]/15 rounded-2xl bg-white shadow-lg overflow-hidden max-h-60 overflow-y-auto"
             x-cloak>
            <template x-if="loading">
                <div class="p-4 text-sm text-slate-500 italic flex items-center gap-2">
                    <span class="animate-spin h-4 w-4 border-2 border-[#7a1621] border-t-transparent rounded-full"></span>
                    Mencari mahasiswa...
                </div>
            </template>
            <template x-if="!loading && results.length === 0">
                <div class="p-4 text-sm text-slate-500 italic">Tidak ada mahasiswa ditemukan.</div>
            </template>
            <template x-if="!loading && results.length > 0">
                <div class="divide-y divide-slate-100">
                    <template x-for="mhs in results" :key="mhs.id">
                        <div @click="window.location.href = `?nim_filter=${mhs.nim}`"
                             class="p-3.5 hover:bg-[#7a1621]/5 cursor-pointer transition flex items-center justify-between">
                            <div>
                                <p class="font-bold text-slate-800 text-sm" x-text="mhs.nama || (mhs.user ? mhs.user.name : 'Unknown')"></p>
                                <p class="text-xs text-slate-500">NIM: <span x-text="mhs.nim"></span></p>
                            </div>
                            <span class="text-xs font-bold text-[#7a1621] flex items-center gap-1">
                                Pilih <span class="material-symbols-outlined text-xs">arrow_forward</span>
                            </span>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>

    <div id="search-results" class="space-y-6">
    {{-- Nilai Override Table --}}
    <div class="glass-card overflow-hidden">
        <div class="p-5 border-b border-[#7a1621]/10 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621]">grade</span>
                Daftar Nilai — Override Langsung
            </h3>
            <div class="flex gap-2 items-center">
                <form method="GET" class="flex gap-2">
                    <select name="nim_filter"
                        class="text-sm border border-slate-205 rounded-xl px-3 py-2 bg-white text-slate-700 focus:ring-2 focus:ring-[#7a1621] focus:outline-none">
                        <option value="">Semua Mahasiswa</option>
                        @foreach($mahasiswas as $mhs)
                            <option value="{{ $mhs->nim }}" {{ request('nim_filter') == $mhs->nim ? 'selected' : '' }}>
                                {{ $mhs->nama }} ({{ $mhs->nim }})
                            </option>
                        @endforeach
                    </select>
                    <select name="grade_filter"
                        class="text-sm border border-slate-205 rounded-xl px-3 py-2 bg-white text-slate-700 focus:ring-2 focus:ring-[#7a1621] focus:outline-none">
                        <option value="">Semua Grade</option>
                        <option value="A" {{ request('grade_filter') == 'A' ? 'selected' : '' }}>A</option>
                        <option value="A-" {{ request('grade_filter') == 'A-' ? 'selected' : '' }}>A-</option>
                        <option value="B+" {{ request('grade_filter') == 'B+' ? 'selected' : '' }}>B+</option>
                        <option value="B" {{ request('grade_filter') == 'B' ? 'selected' : '' }}>B</option>
                        <option value="B-" {{ request('grade_filter') == 'B-' ? 'selected' : '' }}>B-</option>
                        <option value="C+" {{ request('grade_filter') == 'C+' ? 'selected' : '' }}>C+</option>
                        <option value="C" {{ request('grade_filter') == 'C' ? 'selected' : '' }}>C</option>
                        <option value="D" {{ request('grade_filter') == 'D' ? 'selected' : '' }}>D</option>
                        <option value="E" {{ request('grade_filter') == 'E' ? 'selected' : '' }}>E (Tidak Lulus)</option>
                    </select>
                    <button type="submit" class="bg-[#7a1621] text-white px-3 py-2 rounded-xl text-xs font-bold">Filter</button>
                </form>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold uppercase text-[#7a1621] bg-[#7a1621]/5 border-b border-[#7a1621]/10">
                    <tr>
                        <th class="px-5 py-3.5">Mahasiswa</th>
                        <th class="px-5 py-3.5">Mata Kuliah</th>
                        <th class="px-5 py-3.5">T.A.</th>
                        <th class="px-5 py-3.5 text-center">Nilai Akhir</th>
                        <th class="px-5 py-3.5 text-center">Grade</th>
                        <th class="px-5 py-3.5 text-center">Bobot</th>
                        <th class="px-5 py-3.5 text-center">Published</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($nilais as $nilai)
                    @php
                        $gradeColor = match($nilai->grade) {
                            'A'  => 'bg-emerald-500/10 text-emerald-700 border-emerald-500/20',
                            'A-' => 'bg-emerald-500/10 text-emerald-700 border-emerald-500/20',
                            'B+' => 'bg-sky-500/10 text-sky-700 border-sky-500/20',
                            'B'  => 'bg-sky-500/10 text-sky-700 border-sky-500/20',
                            'B-' => 'bg-sky-500/10 text-sky-700 border-sky-500/20',
                            'C+' => 'bg-amber-500/10 text-amber-700 border-amber-500/20',
                            'C'  => 'bg-amber-500/10 text-amber-700 border-amber-500/20',
                            'D'  => 'bg-rose-500/10 text-rose-700 border-rose-500/20',
                            'E'  => 'bg-rose-500/10 text-rose-700 border-rose-500/20',
                            default => 'bg-slate-500/10 text-slate-600 border-slate-500/20',
                        };
                    @endphp
                    <tr class="hover:bg-[#7a1621]/5 transition-colors">
                        <td class="px-5 py-3">
                            <div>
                                <p class="font-semibold text-slate-800 text-xs">{{ $nilai->krs?->mahasiswa?->nama ?? 'N/A' }}</p>
                                <p class="text-[10px] text-slate-400">{{ $nilai->krs?->mahasiswa?->nim }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <p class="font-medium text-slate-700 text-xs">{{ $nilai->krs?->mataKuliah?->nama_mk ?? '-' }}</p>
                            <p class="text-[10px] text-slate-400">{{ $nilai->krs?->mataKuliah?->kode_mk }}</p>
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-500">{{ $nilai->krs?->tahun_ajaran ?? '-' }}</td>
                        <td class="px-5 py-3 text-center font-bold text-slate-800 text-sm">
                            {{ $nilai->nilai_akhir !== null ? number_format($nilai->nilai_akhir, 1) : '—' }}
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-black border {{ $gradeColor }}">
                                {{ $nilai->grade ?? '—' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center text-xs font-semibold text-slate-700">
                            {{ $nilai->bobot !== null ? number_format($nilai->bobot, 2) : '—' }}
                        </td>
                        <td class="px-5 py-3 text-center">
                            @if($nilai->is_published)
                                <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                            @else
                                <span class="w-2 h-2 rounded-full bg-slate-300 inline-block"></span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center">
                            <button onclick="openGradeModal({{ $nilai->id }}, '{{ addslashes($nilai->krs?->mahasiswa?->nama) }}', '{{ addslashes($nilai->krs?->mataKuliah?->nama_mk) }}', {{ $nilai->nilai_akhir ?? 0 }}, '{{ $nilai->grade }}')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#7a1621] hover:bg-[#5e1019] text-white text-xs font-bold rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                Override
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl block mb-2 text-slate-300">grade</span>
                            Belum ada data nilai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $nilais->withQueryString()->links() }}
        </div>
    </div>

    {{-- KRS Status Override Table --}}
    <div class="glass-card overflow-hidden">
        <div class="p-5 border-b border-[#7a1621]/10 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621]">assignment</span>
                KRS — Override Status
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold uppercase text-[#7a1621] bg-[#7a1621]/5 border-b border-[#7a1621]/10">
                    <tr>
                        <th class="px-5 py-3.5">Mahasiswa</th>
                        <th class="px-5 py-3.5">Mata Kuliah</th>
                        <th class="px-5 py-3.5">T.A.</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($krsList as $krs)
                    @php
                        $statusColor = match($krs->status) {
                            'approved'     => 'bg-emerald-500/10 text-emerald-700 border border-emerald-500/20',
                            'sudah submit' => 'bg-sky-500/10 text-sky-700 border border-sky-500/20',
                            'rejected'     => 'bg-rose-500/10 text-rose-700 border border-rose-500/20',
                            default        => 'bg-slate-500/10 text-slate-600 border border-slate-500/20',
                        };
                    @endphp
                    <tr class="hover:bg-[#7a1621]/5 transition-colors">
                        <td class="px-5 py-3">
                            <div>
                                <p class="font-semibold text-slate-800 text-xs">{{ $krs->mahasiswa?->nama ?? 'N/A' }}</p>
                                <p class="text-[10px] text-slate-400">{{ $krs->mahasiswa?->nim }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-700">{{ $krs->mataKuliah?->nama_mk ?? '-' }}</td>
                        <td class="px-5 py-3 text-xs text-slate-500">{{ $krs->tahun_ajaran ?? '-' }}</td>
                        <td class="px-5 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold border {{ $statusColor }}">
                                {{ ucfirst($krs->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <button onclick="openKrsModal({{ $krs->id }}, '{{ addslashes($krs->mahasiswa?->nama) }}', '{{ addslashes($krs->mataKuliah?->nama_mk) }}', '{{ $krs->status }}')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#7a1621] hover:bg-[#5e1019] text-white text-xs font-bold rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-sm">tune</span>
                                Ubah Status
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada data KRS.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $krsList->withQueryString()->links() }}
        </div>
    </div>
    </div> {{-- Closes #search-results --}}
</div> {{-- Closes main space-y-6 container --}}

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- GRADE OVERRIDE MODAL                                                       --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div id="gradeModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeGradeModal()"></div>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative z-10" onclick="event.stopPropagation()">
        {{-- Modal Header --}}
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#7a1621]">grade</span>
                    Override Nilai
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Perubahan ini akan diaudit dan tidak dapat dibatalkan secara otomatis.</p>
            </div>
            <button onclick="closeGradeModal()" class="text-slate-400 hover:text-slate-700">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        {{-- Before/After Info --}}
        <div class="grid grid-cols-2 gap-3 mb-5">
            <div class="bg-slate-50 rounded-xl p-3">
                <p class="text-[10px] font-bold uppercase text-slate-400 mb-1">Sebelum Override</p>
                <p class="text-sm text-slate-700 font-semibold" id="modal-old-nilai">—</p>
                <p class="text-xs text-slate-400" id="modal-old-grade">—</p>
            </div>
            <div class="bg-[#7a1621]/5 border border-[#7a1621]/20 rounded-xl p-3">
                <p class="text-[10px] font-bold uppercase text-[#7a1621] mb-1">Setelah Override</p>
                <p class="text-lg font-black text-[#7a1621]" id="preview-nilai">—</p>
                <p class="text-xs font-bold text-[#7a1621]" id="preview-grade">Grade: —</p>
            </div>
        </div>

        <form id="gradeForm" method="POST" action="">
            @csrf
            {{-- Student & MK Info --}}
            <div class="bg-slate-50 rounded-xl p-3 mb-4 text-sm text-slate-700">
                <p><span class="font-semibold">Mahasiswa:</span> <span id="modal-mahasiswa-name">—</span></p>
                <p><span class="font-semibold">Mata Kuliah:</span> <span id="modal-mk-name">—</span></p>
            </div>

            {{-- New Grade Input --}}
            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Nilai Akhir Baru <span class="text-red-500">*</span></label>
                <input type="number" name="nilai_akhir" id="input-nilai"
                    min="0" max="100" step="0.1"
                    placeholder="0 – 100"
                    oninput="updateGradePreview(this.value)"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#7a1621] focus:border-transparent transition"
                    required>
                <p class="text-xs text-slate-400 mt-1">Grade akan dihitung otomatis berdasarkan nilai yang diinput.</p>
            </div>

            {{-- Reason --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Alasan Override <span class="text-red-500">*</span></label>
                <textarea name="override_reason" rows="3"
                    placeholder="Jelaskan alasan override nilai ini (min. 10 karakter)..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-700 resize-none focus:outline-none focus:ring-2 focus:ring-[#7a1621] focus:border-transparent transition"
                    required minlength="10"></textarea>
            </div>

            {{-- Confirm Checkbox --}}
            <div class="flex items-center gap-2.5 mb-5 p-3 bg-[#7a1621]/5 border border-[#7a1621]/20 rounded-xl">
                <input type="checkbox" id="confirm-override" required
                    class="w-4 h-4 rounded border-[#7a1621]/40 text-[#7a1621] focus:ring-[#7a1621]">
                <label for="confirm-override" class="text-xs text-[#7a1621] font-semibold leading-tight">
                    Saya memahami bahwa override ini akan dicatat dalam audit trail dan tidak dapat dihapus.
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3">
                <button type="button" onclick="closeGradeModal()"
                    class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-50 transition">
                    Batalkan
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-[#7a1621] hover:bg-[#5e1019] text-white rounded-xl text-sm font-bold transition flex items-center justify-center gap-2 shadow-md">
                    <span class="material-symbols-outlined text-sm">check_circle</span>
                    Konfirmasi Override
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- KRS STATUS OVERRIDE MODAL                                                  --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div id="krsModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeKrsModal()"></div>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative z-10" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-5">
            <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-600">assignment</span>
                Override Status KRS
            </h3>
            <button onclick="closeKrsModal()" class="text-slate-400 hover:text-slate-700">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="krsForm" method="POST" action="">
            @csrf
            <div class="bg-slate-50 rounded-xl p-3 mb-4 text-sm text-slate-700">
                <p><span class="font-semibold">Mahasiswa:</span> <span id="krs-mahasiswa-name">—</span></p>
                <p><span class="font-semibold">Mata Kuliah:</span> <span id="krs-mk-name">—</span></p>
                <p><span class="font-semibold">Status Saat Ini:</span> <span id="krs-current-status" class="font-bold text-slate-800">—</span></p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Status Baru <span class="text-red-500">*</span></label>
                <select name="status" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621]">
                    <option value="draft">Draft</option>
                    <option value="sudah submit">Sudah Submit</option>
                    <option value="approved">Approved ✓</option>
                    <option value="rejected">Rejected ✗</option>
                </select>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Alasan Override <span class="text-red-500">*</span></label>
                <textarea name="override_reason" rows="3"
                    placeholder="Jelaskan alasan perubahan status KRS ini..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-700 resize-none focus:outline-none focus:ring-2 focus:ring-[#7a1621]"
                    required minlength="10"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeKrsModal()"
                    class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-50 transition">
                    Batalkan
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-[#7a1621] hover:bg-[#5e1019] text-white rounded-xl text-sm font-bold transition shadow-md">
                    Override Status
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Grade conversion thresholds (matches Nilai::convertToGrade)
const gradeTable = [
    { min: 80,  grade: 'A',  bobot: 4.00 },
    { min: 76,  grade: 'A-', bobot: 3.67 },
    { min: 72,  grade: 'B+', bobot: 3.33 },
    { min: 68,  grade: 'B',  bobot: 3.00 },
    { min: 64,  grade: 'B-', bobot: 2.67 },
    { min: 60,  grade: 'C+', bobot: 2.33 },
    { min: 56,  grade: 'C',  bobot: 2.00 },
    { min: 45,  grade: 'D',  bobot: 1.00 },
    { min: 0,   grade: 'E',  bobot: 0.00 },
];

function convertGrade(nilai) {
    for (const row of gradeTable) {
        if (nilai >= row.min) return row;
    }
    return gradeTable[gradeTable.length - 1];
}

function updateGradePreview(val) {
    const n = parseFloat(val);
    if (isNaN(n)) {
        document.getElementById('preview-nilai').textContent = '—';
        document.getElementById('preview-grade').textContent = 'Grade: —';
        return;
    }
    const g = convertGrade(n);
    document.getElementById('preview-nilai').textContent = n.toFixed(1);
    document.getElementById('preview-grade').textContent = `Grade: ${g.grade} (${g.bobot.toFixed(2)})`;
}

function openGradeModal(nilaiId, mahasiswaName, mkName, oldNilai, oldGrade) {
    document.getElementById('gradeForm').action = `{{ url('super-admin/override/nilai') }}/${nilaiId}`;
    document.getElementById('modal-mahasiswa-name').textContent = mahasiswaName;
    document.getElementById('modal-mk-name').textContent = mkName;
    document.getElementById('modal-old-nilai').textContent = oldNilai ? oldNilai.toFixed(1) : '—';
    document.getElementById('modal-old-grade').textContent = `Grade: ${oldGrade || '—'}`;
    document.getElementById('input-nilai').value = '';
    document.getElementById('preview-nilai').textContent = '—';
    document.getElementById('preview-grade').textContent = 'Grade: —';
    document.getElementById('confirm-override').checked = false;
    const modal = document.getElementById('gradeModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeGradeModal() {
    const modal = document.getElementById('gradeModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function openKrsModal(krsId, mahasiswaName, mkName, currentStatus) {
    document.getElementById('krsForm').action = `{{ url('super-admin/override/krs') }}/${krsId}`;
    document.getElementById('krs-mahasiswa-name').textContent = mahasiswaName;
    document.getElementById('krs-mk-name').textContent = mkName;
    document.getElementById('krs-current-status').textContent = currentStatus;
    const modal = document.getElementById('krsModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeKrsModal() {
    const modal = document.getElementById('krsModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close on backdrop click
document.getElementById('gradeModal').addEventListener('click', function(e) {
    if (e.target === this) closeGradeModal();
});
document.getElementById('krsModal').addEventListener('click', function(e) {
    if (e.target === this) closeKrsModal();
});

// ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { closeGradeModal(); closeKrsModal(); }
});
</script>
@endsection
