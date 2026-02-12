@extends('layouts.admin')

@section('title', 'Manajemen Pengajuan Mahasiswa')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto font-inter">

        {{-- Page Header --}}
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold text-text-primary tracking-tight">Manajemen Pengajuan</h1>
                <p class="text-sm text-text-secondary mt-1">Kelola dan proses pengajuan surat mahasiswa.</p>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            {{-- Total --}}
            <div class="relative overflow-hidden bg-white dark:bg-bg-card rounded-2xl p-6 shadow-sm border border-border-color">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-bold text-blue-600 uppercase tracking-wider">Total</h3>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                            <i class="fas fa-file-alt text-blue-600"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-text-primary">{{ $stats['total'] }}</div>
                    <p class="text-xs text-text-muted mt-1">Seluruh pengajuan</p>
                </div>
            </div>

            {{-- Pending --}}
            <div class="relative overflow-hidden bg-white dark:bg-bg-card rounded-2xl p-6 shadow-sm border border-yellow-200">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-bold text-yellow-600 uppercase tracking-wider">Menunggu</h3>
                        <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-text-primary">{{ $stats['pending'] }}</div>
                    <p class="text-xs text-text-muted mt-1">Perlu ditinjau</p>
                </div>
            </div>

            {{-- Approved --}}
            <div class="relative overflow-hidden bg-white dark:bg-bg-card rounded-2xl p-6 shadow-sm border border-green-200">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-bold text-green-600 uppercase tracking-wider">Disetujui</h3>
                        <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-text-primary">{{ $stats['disetujui'] }}</div>
                    <p class="text-xs text-text-muted mt-1">Pengajuan diterima</p>
                </div>
            </div>

            {{-- Rejected --}}
            <div class="relative overflow-hidden bg-white dark:bg-bg-card rounded-2xl p-6 shadow-sm border border-red-200">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-bold text-red-600 uppercase tracking-wider">Ditolak</h3>
                        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-600"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-text-primary">{{ $stats['ditolak'] }}</div>
                    <p class="text-xs text-text-muted mt-1">Pengajuan ditolak</p>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-border-color p-6 mb-6">
            <form method="GET" action="{{ route('admin.pengajuan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Search --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-text-secondary mb-2">Cari Mahasiswa</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Nama atau NIM..." 
                        class="w-full rounded-xl border-border-color py-2.5 px-4 text-sm focus:ring-2 focus:ring-primary">
                </div>

                {{-- Status Filter --}}
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-2">Status</label>
                    <select name="status" class="w-full rounded-xl border-border-color py-2.5 px-4 text-sm focus:ring-2 focus:ring-primary">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                {{-- Jenis Filter --}}
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-2">Jenis</label>
                    <select name="jenis" class="w-full rounded-xl border-border-color py-2.5 px-4 text-sm focus:ring-2 focus:ring-primary">
                        <option value="all" {{ request('jenis') === 'all' ? 'selected' : '' }}>Semua Jenis</option>
                        <option value="surat_aktif" {{ request('jenis') === 'surat_aktif' ? 'selected' : '' }}>Surat Aktif</option>
                        <option value="cuti" {{ request('jenis') === 'cuti' ? 'selected' : '' }}>Cuti Akademik</option>
                    </select>
                </div>

                <div class="md:col-span-4 flex gap-3">
                    <button type="submit" class="btn bg-primary text-white hover:bg-primary-hover rounded-xl px-6 py-2.5 font-medium">
                        <i class="fas fa-search mr-2"></i> Filter
                    </button>
                    <a href="{{ route('admin.pengajuan.index') }}" class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-xl px-6 py-2.5 font-medium">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white dark:bg-bg-card border border-border-color rounded-2xl shadow-sm overflow-hidden">
            <header class="px-6 py-5 border-b border-border-color bg-gray-50/30">
                <h2 class="font-bold text-text-primary text-lg">Daftar Pengajuan</h2>
            </header>
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead class="bg-gray-50/50 border-b border-border-color">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-text-secondary text-left">Mahasiswa</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-text-secondary text-left">Jenis</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-text-secondary text-left">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-text-secondary text-center">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-text-secondary text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-color">
                        @forelse ($pengajuans as $p)
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                                            {{ substr($p->mahasiswa->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-text-primary">{{ $p->mahasiswa->user->name }}</div>
                                            <div class="text-xs text-text-muted">{{ $p->mahasiswa->nim }}</div>
                                            <div class="text-xs text-text-muted">{{ $p->mahasiswa->prodi ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-xs font-medium 
                                        {{ $p->jenis === 'cuti' ? 'bg-orange-50 text-orange-700' : 'bg-blue-50 text-blue-700' }}">
                                        <i class="fas {{ $p->jenis === 'cuti' ? 'fa-pause' : 'fa-file-signature' }}"></i>
                                        {{ $p->jenis_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-text-secondary">{{ $p->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-text-muted">{{ $p->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {!! $p->status_badge !!}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.pengajuan.show', $p->id) }}" 
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover text-xs font-medium transition-colors">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-text-secondary">
                                    <i class="fas fa-inbox text-4xl text-text-muted mb-3"></i>
                                    <p class="text-base font-medium">Tidak ada data pengajuan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($pengajuans->hasPages())
                <div class="border-t border-border-color px-6 py-4">
                    {{ $pengajuans->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
