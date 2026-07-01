@extends('layouts.super-admin')

@section('title', 'Thesis Override Center')
@section('page-title', 'Thesis Override Center')

@section('content')
@php
    use App\Domain\Skripsi\Enums\SkripsiStatus;

    $colorMap = [
        'gray'   => 'bg-slate-500/10 text-slate-700 border-slate-500/20',
        'purple' => 'bg-purple-500/10 text-purple-700 border-purple-500/20',
        'yellow' => 'bg-amber-500/10 text-amber-700 border-amber-500/20',
        'red'    => 'bg-rose-500/10 text-rose-700 border-rose-500/20',
        'green'  => 'bg-emerald-500/10 text-emerald-700 border-emerald-500/20',
        'blue'   => 'bg-blue-500/10 text-blue-700 border-blue-500/20',
        'teal'   => 'bg-teal-500/10 text-teal-700 border-teal-500/20',
        'indigo' => 'bg-indigo-500/10 text-indigo-700 border-indigo-500/20',
        'orange' => 'bg-orange-500/10 text-orange-700 border-orange-500/20',
        'emerald'=> 'bg-emerald-500/10 text-emerald-700 border-emerald-500/20',
    ];

    // Count by status group
    $totalAll       = $submissions->total();
    $totalDraft     = $submissions->getCollection()->filter(fn($s) => in_array($s->status?->value, ['LOCKED','PROPOSAL_DRAFT']))->count();
    $totalActive    = $submissions->getCollection()->filter(fn($s) => in_array($s->status?->value, ['BIMBINGAN_ACTIVE','ELIGIBLE_SIDANG']))->count();
    $totalCompleted = $submissions->getCollection()->filter(fn($s) => $s->status?->value === 'THESIS_COMPLETED')->count();
@endphp

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621]">menu_book</span>
                Thesis Override Center
            </h2>
            <p class="text-sm text-slate-500 mt-0.5">Override status, pembimbing, dan progress skripsi mahasiswa</p>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="glass-card p-4 flex items-center gap-3 hover:scale-[1.02] transition-transform duration-200 cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-[#7a1621]/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-[#7a1621] text-lg">menu_book</span>
            </div>
            <div>
                <p class="text-xl font-black text-slate-800">{{ $totalAll }}</p>
                <p class="text-xs text-slate-500">Total Skripsi</p>
            </div>
        </div>
        <div class="glass-card p-4 flex items-center gap-3 hover:scale-[1.02] transition-transform duration-200 cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-sky-500/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-sky-600 text-lg">edit_note</span>
            </div>
            <div>
                <p class="text-xl font-black text-sky-700">{{ $submissions->getCollection()->filter(fn($s) => in_array($s->status?->value, ['BIMBINGAN_ACTIVE','ELIGIBLE_SIDANG']))->count() }}</p>
                <p class="text-xs text-slate-500">Bimbingan Aktif</p>
            </div>
        </div>
        <div class="glass-card p-4 flex items-center gap-3 hover:scale-[1.02] transition-transform duration-200 cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-indigo-650 text-lg">gavel</span>
            </div>
            <div>
                <p class="text-xl font-black text-indigo-700">{{ $submissions->getCollection()->filter(fn($s) => in_array($s->status?->value, ['SIDANG_SCHEDULED','SIDANG_COMPLETED']))->count() }}</p>
                <p class="text-xs text-slate-500">Dalam Proses Sidang</p>
            </div>
        </div>
        <div class="glass-card p-4 flex items-center gap-3 hover:scale-[1.02] transition-transform duration-200 cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-emerald-600 text-lg">verified</span>
            </div>
            <div>
                <p class="text-xl font-black text-emerald-700">{{ $submissions->getCollection()->filter(fn($s) => $s->status?->value === 'THESIS_COMPLETED')->count() }}</p>
                <p class="text-xs text-slate-500">Skripsi Selesai</p>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="glass-card p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase">Filter Status</label>
                <select name="status_filter"
                    class="px-3 py-2 rounded-xl border border-slate-205 text-sm text-slate-700 bg-white focus:ring-2 focus:ring-[#7a1621] focus:outline-none">
                    <option value="">Semua Status</option>
                    @foreach(SkripsiStatus::cases() as $case)
                        <option value="{{ $case->value }}" {{ request('status_filter') == $case->value ? 'selected' : '' }}>
                            {{ $case->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if(request('status_filter'))
            <a href="{{ route('super-admin.override.thesis-center') }}"
                class="px-3 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm hover:bg-slate-50 transition flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">close</span> Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Submissions Table --}}
    <div id="search-results" class="glass-card overflow-hidden">
        <div class="p-5 border-b border-[#7a1621]/10 flex items-center gap-2">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621]">list_alt</span>
                Daftar Skripsi
            </h3>
            <span class="text-xs text-slate-400">({{ $submissions->total() }} total)</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold uppercase text-[#7a1621] bg-[#7a1621]/5 border-b border-[#7a1621]/10">
                    <tr>
                        <th class="px-5 py-3.5">Mahasiswa</th>
                        <th class="px-5 py-3.5">Judul Skripsi</th>
                        <th class="px-5 py-3.5">Pembimbing</th>
                        <th class="px-5 py-3.5 text-center">Bimbingan</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($submissions as $skripsi)
                    @php
                        $status = $skripsi->status;
                        $badgeClass = $colorMap[$status?->color() ?? 'gray'] ?? 'bg-slate-500/10 text-slate-700 border border-slate-500/20';
                    @endphp
                    <tr class="hover:bg-[#7a1621]/5 transition-colors">
                        <td class="px-5 py-3">
                            <p class="font-semibold text-slate-800 text-xs">{{ $skripsi->mahasiswa?->nama ?? 'N/A' }}</p>
                            <p class="text-[10px] text-slate-400">{{ $skripsi->mahasiswa?->nim }}</p>
                        </td>
                        <td class="px-5 py-3 max-w-xs">
                            <p class="text-xs text-slate-700 line-clamp-2">{{ $skripsi->judul ?? '—' }}</p>
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-500">
                            {{ $skripsi->approvedSupervisor?->user?->name ?? '—' }}
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-700">
                                <span class="material-symbols-outlined text-sm text-[#7a1621]">edit_note</span>
                                {{ $skripsi->total_bimbingan ?? 0 }}x
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="px-2 py-1 rounded-lg text-[10px] font-bold border {{ $badgeClass }}">
                                {{ $status?->label() ?? $status }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <button onclick="openSkripsiModal(
                                {{ $skripsi->id }},
                                '{{ addslashes($skripsi->mahasiswa?->nama) }}',
                                '{{ addslashes($skripsi->judul ?? '') }}',
                                '{{ $status?->value }}',
                                '{{ addslashes($status?->label() ?? '') }}',
                                '{{ addslashes($skripsi->admin_note ?? '') }}'
                            )"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#7a1621] hover:bg-[#5e1019] text-white text-xs font-bold rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                Override
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl block mb-2 text-slate-300">menu_book</span>
                            Belum ada data skripsi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $submissions->withQueryString()->links() }}
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- SKRIPSI OVERRIDE MODAL                                                     --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div id="skripsiModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeSkripsiModal()"></div>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative z-10" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-5">
            <div>
                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#7a1621]">menu_book</span>
                    Override Status Skripsi
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Super Admin dapat override ke status apapun (bypass alur normal).</p>
            </div>
            <button onclick="closeSkripsiModal()" class="text-slate-400 hover:text-slate-700">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div class="bg-slate-50 rounded-xl p-3 mb-4 text-sm text-slate-700">
            <p><span class="font-semibold">Mahasiswa:</span> <span id="sk-mahasiswa-name">—</span></p>
            <p class="mt-1"><span class="font-semibold">Judul:</span> <span id="sk-judul" class="text-slate-500 text-xs">—</span></p>
            <p class="mt-1">
                <span class="font-semibold">Status Saat Ini:</span>
                <span id="sk-current-status" class="ml-1 px-2 py-0.5 rounded-lg text-xs font-bold bg-slate-500/10 text-slate-700 border border-slate-500/20">—</span>
            </p>
        </div>

        <form id="skripsiForm" method="POST" action="">
            @csrf

            {{-- Status Override --}}
            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-1.5">
                    Override ke Status <span class="text-red-500">*</span>
                </label>
                <select name="status" required id="sk-new-status"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-205 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621]">
                    @foreach(SkripsiStatus::cases() as $case)
                        <option value="{{ $case->value }}">{{ $case->label() }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1">
                    ⚠ Perubahan status <strong>THESIS_COMPLETED</strong> memungkinkan mahasiswa mendaftar wisuda.
                </p>
            </div>

            {{-- Admin Note --}}
            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-1.5">
                    Catatan Admin (Admin Note)
                    <span class="font-normal text-slate-400 ml-1">— opsional, tampil di halaman mahasiswa</span>
                </label>
                <textarea name="admin_note" id="sk-admin-note" rows="2"
                    placeholder="Catatan yang akan ditampilkan kepada mahasiswa..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-205 text-sm text-slate-700 resize-none focus:outline-none focus:ring-2 focus:ring-[#7a1621] transition"></textarea>
            </div>

            {{-- Override Reason --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-700 mb-1.5">
                    Alasan Override (Internal) <span class="text-red-500">*</span>
                </label>
                <textarea name="override_reason" rows="2"
                    placeholder="Alasan internal yang dicatat di audit trail (min. 10 karakter)..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-205 text-sm text-slate-700 resize-none focus:outline-none focus:ring-2 focus:ring-[#7a1621] transition"
                    required minlength="10"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeSkripsiModal()"
                    class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-50 transition">
                    Batalkan
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-[#7a1621] hover:bg-[#5e1019] text-white rounded-xl text-sm font-bold transition flex items-center justify-center gap-2 shadow-md">
                    <span class="material-symbols-outlined text-sm">check_circle</span>
                    Override Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openSkripsiModal(id, mahasiswaName, judul, statusValue, statusLabel, adminNote) {
    document.getElementById('skripsiForm').action = `{{ url('super-admin/override/skripsi') }}/${id}`;
    document.getElementById('sk-mahasiswa-name').textContent = mahasiswaName;
    document.getElementById('sk-judul').textContent = judul || '—';
    document.getElementById('sk-current-status').textContent = statusLabel;
    document.getElementById('sk-new-status').value = statusValue || '';
    document.getElementById('sk-admin-note').value = adminNote || '';
    document.getElementById('skripsiModal').classList.remove('hidden');
    document.getElementById('skripsiModal').classList.add('flex');
}
function closeSkripsiModal() {
    document.getElementById('skripsiModal').classList.add('hidden');
    document.getElementById('skripsiModal').classList.remove('flex');
}
document.getElementById('skripsiModal').addEventListener('click', function(e) {
    if (e.target === this) closeSkripsiModal();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeSkripsiModal();
});
</script>
@endsection
