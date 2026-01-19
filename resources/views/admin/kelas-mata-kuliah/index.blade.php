@extends('layouts.admin')
@section('title', 'Kelas Mata Kuliah')
@section('page-title', 'Kelas Mata Kuliah')
@section('content')
<div class="mb-6 flex items-start justify-between">
    <div>
        <h3 class="text-2xl font-bold text-gray-800 flex items-center"><i class="fas fa-chalkboard-teacher text-maroon mr-2"></i>Daftar Kelas Mata Kuliah</h3>
        <p class="text-sm text-gray-600 mt-1">Kelola pengelompokan kelas per mata kuliah</p>
    </div>
    <a href="{{ route('admin.kelas-mata-kuliah.create') }}" class="bg-maroon text-white px-6 py-3 rounded-lg transition shadow-md transform hover:scale-105 flex items-center"><i class="fas fa-plus mr-2"></i>Tambah Kelas</a>
</div>

<div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-maroon text-white">
                <tr><th class="px-6 py-4 text-left text-sm font-semibold">Mata Kuliah</th><th class="px-6 py-4 text-left text-sm font-semibold">Nama Kelas</th><th class="px-6 py-4 text-left text-sm font-semibold">Dosen</th><th class="px-6 py-4 text-left text-sm font-semibold">Semester</th><th class="px-6 py-4 text-left text-sm font-semibold">Kuota</th><th class="px-6 py-4 text-left text-sm font-semibold">Ruangan</th><th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($kelasMatKul as $k)
                <tr class="hover:bg-blue-50 transition duration-200">
                    <td class="px-6 py-4"><div class="font-semibold text-gray-900">{{ $k->mataKuliah->nama_mk }}</div><div class="text-sm text-gray-500">{{ $k->mataKuliah->kode_mk }}</div></td>
                    <td class="px-6 py-4"><span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"><i class="fas fa-users mr-1"></i>{{ $k->nama_kelas }}</span></td>
                    <td class="px-6 py-4">{{ $k->dosen->user->name }}</td>
                    <td class="px-6 py-4">{{ $k->semester->nama_semester }}</td>
                    <td class="px-6 py-4"><span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">{{ $k->kuota }} mahasiswa</span></td>
                    <td class="px-6 py-4">{{ $k->ruangan ?: '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('admin.kelas-mata-kuliah.edit', $k) }}" class="bg-yellow-500 text-white p-2 rounded-lg hover:bg-yellow-600 transition" title="Edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.kelas-mata-kuliah.destroy', $k) }}" method="POST" onsubmit="return confirm('Hapus kelas ini?')">@csrf @method('DELETE')<button type="submit" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600 transition" title="Hapus"><i class="fas fa-trash"></i></button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-inbox text-4xl mb-3"></i><p class="text-lg font-semibold">Belum ada kelas mata kuliah</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($kelasMatKul->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">{{ $kelasMatKul->links() }}</div>
    @endif
</div>
@endsection
