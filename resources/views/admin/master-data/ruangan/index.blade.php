@extends('layouts.admin')

@section('title', 'Data Ruangan Kelas')
@section('page-title', 'Data Ruangan Kelas')

@section('content')
    <div class="mb-6 flex flex-col items-start md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-door-open mr-3 text-maroon"></i>
                Data Ruangan Kelas
            </h2>
            <p class="text-gray-600 text-sm mt-1">Kelola data ruangan untuk perkuliahan</p>
        </div>
        <div class="flex-shrink-0">
            <div class="flex space-x-2">
                <a href="{{ route('admin.ruangan.create') }}"
                    class="bg-maroon text-white hover:bg-red-900 px-6 py-3 rounded-lg transition flex items-center shadow-md">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Ruangan
                </a>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">

        @if($ruangans->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-maroon text-white">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                Kode Ruangan
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                Nama Ruangan
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                Gedung/Lantai
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                Kapasitas
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($ruangans as $ruangan)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-maroon">{{ $ruangan->kode_ruangan }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $ruangan->nama_ruangan }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $ruangan->gedung ? $ruangan->gedung : '-' }}
                                        {{ $ruangan->lantai ? ' - Lantai ' . $ruangan->lantai : '' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-users text-gray-400 mr-1"></i>
                                        {{ $ruangan->kapasitas }} orang
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $ruangan->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="fas {{ $ruangan->status == 'aktif' ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                        {{ ucfirst($ruangan->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('admin.ruangan.show', $ruangan) }}" 
                                            class="text-blue-600 hover:text-blue-900 transition-colors"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.ruangan.edit', $ruangan) }}" 
                                            class="text-yellow-600 hover:text-yellow-900 transition-colors"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.ruangan.destroy', $ruangan) }}" 
                                              method="POST" 
                                              class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 transition-colors"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $ruangans->links() }}
            </div>
        @else
            <div class="p-12 text-center text-gray-500">
                <i class="fas fa-door-open text-4xl text-gray-300 mb-4"></i>
                <p class="text-lg font-semibold">Belum Ada Data Ruangan</p>
                <p class="text-sm mb-4">Silakan tambah ruangan kelas untuk memulai</p>
                <a href="{{ route('admin.ruangan.create') }}" 
                    class="bg-maroon text-white px-6 py-2 rounded-lg hover:bg-red-900 transition">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Ruangan
                </a>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data Ruangan ini akan dihapus permanen!",
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