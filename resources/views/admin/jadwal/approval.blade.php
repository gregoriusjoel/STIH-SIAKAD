@extends('layouts.admin')

@section('title', 'Persetujuan Jadwal - Admin')
@section('page-title', 'Persetujuan Jadwal')

@section('content')
    <div class="px-4 py-6 md:px-8">
        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Persetujuan Jadwal
                </h1>
                <p class="text-gray-500 mt-1">Review dan setujui pengajuan jadwal dari dosen dan auto-generator.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.jadwal.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Jadwal
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Menunggu Review -->
            <div
                class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                <div
                    class="absolute -right-4 -top-4 w-24 h-24 bg-yellow-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 tracking-wider uppercase mb-1">Menunggu Review</p>
                        <h3 class="text-3xl font-black text-gray-800">{{ $statistics['waiting_admin'] }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-100 to-yellow-50 flex items-center justify-center text-yellow-600 shadow-sm border border-yellow-100">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                </div>
                <div class="relative z-10 mt-4 text-xs font-medium text-yellow-600 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-yellow-500 mr-2 animate-pulse"></span>
                    Butuh tindakan segera
                </div>
            </div>

            <!-- Dalam Proses -->
            <div
                class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                <div
                    class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 tracking-wider uppercase mb-1">Dalam Proses</p>
                        <h3 class="text-3xl font-black text-gray-800">{{ $statistics['in_review'] }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center text-blue-600 shadow-sm border border-blue-100">
                        <i class="fas fa-spinner fa-spin fa-lg"></i>
                    </div>
                </div>
                <div class="relative z-10 mt-4 text-xs font-medium text-blue-600 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                    Sedang direview
                </div>
            </div>

            <!-- Disetujui Dosen -->
            <div
                class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                <div
                    class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 tracking-wider uppercase mb-1">Disetujui Dosen</p>
                        <h3 class="text-3xl font-black text-gray-800">{{ $statistics['approved_dosen'] }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center text-green-600 shadow-sm border border-green-100">
                        <i class="fas fa-user-check fa-lg"></i>
                    </div>
                </div>
                <div class="relative z-10 mt-4 text-xs font-medium text-green-600 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                    Jadwal disetujui
                </div>
            </div>

            <!-- Ditolak Dosen -->
            <div
                class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                <div
                    class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 tracking-wider uppercase mb-1">Ditolak Dosen</p>
                        <h3 class="text-3xl font-black text-gray-800">{{ $statistics['rejected_dosen'] }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-100 to-red-50 flex items-center justify-center text-red-600 shadow-sm border border-red-100">
                        <i class="fas fa-user-slash fa-lg"></i>
                    </div>
                </div>
                <div class="relative z-10 mt-4 text-xs font-medium text-red-600 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                    Memerlukan revisi
                </div>
            </div>
        </div>

        <!-- Tabs and Content -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
            x-data="{ activeTab: 'needing_approval' }">
            <div class="flex border-b border-gray-100 bg-gray-50/80 px-2 pt-2">
                <button @click="activeTab = 'needing_approval'"
                    :class="activeTab === 'needing_approval' ? 'border-maroon text-maroon bg-white shadow-[0_-2px_10px_-3px_rgba(0,0,0,0.05)]' : 'border-transparent text-gray-500 hover:text-gray-800 hover:bg-gray-100/50'"
                    class="px-6 py-3.5 text-sm font-bold border-b-2 transition-all rounded-t-xl flex items-center gap-2">
                    <i class="fas fa-inbox text-lg"></i> Butuh Persetujuan
                    <span
                        class="bg-maroon/10 text-maroon py-0.5 px-2 rounded-full text-xs">{{ $needingApproval->count() }}</span>
                </button>
                <button @click="activeTab = 'in_review'"
                    :class="activeTab === 'in_review' ? 'border-maroon text-maroon bg-white shadow-[0_-2px_10px_-3px_rgba(0,0,0,0.05)]' : 'border-transparent text-gray-500 hover:text-gray-800 hover:bg-gray-100/50'"
                    class="px-6 py-3.5 text-sm font-bold border-b-2 transition-all rounded-t-xl flex items-center gap-2 ml-1">
                    <i class="fas fa-history text-lg"></i> Histori Ditolak Dosen
                    <span
                        class="bg-gray-200 text-gray-700 py-0.5 px-2 rounded-full text-xs">{{ $proposals->where('status', 'rejected_dosen')->count() }}</span>
                </button>
                <button @click="activeTab = 'full_history'"
                    :class="activeTab === 'full_history' ? 'border-maroon text-maroon bg-white shadow-[0_-2px_10px_-3px_rgba(0,0,0,0.05)]' : 'border-transparent text-gray-500 hover:text-gray-800 hover:bg-gray-100/50'"
                    class="px-6 py-3.5 text-sm font-bold border-b-2 transition-all rounded-t-xl flex items-center gap-2 ml-1">
                    <i class="fas fa-list-ul text-lg"></i> Semua Histori
                    <span
                        class="bg-gray-200 text-gray-700 py-0.5 px-2 rounded-full text-xs">{{ $fullHistory->count() }}</span>
                </button>
            </div>

            <div class="p-0">
                <!-- Needing Approval Tab -->
                <div x-show="activeTab === 'needing_approval'" x-transition.opacity class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white border-b border-gray-100">
                                <th class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Mata
                                    Kuliah & Kelas</th>
                                <th class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Dosen
                                    Pengampu</th>
                                <th class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Jadwal
                                    Diajukan</th>
                                <th
                                    class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-center">
                                    Status</th>
                                <th
                                    class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-center">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($needingApproval as $proposal)
                                @php
                                    $dosenApproval = $proposal->approvals->where('role', 'dosen')->sortByDesc('created_at')->first();
                                @endphp
                                <tr class="hover:bg-gray-50/80 transition duration-200 group">
                                    <td class="px-6 py-5">
                                        <div class="font-bold text-gray-900 group-hover:text-maroon transition-colors">
                                            {{ $proposal->mataKuliah->nama_mk }}</div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs font-semibold">Kelas {{ $proposal->kelas->resolved_kelas_name }}</span>
                                            <span class="text-xs text-gray-400 font-medium">{{ $proposal->mataKuliah->sks }}
                                                SKS</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center">
                                            <div
                                                class="h-10 w-10 rounded-full bg-gradient-to-br from-maroon/20 to-maroon/5 text-maroon flex items-center justify-center mr-3 font-bold text-sm shadow-sm border border-maroon/10">
                                                {{ substr($proposal->dosen->user->name ?? 'D', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">
                                                    {{ $proposal->dosen->user->name ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-400 mt-0.5">NIDN:
                                                    {{ $proposal->dosen->nidn ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col xl:flex-row gap-4 items-center">
                                            <!-- Original Schedule -->
                                            <div
                                                class="bg-gray-50 rounded-xl p-3.5 border border-gray-100 min-w-[160px] w-full xl:w-auto relative">
                                                <div
                                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2.5 flex items-center h-4">
                                                    Jadwal Saat Ini
                                                </div>
                                                <div class="space-y-2">
                                                    <div class="flex items-center text-gray-800 font-semibold text-sm">
                                                        <div class="w-6 flex justify-center mr-2 text-gray-400"><i
                                                                class="far fa-calendar-alt"></i></div>
                                                        {{ $proposal->hari }}
                                                    </div>
                                                    <div class="flex items-center text-gray-600 text-sm font-medium">
                                                        <div class="w-6 flex justify-center mr-2 text-gray-400"><i
                                                                class="far fa-clock"></i></div>
                                                        {{ substr($proposal->jam_mulai, 0, 5) }} -
                                                        {{ substr($proposal->jam_selesai, 0, 5) }}
                                                    </div>
                                                    <div class="flex items-center text-gray-600 text-sm font-medium">
                                                        <div class="w-6 flex justify-center mr-2 text-gray-400"><i
                                                                class="fas fa-door-open"></i></div>
                                                        {{ $proposal->ruangan ?: 'Belum ditentukan' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Lecturer Pengajuan (Usulan Dosen) -->
                                            @if($dosenApproval && ($dosenApproval->hari_pengganti || $dosenApproval->jam_mulai_pengganti || $dosenApproval->ruangan_pengganti))
                                                <div class="hidden xl:flex items-center justify-center text-gray-300">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center shadow-sm">
                                                        <i class="fas fa-arrow-right text-xs"></i>
                                                    </div>
                                                </div>
                                                <div class="xl:hidden flex items-center justify-center text-gray-300 w-full">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center shadow-sm">
                                                        <i class="fas fa-arrow-down text-xs"></i>
                                                    </div>
                                                </div>

                                                <div
                                                    class="bg-red-50/80 rounded-xl p-3.5 border border-red-100/60 min-w-[160px] w-full xl:w-auto relative shadow-sm">
                                                    <div
                                                        class="text-[10px] font-bold text-maroon uppercase tracking-wider mb-2.5 flex items-center h-4">
                                                        Usulan Perubahan
                                                        <span class="ml-2 flex h-2 w-2 relative">
                                                            <span
                                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                            <span
                                                                class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                                        </span>
                                                    </div>
                                                    <div class="space-y-2">
                                                        @if($dosenApproval->hari_pengganti)
                                                            <div class="flex items-center text-gray-900 font-semibold text-sm">
                                                                <div class="w-6 flex justify-center mr-2 text-maroon/60"><i
                                                                        class="far fa-calendar-alt"></i></div>
                                                                {{ $dosenApproval->hari_pengganti }}
                                                            </div>
                                                        @endif
                                                        @if(!empty($dosenApproval->jam_mulai_pengganti))
                                                            <div class="flex items-center text-gray-700 text-sm font-medium">
                                                                <div class="w-6 flex justify-center mr-2 text-maroon/60"><i
                                                                        class="far fa-clock"></i></div>
                                                                {{ substr($dosenApproval->jam_mulai_pengganti, 0, 5) }} -
                                                                {{ substr($dosenApproval->jam_selesai_pengganti, 0, 5) }}
                                                            </div>
                                                        @endif
                                                        <div class="flex items-center text-gray-700 text-sm font-medium">
                                                            <div class="w-6 flex justify-center mr-2 text-maroon/60"><i
                                                                    class="fas fa-door-open"></i></div>
                                                            <span
                                                                class="{{ !$dosenApproval->ruangan_pengganti ? 'italic text-gray-400' : '' }}">
                                                                {{ $dosenApproval->ruangan_pengganti ?: 'Sama / Belum ada' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @if($proposal->status === 'approved_dosen')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span> Disetujui
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span> Ditolak
                                            </span>
                                            @if($dosenApproval && !empty($dosenApproval->alasan_penolakan))
                                                <div class="mt-2.5 p-2 bg-gray-50 rounded border border-gray-100 text-[11px] text-gray-500 max-w-[200px] text-left mx-auto truncate"
                                                    title="{{ $dosenApproval->alasan_penolakan }}">
                                                    <span class="font-bold text-gray-700">Alasan:</span>
                                                    {{ Str::limit($dosenApproval->alasan_penolakan, 50) }}
                                                </div>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <div
                                            class="flex items-center justify-center space-x-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('admin.jadwal_admin_approval.show', $proposal->id) }}"
                                                class="h-9 w-9 rounded-lg bg-gray-50 text-gray-600 hover:bg-maroon hover:text-white flex items-center justify-center shadow-sm border border-gray-200 hover:border-maroon transition-all duration-200"
                                                title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.jadwal_admin_approval.approve', $proposal->id) }}"
                                                method="POST" id="approve-form-{{ $proposal->id }}">
                                                @csrf
                                                <button type="button" onclick="Swal.fire({
                                                            title: 'Setujui Jadwal?',
                                                            text: 'Jadwal akan disetujui dan status akan diperbarui.',
                                                            icon: 'question',
                                                            iconColor: '#8B1538',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#10B981',
                                                            cancelButtonColor: '#6B7280',
                                                            confirmButtonText: 'Ya, Setujui',
                                                            cancelButtonText: 'Batal',
                                                            customClass: {
                                                                confirmButton: 'rounded-lg',
                                                                cancelButton: 'rounded-lg'
                                                            }
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                document.getElementById('approve-form-{{ $proposal->id }}').submit();
                                                            }
                                                        })"
                                                    class="h-9 w-9 rounded-lg bg-green-50 text-green-600 hover:bg-green-500 hover:text-white flex items-center justify-center shadow-sm border border-green-200 hover:border-green-500 transition-all duration-200"
                                                    title="Setujui Langsung">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <div
                                                class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                                                <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                                            </div>
                                            <p class="font-medium text-gray-500">Tidak ada pengajuan yang butuh persetujuan saat
                                                ini.</p>
                                            <p class="text-xs mt-1">Semua jadwal sudah ditangani.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- In Review Tab -->
                <div x-show="activeTab === 'in_review'" x-transition.opacity class="overflow-x-auto" style="display: none;">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white border-b border-gray-100">
                                <th class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Mata
                                    Kuliah & Kelas</th>
                                <th class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Dosen
                                    Pengampu</th>
                                <th class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Jadwal
                                </th>
                                <th
                                    class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-center">
                                    Status</th>
                                <th
                                    class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-center">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @php $rejected = $proposals->where('status', 'rejected_dosen'); @endphp
                            @forelse($rejected as $proposal)
                                <tr class="hover:bg-gray-50/80 transition duration-200 group">
                                    <td class="px-6 py-5">
                                        <div class="font-bold text-gray-900 group-hover:text-maroon transition-colors">
                                            {{ $proposal->mataKuliah->nama_mk }}</div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs font-semibold">Kelas {{ $proposal->kelas->resolved_kelas_name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center">
                                            <div
                                                class="h-10 w-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-50 text-gray-600 flex items-center justify-center mr-3 font-bold text-sm shadow-sm border border-gray-200">
                                                {{ substr($proposal->dosen->user->name ?? 'D', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">
                                                    {{ $proposal->dosen->user->name ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-400 mt-0.5">NIDN:
                                                    {{ $proposal->dosen->nidn ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col gap-1">
                                            <div class="flex items-center text-gray-800 font-semibold text-sm">
                                                <i class="far fa-calendar-alt w-5 text-gray-400"></i> {{ $proposal->hari }}
                                            </div>
                                            <div class="flex items-center text-gray-600 text-sm font-medium">
                                                <i class="far fa-clock w-5 text-gray-400"></i>
                                                {{ substr($proposal->jam_mulai, 0, 5) }} -
                                                {{ substr($proposal->jam_selesai, 0, 5) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span> Ditolak Dosen
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <a href="{{ route('admin.jadwal_admin_approval.show', $proposal->id) }}"
                                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 hover:border-gray-300 hover:text-maroon transition shadow-sm">
                                            <i class="fas fa-arrow-right mr-1.5"></i> Proses
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <div
                                                class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                                                <i class="fas fa-history fa-2x text-gray-300"></i>
                                            </div>
                                            <p class="font-medium text-gray-500">Tidak ada histori dosen yang menolak.</p>
                                            <p class="text-xs mt-1">Semua riwayat bersih.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Full History Tab -->
                <div x-show="activeTab === 'full_history'" x-transition.opacity class="overflow-x-auto"
                    style="display: none;">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white border-b border-gray-100">
                                <th class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Mata
                                    Kuliah & Kelas</th>
                                <th class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Dosen
                                    Pengampu</th>
                                <th class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Jadwal
                                    Diajukan</th>
                                <th
                                    class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-center">
                                    Status Terakhir</th>
                                <th
                                    class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-center">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($fullHistory as $proposal)
                                <tr class="hover:bg-gray-50/80 transition duration-200 group">
                                    <td class="px-6 py-5">
                                        <div class="font-bold text-gray-900 group-hover:text-maroon transition-colors">
                                            {{ $proposal->mataKuliah->nama_mk }}</div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs font-semibold">{{ $proposal->kelas->resolved_kelas_name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center">
                                            <div
                                                class="h-10 w-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-50 text-gray-600 flex items-center justify-center mr-3 font-bold text-sm shadow-sm border border-gray-200">
                                                {{ substr($proposal->dosen->user->name ?? 'D', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">
                                                    {{ $proposal->dosen->user->name ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-400 mt-0.5">NIDN:
                                                    {{ $proposal->dosen->nidn ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col gap-1">
                                            <div class="flex items-center text-gray-800 font-semibold text-sm">
                                                <i class="far fa-calendar-alt w-5 text-gray-400"></i> {{ $proposal->hari }}
                                            </div>
                                            <div class="flex items-center text-gray-600 text-sm font-medium">
                                                <i class="far fa-clock w-5 text-gray-400"></i>
                                                {{ substr($proposal->jam_mulai, 0, 5) }} -
                                                {{ substr($proposal->jam_selesai, 0, 5) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @if($proposal->status === 'approved_admin')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span> Disetujui Admin
                                            </span>
                                        @elseif($proposal->status === 'rejected_admin')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span> Ditolak Admin
                                            </span>
                                        @elseif($proposal->status === 'approved_dosen')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span> Disetujui Dosen
                                            </span>
                                        @elseif($proposal->status === 'rejected_dosen')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-700 border border-orange-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500 mr-1.5"></span> Ditolak Dosen
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-500 mr-1.5"></span>
                                                {{ ucfirst(str_replace('_', ' ', $proposal->status)) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <a href="{{ route('admin.jadwal_admin_approval.show', $proposal->id) }}"
                                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 hover:border-gray-300 hover:text-maroon transition shadow-sm">
                                            <i class="fas fa-eye mr-1.5"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <div
                                                class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                                                <i class="fas fa-list-ul fa-2x text-gray-300"></i>
                                            </div>
                                            <p class="font-medium text-gray-500">Belum ada riwayat persetujuan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection