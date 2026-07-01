@extends('layouts.super-admin')

@section('title', 'Financial Override Center')
@section('page-title', 'Financial Override Center')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621]">payments</span>
                Financial Override Center
            </h2>
            <p class="text-sm text-slate-500 mt-0.5">Override status tagihan, invoice, dan pembayaran mahasiswa</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="glass-card p-4 hover:scale-[1.02] transition-transform duration-200 cursor-pointer">
            <p class="text-xs font-bold uppercase text-slate-400 mb-1">Draft</p>
            <p class="text-2xl font-black text-slate-700">{{ $stats['draft'] }}</p>
        </div>
        <div class="glass-card p-4 hover:scale-[1.02] transition-transform duration-200 cursor-pointer">
            <p class="text-xs font-bold uppercase text-slate-400 mb-1">Tagihan Baru</p>
            <p class="text-2xl font-black text-sky-700">{{ $stats['published'] }}</p>
        </div>
        <div class="glass-card p-4 hover:scale-[1.02] transition-transform duration-200 cursor-pointer">
            <p class="text-xs font-bold uppercase text-slate-400 mb-1">Cicilan</p>
            <p class="text-2xl font-black text-amber-700">{{ $stats['in_installment'] }}</p>
        </div>
        <div class="glass-card p-4 hover:scale-[1.02] transition-transform duration-200 cursor-pointer">
            <p class="text-xs font-bold uppercase text-slate-400 mb-1">Lunas</p>
            <p class="text-2xl font-black text-emerald-700">{{ $stats['lunas'] }}</p>
        </div>
        <div class="glass-card p-4 hover:scale-[1.02] transition-transform duration-200 cursor-pointer">
            <p class="text-xs font-bold uppercase text-slate-400 mb-1">Total Tunggakan</p>
            <p class="text-lg font-black text-[#7a1621]">Rp {{ number_format($stats['total_tagihan'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="glass-card p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase">Cari Mahasiswa</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="NIM atau nama mahasiswa..."
                        class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-205 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621] transition">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase">Status</label>
                <select name="status_filter"
                    class="px-3 py-2 rounded-xl border border-slate-205 text-sm text-slate-700 bg-white focus:ring-2 focus:ring-[#7a1621] focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="DRAFT" {{ request('status_filter') == 'DRAFT' ? 'selected' : '' }}>Draft</option>
                    <option value="PUBLISHED" {{ request('status_filter') == 'PUBLISHED' ? 'selected' : '' }}>Tagihan Baru</option>
                    <option value="IN_INSTALLMENT" {{ request('status_filter') == 'IN_INSTALLMENT' ? 'selected' : '' }}>Cicilan</option>
                    <option value="LUNAS" {{ request('status_filter') == 'LUNAS' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>
            <button type="submit"
                class="px-4 py-2 bg-[#7a1621] hover:bg-[#5e1019] text-white rounded-xl text-sm font-semibold shadow-sm transition">
                Filter
            </button>
            @if(request()->hasAny(['search','status_filter']))
            <a href="{{ route('super-admin.override.financial-center') }}"
                class="px-3 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm hover:bg-slate-50 transition">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Invoice Table --}}
    <div id="search-results" class="glass-card overflow-hidden">
        <div class="p-5 border-b border-[#7a1621]/10 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621]">receipt_long</span>
                Daftar Invoice
                <span class="text-xs font-normal text-slate-400">({{ $invoices->total() }} total)</span>
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold uppercase text-[#7a1621] bg-[#7a1621]/5 border-b border-[#7a1621]/10">
                    <tr>
                        <th class="px-5 py-3.5">Invoice</th>
                        <th class="px-5 py-3.5">Mahasiswa</th>
                        <th class="px-5 py-3.5">Semester/T.A.</th>
                        <th class="px-5 py-3.5 text-right">Total Tagihan</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-center">SKS</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($invoices as $invoice)
                    @php
                        $statusConfig = match($invoice->status) {
                            'LUNAS'          => ['bg-emerald-500/10 text-emerald-700 border-emerald-500/20', 'Lunas'],
                            'PUBLISHED'      => ['bg-sky-500/10 text-sky-700 border-sky-500/20', 'Tagihan Baru'],
                            'IN_INSTALLMENT' => ['bg-amber-500/10 text-amber-700 border-amber-500/20', 'Cicilan'],
                            'DRAFT'          => ['bg-slate-500/10 text-slate-600 border-slate-500/20', 'Draft'],
                            default          => ['bg-slate-500/10 text-slate-500 border-slate-500/20', $invoice->status],
                        };
                    @endphp
                    <tr class="hover:bg-[#7a1621]/5 transition-colors">
                        <td class="px-5 py-3">
                            <p class="font-bold text-slate-800 text-xs">#{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-[10px] text-slate-400">{{ $invoice->created_at?->format('d/m/Y') }}</p>
                        </td>
                        <td class="px-5 py-3">
                            <div>
                                <p class="font-semibold text-slate-800 text-xs">{{ $invoice->student?->nama ?? 'N/A' }}</p>
                                <p class="text-[10px] text-slate-400">{{ $invoice->student?->nim }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-500">
                            Sem {{ $invoice->semester }} / {{ $invoice->tahun_ajaran ?? '-' }}
                        </td>
                        <td class="px-5 py-3 text-right font-bold text-slate-800 text-sm">
                            Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold border {{ $statusConfig[0] }}">
                                {{ $statusConfig[1] }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center text-xs text-slate-500">
                            {{ $invoice->sks_ambil ?? '-' }} SKS
                        </td>
                        <td class="px-5 py-3 text-center">
                            @if($invoice->status !== 'LUNAS')
                            <button onclick="openInvoiceModal({{ $invoice->id }}, '{{ addslashes($invoice->student?->nama) }}', '{{ addslashes($invoice->student?->nim) }}', {{ $invoice->total_tagihan }}, '{{ $invoice->status }}', '{{ $statusConfig[1] }}')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#7a1621] hover:bg-[#5e1019] text-white text-xs font-bold rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                Override
                            </button>
                            @else
                            <span class="text-[10px] text-slate-400 font-semibold">Sudah Lunas</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl block mb-2 text-slate-300">payments</span>
                            Tidak ada invoice ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $invoices->withQueryString()->links() }}
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- INVOICE OVERRIDE MODAL                                                     --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div id="invoiceModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeInvoiceModal()"></div>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative z-10" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-5">
            <div>
                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#7a1621]">payments</span>
                    Override Invoice
                </h3>
                <p class="text-xs text-red-500 font-semibold mt-0.5">
                    ⚠ Override ke LUNAS akan membuat payment record secara otomatis.
                </p>
            </div>
            <button onclick="closeInvoiceModal()" class="text-slate-400 hover:text-slate-700">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        {{-- Invoice Info --}}
        <div class="bg-slate-50 rounded-xl p-4 mb-5">
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div>
                    <p class="text-xs text-slate-400">Mahasiswa</p>
                    <p class="font-bold text-slate-800" id="inv-mahasiswa-name">—</p>
                    <p class="text-xs text-slate-500" id="inv-mahasiswa-nim">—</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Total Tagihan</p>
                    <p class="font-black text-[#7a1621] text-lg" id="inv-total">—</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Status Saat Ini</p>
                    <p class="font-bold text-slate-700" id="inv-status-current">—</p>
                </div>
            </div>
        </div>

        <form id="invoiceForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Override ke Status <span class="text-red-500">*</span></label>
                <select name="status" id="inv-new-status" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-205 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621]">
                    <option value="DRAFT">Draft</option>
                    <option value="PUBLISHED">Tagihan Baru (Published)</option>
                    <option value="IN_INSTALLMENT">Dalam Cicilan</option>
                    <option value="LUNAS">✓ LUNAS (Bayar Penuh)</option>
                </select>
            </div>

            {{-- LUNAS warning --}}
            <div id="lunas-warning" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-xl">
                <p class="text-xs text-red-700 font-semibold flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">warning</span>
                    Override ke LUNAS akan membuat payment record manual sebesar total tagihan. Ini bukan pembayaran nyata dari mahasiswa.
                </p>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Alasan Override <span class="text-red-500">*</span></label>
                <textarea name="override_reason" rows="3"
                    placeholder="Jelaskan alasan override invoice ini (min. 10 karakter)..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-205 text-sm text-slate-700 resize-none focus:outline-none focus:ring-2 focus:ring-[#7a1621]"
                    required minlength="10"></textarea>
            </div>

            <div class="flex items-start gap-2.5 mb-5 p-3 bg-[#7a1621]/5 border border-[#7a1621]/20 rounded-xl">
                <input type="checkbox" id="inv-confirm" required
                    class="w-4 h-4 mt-0.5 rounded border-[#7a1621]/30 text-[#7a1621] focus:ring-[#7a1621]">
                <label for="inv-confirm" class="text-xs text-[#7a1621] font-semibold leading-tight">
                    Saya memahami bahwa override invoice ini akan dicatat dalam audit trail, tidak dapat dihapus, dan mungkin mempengaruhi laporan keuangan.
                </label>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeInvoiceModal()"
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

<script>
function openInvoiceModal(invoiceId, mahasiswaName, nim, totalTagihan, status, statusLabel) {
    document.getElementById('invoiceForm').action = `{{ url('super-admin/override/invoice') }}/${invoiceId}`;
    document.getElementById('inv-mahasiswa-name').textContent = mahasiswaName;
    document.getElementById('inv-mahasiswa-nim').textContent = nim;
    document.getElementById('inv-total').textContent = 'Rp ' + totalTagihan.toLocaleString('id-ID');
    document.getElementById('inv-status-current').textContent = statusLabel;
    document.getElementById('inv-new-status').value = status;
    document.getElementById('inv-confirm').checked = false;
    document.getElementById('lunas-warning').classList.add('hidden');

    const modal = document.getElementById('invoiceModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeInvoiceModal() {
    document.getElementById('invoiceModal').classList.add('hidden');
    document.getElementById('invoiceModal').classList.remove('flex');
}

document.getElementById('inv-new-status')?.addEventListener('change', function() {
    const warn = document.getElementById('lunas-warning');
    if (this.value === 'LUNAS') {
        warn.classList.remove('hidden');
    } else {
        warn.classList.add('hidden');
    }
});

document.getElementById('invoiceModal').addEventListener('click', function(e) {
    if (e.target === this) closeInvoiceModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeInvoiceModal();
});
</script>
@endsection
