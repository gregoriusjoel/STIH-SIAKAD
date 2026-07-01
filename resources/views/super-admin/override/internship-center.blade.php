@extends('layouts.super-admin')

@section('title', 'Internship Override Center')
@section('page-title', 'Internship Override Center')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-[#7a1621]">work</span>
            Internship Override Center
        </h2>
        <p class="text-sm text-slate-500 mt-0.5">Override status, nilai, dan pembimbing magang mahasiswa</p>
    </div>

    {{-- Internship Table --}}
    <div class="glass-card overflow-hidden">
        <div class="p-5 border-b border-[#7a1621]/10 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621]">work_history</span>
                Daftar Pengajuan Magang
                <span class="text-xs font-normal text-slate-400">({{ $internships->total() }} total)</span>
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold uppercase text-[#7a1621] bg-[#7a1621]/5 border-b border-[#7a1621]/10">
                    <tr>
                        <th class="px-5 py-3.5">Mahasiswa</th>
                        <th class="px-5 py-3.5">Instansi / Perusahaan</th>
                        <th class="px-5 py-3.5">Jenis</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-center">Dibuat</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($internships as $internship)
                    @php
                        $statusLabel = \App\Models\Internship::STATUS_LABELS[$internship->status] ?? $internship->status;
                        $statusColor = \App\Models\Internship::STATUS_COLORS[$internship->status] ?? 'gray';
                        $colorMap = [
                            'gray'    => 'bg-slate-500/10 text-slate-700 border border-slate-500/20',
                            'blue'    => 'bg-blue-500/10 text-blue-700 border border-blue-500/20',
                            'yellow'  => 'bg-amber-500/10 text-amber-700 border border-amber-500/20',
                            'indigo'  => 'bg-indigo-500/10 text-indigo-700 border border-indigo-500/20',
                            'orange'  => 'bg-orange-500/10 text-orange-700 border border-orange-500/20',
                            'green'   => 'bg-emerald-500/10 text-emerald-700 border border-emerald-500/20',
                            'sky'     => 'bg-sky-500/10 text-sky-700 border border-sky-500/20',
                            'red'     => 'bg-rose-500/10 text-rose-700 border border-rose-500/20',
                            'teal'    => 'bg-teal-500/10 text-teal-700 border border-teal-500/20',
                            'cyan'    => 'bg-cyan-500/10 text-cyan-700 border border-cyan-500/20',
                            'emerald' => 'bg-emerald-500/10 text-emerald-700 border border-emerald-500/20',
                            'lime'    => 'bg-lime-500/10 text-lime-700 border border-lime-500/20',
                            'violet'  => 'bg-violet-500/10 text-violet-700 border border-violet-500/20',
                            'slate'   => 'bg-slate-500/10 text-slate-700 border border-slate-500/20',
                        ];
                        $colorClass = $colorMap[$statusColor] ?? 'bg-slate-500/10 text-slate-700 border border-slate-500/20';
                    @endphp
                    <tr class="hover:bg-[#7a1621]/5 transition-colors">
                        <td class="px-5 py-3">
                            <div>
                                <p class="font-semibold text-slate-800 text-xs">{{ $internship->mahasiswa?->nama ?? 'N/A' }}</p>
                                <p class="text-[10px] text-slate-400">{{ $internship->mahasiswa?->nim }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-700">
                            {{ $internship->instansi ?? '-' }}
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-500">
                            {{ $internship->internshipType?->name ?? ($internship->jenis_magang ?? '-') }}
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold border {{ $colorClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center text-xs text-slate-400">
                            {{ $internship->created_at?->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-3 text-center">
                            <button onclick="openInternshipModal({{ $internship->id }}, '{{ addslashes($internship->mahasiswa?->nama) }}', '{{ addslashes($internship->instansi ?? '') }}', '{{ $internship->status }}', '{{ addslashes($statusLabel) }}')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#7a1621] hover:bg-[#5e1019] text-white text-xs font-bold rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                Override
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl block mb-2 text-slate-300">work</span>
                            Belum ada data magang.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $internships->withQueryString()->links() }}
        </div>
    </div>
</div>

{{-- INTERNSHIP OVERRIDE MODAL --}}
<div id="internshipModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeInternshipModal()"></div>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative z-10" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-5">
            <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621]">work</span>
                Override Status Magang
            </h3>
            <button onclick="closeInternshipModal()" class="text-slate-400 hover:text-slate-700">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div class="bg-slate-50 rounded-xl p-3 mb-4 text-sm text-slate-700">
            <p><span class="font-semibold">Mahasiswa:</span> <span id="int-mahasiswa-name">—</span></p>
            <p><span class="font-semibold">Instansi:</span> <span id="int-instansi">—</span></p>
            <p><span class="font-semibold">Status Saat Ini:</span> <span id="int-current-status" class="font-bold">—</span></p>
        </div>

        <form id="internshipForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Status Baru <span class="text-red-500">*</span></label>
                <select name="status" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-205 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621]">
                    @foreach(\App\Models\Internship::STATUS_LABELS as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1">Super Admin dapat override ke status manapun tanpa melalui alur normal.</p>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Alasan Override <span class="text-red-500">*</span></label>
                <textarea name="override_reason" rows="3"
                    placeholder="Jelaskan alasan override status magang ini..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-205 text-sm text-slate-700 resize-none focus:outline-none focus:ring-2 focus:ring-[#7a1621] transition"
                    required minlength="10"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeInternshipModal()"
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
function openInternshipModal(id, mahasiswaName, instansi, status, statusLabel) {
    document.getElementById('internshipForm').action = `{{ url('super-admin/override/internship') }}/${id}`;
    document.getElementById('int-mahasiswa-name').textContent = mahasiswaName;
    document.getElementById('int-instansi').textContent = instansi || '-';
    document.getElementById('int-current-status').textContent = statusLabel;
    document.getElementById('internshipModal').classList.remove('hidden');
    document.getElementById('internshipModal').classList.add('flex');
}
function closeInternshipModal() {
    document.getElementById('internshipModal').classList.add('hidden');
    document.getElementById('internshipModal').classList.remove('flex');
}
document.getElementById('internshipModal').addEventListener('click', function(e) {
    if (e.target === this) closeInternshipModal();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeInternshipModal();
});
</script>
@endsection
