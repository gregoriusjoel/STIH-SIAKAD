@extends('layouts.app')

@section('title', 'Detail Proposal Jadwal - Admin')

@section('content')
<div class="px-4 py-6 md:px-8">
    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center text-sm text-gray-500 mb-2">
                <a href="{{ route('admin.jadwal.index') }}" class="hover:text-maroon">Jadwal</a>
                <span class="mx-2">/</span>
                <a href="{{ route('admin.jadwal_admin_approval.index') }}" class="hover:text-maroon">Persetujuan</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900">Detail Proposal</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-file-invoice text-maroon mr-3"></i>
                Detail Proposal Jadwal
            </h1>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.jadwal_admin_approval.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900">Informasi Mata Kuliah & Kelas</h3>
                    <span class="px-3 py-1 bg-maroon/10 text-maroon text-xs font-bold rounded-full uppercase tracking-wider">
                        Proposal #{{ $proposal->id }}
                    </span>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Mata Kuliah</p>
                        <p class="text-lg font-bold text-gray-900">{{ $proposal->mataKuliah->nama_mk }}</p>
                        <p class="text-sm text-gray-500">{{ $proposal->mataKuliah->kode_mk }} • {{ $proposal->mataKuliah->sks }} SKS</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Dosen Pengampu</p>
                        <p class="text-lg font-bold text-gray-900">{{ $proposal->dosen->user->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">NIDN: {{ $proposal->dosen->nidn ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Kelas</p>
                        <div class="flex items-center mt-1">
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 font-bold rounded-lg mr-2">{{ $proposal->kelas->section ?? '-' }}</span>
                            <span class="text-sm text-gray-500">Kapasitas: {{ $proposal->kelas->kapasitas ?? 0 }} Mahasiswa</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Semester</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $proposal->mataKuliah->semester }} ({{ $proposal->mataKuliah->semester % 2 == 0 ? 'Genap' : 'Ganjil' }})</p>
                    </div>
                </div>
            </div>

            <!-- Proposed Schedule -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center">
                    <i class="far fa-calendar-check text-green-500 mr-2"></i>
                    <h3 class="font-bold text-gray-900">Jadwal yang Diajukan</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex items-center p-4 bg-green-50 rounded-xl">
                        <div class="p-3 bg-white rounded-lg text-green-600 shadow-sm mr-4">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-green-800 uppercase tracking-tight">Hari</p>
                            <p class="text-lg font-bold text-gray-900">{{ $proposal->hari }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-blue-50 rounded-xl">
                        <div class="p-3 bg-white rounded-lg text-blue-600 shadow-sm mr-4">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-blue-800 uppercase tracking-tight">Waktu</p>
                            <p class="text-lg font-bold text-gray-900">{{ substr($proposal->jam_mulai, 0, 5) }} - {{ substr($proposal->jam_selesai, 0, 5) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-purple-50 rounded-xl">
                        <div class="p-3 bg-white rounded-lg text-purple-600 shadow-sm mr-4">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-purple-800 uppercase tracking-tight">Ruangan</p>
                            <p class="text-lg font-bold text-gray-900">{{ $proposal->ruangan ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approval History -->
            @if($proposal->approvals->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center">
                        <i class="fas fa-history text-gray-400 mr-2"></i>
                        <h3 class="font-bold text-gray-900">Riwayat Approval</h3>
                    </div>
                    <div class="p-0">
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-6 py-3">User</th>
                                        <th class="px-6 py-3">Status</th>
                                        <th class="px-6 py-3">Waktu</th>
                                        <th class="px-6 py-3">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($proposal->approvals->sortByDesc('created_at') as $approval)
                                        <tr class="text-sm">
                                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $approval->user->name }}</td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $statusLabel = [
                                                        'pending' => 'Pending',
                                                        'approved' => 'Disetujui',
                                                        'rejected' => 'Ditolak',
                                                        'approved_with_changes' => 'Disetujui dgn Perubahan'
                                                    ];
                                                    $statusColor = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'approved' => 'bg-green-100 text-green-800',
                                                        'rejected' => 'bg-red-100 text-red-800',
                                                        'approved_with_changes' => 'bg-blue-100 text-blue-800'
                                                    ];
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full font-medium {{ $statusColor[$approval->status] ?? 'bg-gray-100' }}">
                                                    {{ $statusLabel[$approval->status] ?? $approval->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-gray-500">{{ $approval->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 text-gray-600">{{ $approval->comments ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Action Sidebar -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-6 text-lg">Persetujuan Admin</h3>
                
                <form action="{{ route('admin.jadwal_admin_approval.process', $proposal->id) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Keputusan</label>
                        <select name="status" id="status_select" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-maroon focus:border-transparent transition text-sm font-semibold" required onchange="toggleChangesFields()">
                            <option value="approved">Setujui Proposal</option>
                            <option value="approved_with_changes">Setujui dengan Perubahan</option>
                            <option value="rejected">Tolak Proposal</option>
                        </select>
                    </div>

                    <div id="changes_fields" class="space-y-4 pt-4 border-t border-gray-100 mt-4" style="display: none;">
                        <p class="text-xs font-bold text-maroon uppercase">Perubahan Jadwal</p>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Hari</label>
                            <select name="hari" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                                    <option value="{{ $h }}" {{ $proposal->hari == $h ? 'selected' : '' }}>{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Jam Mulai</label>
                                <input type="time" name="jam_mulai" value="{{ substr($proposal->jam_mulai, 0, 5) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Jam Selesai</label>
                                <input type="time" name="jam_selesai" value="{{ substr($proposal->jam_selesai, 0, 5) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Ruangan</label>
                            <input type="text" name="ruangan" value="{{ $proposal->ruangan }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Contoh: R.401">
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Admin</label>
                        <textarea name="comments" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-maroon focus:border-transparent transition text-sm" placeholder="Opsional untuk persetujuan, wajib jika ditolak..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-4 bg-maroon text-white font-bold rounded-xl hover:bg-maroon/90 shadow-lg shadow-maroon/20 transition-all flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i> Simpan Keputusan
                    </button>
                </form>
            </div>

            <!-- Status Alert -->
            <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-blue-800">Review Status</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Setelah disetujui, jadwal ini akan langsung masuk ke daftar <strong>Jadwal Aktif</strong> dan bisa dilihat oleh Dosen & Mahasiswa.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleChangesFields() {
        const status = document.getElementById('status_select').value;
        const fields = document.getElementById('changes_fields');
        if (status === 'approved_with_changes') {
            fields.style.display = 'block';
        } else {
            fields.style.display = 'none';
        }
    }
</script>
@endpush

@endsection
