@extends('layouts.finance')

@section('page-title', 'Pengajuan Cicilan')

@section('content')
<div class="px-4 md:px-0 animate-fade-in pb-20">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Pengajuan Cicilan</h1>
            <p class="text-sm text-slate-500 font-medium">Daftar permohonan skema pembayaran bertahap</p>
        </div>
        <div class="flex items-center gap-4 bg-white p-2 pl-6 rounded-3xl border border-slate-100 shadow-sm">
            <span class="text-[10px] font-black uppercase tracking-widest text-[#8B1538]">Antrian Review</span>
            <div class="size-12 rounded-2xl bg-[#8B1538] text-white flex items-center justify-center font-black text-xl shadow-lg shadow-red-900/20">
                {{ $requests->total() }}
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 bg-emerald-50 border border-emerald-100 rounded-3xl p-6 text-emerald-800 flex items-center gap-4 animate-shake">
            <div class="size-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                <i class="fas fa-check"></i>
            </div>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    @if($requests->isEmpty())
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-20 text-center animate-fade-in">
            <div class="size-24 rounded-[2rem] bg-slate-50 flex items-center justify-center text-slate-200 mx-auto mb-6">
                <i class="fas fa-hand-holding-usd text-4xl"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 tracking-tight">Semua Beres!</h3>
            <p class="text-slate-400 font-medium mt-2">Tidak ada pengajuan cicilan yang perlu ditinjau saat ini.</p>
        </div>
    @else
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Mahasiswa</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Rincian Tagihan</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Permintaan</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($requests as $request)
                            <tr class="group hover:bg-[#8B1538]/[0.02] transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="size-12 rounded-2xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 group-hover:scale-110 transition-transform">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($request->student->user->name ?? 'User') }}&background=8B1538&color=fff&bold=true" alt="Avatar">
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-700 leading-tight">{{ $request->student->user->name ?? 'N/A' }}</p>
                                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mt-1">{{ $request->student->nim ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-[#8B1538] tracking-tight">Rp {{ number_format($request->invoice->total_tagihan, 0, ',', '.') }}</span>
                                        <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest mt-1">
                                            Semester {{ $request->invoice->semester }} ({{ $request->invoice->tahun_ajaran }})
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="inline-flex flex-col items-center px-4 py-2 bg-[#8B1538]/5 rounded-2xl border border-[#8B1538]/10">
                                        <span class="text-xl font-black text-[#8B1538] leading-none">{{ $request->requested_terms }}</span>
                                        <span class="text-[8px] font-black uppercase tracking-tighter text-[#8B1538] opacity-60">Kali Cicilan</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <a href="{{ route('finance.installment-requests.show', $request) }}" 
                                       class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-100 text-slate-600 hover:text-[#8B1538] hover:border-[#8B1538]/20 hover:shadow-lg hover:shadow-red-900/5 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all group/btn">
                                        Review Detail
                                        <i class="fas fa-chevron-right transition-transform group-hover/btn:translate-x-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">
            {{ $requests->links() }}
        </div>
    @endif
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
