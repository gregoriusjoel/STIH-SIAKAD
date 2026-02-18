@extends('layouts.finance')

@section('page-title', 'Review Pengajuan Cicilan')

@section('content')
<div class="px-4 md:px-0 animate-fade-in pb-20">
    {{-- Top Navigation --}}
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('finance.installment-requests.index') }}" 
           class="group inline-flex items-center gap-2 text-slate-400 hover:text-[#8B1538] transition-colors font-bold text-sm">
            <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
            <span>Kembali ke Daftar Pengajuan</span>
        </a>
        
        @php
            $statusClasses = [
                'SUBMITTED' => 'bg-amber-50 text-amber-600 border-amber-100',
                'APPROVED' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                'REJECTED' => 'bg-red-50 text-red-600 border-red-100',
            ];
            $statusLabels = [
                'SUBMITTED' => 'Menunggu Review',
                'APPROVED' => 'Disetujui',
                'REJECTED' => 'Ditolak',
            ];
        @endphp
        <span class="px-4 py-1.5 rounded-full border {{ $statusClasses[$installmentRequest->status] ?? 'bg-slate-50 text-slate-400 border-slate-100' }} text-[10px] font-black uppercase tracking-widest shadow-sm">
            {{ $statusLabels[$installmentRequest->status] ?? $installmentRequest->status }}
        </span>
    </div>

    @if(session('error'))
        <div class="mb-8 bg-red-50 border border-red-100 rounded-3xl p-6 text-red-800 flex items-center gap-4 animate-shake">
            <div class="size-10 rounded-xl bg-red-100 flex items-center justify-center text-red-600 shrink-0">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <p class="font-bold text-sm">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column: Request Profile --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Student & Invoice Context --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                    <div class="flex items-center gap-5">
                        <div class="size-16 rounded-2xl bg-[#8B1538]/5 border border-[#8B1538]/10 flex items-center justify-center text-[#8B1538] text-2xl shadow-inner">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-800 tracking-tight">{{ $installmentRequest->student->user->name ?? 'N/A' }}</h2>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $installmentRequest->student->nim ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 rounded-3xl border border-slate-100/50">
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Total Tagihan</p>
                        <p class="text-2xl font-black text-[#8B1538] tracking-tighter">Rp {{ number_format($installmentRequest->invoice->total_tagihan, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-8 border-y border-slate-50">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="size-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 text-xs"><i class="fas fa-graduation-cap"></i></div>
                            <div>
                                <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Program Studi</p>
                                <p class="text-sm font-bold text-slate-700">{{ $installmentRequest->student->prodi }} ({{ $installmentRequest->student->angkatan }})</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="size-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 text-xs"><i class="fas fa-book-reader"></i></div>
                            <div>
                                <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Periode Tagihan</p>
                                <p class="text-sm font-bold text-slate-700">Semester {{ $installmentRequest->invoice->semester }} - {{ $installmentRequest->invoice->tahun_ajaran }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="size-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 text-xs"><i class="fas fa-calendar-check"></i></div>
                            <div>
                                <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Tgl Pengajuan</p>
                                <p class="text-sm font-bold text-slate-700">{{ $installmentRequest->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="size-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 text-xs"><i class="fas fa-chart-pie"></i></div>
                            <div>
                                <p class="text-[9px] font-black uppercase text-blue-500 tracking-widest">Cicilan Diminta</p>
                                <p class="text-sm font-black text-blue-600">{{ $installmentRequest->requested_terms }}x Perbulan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3">Alasan Pengajuan</p>
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100/50 relative">
                        <i class="fas fa-quote-left absolute top-4 left-4 text-slate-200 text-xl"></i>
                        <p class="text-sm text-slate-600 font-medium leading-relaxed italic pl-6">
                            "{{ $installmentRequest->alasan }}"
                        </p>
                    </div>
                </div>
            </div>

            {{-- Calculation Preview --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-8 animate-fade-in" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-black text-slate-800 tracking-tight">Preview Simulasi</h3>
                    <div class="flex items-center gap-3 p-1 bg-slate-100 rounded-xl border border-slate-200">
                        <button onclick="changeMockTerms(-1)" class="size-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-slate-400 hover:text-[#8B1538] transition-colors"><i class="fas fa-minus text-xs"></i></button>
                        <span id="term_mock_label" class="px-3 font-black text-sm text-slate-700">6x</span>
                        <button onclick="changeMockTerms(1)" class="size-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-slate-400 hover:text-[#8B1538] transition-colors"><i class="fas fa-plus text-xs"></i></button>
                    </div>
                </div>
                
                <div id="preview-result-container" class="space-y-4">
                    {{-- Dynamically filled via JS --}}
                </div>
            </div>
        </div>

        {{-- Right Column: Actions --}}
        <div class="lg:col-span-1 space-y-8 animate-fade-in" style="animation-delay: 0.4s">
            @if($installmentRequest->status === 'SUBMITTED')
                {{-- Approve Action --}}
                <div class="bg-white rounded-[2.5rem] border border-emerald-100 shadow-sm shadow-emerald-900/5 overflow-hidden p-8 ring-1 ring-emerald-50">
                    <h3 class="text-lg font-black text-emerald-800 tracking-tight mb-6 flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        Setujui
                    </h3>
                    <form action="{{ route('finance.installment-requests.approve', $installmentRequest) }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-emerald-700 tracking-widest ml-1">Jumlah Cicilan</label>
                            <div class="relative group">
                                <select name="approved_terms" id="approved_terms" required 
                                        class="w-full pl-12 pr-4 py-4 bg-emerald-50/50 border-none rounded-2xl text-emerald-900 font-black text-sm focus:ring-2 focus:ring-emerald-500 transition-all appearance-none cursor-pointer">
                                    @for($i = 2; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $i == $installmentRequest->requested_terms ? 'selected' : '' }}>{{ $i }}x Cicilan</option>
                                    @endfor
                                </select>
                                <i class="fas fa-list-ol absolute left-4 top-1/2 -translate-y-1/2 text-emerald-300"></i>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-emerald-700 tracking-widest ml-1">Catatan</label>
                            <textarea name="notes" rows="3" class="w-full p-4 bg-emerald-50/30 border-none rounded-2xl text-emerald-900 font-bold text-xs focus:ring-2 focus:ring-emerald-500 placeholder:text-emerald-200" placeholder="Opsional untuk mahasiswa..."></textarea>
                        </div>
                        <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] transition-all shadow-lg shadow-emerald-900/20 active:scale-95">
                            Konfirmasi Approval
                        </button>
                    </form>
                </div>

                {{-- Reject Action --}}
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-8 opacity-60 hover:opacity-100 transition-opacity">
                    <h3 class="text-lg font-black text-slate-800 tracking-tight mb-6 flex items-center gap-2">
                        <i class="fas fa-times-circle text-red-500"></i>
                        Tolak
                    </h3>
                    <form action="{{ route('finance.installment-requests.reject', $installmentRequest) }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                            <textarea name="rejection_reason" rows="4" required class="w-full p-4 bg-slate-50 border-none rounded-2xl text-slate-800 font-bold text-xs focus:ring-2 focus:ring-red-500 placeholder:text-slate-200" placeholder="Sebutkan alasan penolakan..."></textarea>
                        </div>
                        <button type="submit" class="w-full py-4 bg-white border-2 border-slate-100 text-slate-400 hover:text-red-500 hover:border-red-100 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] transition-all active:scale-95">
                            Tolak Pengajuan
                        </button>
                    </form>
                </div>
            @else
                {{-- Review Results --}}
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-8">
                    <h3 class="text-lg font-black text-slate-800 tracking-tight mb-8 px-2">Hasil Review</h3>
                    <div class="space-y-6">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100/50 flex items-center gap-4">
                            <div class="size-10 rounded-xl border-2 border-white bg-[#8B1538] text-white flex items-center justify-center shadow-sm">
                                <i class="fas fa-user-check text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[8px] font-black uppercase text-slate-400 tracking-widest">Reviewer</p>
                                <p class="text-xs font-black text-slate-700">{{ $installmentRequest->reviewer->name ?? '-' }}</p>
                            </div>
                        </div>
                        
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100/50 flex items-center gap-4">
                            <div class="size-10 rounded-xl border-2 border-white bg-slate-200 text-slate-500 flex items-center justify-center shadow-sm">
                                <i class="fas fa-calendar-check text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[8px] font-black uppercase text-slate-400 tracking-widest">Tgl Verifikasi</p>
                                <p class="text-xs font-black text-slate-700">{{ $installmentRequest->reviewed_at?->format('d M Y, H:i') ?? '-' }}</p>
                            </div>
                        </div>

                        @if($installmentRequest->status === 'APPROVED')
                            <div class="p-6 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-800">
                                <p class="text-[10px] font-black uppercase tracking-widest mb-1 opacity-60">Status Review</p>
                                <p class="font-black text-lg">Disetujui {{ $installmentRequest->approved_terms }}x</p>
                            </div>
                        @else
                            <div class="p-6 rounded-2xl bg-red-50 border border-red-100 text-red-800">
                                <p class="text-[10px] font-black uppercase tracking-widest mb-1 opacity-60">Alasan Penolakan</p>
                                <p class="text-sm font-bold leading-relaxed italic">{{ $installmentRequest->rejection_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    let currentMockTerms = {{ $installmentRequest->requested_terms }};
    const totalTagihan = {{ $installmentRequest->invoice->total_tagihan }};
    
    function changeMockTerms(delta) {
        let newVal = currentMockTerms + delta;
        if(newVal >= 2 && newVal <= 12) {
            currentMockTerms = newVal;
            calculatePreview();
        }
    }

    function calculatePreview() {
        const terms = currentMockTerms;
        document.getElementById('term_mock_label').innerText = terms + 'x';
        
        const rounding = 1000;
        const baseCicilan = Math.floor(totalTagihan / terms);
        const cicilanBulat = Math.floor(baseCicilan / rounding) * rounding;
        
        let container = document.getElementById('preview-result-container');
        let html = '';
        
        for (let i = 1; i <= terms; i++) {
            let nominal = (i === terms) ? (totalTagihan - (cicilanBulat * (terms - 1))) : cicilanBulat;
            let delay = i * 0.05;
            
            html += `
                <div class="flex items-center justify-between p-4 bg-slate-50/50 rounded-2xl border border-slate-50 animate-fade-in" style="animation-delay: ${delay}s">
                    <div class="flex items-center gap-3">
                        <div class="size-6 rounded-lg bg-[#8B1538] text-white font-black text-[10px] flex items-center justify-center">${i}</div>
                        <span class="text-xs font-black text-slate-500 uppercase tracking-widest">Term - ${i}</span>
                    </div>
                    <span class="text-sm font-black text-slate-700 tracking-tight">Rp ${nominal.toLocaleString('id-ID')}</span>
                </div>
            `;
        }
        
        container.innerHTML = html;
        
        // Sync the form select if in review mode
        const approvedSelect = document.getElementById('approved_terms');
        if(approvedSelect) approvedSelect.value = terms;
    }

    // Initial load
    document.addEventListener('DOMContentLoaded', calculatePreview);
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .animate-shake { animation: shake 0.4s ease-in-out; }
</style>
@endsection
