@extends('layouts.admin')

@section('title', 'Jam Perkuliahan')
@section('page-title', 'Jam Perkuliahan')

@section('content')
    <div class="mb-6 flex flex-col items-start md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-clock mr-3 text-maroon"></i>
                Jam Perkuliahan
            </h2>
            <p class="text-gray-600 text-sm mt-1">Kelola jadwal sesi perkuliahan</p>
        </div>
        <div class="flex-shrink-0">
            <div class="flex space-x-2">
                <a href="{{ route('admin.jam-perkuliahan.create') }}"
                    class="bg-maroon text-white hover:bg-red-900 px-6 py-3 rounded-lg transition flex items-center shadow-md">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Jam Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-maroon text-white">
                    <tr>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            Sesi
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            Waktu
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            Durasi
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @forelse($jamPerkuliahan as $jam)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-maroon dark:bg-red-900 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow">
                                                {{ $jam->jam_ke }}
                                            </div>
                                            <span class="ml-3 font-medium text-gray-900 dark:text-gray-100">Jam ke-{{ $jam->jam_ke }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded text-sm font-medium">
                                                {{ date('H:i', strtotime($jam->jam_mulai)) }}
                                            </span>
                                            <i class="fas fa-arrow-right text-gray-300 dark:text-gray-600 text-xs"></i>
                                            <span class="px-2 py-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded text-sm font-medium">
                                                {{ date('H:i', strtotime($jam->jam_selesai)) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $start = strtotime($jam->jam_mulai);
                                            $end = strtotime($jam->jam_selesai);
                                            if (substr($jam->jam_selesai, 0, 5) === '00:00') {
                                                $end = strtotime('+1 day', $end);
                                            }
                                            $duration = ($end - $start) / 60;
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300">
                                            <i class="fas fa-stopwatch mr-1"></i> {{ $duration }} menit
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($jam->is_active)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                <i class="fas fa-check-circle mr-1"></i> Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                                <i class="fas fa-times-circle mr-1"></i> Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('admin.jam-perkuliahan.edit', $jam->id) }}"
                                                class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.jam-perkuliahan.destroy', $jam->id) }}" method="POST"
                                                class="inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition"
                                                    title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700">
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-clock text-3xl text-gray-300 dark:text-gray-600"></i>
                                            </div>
                                            <p class="text-gray-500 dark:text-gray-400 font-medium mb-1">Belum ada data jam perkuliahan</p>
                                            <p class="text-gray-400 dark:text-gray-500 text-sm mb-4">Tambahkan jam perkuliahan pertama untuk memulai</p>
                                            <a href="{{ route('admin.jam-perkuliahan.create') }}"
                                                class="inline-flex items-center px-4 py-2 bg-maroon text-white rounded-lg hover:bg-red-900 transition text-sm">
                                                <i class="fas fa-plus mr-2"></i> Tambah Jam Perkuliahan
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

    @push('scripts')
        <script>
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data Jam Perkuliahan ini akan dihapus permanen!",
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