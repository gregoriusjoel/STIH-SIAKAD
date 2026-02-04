@extends('layouts.admin')

@section('title', 'Jam Perkuliahan')
@section('page-title', 'Master Data Jam Perkuliahan')

@section('content')
<div class="px-4 py-6 md:px-8">
    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Jam Perkuliahan</h1>
            <p class="text-gray-500 mt-1">Kelola jadwal jam perkuliahan</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.jam-perkuliahan.create') }}" class="inline-flex items-center px-4 py-2 bg-maroon text-white rounded-lg hover:bg-maroon/90 transition">
                <i class="fas fa-plus mr-2"></i> Tambah Jam
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Jam ke -</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Pukul</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($jamPerkuliahan as $jam)
                    <tr class="hover:bg-gray-50/50 transition duration-200">
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-bold text-gray-900">Jam ke - {{ $jam->jam_ke }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-medium text-gray-700">
                                {{ date('H.i', strtotime($jam->jam_mulai)) }} - {{ date('H.i', strtotime($jam->jam_selesai)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($jam->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-times-circle mr-1"></i> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.jam-perkuliahan.edit', $jam->id) }}" class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.jam-perkuliahan.destroy', $jam->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus jam perkuliahan ini?')" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-clock fa-3x mb-4 text-gray-200"></i>
                                <p class="font-medium">Belum ada data jam perkuliahan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
