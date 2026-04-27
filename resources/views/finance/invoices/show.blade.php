@extends('layouts.finance')

@section('page-title', 'Detail Tagihan #' . $invoice->id)

@section('content')
<div class="px-4 md:px-0 animate-fade-in pb-20">
    {{-- Top Action Bar --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <a href="{{ route('finance.invoices.index') }}" 
               class="group inline-flex items-center gap-2 text-slate-400 hover:text-[#8B1538] transition-colors font-bold text-sm mb-2">
                <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
                <span>Kembali ke Daftar Tagihan</span>
            </a>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                Tagihan #{{ $invoice->id }}
                @php
                    $statusConfig = [
                        'DRAFT' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => 'Draft', 'icon' => 'fa-file-alt'],
                        'PUBLISHED' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'label' => 'Published', 'icon' => 'fa-paper-plane'],
                        'IN_INSTALLMENT' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'label' => 'Dalam Cicilan', 'icon' => 'fa-clock'],
                        'LUNAS' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'label' => 'Lunas', 'icon' => 'fa-check-circle'],
                        'CANCELLED' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'label' => 'Dibatalkan', 'icon' => 'fa-times-circle'],
                    ];
                    $currentStatus = $statusConfig[$invoice->status] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => $invoice->status, 'icon' => 'fa-question-circle'];
                @endphp
                <span class="{{ $currentStatus['bg'] }} {{ $currentStatus['text'] }} text-[10px] font-black uppercase tracking-[0.2em] px-3 py-1.5 rounded-full flex items-center gap-2 border border-current opacity-70">
                    <i class="fas {{ $currentStatus['icon'] }}"></i>
                    {{ $currentStatus['label'] }}
                </span>
            </h1>
        </div>

        @if($invoice->status === 'DRAFT')
            <div class="flex items-center gap-3">
                <form action="{{ route('finance.invoices.publish', $invoice) }}" method="POST" onsubmit="return confirm('Publish tagihan ini?')">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-[#8B1538] hover:bg-[#6D1029] text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-lg shadow-red-900/10 flex items-center gap-2 group">
                        <i class="fas fa-rocket transition-transform group-hover:-translate-y-1"></i>
                        Publish Tagihan
                    </button>
                </form>
                <form action="{{ route('finance.invoices.cancel', $invoice) }}" method="POST" onsubmit="return confirm('Batalkan tagihan ini?')">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-white border border-red-100 text-red-500 hover:bg-red-50 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all flex items-center gap-2 group">
                        <i class="fas fa-ban transition-transform group-hover:rotate-12"></i>
                        Batalkan
                    </button>
                </form>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-8 bg-emerald-50 border border-emerald-100 rounded-[2rem] p-6 text-emerald-800 flex items-center gap-4 animate-shake">
            <div class="size-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                <i class="fas fa-check"></i>
            </div>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column: Student & Invoice Info --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Student Profile --}}
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden p-8">
                <div class="flex items-center gap-6 mb-8">
                    <div class="size-20 rounded-3xl overflow-hidden border-2 border-slate-50 flex-shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($invoice->student->user->name ?? 'Student') }}&background=8B1538&color=fff&bold=true&font-size=0.4" 
                             class="w-full h-full object-cover" alt="Avatar">
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800 tracking-tight">{{ $invoice->student->user->name ?? 'N/A' }}</h2>
                        <p class="text-sm text-slate-400 font-bold uppercase tracking-widest">{{ $invoice->student->nim ?? 'N/A' }}</p>
                        <div class="flex items-center gap-4 mt-2">
                            <span class="text-[10px] font-black uppercase text-[#8B1538] bg-[#8B1538]/5 px-2 py-1 rounded-md">
                                {{ $invoice->student->prodi ?? 'Prodi Belum Diatur' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-slate-50">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Email Utama</p>
                        <p class="text-sm font-bold text-slate-700">{{ $invoice->student->user->email ?? '-' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Angkatan</p>
                        <p class="text-sm font-bold text-slate-700">{{ $invoice->student->angkatan ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Invoice Details Table --}}
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-lg font-black text-slate-800 tracking-tight">Rincian Tagihan</h3>
                    <div class="text-right">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Nominal</p>
                        <p class="text-2xl font-black text-[#8B1538] tracking-tighter">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</p>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Keterangan</th>
                                <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr>
                                <td class="px-8 py-4 text-sm font-bold text-slate-600">Semester / Tahun Ajaran</td>
                                <td class="px-8 py-4 text-sm font-black text-slate-800 text-right">{{ $invoice->semester }} / {{ $invoice->tahun_ajaran }}</td>
                            </tr>
                            <tr>
                                <td class="px-8 py-4 text-sm font-bold text-slate-600">Beban SKS (Ambil / Bayar)</td>
                                <td class="px-8 py-4 text-sm font-black text-slate-800 text-right">{{ $invoice->sks_ambil }} SKS / {{ $invoice->paket_sks_bayar }} SKS</td>
                            </tr>
                            <tr>
                                <td class="px-8 py-4 text-sm font-bold text-slate-600">Izin Skema Cicilan</td>
                                <td class="px-8 py-4 text-right">
                                    <span class="text-[10px] font-black uppercase tracking-[0.1em] px-2 py-1 rounded shadow-sm border {{ $invoice->allow_partial ? 'border-emerald-100 text-emerald-600 bg-emerald-50' : 'border-slate-100 text-slate-400 bg-slate-50' }}">
                                        {{ $invoice->allow_partial ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                            </tr>
                            @if($invoice->notes)
                                <tr>
                                    <td class="px-8 py-4 text-sm font-bold text-slate-600">Catatan Internal</td>
                                    <td class="px-8 py-4 text-sm font-medium text-slate-400 text-right italic">{{ $invoice->notes }}</td>
                                </tr>
                            @endif
                            @if(false){{-- VA Row Hidden --}}
                            @if($invoice->bank_name && $invoice->va_number)
                                <tr class="bg-blue-50/30">
                                    <td class="px-8 py-4 text-sm font-bold text-slate-600">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-university text-blue-500"></i>
                                            Metode Pembayaran VA
                                        </div>
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <div class="inline-flex flex-col items-end gap-1">
                                            <span class="text-[10px] font-black uppercase tracking-widest text-blue-500">{{ $invoice->bank_name }}</span>
                                            <span class="text-sm font-black text-slate-800 tracking-widest font-mono">{{ $invoice->va_number }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            @endif{{-- End VA Row Hidden --}}
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Right Column: Payment History, Installment Info & Timeline --}}
        <div class="lg:col-span-1 space-y-8">
            {{-- Payment History --}}
            @if($invoice->payments->isNotEmpty())
                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden pb-8">
                    <div class="px-8 py-6 border-b border-slate-50">
                        <h3 class="text-lg font-black text-slate-800 tracking-tight">Riwayat Pembayaran</h3>
                    </div>
                    <div class="px-8 mt-6 space-y-4">
                        @foreach($invoice->payments as $payment)
                            <div x-data="{ proofOpen: false }" class="p-5 rounded-2xl border border-slate-50 bg-slate-50/20 group hover:border-[#8B1538]/20 hover:bg-[#8B1538]/5 transition-all">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="size-9 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-[#8B1538] shadow-sm">
                                        <i class="fas fa-receipt text-xs"></i>
                                    </div>
                                    <span class="text-[10px] font-black uppercase px-2 py-1 bg-emerald-100 text-emerald-600 rounded">Verified</span>
                                </div>
                                <p class="text-lg font-black text-slate-800 tracking-tighter">Rp {{ number_format($payment->amount_approved, 0, ',', '.') }}</p>
                                <p class="text-[10px] font-black uppercase text-slate-400 mt-1">{{ $payment->paid_date->format('d M Y H:i') }}</p>

                                {{-- Tombol Lihat Bukti --}}
                                @if($payment->proof && $payment->proof->file_path)
                                    <button @click="proofOpen = true"
                                        class="mt-3 w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl border border-[#8B1538]/20 text-[#8B1538] bg-[#8B1538]/5 hover:bg-[#8B1538]/10 transition text-[11px] font-black uppercase tracking-wider">
                                        <i class="fas {{ $payment->proof->is_pdf ? 'fa-file-pdf' : 'fa-image' }} text-xs"></i>
                                        Lihat Bukti Bayar
                                    </button>

                                    {{-- Modal Bukti --}}
                                    <template x-teleport="body">
                                        <div x-show="proofOpen" x-cloak
                                            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/70 backdrop-blur-md p-4"
                                            @click="proofOpen = false"
                                            @keydown.escape.window="proofOpen = false"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0"
                                            x-transition:enter-end="opacity-100">
                                            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden"
                                                @click.stop>
                                            {{-- Header --}}
                                            <div class="flex items-center justify-between px-6 py-4 bg-[#8B1538] text-white rounded-t-3xl flex-shrink-0">
                                                <div class="flex items-center gap-3">
                                                    <div class="size-9 rounded-xl bg-white/10 flex items-center justify-center">
                                                        <i class="fas {{ $payment->proof->is_pdf ? 'fa-file-pdf' : 'fa-image' }} text-sm"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-black text-sm">Bukti Pembayaran</p>
                                                        <p class="text-[10px] text-white/70 font-bold">Rp {{ number_format($payment->amount_approved, 0, ',', '.') }} &bull; {{ $payment->paid_date->format('d M Y') }}</p>
                                                    </div>
                                                </div>
                                                <button @click="proofOpen = false" class="size-8 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition">
                                                    <i class="fas fa-times text-sm"></i>
                                                </button>
                                            </div>
                                            {{-- Body --}}
                                            <div class="flex-1 overflow-auto p-4 flex items-center justify-center bg-slate-50">
                                                @if($payment->proof->is_pdf)
                                                    <div class="text-center py-10">
                                                        <i class="fas fa-file-pdf text-6xl text-red-400 mb-4 block"></i>
                                                        <p class="text-sm font-bold text-slate-600 mb-4">File PDF tidak dapat ditampilkan langsung.</p>
                                                        <a href="{{ $payment->proof->file_url }}" target="_blank"
                                                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#8B1538] text-white rounded-xl font-black text-xs uppercase tracking-wider hover:bg-[#6D1029] transition">
                                                            <i class="fas fa-external-link-alt"></i> Buka / Download PDF
                                                        </a>
                                                    </div>
                                                @else
                                                    <img src="{{ $payment->proof->file_url }}" alt="Bukti Bayar"
                                                        class="max-w-full max-h-[65vh] rounded-2xl shadow-lg object-contain">
                                                @endif
                                            </div>
                                            {{-- Footer --}}
                                            <div class="px-6 py-3 border-t border-slate-100 flex justify-between items-center flex-shrink-0">
                                                <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">
                                                    {{ $payment->proof->is_pdf ? 'Dokumen PDF' : 'Gambar' }} &bull; Upload: {{ $payment->proof->created_at->format('d M Y') }}
                                                </span>
                                                <a href="{{ $payment->proof->file_url }}" target="_blank"
                                                    class="flex items-center gap-1.5 px-3 py-1.5 border border-slate-200 rounded-xl text-[10px] font-black text-slate-600 hover:bg-slate-50 transition uppercase tracking-wider">
                                                    <i class="fas fa-download text-[10px]"></i> Unduh
                                                </a>
                                            </div>
                                        </div>
                                    </template>
                                @else
                                    <div class="mt-3 w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl border border-slate-100 text-slate-300 text-[11px] font-black uppercase tracking-wider cursor-not-allowed">
                                        <i class="fas fa-image text-xs"></i> Tidak ada bukti
                                    </div>
                                @endif

                                @if($payment->installment)
                                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center gap-2">
                                        <i class="fas fa-list-ol text-[10px] text-slate-300"></i>
                                        <span class="text-[10px] font-black uppercase tracking-widest text-[#8B1538]">Cicilan Ke-{{ $payment->installment->installment_no ?? '-' }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Installment Request Status Card --}}
            @if($invoice->installmentRequest)
                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden p-8">
                    <h3 class="text-lg font-black text-slate-800 tracking-tight mb-6 flex items-center gap-2">
                        <i class="fas fa-hand-holding-usd text-[#8B1538]"></i>
                        Pengajuan Cicilan
                    </h3>

                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100/50">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Status</span>
                            @php
                                $reqStatus = [
                                    'SUBMITTED' => ['text' => 'text-amber-600', 'bg' => 'bg-amber-100', 'label' => 'Pending Review'],
                                    'APPROVED' => ['text' => 'text-emerald-600', 'bg' => 'bg-emerald-100', 'label' => 'Approved'],
                                    'REJECTED' => ['text' => 'text-red-600', 'bg' => 'bg-red-100', 'label' => 'Rejected'],
                                ];
                                $s = $reqStatus[$invoice->installmentRequest->status] ?? ['text' => 'text-slate-400', 'bg' => 'bg-slate-100', 'label' => $invoice->installmentRequest->status];
                            @endphp
                            <span class="{{ $s['bg'] }} {{ $s['text'] }} text-[10px] font-black uppercase px-2 py-1 rounded">{{ $s['label'] }}</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 border border-slate-100 rounded-2xl text-center">
                                <p class="text-[10px] font-black uppercase text-slate-400">Diminta</p>
                                <p class="text-xl font-black text-slate-800 tracking-tight">{{ $invoice->installmentRequest->requested_terms }}x</p>
                            </div>
                            @if($invoice->installmentRequest->approved_terms)
                                <div class="p-4 border border-emerald-100 bg-emerald-50/20 rounded-2xl text-center">
                                    <p class="text-[10px] font-black uppercase text-emerald-600">Disetujui</p>
                                    <p class="text-xl font-black text-emerald-600 tracking-tight">{{ $invoice->installmentRequest->approved_terms }}x</p>
                                </div>
                            @endif
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl italic text-xs text-slate-500 font-medium">
                            "{{ $invoice->installmentRequest->alasan }}"
                        </div>
                    </div>
                </div>
            @endif

            {{-- Installment Schedule --}}
            @if($invoice->installments->isNotEmpty())
                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden" 
                     x-data="{ 
                        page: 0, 
                        perPage: 3, 
                        total: {{ $invoice->installments->count() }},
                        get maxPage() { return Math.ceil(this.total / this.perPage) - 1 }
                     }">
                    <div class="px-8 py-6 border-b border-slate-50 bg-[#8B1538]/5 flex items-center justify-between">
                        <h3 class="text-lg font-black text-[#8B1538] tracking-tight">Jadwal Cicilan</h3>
                        
                        {{-- Pagination Controls --}}
                        <template x-if="total > perPage">
                            <div class="flex items-center gap-2">
                                <button @click="page = Math.max(0, page - 1)" 
                                        :disabled="page === 0"
                                        :class="page === 0 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-[#8B1538] hover:text-white'"
                                        class="size-8 rounded-xl border border-[#8B1538]/20 flex items-center justify-center text-[#8B1538] transition-all active:scale-90">
                                    <i class="fas fa-chevron-left text-[10px]"></i>
                                </button>
                                <div class="px-3 py-1 bg-[#8B1538]/10 rounded-lg">
                                    <span class="text-[10px] font-black text-[#8B1538]" x-text="(page + 1) + ' / ' + (maxPage + 1)"></span>
                                </div>
                                <button @click="page = Math.min(maxPage, page + 1)" 
                                        :disabled="page === maxPage"
                                        :class="page === maxPage ? 'opacity-30 cursor-not-allowed' : 'hover:bg-[#8B1538] hover:text-white'"
                                        class="size-8 rounded-xl border border-[#8B1538]/20 flex items-center justify-center text-[#8B1538] transition-all active:scale-90">
                                    <i class="fas fa-chevron-right text-[10px]"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                    <div class="p-8 space-y-6">
                        @foreach($invoice->installments as $index => $installment)
                            <div class="relative pl-8 pb-6 last:pb-0 border-l-2 border-slate-100 last:border-l-0"
                                 x-show="Math.floor({{ $index }} / perPage) === page"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-x-4"
                                 x-transition:enter-end="opacity-100 translate-x-0">
                                <div class="absolute left-[-9px] top-0 size-4 rounded-full border-2 border-white ring-2 {{ $installment->status === 'PAID' ? 'ring-emerald-400 bg-emerald-400' : ($installment->status === 'WAITING_VERIFICATION' ? 'ring-amber-400 bg-amber-400 text-white' : 'ring-slate-200 bg-white shadow-sm') }} flex items-center justify-center">
                                    @if($installment->status === 'PAID')
                                        <i class="fas fa-check text-[8px] text-white"></i>
                                    @elseif($installment->status === 'WAITING_VERIFICATION')
                                        <i class="fas fa-hourglass-half text-[6px]"></i>
                                    @endif
                                </div>
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-black text-slate-800">Cicilan {{ $installment->installment_no }}</p>
                                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $installment->due_date->format('d M Y') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-black {{ $installment->status === 'PAID' ? 'text-emerald-600' : 'text-slate-800' }}">Rp {{ number_format($installment->amount, 0, ',', '.') }}</p>
                                        @php
                                            $instStatus = [
                                                'PENDING' => ['label' => 'Belum Lunas', 'class' => 'text-slate-400'],
                                                'WAITING_VERIFICATION' => ['label' => 'Menunggu Verif', 'class' => 'text-amber-500'],
                                                'PAID' => ['label' => 'Lunas', 'class' => 'text-emerald-500'],
                                            ];
                                            $currInst = $instStatus[$installment->status] ?? ['label' => $installment->status, 'class' => 'text-slate-400'];
                                        @endphp
                                        <p class="text-[9px] font-black uppercase tracking-widest {{ $currInst['class'] }}">{{ $currInst['label'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Audit Logs / Creation Info --}}
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden p-8">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="size-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <i class="fas fa-history text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black uppercase text-slate-400">Dibuat Pada</p>
                            <p class="text-xs font-bold text-slate-700">{{ $invoice->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @if($invoice->published_at)
                    <div class="flex items-center gap-3">
                        <div class="size-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-400">
                            <i class="fas fa-paper-plane text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black uppercase text-slate-400">Dipublish Pada</p>
                            <p class="text-xs font-bold text-slate-700">{{ $invoice->published_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .animate-shake { animation: shake 0.4s ease-in-out; }
</style>
@endsection
