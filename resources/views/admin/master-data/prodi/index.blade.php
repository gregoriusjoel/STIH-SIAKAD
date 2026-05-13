@extends('layouts.admin')

@section('title', 'Master Data Prodi')
@section('page-title', 'Master Data Prodi')

@push('styles')
    <style>
        .action-btn {
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            transform: scale(1.1);
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">

        <div class="mb-6 flex flex-col items-start md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-graduation-cap mr-3 text-maroon"></i>
                    Master Data Prodi
                </h2>
                <p class="text-gray-600 text-sm mt-1">Kelola data program studi yang tersedia di sistem</p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('admin.prodi.create') }}"
                    class="bg-maroon text-white hover:bg-red-900 px-6 py-3 rounded-lg transition flex items-center shadow-md transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Prodi
                </a>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-white dark:bg-[#1a1c23] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-maroon text-white">
                            <th scope="col" class="px-6 py-3.5 text-left text-[10px] font-bold uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-[10px] font-bold uppercase tracking-wider">Kode Prodi</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-[10px] font-bold uppercase tracking-wider">Nama Prodi</th>
                            <th scope="col" class="px-6 py-3.5 text-center text-[10px] font-bold uppercase tracking-wider">Jenjang</th>
                            <th scope="col" class="px-6 py-3.5 text-center text-[10px] font-bold uppercase tracking-wider">Fakultas</th>
                            <th scope="col" class="px-6 py-3.5 text-center text-[10px] font-bold uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3.5 text-center text-[10px] font-bold uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                        @forelse($prodis as $index => $prodi)
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-white/2 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-500 dark:text-gray-400 tabular-nums">
                                    {{ $prodis->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-800 text-xs font-black text-gray-700 dark:text-gray-300 font-mono tracking-wide">
                                        {{ $prodi->kode_prodi }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-900 dark:text-white text-sm">{{ $prodi->nama_prodi }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $jenjangColor = match($prodi->jenjang) {
                                            'S1' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-800',
                                            'S2' => 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border-green-200 dark:border-green-800',
                                            'S3' => 'bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-400 border-purple-200 dark:border-purple-800',
                                            default => 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 border-yellow-200 dark:border-yellow-800',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-black tracking-wide border {{ $jenjangColor }}">
                                        {{ $prodi->jenjang }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                        {{ optional($prodi->fakultas)->nama_fakultas ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($prodi->status == 'aktif')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black tracking-wide bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black tracking-wide bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.prodi.show', $prodi->id) }}"
                                            class="action-btn w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center hover:bg-blue-100 dark:hover:bg-blue-900/40"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('admin.prodi.edit', $prodi->id) }}"
                                            class="action-btn w-8 h-8 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 flex items-center justify-center hover:bg-yellow-100 dark:hover:bg-yellow-900/40"
                                            title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form action="{{ route('admin.prodi.destroy', $prodi->id) }}"
                                            method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="action-btn w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 flex items-center justify-center hover:bg-red-100 dark:hover:bg-red-900/40"
                                                title="Hapus">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-2xl flex items-center justify-center shadow-inner">
                                            <i class="fas fa-graduation-cap text-gray-300 dark:text-gray-600 text-3xl"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Belum Ada Data Prodi</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">Silakan tambahkan program studi baru untuk memulai.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($prodis->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-white/2">
                    {{ $prodis->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data Prodi ini akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7a1621',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection