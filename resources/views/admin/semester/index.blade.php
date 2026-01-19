@extends('layouts.admin')
@section('title', 'Semester & Tahun Ajaran')
@section('page-title', 'Semester & Tahun Ajaran')
@section('content')
<div class="mb-6 flex items-start justify-between">
    <div>
        <h3 class="text-2xl font-bold text-gray-800 flex items-center"><i class="fas fa-calendar-alt text-maroon mr-2"></i>Semester & Tahun Ajaran</h3>
        <p class="text-sm text-gray-600 mt-1">Atur periode akademik aktif dan tahun ajaran</p>
    </div>
    
</div>
@if(isset($semesterAktif))
<div class="bg-white rounded-xl shadow-lg border-l-4 border-maroon overflow-hidden mb-6">
    <div class="p-6 flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase">SEMESTER AKTIF</span>
            <h2 class="text-3xl font-bold text-gray-800 mt-1">{{ $semesterAktif->nama_semester }} {{ $semesterAktif->tahun_ajaran }}</h2>
            <div class="mt-3 flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Sedang Berjalan</span>
                <span class="text-sm text-gray-600"><i class="far fa-calendar-alt mr-1"></i> Berakhir {{ $semesterAktif->tanggal_selesai?->format('d M Y') }}</span>
            </div>
            <div class="mt-4 flex gap-3">
                <a href="{{ route('admin.semester.create') }}" class="btn-maroon px-6 py-3 rounded-lg transition shadow-md transform hover:scale-105 flex items-center"><i class="fas fa-plus mr-2"></i>Set Semester Baru</a>
                <a href="{{ route('admin.semester.index') }}" class="px-4 py-2 bg-white border border-gray-200 rounded-lg">Lihat Kalender Akademik</a>
            </div>
        </div>
        <div class="hidden md:block text-gray-100">
            <svg width="120" height="120" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="4" width="18" height="16" rx="2" stroke="#F3F4F6" stroke-width="2"/><path d="M16 2V6" stroke="#F3F4F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 2V6" stroke="#F3F4F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
    </div>
</div>
@endif

<div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-maroon text-white">
                <tr><th class="px-6 py-4 text-left text-sm font-semibold">Nama Semester</th><th class="px-6 py-4 text-left text-sm font-semibold">Tahun Ajaran</th><th class="px-6 py-4 text-left text-sm font-semibold">Tanggal Mulai</th><th class="px-6 py-4 text-left text-sm font-semibold">Tanggal Selesai</th><th class="px-6 py-4 text-center text-sm font-semibold">Status</th><th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($semesters as $s)
                <tr class="hover:bg-gray-50 transition duration-200">
                    <td class="px-6 py-4"><div class="font-semibold text-gray-900">{{ $s->nama_semester }}</div></td>
                    <td class="px-6 py-4"><span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800"><i class="fas fa-graduation-cap mr-1"></i>{{ $s->tahun_ajaran }}</span></td>
                    <td class="px-6 py-4"><i class="fas fa-calendar-check text-maroon mr-1"></i>{{ \Carbon\Carbon::parse($s->tanggal_mulai)->format('d M Y') }}</td>
                    <td class="px-6 py-4"><i class="fas fa-calendar-times text-maroon mr-1"></i>{{ \Carbon\Carbon::parse($s->tanggal_selesai)->format('d M Y') }}</td>
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
