@extends('layouts.super-admin')

@section('title', 'Graduation Override Center')
@section('page-title', 'Graduation Override Center')

@section('content')
@php
    use App\Domain\Wisuda\Enums\WisudaRegistrationStatus;

    $colorMap = [
        'yellow' => 'bg-amber-500/10 text-amber-700 border-amber-500/20',
        'green'  => 'bg-emerald-500/10 text-emerald-700 border-emerald-500/20',
        'red'    => 'bg-rose-500/10 text-rose-700 border-rose-500/20',
        'indigo' => 'bg-indigo-500/10 text-indigo-700 border-indigo-500/20',
    ];
@endphp

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621] font-bold">workspace_premium</span>
                Graduation Override Center
            </h2>
            <p class="text-sm text-slate-500 mt-0.5">Override status, batch wisuda, dan verifikasi berkas kelulusan mahasiswa</p>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="glass-card p-4 flex items-center gap-3 hover:scale-[1.02] transition-transform duration-200 cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-amber-600 text-lg">pending_actions</span>
            </div>
            <div>
                <p class="text-xl font-black text-slate-800">{{ $stats['pending'] ?? 0 }}</p>
                <p class="text-xs text-slate-500">Menunggu Verifikasi</p>
            </div>
        </div>
        <div class="glass-card p-4 flex items-center gap-3 hover:scale-[1.02] transition-transform duration-200 cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-emerald-600 text-lg">check_circle</span>
            </div>
            <div>
                <p class="text-xl font-black text-emerald-700">{{ $stats['approved'] ?? 0 }}</p>
                <p class="text-xs text-slate-500">Disetujui</p>
            </div>
        </div>
        <div class="glass-card p-4 flex items-center gap-3 hover:scale-[1.02] transition-transform duration-200 cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-indigo-650 text-lg">event_available</span>
            </div>
            <div>
                <p class="text-xl font-black text-indigo-700">{{ $stats['scheduled'] ?? 0 }}</p>
                <p class="text-xs text-slate-500">Terjadwal Wisuda</p>
            </div>
        </div>
        <div class="glass-card p-4 flex items-center gap-3 hover:scale-[1.02] transition-transform duration-200 cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-rose-600 text-lg">cancel</span>
            </div>
            <div>
                <p class="text-xl font-black text-rose-700">{{ $stats['rejected'] ?? 0 }}</p>
                <p class="text-xs text-slate-500">Ditolak</p>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="glass-card p-4">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase">Filter Status</label>
                <select name="status_filter"
                    class="px-3 py-2 rounded-xl border border-slate-205 text-sm text-slate-700 bg-white focus:ring-2 focus:ring-[#7a1621] focus:outline-none">
                    <option value="">Semua Status</option>
                    @foreach(WisudaRegistrationStatus::cases() as $case)
                        <option value="{{ $case->value }}" {{ request('status_filter') == $case->value ? 'selected' : '' }}>
                            {{ $case->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase">Filter Batch</label>
                <select name="batch_filter"
                    class="px-3 py-2 rounded-xl border border-slate-205 text-sm text-slate-700 bg-white focus:ring-2 focus:ring-[#7a1621] focus:outline-none">
                    <option value="">Semua Batch</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ request('batch_filter') == $batch->id ? 'selected' : '' }}>
                            {{ $batch->nama_batch }} ({{ $batch->tanggal->format('d M Y') }})
                        </option>
                    @endforeach
                </select>
            </div>

            @if(request('status_filter') || request('batch_filter'))
            <a href="{{ route('super-admin.override.graduation') }}"
                class="px-3 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm hover:bg-slate-50 transition flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">close</span> Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Registrations Table --}}
    <div id="search-results" class="glass-card overflow-hidden">
        <div class="p-5 border-b border-[#7a1621]/10 flex items-center gap-2">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621]">list_alt</span>
                Daftar Pendaftar Wisuda
            </h3>
            <span class="text-xs text-slate-400">({{ $registrations->total() }} total)</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold uppercase text-[#7a1621] bg-[#7a1621]/5 border-b border-[#7a1621]/10">
                    <tr>
                        <th class="px-5 py-3.5">Mahasiswa</th>
                        <th class="px-5 py-3.5">Batch Wisuda</th>
                        <th class="px-5 py-3.5">Kontak</th>
                        <th class="px-5 py-3.5">Berkas Persyaratan</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($registrations as $reg)
                    @php
                        $status = $reg->status;
                        $badgeClass = $colorMap[$status?->color() ?? 'yellow'] ?? 'bg-amber-500/10 text-amber-700 border border-amber-500/20';
                    @endphp
                    <tr class="hover:bg-[#7a1621]/5 transition-colors">
                        <td class="px-5 py-3.5">
                            <p class="font-semibold text-slate-800 text-xs">{{ $reg->mahasiswa?->nama ?? 'N/A' }}</p>
                            <p class="text-[10px] text-slate-400">NIM: {{ $reg->mahasiswa?->nim }}</p>
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="text-xs text-slate-700 font-semibold">{{ $reg->batch?->nama_batch ?? '—' }}</p>
                            @if($reg->batch?->tanggal)
                                <p class="text-[10px] text-slate-400">{{ $reg->batch->tanggal->format('d M Y') }} | {{ $reg->batch->lokasi }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-500">
                            <p>{{ $reg->email_aktif ?? '—' }}</p>
                            <p class="text-[10px]">{{ $reg->no_hp ?? '—' }}</p>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex flex-wrap max-w-xs">
                                @forelse($reg->documents as $doc)
                                    <a href="{{ $doc->url }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-[10px] text-slate-600 hover:text-[#7a1621] border border-slate-200 rounded px-1.5 py-0.5 mr-1 mb-1 bg-white hover:border-[#7a1621] transition-colors"
                                        title="{{ $doc->original_name }} ({{ $doc->file_size_human }})">
                                        <span class="material-symbols-outlined text-[12px] text-slate-400">description</span>
                                        {{ $doc->file_type instanceof \BackedEnum ? $doc->file_type->label() : $doc->file_type }}
                                    </a>
                                @empty
                                    <span class="text-xs text-slate-400 italic">Belum mengunggah berkas</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold border {{ $badgeClass }}">
                                {{ $status instanceof \BackedEnum ? $status->label() : $status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <button onclick="openGraduationModal(
                                {{ $reg->id }},
                                '{{ addslashes($reg->mahasiswa?->nama ?? 'N/A') }}',
                                '{{ $reg->wisuda_batch_id }}',
                                '{{ $status instanceof \BackedEnum ? $status->value : $status }}',
                                '{{ $status instanceof \BackedEnum ? $status->label() : $status }}',
                                '{{ addslashes($reg->rejection_note ?? '') }}'
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
                            <span class="material-symbols-outlined text-4xl block mb-2 text-slate-300">workspace_premium</span>
                            Belum ada pendaftaran wisuda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $registrations->withQueryString()->links() }}
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- GRADUATION OVERRIDE MODAL                                                  --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div id="graduationModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeGraduationModal()"></div>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative z-10" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-5">
            <div>
                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#7a1621] font-bold">workspace_premium</span>
                    Override Pendaftaran Wisuda
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Super Admin dapat override status dan batch wisuda mahasiswa secara langsung.</p>
            </div>
            <button onclick="closeGraduationModal()" class="text-slate-400 hover:text-slate-700">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div class="bg-slate-50 rounded-xl p-3 mb-4 text-sm text-slate-700">
            <p><span class="font-semibold">Mahasiswa:</span> <span id="gr-mahasiswa-name">—</span></p>
            <p class="mt-1">
                <span class="font-semibold">Status Saat Ini:</span>
                <span id="gr-current-status" class="ml-1 px-2 py-0.5 rounded-lg text-xs font-bold bg-slate-500/10 text-slate-700 border border-slate-500/20">—</span>
            </p>
        </div>

        <form id="graduationForm" method="POST" action="">
            @csrf

            {{-- Status Override --}}
            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-1.5">
                    Status Wisuda <span class="text-red-500">*</span>
                </label>
                <select name="status" required id="gr-status"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-205 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621]">
                    @foreach(WisudaRegistrationStatus::cases() as $case)
                        <option value="{{ $case->value }}">{{ $case->label() }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Batch Override --}}
            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-1.5">
                    Batch Wisuda <span class="text-red-500">*</span>
                </label>
                <select name="wisuda_batch_id" required id="gr-batch"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-205 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621]">
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}">{{ $batch->nama_batch }} ({{ $batch->tanggal->format('d M Y') }})</option>
                    @endforeach
                </select>
            </div>

            {{-- Rejection / Admin Note --}}
            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-1.5">
                    Catatan Penolakan / Catatan Wisuda
                    <span class="font-normal text-slate-400 ml-1">— opsional, disimpan ke data pendaftaran</span>
                </label>
                <textarea name="rejection_note" id="gr-rejection-note" rows="2"
                    placeholder="Catatan penolakan jika ditolak, atau catatan administratif..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-205 text-sm text-slate-700 resize-none focus:outline-none focus:ring-2 focus:ring-[#7a1621] transition"></textarea>
            </div>

            {{-- Override Reason --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-700 mb-1.5">
                    Alasan Override (Internal Audit) <span class="text-red-500">*</span>
                </label>
                <textarea name="override_reason" rows="2"
                    placeholder="Alasan melakukan override ini yang dicatat di audit trail (min. 10 karakter)..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-205 text-sm text-slate-700 resize-none focus:outline-none focus:ring-2 focus:ring-[#7a1621] transition"
                    required minlength="10"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeGraduationModal()"
                    class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-50 transition">
                    Batalkan
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-[#7a1621] hover:bg-[#5e1019] text-white rounded-xl text-sm font-bold transition flex items-center justify-center gap-2 shadow-md">
                    <span class="material-symbols-outlined text-sm">check_circle</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openGraduationModal(id, mahasiswaName, batchId, statusValue, statusLabel, rejectionNote) {
    document.getElementById('graduationForm').action = `{{ url('super-admin/override/wisuda') }}/${id}`;
    document.getElementById('gr-mahasiswa-name').textContent = mahasiswaName;
    document.getElementById('gr-current-status').textContent = statusLabel;
    document.getElementById('gr-status').value = statusValue || 'pending';
    document.getElementById('gr-batch').value = batchId || '';
    document.getElementById('gr-rejection-note').value = rejectionNote || '';
    
    document.getElementById('graduationModal').classList.remove('hidden');
    document.getElementById('graduationModal').classList.add('flex');
}

function closeGraduationModal() {
    document.getElementById('graduationModal').classList.add('hidden');
    document.getElementById('graduationModal').classList.remove('flex');
}

document.getElementById('graduationModal').addEventListener('click', function(e) {
    if (e.target === this) closeGraduationModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeGraduationModal();
});
</script>
@endsection
