@extends('layouts.admin')

@section('title', 'Manajemen Semester')
@section('page-title', 'Semester & Tahun Ajaran')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center"><i class="fas fa-calendar-alt mr-3 text-maroon"></i>Semester & Tahun Ajaran</h2>
            <p class="text-gray-600 text-sm mt-1">Kelola daftar semester dan atur semester aktif</p>
        </div>
        <div>
            <a href="{{ route('admin.semester.create') }}" class="px-4 py-2 bg-maroon text-white rounded-md hover:bg-red-900">Tambah Semester</a>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <p class="text-xs text-gray-400 uppercase">Semester Aktif</p>
            @if($semesterAktif)
                <h3 class="text-2xl font-bold text-gray-800 mt-2">{{ $semesterAktif->nama_semester }} {{ $semesterAktif->tahun_ajaran }}</h3>
                <p class="text-sm text-gray-500 mt-2">Mulai: {{ optional($semesterAktif->tanggal_mulai)->format('d M Y') }} — Selesai: {{ optional($semesterAktif->tanggal_selesai)->format('d M Y') }}</p>
            @else
                <h3 class="text-lg font-semibold text-gray-800 mt-2">Belum ada semester aktif</h3>
                <p class="text-sm text-gray-500 mt-2">Silakan pilih salah satu semester di daftar sebelah kanan.</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Daftar Semester</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="text-xs text-gray-600 uppercase">
                        <tr>
                            <th class="px-4 py-2 text-left">Nama</th>
                            <th class="px-4 py-2 text-left">Tahun Ajaran</th>
                            <th class="px-4 py-2 text-left">Periode</th>
                            <th class="px-4 py-2 text-center">Status</th>
                            <th class="px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm">
                        @foreach($allSemesters as $s)
                            <tr>
                                <td class="px-4 py-3">{{ $s->nama_semester }}</td>
                                <td class="px-4 py-3">{{ $s->tahun_ajaran }}</td>
                                <td class="px-4 py-3">{{ optional($s->tanggal_mulai)->format('d M Y') ?? '-' }} — {{ optional($s->tanggal_selesai)->format('d M Y') ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($s->status === 'aktif')
                                        <span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-800">Aktif</span>
                                    @else
                                        <span class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-800">Non-aktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        @if($s->status !== 'aktif')
                                            <form action="{{ route('admin.semester.set-active') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="semester_id" value="{{ $s->id }}">
                                                <button class="px-3 py-1 bg-maroon text-white rounded-md text-sm">Set Aktif</button>
                                            </form>
                                        @endif

                                        <a href="{{ route('admin.semester.edit', $s) }}" class="text-blue-600">Edit</a>

                                        <form action="{{ route('admin.semester.destroy', $s) }}" method="POST" onsubmit="return confirm('Hapus semester ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
