@extends('layouts.finance')

@section('page-title', 'Bebas Keuangan')

@section('content')
<div class="px-4 md:px-0 animate-fade-in pb-20 font-inter">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Bebas Keuangan</h1>
            <p class="text-sm text-slate-500 font-medium">Kelola dan proses pengajuan Surat Bebas Keuangan mahasiswa</p>
        </div>
        <div class="flex items-center gap-4 bg-white p-2 pl-6 rounded-3xl border border-slate-100 shadow-sm">
            <span class="text-[10px] font-black uppercase tracking-widest text-[#8B1538]">Menunggu Review</span>
            <div class="size-12 rounded-2xl bg-[#8B1538] text-white flex items-center justify-center font-black text-xl shadow-lg shadow-red-900/20">
                {{ $stats['submitted'] }}
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

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        {{-- Total --}}
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="size-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600">
                <i class="fas fa-file-alt text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800 leading-none">{{ $stats['total'] }}</p>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Total Pengajuan</p>
            </div>
        </div>

        {{-- Submitted --}}
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="size-12 rounded-2xl bg-yellow-50 flex items-center justify-center text-yellow-600">
                <i class="fas fa-clock text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800 leading-none">{{ $stats['submitted'] }}</p>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Perlu Ditinjau</p>
            </div>
        </div>

        {{-- Approved --}}
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="size-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800 leading-none">{{ $stats['approved'] }}</p>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Disetujui</p>
            </div>
        </div>

        {{-- Rejected --}}
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="size-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600">
                <i class="fas fa-times-circle text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800 leading-none">{{ $stats['rejected'] }}</p>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Ditolak</p>
            </div>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 mb-8">
        <form method="GET" action="{{ route('finance.pengajuan.index') }}" class="flex flex-col md:flex-row items-stretch md:items-end gap-4">
            {{-- Search input --}}
            <div class="flex-1">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Cari Mahasiswa</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nama atau NIM..."
                        class="w-full rounded-2xl border border-slate-100 bg-slate-50/50 py-3 pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#8B1538]/20 focus:border-[#8B1538] transition-all">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>

            {{-- Status dropdown --}}
            <div class="w-full md:w-60">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Status</label>
                <select name="status" class="w-full rounded-2xl border border-slate-100 bg-slate-50/50 py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#8B1538]/20 focus:border-[#8B1538] transition-all">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Diajukan (Menunggu Review)</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-6 py-3 bg-[#8B1538] text-white hover:bg-[#6D1029] rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all shadow-md shadow-red-900/10">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('finance.pengajuan.index') }}" class="px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Content Table --}}
    @if($pengajuans->isEmpty())
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-20 text-center">
            <div class="size-24 rounded-[2rem] bg-slate-50 flex items-center justify-center text-slate-200 mx-auto mb-6">
                <i class="fas fa-file-invoice-dollar text-4xl"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 tracking-tight">Tidak ada data</h3>
            <p class="text-slate-400 font-medium mt-2">Tidak ada pengajuan bebas keuangan yang cocok dengan filter Anda.</p>
        </div>
    @else
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Mahasiswa</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Tanggal Pengajuan</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Status</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($pengajuans as $p)
                            <tr class="group hover:bg-[#8B1538]/[0.02] transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="size-12 rounded-2xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 group-hover:scale-110 transition-transform flex items-center justify-center font-bold text-slate-700">
                                            {{ substr($p->mahasiswa->user->name ?? 'M', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-700 leading-tight">{{ $p->mahasiswa->user->name ?? 'N/A' }}</p>
                                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mt-1">NIM: {{ $p->mahasiswa->nim ?? 'N/A' }} | {{ $p->mahasiswa->prodi ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-sm font-bold text-slate-600">{{ $p->created_at->format('d M Y') }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium uppercase mt-0.5">{{ $p->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    {!! $p->status_badge !!}
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <a href="{{ route('finance.pengajuan.show', $p->id) }}" 
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
            {{ $pengajuans->links() }}
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
