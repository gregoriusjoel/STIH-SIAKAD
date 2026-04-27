@extends('layouts.admin')

@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')

@section('content')
    <!-- Page Header & Action -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center">
                Daftar Pengumuman
            </h2>
            <p class="text-gray-500 dark:text-gray-400 mt-2 font-medium">Informasi dan pengumuman terbaru untuk seluruh
                civitas akademika</p>
        </div>
        <a href="{{ route('admin.pengumuman.create') }}"
            class="bg-maroon hover:bg-red-900 text-white px-8 py-3.5 rounded-xl font-bold transition-all flex items-center justify-center shadow-lg shadow-red-900/20 hover:scale-[1.02] active:scale-[0.98]">
            <i class="fas fa-plus-circle mr-3 text-lg"></i>
            Buat Pengumuman Baru
        </a>
    </div>

    <!-- Announcement Table/List -->
    <div
        class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-900/20">
                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">No</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Detail
                            Informasi</th>
                        <th class="px-8 py-5 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Target &
                            Status</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Waktu
                            Publikasi</th>
                        <th class="px-8 py-5 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($pengumumans as $p)
                        <tr
                            class="group hover:bg-maroon/[0.02] dark:hover:bg-maroon/[0.05] transition-all duration-500 relative">
                            <td
                                class="px-8 py-10 text-sm font-black text-gray-300 group-hover:text-maroon/60 transition-colors">
                                {{ str_pad($loop->iteration + ($pengumumans->currentPage() - 1) * $pengumumans->perPage(), 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-8 py-10">
                                <div class="flex items-start gap-5">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-gray-900 group-hover:bg-maroon group-hover:text-white transition-all duration-500 flex items-center justify-center flex-shrink-0 shadow-sm border border-gray-100 dark:border-gray-700">
                                        <i class="fas fa-bullhorn text-lg"></i>
                                    </div>
                                    <div class="flex flex-col gap-2 min-w-[300px]">
                                        <span
                                            class="text-lg font-extrabold text-gray-900 dark:text-white group-hover:text-maroon transition-colors">
                                            {{ $p->judul }}
                                        </span>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1 font-medium italic">
                                            "{{ strip_tags($p->isi) }}"
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-10">
                                <div class="flex flex-col items-center gap-3">
                                    <div
                                        class="flex items-center gap-2 px-3 py-1 bg-green-50 dark:bg-green-900/20 rounded-full border border-green-100 dark:border-green-800">
                                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                        <span
                                            class="text-[10px] font-black text-green-600 dark:text-green-400 tracking-widest uppercase text-center whitespace-nowrap">Aktif
                                            & Publik</span>
                                    </div>
                                    @if($p->target == 'semua')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black bg-maroon text-white shadow-sm shadow-maroon/20 tracking-widest min-w-[140px] justify-center whitespace-nowrap">
                                            <i class="fas fa-globe mr-2"></i> SEMUA CIVITAS
                                        </span>
                                    @elseif($p->target == 'dosen')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black bg-green-500 text-white shadow-sm shadow-green-500/20 tracking-widest min-w-[140px] justify-center whitespace-nowrap">
                                            <i class="fas fa-user-tie mr-2"></i> KHUSUS DOSEN
                                        </span>
                                    @elseif($p->target == 'mahasiswa')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black bg-yellow-500 text-white shadow-sm shadow-yellow-500/20 tracking-widest min-w-[140px] justify-center whitespace-nowrap">
                                            <i class="fas fa-user-graduate mr-2"></i> KHUSUS MAHASISWA
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-10">
                                <div class="flex items-center gap-4 group/date">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-900 flex items-center justify-center text-gray-400 group-hover/date:text-maroon transition-colors">
                                        <i class="far fa-calendar-alt text-lg"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-extrabold text-gray-800 dark:text-gray-200 whitespace-nowrap">
                                            {{ $p->published_at ? \Carbon\Carbon::parse($p->published_at)->isoFormat('D MMMM Y') : '-' }}
                                        </span>
                                        <span
                                            class="text-xs font-bold text-maroon/60 dark:text-red-400/60 uppercase tracking-tighter">
                                            {{ $p->published_at ? \Carbon\Carbon::parse($p->published_at)->locale('id')->diffForHumans() : '' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-10">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('admin.pengumuman.edit', $p) }}"
                                        class="w-11 h-11 flex items-center justify-center rounded-2xl bg-white dark:bg-gray-800 text-gray-400 hover:bg-maroon hover:text-white transition-all duration-300 shadow-sm hover:shadow-maroon/20 group/btn border border-gray-100 dark:border-gray-700"
                                        title="Edit">
                                        <i class="fas fa-pen-nib text-sm group-hover/btn:rotate-12 transition-transform"></i>
                                    </a>
                                    <form action="{{ route('admin.pengumuman.destroy', $p) }}" method="POST"
                                        class="inline delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-11 h-11 flex items-center justify-center rounded-2xl bg-white dark:bg-gray-800 text-gray-400 hover:bg-maroon hover:text-white transition-all duration-300 shadow-sm hover:shadow-maroon/20 group/btn border border-gray-100 dark:border-gray-700"
                                            title="Hapus">
                                            <i
                                                class="fas fa-trash-alt text-sm group-hover/btn:scale-110 transition-transform"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div
                                        class="w-24 h-24 bg-gray-50 dark:bg-gray-900/50 rounded-full flex items-center justify-center mb-6">
                                        <i class="fas fa-bullhorn text-4xl text-gray-300"></i>
                                    </div>
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">Belum Ada Pengumuman</h4>
                                    <p class="text-gray-500 mt-2 max-w-sm">Klik tombol di atas untuk membuat pengumuman pertama
                                        Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengumumans->hasPages())
            <div class="px-8 py-6 bg-gray-50/50 dark:bg-gray-900/20 border-t border-gray-100 dark:border-gray-700">
                {{ $pengumumans->links() }}
            </div>
        @endif
    </div>
    </div>

    @push('scripts')
        <script>
            // SweetAlert Delete Confirmation
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data pengumuman ini akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7a1621',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#f3f4f6' : '#1f293b',
                        customClass: {
                            confirmButton: 'btn btn-danger',
                            cancelButton: 'btn btn-secondary'
                        }
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