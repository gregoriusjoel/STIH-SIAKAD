@extends('layouts.mahasiswa')

@section('title', 'Prestasi & Kegiatan')
@section('page-title', 'Prestasi & Kegiatan')

@section('content')
<div class="px-4 py-6 max-w-[1600px] mx-auto space-y-6">

    {{-- Premium Header Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8 shadow-sm relative overflow-hidden flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <!-- Background Accents -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-gradient-to-br from-[#8B1538]/5 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 bg-gradient-to-tr from-[#8B1538]/5 to-transparent rounded-full blur-2xl"></div>

        <div class="relative flex items-start sm:items-center gap-5 z-10">
            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br from-[#8B1538] to-[#6D1029] text-white flex items-center justify-center shadow-lg shadow-red-900/20 shrink-0">
                <span class="material-symbols-outlined text-3xl sm:text-4xl">emoji_events</span>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight leading-none mb-2">Prestasi Mahasiswa</h1>
                <p class="text-sm text-gray-500 font-medium">Ajukan prestasi atau kegiatan untuk mendapatkan surat resmi / tugas.</p>
            </div>
        </div>
        <div class="relative z-10 w-full sm:w-auto mt-2 sm:mt-0 flex flex-col sm:flex-row gap-3">
            <div class="shadow-lg shadow-[#8B1538]/10 rounded-xl">
                <a href="{{ route('mahasiswa.prestasi.create', ['tipe' => 'pengajuan']) }}"
                   class="group w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-[#8B1538] to-[#6D1029] text-white rounded-xl font-bold transition-all hover:shadow-xl hover:shadow-red-900/30 overflow-hidden relative">
                    <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">add_circle</span>
                    <span class="relative">Pengajuan Pra-Kegiatan</span>
                </a>
            </div>
            <div class="shadow-lg shadow-[#8B1538]/10 rounded-xl">
                <a href="{{ route('mahasiswa.prestasi.create', ['tipe' => 'pelaporan']) }}"
                   class="group w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-[#8B1538] to-[#6D1029] text-white rounded-xl font-bold transition-all hover:shadow-xl hover:shadow-red-900/30 overflow-hidden relative">
                    <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">upload_file</span>
                    <span class="relative">Lapor Prestasi (Sertifikat)</span>
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
    @if(session('error'))
        <div class="p-4 bg-red-50/80 backdrop-blur-sm border border-red-200/60 rounded-2xl flex items-start gap-3 shadow-sm">
            <span class="material-symbols-outlined text-red-600 mt-0.5">error</span>
            <div>
                <h4 class="text-sm font-bold text-red-800">Gagal!</h4>
                <p class="text-sm text-red-600 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- List --}}
    @if($prestasis->isEmpty())
        <div class="bg-white rounded-3xl shadow-sm p-12 text-center border border-dashed border-gray-200">
            <div class="w-24 h-24 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-6 border border-gray-100">
                <span class="material-symbols-outlined text-5xl text-gray-300">workspace_premium</span>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Belum Ada Data Prestasi</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto text-sm">Anda belum memiliki riwayat pengajuan kegiatan atau pelaporan prestasi.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($prestasis as $prestasi)
                <a href="{{ route('mahasiswa.prestasi.show', $prestasi) }}"
                   class="group block bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl hover:shadow-red-900/5 hover:-translate-y-1 transition-all duration-300 border border-gray-100 hover:border-red-100 relative overflow-hidden">
                    
                    {{-- Status Badge (Top Right Absolute) --}}
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
                    
                    <div class="mt-6 pt-5 border-t border-gray-50 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-start gap-2.5">
                            <span class="material-symbols-outlined text-[18px] text-gray-400 mt-0.5">corporate_fare</span>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Penyelenggara</p>
                                <p class="text-xs font-semibold text-gray-700 line-clamp-1">
                                    {{ $prestasi->penyelenggara }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <span class="material-symbols-outlined text-[18px] text-gray-400 mt-0.5">calendar_month</span>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Tanggal</p>
                                <p class="text-xs font-semibold text-gray-700">
                                    {{ $prestasi->tanggal_mulai?->format('d M y') ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        @if($prestasis->hasPages())
            <div class="mt-6">{{ $prestasis->links() }}</div>
        @endif
    @endif
</div>
@endsection
