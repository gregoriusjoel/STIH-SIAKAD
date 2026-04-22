@extends('layouts.admin')
@section('title', 'Detail Kelas Perkuliahan')
@section('page-title', 'Kelas Perkuliahan')
@section('content')

<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.kelas-perkuliahan.index') }}" class="text-maroon hover:text-red-800 text-sm font-medium flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.kelas-perkuliahan.edit', $kelasPerkuliahan) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg text-sm font-medium hover:bg-yellow-600 transition flex items-center gap-2">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    {{-- Main Info Card --}}
    <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-layer-group text-maroon mr-2"></i>{{ $kelasPerkuliahan->nama_kelas }}
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $kelasPerkuliahan->display_label }}</p>
                </div>
                <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-black bg-blue-100 text-blue-800">
                    {{ $kelasPerkuliahan->nama_kelas }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-0 divide-x divide-y divide-gray-200">
            <div class="p-5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tingkat</p>
                <p class="text-lg font-bold text-gray-900">{{ $kelasPerkuliahan->tingkat }}</p>
            </div>
            <div class="p-5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Kode Prodi</p>
                <p class="text-lg font-bold text-gray-900 font-mono">{{ $kelasPerkuliahan->kode_prodi }}</p>
            </div>
            <div class="p-5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Kode Kelas</p>
                <p class="text-lg font-bold text-gray-900 font-mono">{{ $kelasPerkuliahan->kode_kelas }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-0 divide-x divide-gray-200 border-t border-gray-200">
            <div class="p-5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Program Studi</p>
                <p class="font-semibold text-gray-900">{{ $kelasPerkuliahan->prodi?->nama_prodi ?? 'Belum ditetapkan' }}</p>
            </div>
            <div class="p-5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tahun Akademik</p>
                <p class="font-semibold text-gray-900">
                    @if($kelasPerkuliahan->tahunAkademik)
                        {{ $kelasPerkuliahan->tahunAkademik->nama_semester }} {{ $kelasPerkuliahan->tahunAkademik->tahun_ajaran }}
                    @else
                        Tidak terikat
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Related Kelas Mata Kuliah --}}
    <div class="bg-white rounded-xl shadow-lg border-t-4 border-blue-500 overflow-hidden mb-6">
        <div class="p-5 border-b border-gray-200">
            <h4 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-chalkboard-teacher text-blue-500 mr-2"></i>Kelas Mata Kuliah Terkait
            </h4>
        </div>
        @if($kelasPerkuliahan->kelasMataKuliahs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase">Mata Kuliah</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase">Dosen</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase">Kode Kelas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($kelasPerkuliahan->kelasMataKuliahs as $kmk)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <div class="font-semibold text-gray-900">{{ $kmk->mataKuliah?->nama_mk ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $kmk->mataKuliah?->kode_mk ?? '' }}</div>
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-700">{{ $kmk->dosen?->user?->name ?? '-' }}</td>
                        <td class="px-5 py-3 font-mono text-sm text-gray-700">{{ $kmk->kode_kelas }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-inbox text-3xl mb-2"></i>
            <p class="font-medium">Belum ada kelas mata kuliah yang terhubung.</p>
        </div>
        @endif
    </div>

    {{-- Related Mahasiswa --}}
    <div class="bg-white rounded-xl shadow-lg border-t-4 border-green-500 overflow-hidden">
        <div class="p-5 border-b border-gray-200">
            <h4 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-user-graduate text-green-500 mr-2"></i>Mahasiswa Terdaftar
                <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">{{ $kelasPerkuliahan->mahasiswas->count() }}</span>
            </h4>
        </div>
        @if($kelasPerkuliahan->mahasiswas->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase">NIM</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase">Nama</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($kelasPerkuliahan->mahasiswas as $mhs)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-mono text-sm text-gray-700">{{ $mhs->nim }}</td>
                        <td class="px-5 py-3 font-semibold text-gray-900">{{ $mhs->user?->name ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-user-slash text-3xl mb-2"></i>
            <p class="font-medium">Belum ada mahasiswa yang terdaftar di kelas ini.</p>
        </div>
        @endif
    </div>
</div>
@endsection
