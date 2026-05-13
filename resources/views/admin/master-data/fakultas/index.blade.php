@extends('layouts.admin')

@section('title', 'Master Data Fakultas')
@section('page-title', 'Master Data Fakultas')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                Master Data Fakultas
            </h2>
            <p class="mt-1 text-sm text-gray-500">Kelola daftar fakultas dan status operasionalnya di sistem.</p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('admin.fakultas.create') }}"
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-maroon hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-maroon transition-colors">
                <i class="fas fa-plus mr-2 text-xs"></i>
                Tambah Fakultas
            </a>
        </div>
    </div>

    @if($fakultas->isEmpty())
        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-r-lg p-4 mb-6 shadow-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-blue-800">Belum Ada Data Fakultas</h3>
                    <div class="mt-1 text-sm text-blue-700">
                        <p>Silakan tambahkan Fakultas baru untuk melanjutkan pengaturan sistem.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="flex flex-col bg-white shadow-md sm:rounded-xl overflow-hidden mb-6">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-maroon text-white">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-white/90 uppercase tracking-wider">No</th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-white/90 uppercase tracking-wider">Kode Fakultas</th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-white/90 uppercase tracking-wider">Nama Fakultas</th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-white/90 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-white/90 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($fakultas as $index => $item)
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                        {{ $fakultas->firstItem() + $index }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            {{ $item->kode_fakultas }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->nama_fakultas }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                                            {{ $item->status == 'aktif' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $item->status == 'aktif' ? 'bg-green-500' : 'bg-red-500' }} mr-1.5"></span>
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('admin.fakultas.show', $item->id) }}" 
                                                class="text-blue-600 hover:text-blue-900 p-1.5 rounded-md hover:bg-blue-50 transition-colors" 
                                                title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.fakultas.edit', $item->id) }}" 
                                                class="text-yellow-600 hover:text-yellow-900 p-1.5 rounded-md hover:bg-yellow-50 transition-colors"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.fakultas.destroy', $item->id) }}" 
                                                method="POST" class="inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 p-1.5 rounded-md hover:bg-red-50 transition-colors"
                                                    title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-university text-4xl text-gray-300 mb-4"></i>
                                            <h3 class="text-sm font-medium text-gray-900">Tidak ada data</h3>
                                            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan fakultas baru.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($fakultas->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $fakultas->links() }}
            </div>
        @endif
    </div>
    
    @push('scripts')
        <script>
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus Fakultas?',
                        text: "Data ini tidak dapat dikembalikan setelah dihapus.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7a1621',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
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