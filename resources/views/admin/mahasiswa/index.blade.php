@extends('layouts.admin')

@section('title', 'Data Mahasiswa')
@section('page-title', 'Data Mahasiswa')

@section('content')
<div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon">
    <div class="p-6 border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-user-graduate text-maroon mr-3 text-2xl"></i>
                Daftar Mahasiswa STIH
            </h3>
            <p class="text-sm text-gray-600 mt-1">Kelola data mahasiswa kampus STIH</p>
        </div>
        <a href="{{ route('admin.mahasiswa.create') }}" class="btn-maroon px-6 py-3 rounded-lg hover:bg-opacity-90 transition transform hover:scale-105 shadow-md">
            <span class="flex items-center">
                <i class="fas fa-plus-circle mr-2"></i>
                Tambah Mahasiswa
            </span>
        </a>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-maroon text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-id-card mr-2"></i>NPM
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Nama
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-envelope mr-2"></i>Email
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-graduation-cap mr-2"></i>Prodi
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-calendar mr-2"></i>Angkatan
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-2"></i>Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($mahasiswas as $mahasiswa)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-maroon">
                                {{ $mahasiswa->npm }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-maroon text-white rounded-full w-10 h-10 flex items-center justify-center font-bold mr-3">
                                        {{ strtoupper(substr($mahasiswa->user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $mahasiswa->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                {{ $mahasiswa->user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">
                                    {{ $mahasiswa->prodi }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <i class="fas fa-calendar-alt text-gray-400 mr-1"></i>
                                {{ $mahasiswa->angkatan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $mahasiswa->status == 'aktif' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $mahasiswa->status == 'cuti' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $mahasiswa->status == 'lulus' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $mahasiswa->status == 'drop-out' ? 'bg-red-100 text-red-800' : '' }}">
                                    <i class="fas fa-circle text-xs mr-1"></i>
                                    {{ ucfirst($mahasiswa->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button type="button" onclick="document.getElementById('modal-mahasiswa-{{ $mahasiswa->id }}').classList.remove('hidden')" class="text-blue-600 hover:text-blue-900 transition" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.mahasiswa.edit', $mahasiswa) }}" class="text-indigo-600 hover:text-indigo-900 transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.mahasiswa.destroy', $mahasiswa) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Mahasiswa -->
                        <div id="modal-mahasiswa-{{ $mahasiswa->id }}" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden">
                            <div class="bg-white rounded-lg shadow-2xl w-11/12 md:w-3/4 lg:w-2/3 max-h-[90vh] overflow-y-auto overflow-x-hidden">
                                <div class="flex items-center justify-between px-6 py-4 bg-maroon text-white">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-10 w-10 rounded-full bg-white bg-opacity-10 flex items-center justify-center text-white font-bold">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold">Detail Mahasiswa</h3>
                                            <p class="text-sm text-white text-opacity-90">Informasi lengkap mahasiswa</p>
                                        </div>
                                    </div>
                                    <button onclick="document.getElementById('modal-mahasiswa-{{ $mahasiswa->id }}').classList.add('hidden')" class="text-white text-xl leading-none">&times;</button>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-1 gap-2 text-sm text-gray-700">
                                        <div><strong>Nama:</strong> {{ $mahasiswa->user->name }}</div>
                                        <div><strong>Email:</strong> {{ $mahasiswa->user->email }}</div>
                                        <div><strong>NPM:</strong> {{ $mahasiswa->npm }}</div>
                                        <div><strong>Prodi:</strong> {{ $mahasiswa->prodi }}</div>
                                        <div><strong>Angkatan:</strong> {{ $mahasiswa->angkatan }}</div>
                                        <div><strong>Status:</strong> {{ ucfirst($mahasiswa->status) }}</div>
                                    </div>
                                </div>
                                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                                    <a href="{{ route('admin.mahasiswa.edit', $mahasiswa) }}" class="bg-maroon text-white px-4 py-2 rounded shadow">Edit</a>
                                    <button onclick="document.getElementById('modal-mahasiswa-{{ $mahasiswa->id }}').classList.add('hidden')" class="px-4 py-2 border rounded">Tutup</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-gray-300 text-5xl mb-3"></i>
                                <p class="text-lg">Belum ada data mahasiswa</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $mahasiswas->links() }}
        </div>
    </div>
</div>
@endsection
