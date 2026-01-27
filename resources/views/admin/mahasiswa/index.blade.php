@extends('layouts.admin')

@section('title', 'Data Mahasiswa')
@section('page-title', 'Data Mahasiswa')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-user-graduate text-maroon mr-3 text-2xl"></i>
                Daftar Mahasiswa STIH
            </h3>
            <p class="text-sm text-gray-600 mt-1">Kelola data mahasiswa kampus STIH</p>
        </div>

        <a href="{{ route('admin.mahasiswa.create') }}"
            class="bg-maroon text-white hover:bg-red-900 px-6 py-3 rounded-lg transition flex items-center shadow-md transform hover:scale-105">
            <i class="fas fa-plus mr-2"></i>
            Tambah Mahasiswa
        </a>
    </div>

    <div x-data="{ selectedMahasiswa: null }"
        class="bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border-separate" style="border-spacing: 0;">
                <thead class="bg-maroon text-white rounded-t-xl">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider rounded-tl-xl">
                            <i class=""></i>No
                        </th>
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
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-2"></i>Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider rounded-tr-xl">
                            <i class="fas fa-cog mr-2"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($mahasiswas as $mahasiswa)
                        <tr class="hover:bg-blue-50 transition cursor-pointer"
                            @click='selectedMahasiswa = { npm: @js($mahasiswa->npm), name: @js($mahasiswa->user->name), email: @js($mahasiswa->user->email), no_hp: @js($mahasiswa->no_hp ?? "-"), prodi: @js($mahasiswa->prodi), angkatan: @js($mahasiswa->angkatan), status: @js(ucfirst($mahasiswa->status)), foto: @js($mahasiswa->foto ? asset("storage/" . $mahasiswa->foto) : null) }'
                            :class="{ 'bg-blue-50': selectedMahasiswa && selectedMahasiswa.npm === '{{ $mahasiswa->npm }}' }">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ ($mahasiswas->currentPage() - 1) * $mahasiswas->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-maroon">
                                {{ $mahasiswa->npm }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="bg-maroon text-white rounded-full w-10 h-10 flex items-center justify-center font-bold mr-3">
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
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 inline-flex items-center justify-center mx-auto text-xs leading-5 font-semibold rounded-full 
                                            {{ $mahasiswa->status == 'aktif' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $mahasiswa->status == 'cuti' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $mahasiswa->status == 'lulus' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $mahasiswa->status == 'drop-out' ? 'bg-red-100 text-red-800' : '' }}">
                                    <i class="fas fa-circle text-xs mr-1"></i>
                                    {{ ucfirst($mahasiswa->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2" @click.stop>
                                    <a href="{{ route('admin.mahasiswa.show', $mahasiswa) }}"
                                        class="text-blue-600 hover:text-blue-900 transition" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.mahasiswa.edit', $mahasiswa) }}"
                                        class="text-indigo-600 hover:text-indigo-900 transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.mahasiswa.destroy', $mahasiswa) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
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