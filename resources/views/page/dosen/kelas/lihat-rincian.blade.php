@extends('layouts.app')

@section('title', 'Detail Pertemuan')

@section('content')
    <div class="px-4 py-6 max-w-[1200px] mx-auto">
        {{-- BREADCRUMB --}}
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('dosen.kelas') }}" class="hover:text-primary transition-colors">Kelas</a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <a href="{{ route('dosen.kelas.detail', $kelas->id) }}" class="hover:text-primary transition-colors">Detail
                Kelas</a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="font-medium text-gray-800">{{ $meeting['label'] }}</span>
        </nav>

        {{-- HEADER --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="size-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-3xl">event_note</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $meeting['label'] }}</h1>
                        <p class="text-gray-500">{{ $kelas->mataKuliah->nama }} - Kelas {{ $kelas->section }}</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('dosen.kelas.absensi', ['id' => $kelas->id]) }}?pertemuan={{ $meeting['no'] }}"
                        class="px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">how_to_reg</span>
                        Isi Absensi
                    </a>
                    <a href="{{ route('dosen.kelas.pertemuan.materi', ['id' => $kelas->id, 'pertemuan' => $meeting['no']]) }}"
                        class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">folder_open</span>
                        Materi
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6 pt-6 border-t border-gray-100">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Tanggal</p>
                    <p class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-400 text-[18px]">calendar_today</span>
                        {{ $meeting['date'] }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Waktu</p>
                    <p class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-400 text-[18px]">schedule</span>
                        {{ $meeting['time'] }} (WIB)
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Ruangan</p>
                    <p class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-400 text-[18px]">location_on</span>
                        {{ $meeting['room'] }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Materi / Topik</p>
                    <p class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-400 text-[18px]">topic</span>
                        -
                    </p>
                </div>
            </div>
        </div>

        {{-- ATTENDANCE LIST --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 text-lg">Daftar Kehadiran</h3>
                <span class="px-3 py-1 rounded-full bg-gray-100 text-xs font-bold text-gray-600">
                    Total Mahasiswa: {{ count($students) }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 text-xs uppercase tracking-wider text-gray-500 font-bold">
                            <th class="px-6 py-4 w-16">No</th>
                            <th class="px-6 py-4">Mahasiswa</th>
                            <th class="px-6 py-4">NIM</th>
                            <th class="px-6 py-4 text-center">Status Kehadiran</th>
                            <th class="px-6 py-4 text-center">Waktu Scan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($students as $index => $student)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-gray-500 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">{{ $student['name'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $student['prodi'] }}</div>
                                </td>
                                <td class="px-6 py-4 font-mono text-sm text-gray-600">{{ $student['nim'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">
                                        Belum Absen
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-gray-400 text-sm">-</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    Tidak ada mahasiswa terdaftar di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection