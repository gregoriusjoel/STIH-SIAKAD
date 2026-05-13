@extends('layouts.admin')

@section('title', 'Manajemen Prestasi Mahasiswa & Dosen')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-[1600px] mx-auto font-inter">

    {{-- Page Header --}}
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl font-bold text-text-primary tracking-tight">Manajemen Prestasi</h1>
            <p class="text-sm text-text-secondary mt-1">Kelola pengajuan prestasi dan pelaporan kegiatan akademik & non-akademik.</p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" @click="$dispatch('open-settings-modal')" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl text-sm font-semibold shadow-sm inline-flex items-center gap-2">
                <i class="fas fa-cog"></i> Pengaturan Surat
            </button>
            <a href="{{ route('admin.prestasi.export-excel', request()->all()) }}" class="px-4 py-2 bg-[#7a1621] hover:bg-[#63101a] text-white rounded-xl text-sm font-semibold shadow-sm inline-flex items-center gap-2 transition-colors">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">{{ session('error') }}</div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-bg-card rounded-2xl p-6 shadow-sm border border-border-color">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-bold text-blue-600 uppercase tracking-wider">Total</h3>
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center"><i class="fas fa-trophy text-blue-600"></i></div>
            </div>
            <div class="text-3xl font-bold text-text-primary">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-500 mt-2">{{ $stats['mahasiswa'] }} Mhs / {{ $stats['dosen'] }} Dosen</div>
        </div>
        <div class="bg-white dark:bg-bg-card rounded-2xl p-6 shadow-sm border border-yellow-200">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-bold text-yellow-600 uppercase tracking-wider">Perlu Review</h3>
                <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center"><i class="fas fa-clock text-yellow-600"></i></div>
            </div>
            <div class="text-3xl font-bold text-text-primary">{{ $stats['pending'] }}</div>
        </div>
        <div class="bg-white dark:bg-bg-card rounded-2xl p-6 shadow-sm border border-border-color">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Nasional</h3>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center"><i class="fas fa-flag text-indigo-600"></i></div>
            </div>
            <div class="text-3xl font-bold text-text-primary">{{ $stats['nasional'] }}</div>
        </div>
        <div class="bg-white dark:bg-bg-card rounded-2xl p-6 shadow-sm border border-border-color">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-bold text-purple-600 uppercase tracking-wider">Internasional</h3>
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center"><i class="fas fa-globe text-purple-600"></i></div>
            </div>
            <div class="text-3xl font-bold text-text-primary">{{ $stats['internasional'] }}</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-border-color mb-6">
        <form method="GET" class="p-4 flex flex-wrap items-center gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama/Kegiatan..."
                   class="rounded-xl border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-800 text-sm px-4 py-2.5 w-64 focus:ring-[#7a1621] focus:border-[#7a1621] transition-all">
            
            <select name="role" class="rounded-xl border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-800 text-sm px-4 py-2.5 focus:ring-[#7a1621] focus:border-[#7a1621] transition-all">
                <option value="">Semua Role</option>
                <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                <option value="dosen" {{ request('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
            </select>

            <select name="tipe" class="rounded-xl border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-800 text-sm px-4 py-2.5 focus:ring-[#7a1621] focus:border-[#7a1621] transition-all">
                <option value="">Semua Tipe</option>
                <option value="pengajuan" {{ request('tipe') == 'pengajuan' ? 'selected' : '' }}>Pengajuan (Pra-Kegiatan)</option>
                <option value="pelaporan" {{ request('tipe') == 'pelaporan' ? 'selected' : '' }}>Pelaporan (Pasca-Kegiatan)</option>
            </select>

            <select name="status" class="rounded-xl border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-800 text-sm px-4 py-2.5 focus:ring-[#7a1621] focus:border-[#7a1621] transition-all">
                <option value="">Semua Status</option>
                @foreach(\App\Models\Prestasi::STATUS_LABELS as $val => $label)
                    <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            
            <button type="submit" class="px-6 py-2.5 bg-[#7a1621] hover:bg-[#63101a] text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">Filter</button>
            @if(request()->hasAny(['search','status','role','tipe']))
                <a href="{{ route('admin.prestasi.index') }}" 
                   class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 font-semibold rounded-xl transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-border-color overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800 text-left border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-gray-600">Pengaju</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Kegiatan</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Tanggal</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Surat</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($prestasis as $p)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-800 font-bold text-xs">
                                    {{ substr($p->pengaju_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-semibold">{{ $p->pengaju_name }}</div>
                                    <div class="text-xs text-gray-500 font-mono">{{ $p->pengaju_identifier }} • {{ ucfirst($p->pengaju_role) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $p->nama_kegiatan }}</div>
                            <div class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                <span class="bg-gray-100 px-2 py-0.5 rounded">{{ $p->tingkat_label }}</span>
                                <span>{{ ucfirst($p->tipe) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <div class="text-gray-600">{{ $p->tanggal_mulai?->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($p->surats->count() > 0)
                                <div class="flex flex-col gap-1">
                                    @foreach($p->surats as $surat)
                                        <a href="{{ route('admin.prestasi.surat.preview', [$p, $surat]) }}" target="_blank" class="text-xs text-[#7a1621] hover:underline flex items-center gap-1">
                                            <i class="fas fa-file-pdf"></i> {{ $surat->jenis_surat_label }}
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            {!! $p->status_badge !!}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.prestasi.show', $p) }}" class="text-[#7a1621] hover:underline text-sm font-semibold px-3 py-1.5 bg-red-50 rounded-lg inline-block">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">Belum ada data prestasi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($prestasis->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $prestasis->withQueryString()->links() }}</div>
        @endif
    </div>

    {{-- Settings Modal --}}
    @include('admin.prestasi.partials.settings-modal')

</div>
@endsection
