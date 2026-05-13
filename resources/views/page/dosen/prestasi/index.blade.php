@extends('layouts.app')

@section('title', 'Prestasi & Kegiatan')
@section('page-title', 'Prestasi & Kegiatan')

@section('content')
<div class="px-4 py-6 max-w-[1600px] mx-auto space-y-6" x-data="{ tab: 'pribadi' }">

    {{-- Premium Header Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-8 shadow-sm relative overflow-hidden flex flex-col lg:flex-row lg:items-center justify-between gap-4 sm:gap-6">
        <!-- Background Accents -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-gradient-to-br from-[#8B1538]/5 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 bg-gradient-to-tr from-[#8B1538]/5 to-transparent rounded-full blur-2xl"></div>

        <div class="relative flex flex-col sm:flex-row items-start sm:items-center gap-5 z-10">
            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br from-[#8B1538] to-[#6D1029] text-white flex items-center justify-center shadow-lg shadow-red-900/20 shrink-0">
                <span class="material-symbols-outlined text-3xl sm:text-4xl">workspace_premium</span>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight leading-tight mb-2">Prestasi & Kegiatan</h1>
                <p class="text-sm text-gray-500 font-medium">Ajukan kegiatan atau pantau kegiatan mahasiswa dampingan Anda.</p>
            </div>
        </div>
        <div class="relative z-10 w-full lg:w-auto flex flex-col sm:flex-row gap-3">
            <div class="shadow-lg shadow-[#8B1538]/10 rounded-xl flex-1 sm:flex-none">
                <a href="{{ route('dosen.prestasi.create', ['tipe' => 'pengajuan']) }}"
                   class="group w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-[#8B1538] to-[#6D1029] text-white rounded-xl font-bold transition-all hover:shadow-xl hover:shadow-red-900/30 overflow-hidden relative">
                    <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">add_circle</span>
                    <span class="relative">Ajukan Kegiatan</span>
                </a>
            </div>
            <div class="shadow-lg shadow-[#8B1538]/10 rounded-xl flex-1 sm:flex-none">
                <a href="{{ route('dosen.prestasi.create', ['tipe' => 'pelaporan']) }}"
                   class="group w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-[#8B1538] to-[#6D1029] text-white rounded-xl font-bold transition-all hover:shadow-xl hover:shadow-red-900/30 overflow-hidden relative">
                    <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">upload_file</span>
                    <span class="relative">Lapor Kegiatan</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50/80 backdrop-blur-sm border border-green-200/60 rounded-2xl flex items-start gap-3 shadow-sm">
            <span class="material-symbols-outlined text-green-600 mt-0.5">check_circle</span>
            <div>
                <h4 class="text-sm font-bold text-green-800">Berhasil!</h4>
                <p class="text-sm text-green-600 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Tabs --}}
    <div class="flex flex-wrap gap-2 sm:gap-4 border-b border-gray-200">
        <button @click="tab = 'pribadi'" :class="tab === 'pribadi' ? 'border-[#7a1621] text-[#7a1621]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="px-3 sm:px-6 py-3 border-b-2 font-bold text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-outlined text-[18px] sm:text-[20px]">person</span> Pengajuan Pribadi
        </button>
        <button @click="tab = 'dampingan'" :class="tab === 'dampingan' ? 'border-[#7a1621] text-[#7a1621]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="px-3 sm:px-6 py-3 border-b-2 font-bold text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-outlined text-[18px] sm:text-[20px]">group</span> Mahasiswa Dampingan
        </button>
    </div>

    {{-- List Pribadi --}}
    <div x-show="tab === 'pribadi'" x-cloak>
        @if($ownPrestasis->isEmpty())
            <div class="bg-white rounded-3xl shadow-sm p-12 text-center border border-dashed border-gray-200">
                <div class="w-24 h-24 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-6 border border-gray-100">
                    <span class="material-symbols-outlined text-5xl text-gray-300">workspace_premium</span>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Belum Ada Data Kegiatan Pribadi</h3>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($ownPrestasis as $prestasi)
                    <a href="{{ route('dosen.prestasi.show', $prestasi) }}"
                       class="group block bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl hover:shadow-red-900/5 hover:-translate-y-1 transition-all duration-300 border border-gray-100 hover:border-red-100 relative overflow-hidden">
                        
                        <div class="absolute top-4 right-4 z-10 scale-90 origin-top-right">
                            {!! $prestasi->status_badge !!}
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0 group-hover:bg-red-50 group-hover:border-red-100 group-hover:text-red-600 text-gray-400 transition-colors">
                                <span class="material-symbols-outlined text-2xl">emoji_events</span>
                            </div>
                            <div class="flex-1 min-w-0 pt-0.5 pr-20">
                                <h3 class="text-lg font-bold text-gray-900 truncate group-hover:text-[#8B1538] transition-colors">
                                    {{ $prestasi->nama_kegiatan }}
                                </h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-bold tracking-widest uppercase rounded">
                                        {{ ucfirst($prestasi->tipe) }}
                                    </span>
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold tracking-widest uppercase rounded">
                                        {{ $prestasi->tingkat_label }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            @if($ownPrestasis instanceof \Illuminate\Pagination\LengthAwarePaginator && $ownPrestasis->hasPages())
                <div class="mt-6">{{ $ownPrestasis->links() }}</div>
            @endif
        @endif
    </div>

    {{-- List Dampingan --}}
    <div x-show="tab === 'dampingan'" x-cloak>
        @if($dampinganPrestasis->isEmpty())
            <div class="bg-white rounded-3xl shadow-sm p-12 text-center border border-dashed border-gray-200">
                <div class="w-24 h-24 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-6 border border-gray-100">
                    <span class="material-symbols-outlined text-5xl text-gray-300">group</span>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Belum Ada Mahasiswa Dampingan</h3>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($dampinganPrestasis as $prestasi)
                    <a href="{{ route('dosen.prestasi.show', $prestasi) }}"
                       class="group block bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl hover:shadow-indigo-900/5 hover:-translate-y-1 transition-all duration-300 border border-gray-100 hover:border-indigo-100 relative overflow-hidden">
                        
                        <div class="absolute top-4 right-4 z-10 scale-90 origin-top-right">
                            {!! $prestasi->status_badge !!}
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0 group-hover:bg-indigo-50 group-hover:border-indigo-100 group-hover:text-indigo-600 text-gray-400 transition-colors">
                                <span class="material-symbols-outlined text-2xl">person</span>
                            </div>
                            <div class="flex-1 min-w-0 pt-0.5 pr-20">
                                <h3 class="text-sm font-bold text-gray-900 truncate group-hover:text-indigo-700 transition-colors">
                                    {{ $prestasi->pengaju_name }}
                                </h3>
                                <p class="text-xs text-gray-500 font-medium mt-0.5 truncate">{{ $prestasi->nama_kegiatan }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
