@extends('layouts.finance')

@section('page-title', 'Detail Bukti Pembayaran')

@section('content')
<div class="px-4 md:px-0 animate-fade-in pb-20">
    {{-- Top Navigation --}}
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('finance.payment-proofs.index') }}" 
           class="group inline-flex items-center gap-2 text-slate-400 hover:text-[#8B1538] transition-colors font-bold text-sm">
            <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
            <span>Kembali ke Antrian Verifikasi</span>
        </a>
        
        <span class="px-4 py-1.5 rounded-full border border-amber-100 bg-amber-50 text-amber-600 text-[10px] font-black uppercase tracking-widest shadow-sm">
            {{ $paymentProof->status }}
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

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Left: Payment Details & Actions (4 cols) --}}
        <div class="lg:col-span-4 space-y-8">
            {{-- Student Summary --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="size-14 rounded-2xl bg-[#8B1538]/5 border border-[#8B1538]/10 flex items-center justify-center text-[#8B1538] text-xl">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-slate-800 leading-tight tracking-tight">{{ $paymentProof->invoice->student->user->name ?? 'N/A' }}</h2>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mt-0.5">{{ $paymentProof->invoice->student->nim ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="p-6 bg-[#8B1538] rounded-[2rem] text-white shadow-lg shadow-red-900/10">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60 mb-1">Nominal Transfer</p>
                        <p class="text-3xl font-black tracking-tighter">Rp {{ number_format($paymentProof->amount_submitted, 0, ',', '.') }}</p>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="p-4 bg-slate-50 border border-slate-100/50 rounded-2xl">
                            <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest mb-1">Metode / Bank</p>
                            <p class="text-sm font-black text-slate-700">{{ $paymentProof->method ?? '-' }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 border border-slate-100/50 rounded-2xl">
                            <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest mb-1">Tanggal Transfer</p>
                            <p class="text-sm font-black text-slate-700">{{ $paymentProof->transfer_date->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Verification Actions --}}
            @if($paymentProof->status === 'UPLOADED')
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8 space-y-6">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4">Proses Verifikasi</h3>
                
                <form action="{{ route('finance.payment-proofs.review', $paymentProof) }}" method="POST" onsubmit="return confirm('Setujui pembayaran ini?')">
                    @csrf
                    <input type="hidden" name="action" value="approve">
                    <div class="mb-4">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-widest ml-1">Catatan (Opsional)</label>
                        <textarea name="notes" rows="2" class="w-full p-4 bg-slate-50 border-none rounded-2xl text-slate-800 font-bold text-xs focus:ring-2 focus:ring-[#8B1538] placeholder:text-slate-200" placeholder="Catatan untuk mahasiswa..."></textarea>
                    </div>
                    <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] transition-all shadow-lg shadow-emerald-900/10 flex items-center justify-center gap-3 group active:scale-95">
                        <i class="fas fa-check-circle transition-transform group-hover:scale-125"></i>
                        Verifikasi & Selesai
                    </button>
                </form>

                <div class="relative py-2">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-100"></div></div>
                    <div class="relative flex justify-center"><span class="bg-white px-3 text-[9px] font-black text-slate-300 uppercase letter-spacing-widest">Atau</span></div>
                </div>

                <form action="{{ route('finance.payment-proofs.review', $paymentProof) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="action" value="reject">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-widest ml-1">Alasan Penolakan</label>
                        <textarea name="notes" rows="3" required class="w-full p-4 bg-slate-50 border-none rounded-2xl text-slate-800 font-bold text-xs focus:ring-2 focus:ring-red-500 placeholder:text-slate-200" placeholder="Kenapa bukti ini ditolak?"></textarea>
                    </div>
                    <button type="submit" class="w-full py-4 bg-white border border-red-100 text-red-500 hover:bg-red-50 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] transition-all active:scale-95">
                        Tolak Bukti Pembayaran
                    </button>
                </form>
            </div>
            @else
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4">Hasil Verifikasi</h3>
                <div class="p-6 rounded-[2rem] {{ $paymentProof->status === 'APPROVED' ? 'bg-emerald-50 text-emerald-800' : 'bg-red-50 text-red-800' }}">
                    <p class="text-[10px] font-black uppercase tracking-widest mb-1 opacity-60">Status Dokument</p>
                    <p class="text-xl font-black">{{ $paymentProof->status }}</p>
                    @if($paymentProof->finance_notes)
                        <div class="mt-4 pt-4 border-t border-current/10">
                            <p class="text-[9px] font-black uppercase tracking-widest mb-1 opacity-60">Catatan Keuangan</p>
                            <p class="text-sm font-bold italic">"{{ $paymentProof->finance_notes }}"</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Right: Proof Image (8 cols) --}}
        <div class="lg:col-span-8">
            <div class="bg-slate-900 rounded-[3rem] border-8 border-slate-800 shadow-2xl overflow-hidden relative group min-h-[600px] flex items-center justify-center">
                <div class="absolute top-8 left-8 z-10">
                    <span class="bg-white/10 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-full border border-white/20 shadow-xl">
                        Original Document
                    </span>
                </div>

                @if($paymentProof->file_path)
                    @if($paymentProof->is_pdf)
                        <div class="w-full h-full min-h-[850px] flex flex-col">
                            <iframe src="{{ $paymentProof->file_url }}" 
                                    class="w-full h-[850px] rounded-2xl border-none shadow-2xl"
                                    allow="autoplay"></iframe>
                            <div class="p-4 flex justify-center bg-slate-800/50">
                                <a href="{{ $paymentProof->file_url }}" target="_blank" class="text-xs font-black text-white/70 hover:text-white uppercase tracking-[0.2em] flex items-center gap-2 transition-colors">
                                    <i class="fas fa-external-link-alt"></i>
                                    Open Document in New Tab
                                </a>
                            </div>
                        </div>
                    @else
                        <img src="{{ $paymentProof->file_url }}" 
                             class="w-full h-full object-contain transition-transform duration-700 group-hover:scale-105" 
                             alt="Bukti Pembayaran"
                             id="proof-image">
                        
                        {{-- Zoom Hint --}}
                        <div class="absolute bottom-8 right-8 animate-bounce opacity-50 group-hover:opacity-100 transition-opacity">
                            <div class="size-12 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/30">
                                <i class="fas fa-search-plus"></i>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-20 px-8">
                        <i class="fas fa-file-excel text-6xl text-slate-700 mb-6 block"></i>
                        <p class="text-slate-500 font-bold">File tidak dapat ditampilkan atau tidak ditemukan.</p>
                    </div>
                @endif
            </div>

            {{-- Metadata / Info Card --}}
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ $paymentProof->file_url }}" target="_blank" 
                   class="bg-white rounded-3xl p-6 border border-slate-100 flex items-center gap-4 hover:border-[#8B1538] transition-all group">
                    <div class="size-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center shadow-inner group-hover:bg-[#8B1538] group-hover:text-white transition-colors">
                        <i class="fas fa-file-download text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Download Dokumen</p>
                        <p class="text-xs font-black text-slate-700 break-all">{{ basename($paymentProof->file_path) }}</p>
                    </div>
                </a>
                <div class="bg-white rounded-3xl p-6 border border-slate-100 flex items-center gap-4">
                    <div class="size-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center shadow-inner">
                        <i class="fas fa-cloud-upload-alt text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Waktu Upload</p>
                        <p class="text-xs font-black text-slate-700">{{ $paymentProof->created_at->format('d M Y, H:i') }}</p>
                    </div>
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

    #proof-image {
        max-height: 80vh;
        cursor: zoom-in;
    }
</style>
@endsection
