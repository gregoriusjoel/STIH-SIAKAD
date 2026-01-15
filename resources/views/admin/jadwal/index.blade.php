@extends('layouts.admin')
@section('title', 'Jadwal Perkuliahan')
@section('page-title', 'Jadwal Perkuliahan')
@section('content')
<div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-xl font-bold text-gray-800"><i class="fas fa-clock text-maroon mr-2"></i>Jadwal Perkuliahan</h3>
        <a href="{{ route('admin.jadwal.create') }}" class="bg-maroon text-white px-6 py-3 rounded-lg hover:bg-maroon-700 transition shadow-md transform hover:scale-105 flex items-center"><i class="fas fa-plus mr-2"></i>Tambah Jadwal</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-maroon text-white">
                <tr><th class="px-6 py-4 text-left text-sm font-semibold">Hari</th><th class="px-6 py-4 text-left text-sm font-semibold">Jam</th><th class="px-6 py-4 text-left text-sm font-semibold">Kelas</th><th class="px-6 py-4 text-left text-sm font-semibold">Mata Kuliah</th><th class="px-6 py-4 text-left text-sm font-semibold">Dosen</th><th class="px-6 py-4 text-left text-sm font-semibold">Ruangan</th><th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($jadwals as $j)
                <tr class="hover:bg-maroon-50 transition duration-200">
                    <td class="px-6 py-4"><span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-maroon-100 text-maroon-800"><i class="fas fa-calendar-day mr-1"></i>{{ $j->hari }}</span></td>
                    <td class="px-6 py-4"><div class="font-semibold text-gray-900"><i class="fas fa-clock text-maroon-600 mr-1"></i>{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</div></td>
                    <td class="px-6 py-4"><span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">{{ $j->kelasMataKuliah->nama_kelas }}</span></td>
                    <td class="px-6 py-4"><div class="font-semibold text-gray-900">{{ $j->kelasMataKuliah->mataKuliah->nama_mk }}</div><div class="text-sm text-gray-500">{{ $j->kelasMataKuliah->mataKuliah->kode_mk }}</div></td>
                    <td class="px-6 py-4">{{ $j->kelasMataKuliah->dosen->user->name }}</td>
                    <td class="px-6 py-4"><i class="fas fa-door-open text-maroon-600 mr-1"></i>{{ $j->ruangan }}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('admin.jadwal.edit', $j) }}" class="bg-yellow-500 text-white p-2 rounded-lg hover:bg-yellow-600 transition" title="Edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.jadwal.destroy', $j) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">@csrf @method('DELETE')<button type="submit" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600 transition" title="Hapus"><i class="fas fa-trash"></i></button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-inbox text-4xl mb-3"></i><p class="text-lg font-semibold">Belum ada jadwal perkuliahan</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($jadwals->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">{{ $jadwals->links() }}</div>
    @endif
</div>
@endsection
