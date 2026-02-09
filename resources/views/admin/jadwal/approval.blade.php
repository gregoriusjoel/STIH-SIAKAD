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
            <p class="text-gray-500 mt-1">Review dan setujui proposal jadwal dari dosen dan auto-generator.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.jadwal.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Jadwal
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Menunggu Review</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['waiting_admin'] }}</p>
                </div>
                <div class="p-3 bg-yellow-50 rounded-lg text-yellow-600">
                    <i class="fas fa-clock fa-lg"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Dalam Proses</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['in_review'] }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                    <i class="fas fa-spinner fa-spin fa-lg"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Disetujui Dosen</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['approved_dosen'] }}</p>
                </div>
                <div class="p-3 bg-green-50 rounded-lg text-green-600">
                    <i class="fas fa-user-check fa-lg"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-maroon">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Ditolak Dosen</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['rejected_dosen'] }}</p>
                </div>
                <div class="p-3 bg-red-50 rounded-lg text-maroon">
                    <i class="fas fa-user-slash fa-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs and Content -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ activeTab: 'needing_approval' }">
        <div class="flex border-b border-gray-100 bg-gray-50/50">
            <button @click="activeTab = 'needing_approval'" :class="activeTab === 'needing_approval' ? 'border-maroon text-maroon bg-white' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-4 text-sm font-medium border-b-2 transition">
                Butuh Persetujuan ({{ $needingApproval->count() }})
            </button>
            <button @click="activeTab = 'in_review'" :class="activeTab === 'in_review' ? 'border-maroon text-maroon bg-white' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-4 text-sm font-medium border-b-2 transition">
                Histori Ditolak Dosen ({{ $proposals->where('status','rejected_dosen')->count() }})
            </button>
        </div>

        <div class="p-0">
            <!-- Needing Approval Tab -->
            <div x-show="activeTab === 'needing_approval'" class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Kuliah & Kelas</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dosen</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jadwal Diajukan</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status Dosen</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($needingApproval as $proposal)
                            @php
                                $dosenApproval = $proposal->approvals->where('role', 'dosen')->sortByDesc('created_at')->first();
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition duration-200">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $proposal->mataKuliah->nama_mk }}</div>
                                    <div class="text-xs text-gray-500 font-medium">Kelas {{ $proposal->kelas->section ?? '-' }} • {{ $proposal->mataKuliah->sks }} SKS</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-maroon/10 text-maroon flex items-center justify-center mr-3 font-bold text-xs uppercase">
                                            {{ substr($proposal->dosen->user->name ?? 'D', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $proposal->dosen->user->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">NIDN: {{ $proposal->dosen->nidn ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col xl:flex-row gap-6 items-start">
                                        <!-- Original Schedule -->
                                        <div class="bg-gray-50/50 rounded-lg p-3 border border-gray-100 min-w-[150px]">
                                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 flex items-center h-4">
                                                Jadwal Saat Ini
                                            </div>
                                            <div class="space-y-1">
                                                <div class="flex items-center text-gray-900 font-medium text-sm">
                                                    <div class="w-6 flex justify-center mr-1.5 text-gray-400"><i class="far fa-calendar-alt"></i></div>
                                                    {{ $proposal->hari }}
                                                </div>
                                                <div class="flex items-center text-gray-600 text-sm">
                                                    <div class="w-6 flex justify-center mr-1.5 text-gray-400"><i class="far fa-clock"></i></div>
                                                    {{ substr($proposal->jam_mulai, 0, 5) }} - {{ substr($proposal->jam_selesai, 0, 5) }}
                                                </div>
                                                <div class="flex items-center text-gray-600 text-sm">
                                                    <div class="w-6 flex justify-center mr-1.5 text-gray-400"><i class="fas fa-door-open"></i></div>
                                                    {{ $proposal->ruangan ?: 'Belum ditentukan' }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Lecturer Proposal (Usulan Dosen) -->
                                        @if($dosenApproval && ($dosenApproval->hari_pengganti || $dosenApproval->jam_mulai_pengganti || $dosenApproval->ruangan_pengganti))
                                            <div class="hidden xl:flex items-center self-center text-gray-300 mt-4">
                                                <i class="fas fa-arrow-right"></i>
                                            </div>
                                            
                                            <div class="bg-red-50/50 rounded-lg p-3 border border-red-100 min-w-[150px]">
                                                <div class="text-[10px] font-bold text-maroon uppercase tracking-wider mb-1.5 flex items-center h-4">
                                                    Usulan Perubahan
                                                    <span class="ml-2 w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                                </div>
                                                <div class="space-y-1">
                                                    @if($dosenApproval->hari_pengganti)
                                                        <div class="flex items-center text-gray-900 font-medium text-sm">
                                                            <div class="w-6 flex justify-center mr-1.5 text-maroon/60"><i class="far fa-calendar-alt"></i></div>
                                                            {{ $dosenApproval->hari_pengganti }}
                                                        </div>
                                                    @endif
                                                    @if(!empty($dosenApproval->jam_mulai_pengganti))
                                                        <div class="flex items-center text-gray-600 text-sm">
                                                            <div class="w-6 flex justify-center mr-1.5 text-maroon/60"><i class="far fa-clock"></i></div>
                                                            {{ substr($dosenApproval->jam_mulai_pengganti, 0, 5) }} - {{ substr($dosenApproval->jam_selesai_pengganti, 0, 5) }}
                                                        </div>
                                                    @endif
                                                    <div class="flex items-center text-gray-600 text-sm">
                                                        <div class="w-6 flex justify-center mr-1.5 text-maroon/60"><i class="fas fa-door-open"></i></div>
                                                        <span class="{{ !$dosenApproval->ruangan_pengganti ? 'italic text-gray-400' : '' }}">
                                                            {{ $dosenApproval->ruangan_pengganti ?: 'Sama / Belum ada' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($proposal->status === 'approved_dosen')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i> Ditolak
                                        </span>
                                        @if($dosenApproval && !empty($dosenApproval->alasan_penolakan))
                                            <div class="mt-2 text-xs text-gray-500 max-w-[220px] truncate" title="{{ $dosenApproval->alasan_penolakan }}">Alasan: {{ Str::limit($dosenApproval->alasan_penolakan, 80) }}</div>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('admin.jadwal_admin_approval.show', $proposal->id) }}" class="p-2 bg-maroon/10 text-maroon rounded-lg hover:bg-maroon/20 transition" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.jadwal_admin_approval.approve', $proposal->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" onclick="event.preventDefault(); showConfirm('Apakah Anda yakin ingin menyetujui proposal ini?', () => this.closest('form').submit());" class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition" title="Setujui Langsung">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-check-circle fa-3x mb-4 text-gray-200"></i>
                                        <p class="font-medium">Tidak ada proposal yang butuh persetujuan saat ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- In Review Tab -->
            <div x-show="activeTab === 'in_review'" class="overflow-x-auto" style="display: none;">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Kuliah & Kelas</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dosen</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jadwal</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Admin</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php $rejected = $proposals->where('status', 'rejected_dosen'); @endphp
                        @forelse($rejected as $proposal)
                            <tr class="hover:bg-gray-50/50 transition duration-200">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $proposal->mataKuliah->nama_mk }}</div>
                                    <div class="text-xs text-gray-500 font-medium">Kelas {{ $proposal->kelas->section ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $proposal->dosen->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-700">
                                    {{ $proposal->hari }}, {{ substr($proposal->jam_mulai, 0, 5) }} - {{ substr($proposal->jam_selesai, 0, 5) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i> Ditolak Dosen
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.jadwal_admin_approval.show', $proposal->id) }}" class="inline-flex items-center px-3 py-1.5 bg-maroon text-white text-xs font-medium rounded-lg hover:bg-maroon/90 transition">
                                        Proses
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <p class="font-medium">Tidak ada histori dosen yang menolak.</p>
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
