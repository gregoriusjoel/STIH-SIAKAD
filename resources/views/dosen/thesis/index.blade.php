@extends('layouts.app')
@section('title', 'Skripsi Mahasiswa')

@section('content')
<div class="space-y-6">
    {{-- Premium Header Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden">
        {{-- Subtle Background Accent --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-[#8B1538]/10 to-transparent blur-3xl -mr-32 -mt-32"></div>
        
        <div class="p-6 sm:p-8 relative">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                {{-- Left: Profile & Info --}}
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#8B1538] to-[#6b102b] flex items-center justify-center shadow-lg shadow-red-900/10 shrink-0">
                        <span class="material-symbols-outlined text-white text-3xl font-light">school</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Pembimbing Skripsi</h1>
                        <p class="text-sm text-gray-500 mt-1 font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">group</span>
                            Manajemen bimbingan dan pengujian skripsi mahasiswa.
                        </p>
                    </div>
                </div>

                {{-- Right: Quick Stats --}}
                <div class="flex items-center gap-3">
                    <div class="bg-red-50 border border-red-100 rounded-2xl px-5 py-3 text-center min-w-[100px]">
                        <p class="text-[10px] uppercase tracking-widest font-black text-[#8B1538] mb-0.5">Total Bimbingan</p>
                        <p class="text-xl font-black text-gray-900">{{ $bimbingans->count() }}</p>
                    </div>
                    @if(!empty($pendingRequests) && $pendingRequests->count())
                    <div class="bg-amber-50 border border-amber-100 rounded-2xl px-5 py-3 text-center min-w-[100px]">
                        <p class="text-[10px] uppercase tracking-widest font-black text-amber-600 mb-0.5">Permintaan Baru</p>
                        <p class="text-xl font-black text-amber-700">{{ $pendingRequests->count() }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Bimbingan Sections --}}
    <div class="grid grid-cols-1 gap-6">
        {{-- Pending Supervisor Requests --}}
        @if(!empty($pendingRequests) && $pendingRequests->count())
        <div>
            <div class="flex items-center justify-between mb-4 px-2">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500">pending_actions</span>
                    <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest italic">Permintaan Pembimbing Masuk</h2>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 text-[10px] font-black uppercase tracking-wider">{{ $pendingRequests->count() }} Menunggu</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($pendingRequests as $thesis)
                <div class="bg-white border border-amber-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-50 rounded-full -mr-16 -mt-16 opacity-50"></div>
                    
                    <div class="relative">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shrink-0">
                                <span class="material-symbols-outlined text-2xl font-light">account_circle</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-gray-900 truncate tracking-tight">{{ $thesis->mahasiswa?->user?->name }}</h3>
                                <p class="text-xs text-gray-400 font-medium truncate mt-0.5">{{ $thesis->mahasiswa?->nim }}</p>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50/80 rounded-xl p-3 mb-5 border border-gray-100">
                            <h4 class="text-[10px] uppercase tracking-widest font-black text-gray-400 mb-1">Judul yang Diajukan</h4>
                            <p class="text-xs font-semibold text-gray-700 line-clamp-2 leading-relaxed italic">"{{ $thesis->judul }}"</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <form action="{{ route('dosen.thesis.supervisor.accept', $thesis) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">check</span>
                                    Terima
                                </button>
                            </form>
                            <form action="{{ route('dosen.thesis.supervisor.reject', $thesis) }}" method="POST" onsubmit="return collectRejectNote(this);" class="flex-1">
                                @csrf
                                <input type="hidden" name="note" value="" />
                                <button type="submit" class="w-full bg-white border border-red-100 text-red-600 hover:bg-red-50 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">close</span>
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Mahasiswa Bimbingan --}}
        <div>
            <div class="flex items-center justify-between mb-4 px-2 text-red-500">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#8B1538]">groups</span>
                    <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest italic">Mahasiswa Bimbingan Saya</h2>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-red-50 text-[#8B1538] text-[10px] font-black uppercase tracking-wider">{{ $bimbingans->count() }} Aktif</span>
            </div>

            @if($bimbingans->count())
            <div class="grid grid-cols-1 gap-3">
                @foreach($bimbingans as $thesis)
                @php 
                    $color = $thesis->status->color(); 
                    $colorMap = [
                        'yellow' => 'amber',
                        'green' => 'emerald',
                        'blue' => 'blue',
                        'red' => 'red',
                        'purple' => 'purple',
                        'indigo' => 'indigo',
                        'gray' => 'gray',
                        'orange' => 'orange'
                    ];
                    $tColor = $colorMap[$color] ?? $color;
                    $isPendingAdmin = $thesis->status === \App\Domain\Thesis\Enums\ThesisStatus::PROPOSAL_SUBMITTED;
                @endphp
                <a href="{{ route('dosen.thesis.show', $thesis) }}"
                    class="group flex flex-col sm:flex-row sm:items-center justify-between bg-white border border-gray-100 rounded-2xl p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all relative overflow-hidden">
                    
                    @if($isPendingAdmin)
                    <div class="absolute top-0 left-0 w-1 h-full bg-amber-400 animate-pulse"></div>
                    @endif

                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-[#8B1538]/5 group-hover:text-[#8B1538] transition-colors shrink-0">
                            <span class="material-symbols-outlined text-2xl font-light">person</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 mb-0.5">
                                <h3 class="font-bold text-gray-900 truncate tracking-tight group-hover:text-[#8B1538] transition-colors">{{ $thesis->mahasiswa?->user?->name }}</h3>
                                <span class="text-[10px] font-medium text-gray-400">#{{ $thesis->mahasiswa?->nim }}</span>
                            </div>
                            <p class="text-xs text-gray-400 font-medium truncate italic">"{{ $thesis->judul }}"</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-6 mt-4 sm:mt-0 ml-0 sm:ml-4 shrink-0">
                        <div class="text-right">
                            <p class="text-[10px] uppercase tracking-widest font-black text-gray-400 leading-none mb-1">Bimbingan</p>
                            <p class="text-sm font-black text-gray-700">
                                @if($isPendingAdmin)
                                <span class="text-xs text-gray-300 italic">Menunggu...</span>
                                @else
                                {{ $thesis->total_bimbingan }}<span class="text-gray-300 mx-1">/</span>8
                                @endif
                            </p>
                        </div>
                        <div class="min-w-[120px] text-right">
                            <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-{{ $tColor }}-100 text-{{ $tColor }}-700 {{ $isPendingAdmin ? 'animate-pulse' : '' }}">
                                {{ $thesis->status->label() }}
                            </span>
                        </div>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-gray-300 group-hover:text-[#8B1538] group-hover:bg-red-50 transition-all">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div class="bg-white border border-gray-100 rounded-3xl p-12 text-center group">
                <div class="w-20 h-20 rounded-3xl bg-gray-50 flex items-center justify-center mx-auto mb-5 text-gray-200 group-hover:scale-110 group-hover:text-[#8B1538]/20 transition-all duration-500">
                    <span class="material-symbols-outlined text-4xl font-light">group_off</span>
                </div>
                <h3 class="text-lg font-black text-gray-900 tracking-tight">Belum Ada Mahasiswa Bimbingan</h3>
                <p class="text-sm text-gray-400 mt-2 max-w-xs mx-auto leading-relaxed">Permintaan bimbingan yang telah Anda terima akan muncul di sini setelah diverifikasi oleh Admin.</p>
                <div class="mt-8 flex items-center justify-center gap-2 text-amber-600 bg-amber-50 rounded-2xl px-4 py-2.5 w-fit mx-auto border border-amber-100/50">
                    <span class="material-symbols-outlined text-[18px]">info</span>
                    <p class="text-[11px] font-bold uppercase tracking-wider">Cek Permintaan Masuk di Bagian Atas</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Penguji Section --}}
        @if($sidangs->count())
        <div>
            <div class="flex items-center gap-2 mb-4 px-2 text-indigo-500">
                <span class="material-symbols-outlined">how_to_reg</span>
                <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest italic">Jadwal Sidang (Sebagai Penguji)</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($sidangs as $thesis)
                @php 
                    $color = $thesis->status->color(); 
                    $colorMap = ['yellow' => 'amber', 'green' => 'emerald', 'blue' => 'blue', 'red' => 'red', 'purple' => 'purple', 'indigo' => 'indigo', 'gray' => 'gray'];
                    $tColor = $colorMap[$color] ?? $color;
                @endphp
                <a href="{{ route('dosen.thesis.show', $thesis) }}"
                    class="group flex flex-col bg-indigo-50/50 border border-indigo-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-indigo-600 shadow-sm border border-indigo-50">
                                <span class="material-symbols-outlined text-[22px]">account_circle</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-gray-900 truncate tracking-tight">{{ $thesis->mahasiswa?->user?->name }}</h3>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-indigo-100 text-indigo-700 tracking-wider">PENGUJI</span>
                            </div>
                        </div>
                        <span class="px-2 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-{{ $tColor }}-100 text-{{ $tColor }}-700">
                            {{ $thesis->status->label() }}
                        </span>
                    </div>

                    @if($thesis->sidangSchedule)
                    <div class="bg-white rounded-xl p-3 border border-indigo-100 space-y-2">
                        <div class="flex items-center gap-2 text-xs font-semibold text-gray-700">
                            <span class="material-symbols-outlined text-[16px] text-indigo-400">calendar_month</span>
                            {{ $thesis->sidangSchedule->tanggal->format('d M Y') }}
                        </div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-gray-700">
                            <span class="material-symbols-outlined text-[16px] text-indigo-400">schedule</span>
                            {{ substr($thesis->sidangSchedule->waktu_mulai, 0, 5) }} WIB
                        </div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-gray-700">
                            <span class="material-symbols-outlined text-[16px] text-indigo-400">meeting_room</span>
                            {{ $thesis->sidangSchedule->ruangan_label }}
                        </div>
                    </div>
                    @endif
                    
                    <div class="mt-4 flex items-center justify-between">
                        <p class="text-xs font-medium text-gray-400 truncate flex-1 min-w-0 italic mr-4">"{{ $thesis->judul }}"</p>
                        <span class="material-symbols-outlined text-[18px] text-gray-300 group-hover:text-indigo-600 transition-colors">chevron_right</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function collectRejectNote(form) {
    var note = prompt('Silakan masukkan alasan penolakan (opsional):');
    if (note === null) {
        // user cancelled
        return false;
    }
    var input = form.querySelector('input[name="note"]');
    if (input) input.value = note;
    return true;
}
</script>
@endpush
