@extends('layouts.admin')

@section('title', 'Master Data Fakultas')
@section('page-title', 'Master Data Fakultas')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-university mr-3 text-maroon"></i>
                Master Data Fakultas
            </h2>
            <p class="text-gray-600 text-sm mt-1">Kelola data fakultas yang tersedia di sistem</p>
        </div>
        @if($prodiCount > 0)
            <a href="{{ route('admin.fakultas.create') }}"
                class="bg-maroon text-white hover:bg-red-900 px-6 py-3 rounded-lg transition flex items-center shadow-md transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i>
                Tambah Fakultas
            </a>
        @else
            <div class="bg-gray-400 text-white px-6 py-3 rounded-lg cursor-not-allowed flex items-center shadow-md">
                <i class="fas fa-plus mr-2"></i>
                Tambah Fakultas
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex">
                <div class="py-1"><i class="fas fa-check-circle text-green-500 mr-2"></i></div>
                <div>{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex">
                <div class="py-1"><i class="fas fa-exclamation-circle text-red-500 mr-2"></i></div>
                <div>{{ session('error') }}</div>
            </div>
        </div>
    @endif

    @if($prodiCount == 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                <div>
                    <h3 class="text-yellow-800 font-semibold">Prodi Belum Tersedia</h3>
                    <p class="text-yellow-700 text-sm mt-1">
                        Silakan tambahkan Prodi terlebih dahulu sebelum menambah Fakultas. 
                        Fakultas harus terkait dengan Program Studi.
                    </p>
                    <a href="{{ route('admin.prodi.create') }}" 
                        class="inline-flex items-center mt-3 bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition text-sm">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Prodi
                    </a>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-maroon text-white">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            No
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-code mr-2"></i>Kode Fakultas
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-university mr-2"></i>Nama Fakultas
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-graduation-cap mr-2"></i>Prodi
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-toggle-on mr-2"></i>Status
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($fakultas as $index => $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $fakultas->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->kode_fakultas }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->nama_fakultas }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php $prodi = $item->prodis->first(); @endphp
                                @if($prodi)
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            {{ $prodi->jenjang == 'S1' ? 'bg-blue-100 text-blue-800' : 
                                               ($prodi->jenjang == 'S2' ? 'bg-green-100 text-green-800' : 
                                               ($prodi->jenjang == 'S3' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                            {{ $prodi->jenjang }}
                                        </span>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $prodi->nama_prodi }}</div>
                                            <div class="text-sm text-gray-500">{{ $prodi->kode_prodi }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-sm text-gray-500">-</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    {{ $item->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas {{ $item->status == 'aktif' ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.fakultas.show', $item->id) }}" 
                                        class="text-blue-600 hover:text-blue-900 transition-colors" 
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.fakultas.edit', $item->id) }}" 
                                        class="text-yellow-600 hover:text-yellow-900 transition-colors"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.fakultas.destroy', $item->id) }}" 
                                        method="POST" class="inline" 
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus fakultas ini?')">
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
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-university text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-semibold">Belum Ada Data Fakultas</p>
                                    @if($prodiCount > 0)
                                        <p class="text-sm">Silakan tambahkan fakultas baru</p>
                                    @else
                                        <p class="text-sm">Silakan tambahkan Prodi terlebih dahulu</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($fakultas->hasPages())
            <div class="bg-gray-50 px-6 py-4">
                {{ $fakultas->links() }}
            </div>
        @endif
    </div>
@endsection