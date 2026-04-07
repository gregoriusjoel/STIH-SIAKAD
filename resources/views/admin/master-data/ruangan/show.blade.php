@extends('layouts.admin')

@section('title', 'Detail Ruangan')
@section('page-title', 'Detail Ruangan')

@section('content')
    <div class="mb-6 flex flex-col items-start md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-door-open mr-3 text-maroon"></i>
                Detail Ruangan: {{ $ruangan->kode_ruangan }}
            </h2>
            <p class="text-gray-600 text-sm mt-1">Informasi lengkap ruangan {{ $ruangan->nama_ruangan }}</p>
        </div>
        <div class="flex-shrink-0">
            <div class="flex space-x-2">
                <a href="{{ route('admin.ruangan.edit', $ruangan) }}"
                    class="bg-yellow-600 text-white hover:bg-yellow-700 px-6 py-3 rounded-lg transition flex items-center shadow-md">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
                <a href="{{ route('admin.ruangan.index') }}"
                    class="bg-gray-600 text-white hover:bg-gray-700 px-6 py-3 rounded-lg transition flex items-center shadow-md">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Ruangan -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-maroon border-b border-maroon">
                    <h3 class="text-lg font-semibold text-white">Informasi Ruangan</h3>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Kode Ruangan</label>
                            <div class="mt-1 text-lg font-semibold text-maroon">
                                <i class="fas fa-barcode mr-2"></i>
                                {{ $ruangan->kode_ruangan }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nama Ruangan</label>
                            <div class="mt-1 text-lg font-semibold text-gray-900">
                                <i class="fas fa-door-open mr-2 text-maroon"></i>
                                {{ $ruangan->nama_ruangan }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Gedung</label>
                            <div class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-building mr-1"></i>
                                {{ $ruangan->gedung ?: '-' }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Lantai</label>
                            <div class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-layer-group mr-1"></i>
                                {{ $ruangan->lantai ? 'Lantai ' . $ruangan->lantai : '-' }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Kapasitas</label>
                            <div class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-users mr-1"></i>
                                {{ $ruangan->kapasitas }} orang
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $ruangan->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas {{ $ruangan->status == 'aktif' ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                    {{ ucfirst($ruangan->status) }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Dibuat Pada</label>
                            <div class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $ruangan->created_at->format('d F Y H:i') }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Terakhir Diubah</label>
                            <div class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $ruangan->updated_at->format('d F Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Penggunaan -->
        <div>
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-maroon border-b border-maroon">
                    <h3 class="text-lg font-semibold text-white">Statistik Penggunaan</h3>
                </div>

                <div class="p-6 space-y-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $ruangan->jadwals->count() }}</div>
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-calendar-check mr-1"></i>
                            Jadwal Aktif
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-600">{{ $ruangan->jadwalProposals->count() }}</div>
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-clock mr-1"></i>
                            Proposal Jadwal
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Aktif -->
    @if($ruangan->jadwals->count() > 0)
        <div class="mt-6 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-maroon border-b border-maroon">
                <h3 class="text-lg font-semibold text-white">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Jadwal Aktif di Ruangan Ini
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mata Kuliah
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kelas
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hari & Waktu
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($ruangan->jadwals as $jadwal)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $jadwal->kelas->mataKuliah->nama_mk ?? '-' }}</div>
                                    <div class="text-sm text-gray-500">{{ $jadwal->kelas->mataKuliah->kode_mk ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $jadwal->kelas->kode_kelas ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $jadwal->hari }}</div>
                                    <div class="text-sm text-gray-500">{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $jadwal->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($jadwal->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Proposal Jadwal -->
    @if($ruangan->jadwalProposals->count() > 0)
        <div class="mt-6 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-maroon border-b border-maroon">
                <h3 class="text-lg font-semibold text-white">
                    <i class="fas fa-clock mr-2"></i>
                    Proposal Jadwal di Ruangan Ini
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mata Kuliah
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hari & Waktu
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($ruangan->jadwalProposals as $proposal)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $proposal->mataKuliah->nama_mk ?? '-' }}</div>
                                    <div class="text-sm text-gray-500">{{ $proposal->mataKuliah->kode_mk ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $proposal->hari }}</div>
                                    <div class="text-sm text-gray-500">{{ substr($proposal->jam_mulai, 0, 5) }} - {{ substr($proposal->jam_selesai, 0, 5) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if(in_array($proposal->status, ['pending_dosen']))
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu Dosen</span>
                                    @elseif(in_array($proposal->status, ['approved_dosen','pending_admin']))
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Menunggu Admin</span>
                                    @elseif($proposal->status === 'approved_admin')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if($ruangan->jadwals->count() == 0 && $ruangan->jadwalProposals->count() == 0)
        <div class="mt-6 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-12 text-center text-gray-500">
                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                <p class="text-lg font-semibold">Ruangan Belum Digunakan</p>
                <p class="text-sm">Ruangan ini belum memiliki jadwal aktif atau proposal jadwal</p>
            </div>
        </div>
    @endif
@endsection