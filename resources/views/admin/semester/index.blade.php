@extends('layouts.admin')
@section('title', 'Semester & Tahun Ajaran')
@section('page-title', 'Semester & Tahun Ajaran')
@section('content')
<div class="bg-white rounded-xl shadow-lg border-t-4 border-purple-600 overflow-hidden">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-xl font-bold text-gray-800"><i class="fas fa-calendar-alt text-purple-600 mr-2"></i>Data Semester & Tahun Ajaran</h3>
        <a href="{{ route('admin.semester.create') }}" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition shadow-md transform hover:scale-105 flex items-center"><i class="fas fa-plus mr-2"></i>Tambah Semester</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-purple-600 to-purple-800 text-white">
                <tr><th class="px-6 py-4 text-left text-sm font-semibold">Nama Semester</th><th class="px-6 py-4 text-left text-sm font-semibold">Tahun Ajaran</th><th class="px-6 py-4 text-left text-sm font-semibold">Tanggal Mulai</th><th class="px-6 py-4 text-left text-sm font-semibold">Tanggal Selesai</th><th class="px-6 py-4 text-center text-sm font-semibold">Status</th><th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($semesters as $s)
                <tr class="hover:bg-purple-50 transition duration-200">
                    <td class="px-6 py-4"><div class="font-semibold text-gray-900">{{ $s->nama_semester }}</div></td>
                    <td class="px-6 py-4"><span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800"><i class="fas fa-graduation-cap mr-1"></i>{{ $s->tahun_ajaran }}</span></td>
                    <td class="px-6 py-4"><i class="fas fa-calendar-check text-purple-600 mr-1"></i>{{ \Carbon\Carbon::parse($s->tanggal_mulai)->format('d M Y') }}</td>
                    <td class="px-6 py-4"><i class="fas fa-calendar-times text-purple-600 mr-1"></i>{{ \Carbon\Carbon::parse($s->tanggal_selesai)->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($s->status === 'aktif')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Aktif</span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('admin.semester.edit', $s) }}" class="bg-yellow-500 text-white p-2 rounded-lg hover:bg-yellow-600 transition" title="Edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.semester.destroy', $s) }}" method="POST" onsubmit="return confirm('Hapus semester ini?')">@csrf @method('DELETE')<button type="submit" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600 transition" title="Hapus"><i class="fas fa-trash"></i></button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-inbox text-4xl mb-3"></i><p class="text-lg font-semibold">Belum ada semester</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($semesters->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">{{ $semesters->links() }}</div>
    @endif
</div>
@endsection
