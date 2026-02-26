@extends('layouts.app')

@section('title', 'Persetujuan Jadwal Kuliah')

@section('content')
<div class="px-6 py-6 w-full max-w-[1600px] mx-auto flex flex-col gap-6" x-data="{ 
    activeTab: '{{ $approvedProposals->count() > 0 ? 'approved' : ($inAdminReview->count() > 0 ? 'admin_review' : ($finalApproved->count() > 0 ? 'final_approved' : 'rejected')) }}',
    showRejectModal: false,
    rejectProposalId: null,
    hariPengganti: '',
    jamMulaiPengganti: '',
    jamSelesaiPengganti: '',
    alasanPenolakan: '',
    availableSlots: [],
    proposalSks: 1,
    
    openRejectModal(id, sks) {
        this.rejectProposalId = id;
        this.proposalSks = sks || 1;
        this.showRejectModal = true;
    },
    closeRejectModal() {
        this.showRejectModal = false;
        this.rejectProposalId = null;
        this.alasanPenolakan = '';
        this.hariPengganti = '';
        this.jamMulaiPengganti = '';
        this.jamSelesaiPengganti = '';
    },
    
    fetchSlots() {
        if (!this.hariPengganti) {
            this.availableSlots = [];
            this.availableSlots = [];
            return;
        }
        fetch('{{ route("dosen.jadwal_approval.available_slots", ":hari") }}'.replace(':hari', this.hariPengganti) + '?sks=' + this.proposalSks)
            .then(res => res.json())
            .then(data => {
                this.availableSlots = data;
            });
    },
    
    updateSelesai() {
        const slot = this.availableSlots.find(s => s.jam_mulai === this.jamMulaiPengganti);
        if (slot) {
            this.jamSelesaiPengganti = slot.jam_selesai;
        }
    },
    
    approveProposal(id) {
        Swal.fire({
            title: 'Setujui Jadwal?',
            text: 'Jadwal ini akan disetujui dan diajukan ke admin.',
            icon: 'question',
            iconColor: '#8B1538',
            showCancelButton: true,
            confirmButtonColor: '#8B1538',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                
                fetch(`{{ route('dosen.jadwal_approval.approve', ':id') }}`.replace(':id', id), {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Jadwal berhasil disetujui.',
                            icon: 'success',
                            confirmButtonColor: '#8B1538'
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Gagal menyetujui proposal: ' + data.message,
                            icon: 'error',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                })
                .catch(err => {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan sistem.',
                        icon: 'error',
                        confirmButtonColor: '#ef4444'
                    });
                });
            }
        });
    },
    
    submitRejection() {
        if (!this.rejectProposalId) return;
        // Validate alternative schedule is provided
        if (!this.hariPengganti || !this.jamMulaiPengganti || !this.jamSelesaiPengganti) {
            alert('Mohon lengkapi Usulan Jadwal Alternatif (hari, jam mulai, dan jam selesai).');
            return;
        }

        // Confirm rejection
        Swal.fire({
            title: 'Tolak Proposal?',
            text: 'Proposal jadwal ini akan ditolak dan dosen akan diberitahu.',
            icon: 'warning',
            iconColor: '#8B1538',
            showCancelButton: true,
            confirmButtonColor: '#8B1538',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Tolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('alasan_penolakan', this.alasanPenolakan);
                formData.append('hari_pengganti', this.hariPengganti);
                formData.append('jam_mulai_pengganti', this.jamMulaiPengganti);
                formData.append('jam_selesai_pengganti', this.jamSelesaiPengganti);

                fetch(`{{ route('dosen.jadwal_approval.reject', ':id') }}`.replace(':id', this.rejectProposalId), {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => {
                    return res.text().then(text => {
                        try { return JSON.parse(text); } catch (e) { return { __raw: text, ok: res.ok }; }
                    });
                })
                .then(data => {
                    if (data && data.success) {
                        Swal.fire({
                            title: 'Ditolak!',
                            text: 'Proposal berhasil ditolak.',
                            icon: 'success',
                            confirmButtonColor: '#8B1538'
                        }).then(() => location.reload());
                        return;
                    }

                    let msg = 'Terjadi kesalahan';
                    if (!data) msg = 'No response from server';
                    else if (data.message) msg = data.message;
                    else if (data.error) msg = data.error;
                    else if (data.errors) {
                        const errs = Object.values(data.errors).flat();
                        msg = errs.join(', ');
                    } else if (data.__raw) {
                        msg = data.__raw;
                    }

                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Gagal menolak proposal: ' + msg,
                        icon: 'error',
                        confirmButtonColor: '#ef4444'
                    });
                })
                .catch(err => {
                    let msg = 'Error';
                    if (err && typeof err === 'object') {
                        msg = err.message || err.error || JSON.stringify(err);
                    } else msg = String(err);
                    
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan sistem: ' + msg,
                        icon: 'error',
                        confirmButtonColor: '#ef4444'
                    });
                });
            }
        });
    }
}">
    <!-- Header Section -->
    <div class="flex flex-col gap-1 mb-6 mt-2">
        <h1 class="text-3xl font-black text-[#111218] dark:text-white tracking-tight flex items-center gap-3">
            <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[24px]">fact_check</span>
            </div>
            Persetujuan Jadwal Kuliah
        </h1>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
        <!-- Menunggu Review -->
        <div class="bg-white dark:bg-[#1a1d2e] p-5 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] hover:shadow-md transition-all flex flex-col justify-between h-full min-h-[110px] relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-amber-50/60 dark:bg-amber-900/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-amber-100/60 transition-colors"></div>
            <div class="flex items-start justify-between relative z-10 mb-4">
                <div class="w-10 h-10 bg-amber-50 dark:bg-amber-900/20 text-amber-500 rounded-xl flex items-center justify-center border border-amber-100 dark:border-amber-800/30">
                    <span class="material-symbols-outlined text-[20px]">assignment_turned_in</span>
                </div>
                <span class="text-3xl font-black text-[#111218] dark:text-white leading-none">{{ $pendingProposals->count() }}</span>
            </div>
            <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider relative z-10 truncate">MENUNGGU REVIEW</p>
        </div>

        <!-- Disetujui Dosen -->
        <div class="bg-white dark:bg-[#1a1d2e] p-5 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] hover:shadow-md transition-all flex flex-col justify-between h-full min-h-[110px] relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-blue-50/60 dark:bg-blue-900/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-blue-100/60 transition-colors"></div>
            <div class="flex items-start justify-between relative z-10 mb-4">
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/20 text-blue-500 rounded-xl flex items-center justify-center border border-blue-100 dark:border-blue-800/30">
                    <span class="material-symbols-outlined text-[20px]">thumb_up</span>
                </div>
                <span class="text-3xl font-black text-[#111218] dark:text-white leading-none">{{ $approvedProposals->count() }}</span>
            </div>
            <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider relative z-10 truncate">DISETUJUI DOSEN</p>
        </div>

        <!-- Ditolak Dosen -->
        <div class="bg-white dark:bg-[#1a1d2e] p-5 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] hover:shadow-md transition-all flex flex-col justify-between h-full min-h-[110px] relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-red-50/60 dark:bg-red-900/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-red-100/60 transition-colors"></div>
            <div class="flex items-start justify-between relative z-10 mb-4">
                <div class="w-10 h-10 bg-red-50 dark:bg-red-900/20 text-red-500 rounded-xl flex items-center justify-center border border-red-100 dark:border-red-800/30">
                    <span class="material-symbols-outlined text-[20px]">thumb_down</span>
                </div>
                <span class="text-3xl font-black text-[#111218] dark:text-white leading-none">{{ $rejectedProposals->count() }}</span>
            </div>
            <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider relative z-10 truncate">DITOLAK DOSEN</p>
        </div>

        <!-- Review Admin -->
        <div class="bg-white dark:bg-[#1a1d2e] p-5 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] hover:shadow-md transition-all flex flex-col justify-between h-full min-h-[110px] relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-purple-50/60 dark:bg-purple-900/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-purple-100/60 transition-colors"></div>
            <div class="flex items-start justify-between relative z-10 mb-4">
                <div class="w-10 h-10 bg-purple-50 dark:bg-purple-900/20 text-purple-600 rounded-xl flex items-center justify-center border border-purple-100 dark:border-purple-800/30">
                    <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
                </div>
                <span class="text-3xl font-black text-[#111218] dark:text-white leading-none">{{ $inAdminReview->count() }}</span>
            </div>
            <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider relative z-10 truncate">REVIEW ADMIN</p>
        </div>

        <!-- Jadwal Aktif -->
        <div class="bg-white dark:bg-[#1a1d2e] p-5 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] hover:shadow-md transition-all flex flex-col justify-between h-full min-h-[110px] relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-50/60 dark:bg-emerald-900/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-emerald-100/60 transition-colors"></div>
            <div class="flex items-start justify-between relative z-10 mb-4">
                <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 rounded-xl flex items-center justify-center border border-emerald-100 dark:border-emerald-800/30">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                </div>
                <span class="text-3xl font-black text-[#111218] dark:text-white leading-none">{{ $finalApproved->count() }}</span>
            </div>
            <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider relative z-10 truncate">JADWAL AKTIF</p>
        </div>

        <!-- Ditolak Admin -->
        <div class="bg-white dark:bg-[#1a1d2e] p-5 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] hover:shadow-md transition-all flex flex-col justify-between h-full min-h-[110px] relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-rose-50/60 dark:bg-rose-900/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-rose-100/60 transition-colors"></div>
            <div class="flex items-start justify-between relative z-10 mb-4">
                <div class="w-10 h-10 bg-rose-50 dark:bg-rose-900/20 text-rose-500 rounded-xl flex items-center justify-center border border-rose-100 dark:border-rose-800/30">
                    <span class="material-symbols-outlined text-[20px]">cancel</span>
                </div>
                <span class="text-3xl font-black text-[#111218] dark:text-white leading-none">{{ $finalRejected->count() }}</span>
            </div>
            <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider relative z-10 truncate">DITOLAK ADMIN</p>
        </div>
    </div>

    <!-- Tables Grid Layout 50:50 -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8 items-stretch">
        <!-- Pending Proposals Table (Main Focus) -->
        <div class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-100 dark:border-slate-800 shadow-md overflow-hidden flex flex-col w-full relative h-full min-w-0">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-400 via-primary to-pink-500"></div>
        <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-800 flex items-center bg-gray-50/50 dark:bg-[#1a1d2e]">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-white dark:bg-slate-800 flex items-center justify-center shadow-sm border border-gray-100 dark:border-slate-700 text-primary">
                    <span class="material-symbols-outlined text-[18px]">assignment_add</span>
                </div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-[#111218] dark:text-white">PROPOSAL MENUNGGU PERSETUJUAN ANDA ({{ $pendingProposals->count() }})</h3>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-800">
                <thead class="bg-white dark:bg-[#1a1d2e]">
                    <tr>
                        <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wide">MATA KULIAH</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wide">KELAS</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wide">JADWAL USULAN</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wide">RUANGAN</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wide">DIBUAT OLEH</th>
                        <th class="px-4 py-3 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wide">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse($pendingProposals as $proposal)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-4 py-4">
                            <div class="text-xs font-bold text-[#111218] dark:text-white leading-tight min-w-[120px]">{{ $proposal->mataKuliah->nama_mk }}</div>
                            <div class="text-[9px] text-gray-400 uppercase tracking-wide mt-1">{{ $proposal->mataKuliah->kode_mk }} • {{ $proposal->mataKuliah->sks }} SKS</div>
                        </td>
                        <td class="px-4 py-4 text-xs font-medium text-gray-600 dark:text-slate-300">
                            {{ $proposal->kelas->section }}
                        </td>
                        <td class="px-4 py-4" style="vertical-align: middle;">
                            <div class="text-xs font-bold text-[#8B1538]">{{ $proposal->hari }}</div>
                            <div class="text-[10px] text-gray-500 mt-0.5 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($proposal->jam_mulai)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($proposal->jam_selesai)->format('H:i') }}
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="bg-gray-100/80 text-gray-700 dark:bg-slate-800 dark:text-gray-300 px-2 py-1 rounded-md text-[10px] font-bold border border-gray-200 dark:border-slate-700 whitespace-nowrap">
                                {{ $proposal->ruangan ?: 'TBA' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-[10px] text-gray-500 font-medium leading-tight">
                            {{ $proposal->generatedBy->name ?? 'System' }} 
                            <span class="text-gray-400 block text-[9px] mt-0.5">Staf Akademik</span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="approveProposal('{{ $proposal->id }}')" 
                                        class="group flex items-center justify-center gap-1 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-500/10 hover:bg-emerald-500 text-emerald-600 dark:text-emerald-400 hover:text-white rounded-lg text-[10px] font-bold transition-all duration-300 shadow-sm border border-emerald-200 dark:border-emerald-500/20 hover:border-emerald-500 hover:shadow-emerald-500/20">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span> Setujui
                                </button>
                                <button @click="openRejectModal('{{ $proposal->id }}', {{ $proposal->mataKuliah->sks ?? 1 }})" 
                                        class="group flex items-center justify-center gap-1 px-3 py-1.5 bg-rose-50 dark:bg-rose-500/10 hover:bg-rose-500 text-rose-600 dark:text-rose-400 hover:text-white rounded-lg text-[10px] font-bold transition-all duration-300 shadow-sm border border-rose-200 dark:border-rose-500/20 hover:border-rose-500 hover:shadow-rose-500/20">
                                    <span class="material-symbols-outlined text-[14px]">cancel</span> Tolak
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-gray-400 text-xs">
                            Tidak ada proposal yang menunggu persetujuan Anda saat ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- History Section -->
        <div class="flex flex-col gap-0 bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-100 dark:border-slate-800 shadow-md overflow-hidden relative h-full min-w-0">
        <!-- Banner Header -->
        <div class="bg-[#8B1538] px-6 py-4 flex flex-col 2xl:flex-row 2xl:items-center justify-between gap-4">
            <div class="flex items-center justify-between w-full 2xl:w-auto">
                <div class="flex items-center gap-2 text-white">
                    <span class="material-symbols-outlined text-xl">history</span>
                    <span class="text-xs font-bold uppercase tracking-widest leading-tight">HISTORY PENGAJUAN PENJADWALAN ({{ $historyApprovals->total() }})</span>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full 2xl:w-auto">
                <form action="" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                        <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-800 text-sm rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-200 transition-all cursor-pointer w-full sm:w-auto min-w-[150px] shadow-sm">
                            <option value="">Semua Status</option>
                            <option value="approve" {{ request('status') === 'approve' ? 'selected' : '' }}>Disetujui</option>
                            <option value="reject" {{ request('status') === 'reject' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        <div class="relative w-full sm:w-[240px]">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari mata kuliah..." class="bg-white border border-gray-200 text-gray-800 placeholder-gray-400 text-sm rounded-lg pl-4 pr-10 py-2 focus:outline-none focus:ring-2 focus:ring-red-200 w-full transition-all shadow-sm">
                            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">search</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Table -->
        <div class="w-full overflow-x-auto overflow-y-hidden">
             <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-800">
                <thead class="bg-gray-50/50 dark:bg-slate-800/30">
                    <tr>
                        <th class="px-4 py-3 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wide">MATA KULIAH</th>
                        <th class="px-4 py-3 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wide">KELAS</th>
                        <th class="px-4 py-3 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wide">JADWAL</th>
                        <th class="px-4 py-3 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wide">RUANGAN</th>
                        <th class="px-4 py-3 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wide">STATUS</th>
                        <th class="px-4 py-3 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wide">ALASAN/USULAN</th>
                        <th class="px-4 py-3 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wide">TANGGAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800 bg-white dark:bg-[#1a1d2e]">
                    @forelse($historyApprovals as $approval)
                    @php $proposal = $approval->jadwalProposal; @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-4 py-3 text-center">
                            <div class="text-xs font-bold text-[#111218] dark:text-white leading-tight mx-auto min-w-[120px]">{{ $proposal->mataKuliah->nama_mk }}</div>
                        </td>
                        <td class="px-4 py-3 text-center text-xs font-medium text-gray-600">{{ $proposal->kelas->section }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="text-xs font-bold text-[#8B1538] leading-none mb-1">{{ $proposal->hari }}</div>
                            <div class="text-[10px] text-gray-500 font-medium whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($proposal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($proposal->jam_selesai)->format('H:i') }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center text-xs font-medium text-gray-600 whitespace-nowrap">{{ $proposal->ruangan ?: '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($approval->action == 'reject')
                                <span class="px-2 py-1 bg-red-100 text-red-600 rounded text-[9px] font-bold uppercase whitespace-nowrap">Ditolak Dosen</span>
                            @else
                                @if($proposal->status == 'approved_admin')
                                    <span class="px-2 py-1 bg-green-100 text-green-600 rounded text-[9px] font-bold uppercase whitespace-nowrap">Jadwal Aktif</span>
                                @elseif($proposal->status == 'rejected_admin')
                                    <span class="px-2 py-1 bg-red-100 text-red-600 rounded text-[9px] font-bold uppercase whitespace-nowrap">Ditolak Admin</span>
                                @elseif($proposal->status == 'pending_admin')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-600 rounded text-[9px] font-bold uppercase whitespace-nowrap">Review Admin</span>
                                @else
                                    <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded text-[9px] font-bold uppercase whitespace-nowrap">Disetujui Dosen</span>
                                @endif
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="text-[10px] text-gray-600 max-w-[140px] mx-auto truncate" title="{{ $approval->alasan_penolakan ?? '-' }}">
                                {{ $approval->alasan_penolakan ?? '-' }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center text-[10px] text-gray-500 whitespace-nowrap">
                            {{ $approval->created_at->format('d/m/Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                         <td colspan="7" class="px-4 py-8 text-center text-gray-400 text-xs">
                            Tidak ada data ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
             </table>
             
             @if($historyApprovals->hasPages())
                 <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/30">
                     {{ $historyApprovals->links() }}
                 </div>
             @endif
        </div>
    </div>
    </div>

    <!-- Modals (Reused from logic) -->
    <!-- Rejection Modal -->
    <div x-show="showRejectModal" 
         x-cloak 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="closeRejectModal"></div>
        
        <div class="relative bg-white dark:bg-[#0b1220] rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all border border-gray-100 dark:border-slate-800"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            
            <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between bg-white dark:bg-[#0b1220]">
                <h3 class="text-lg font-bold text-[#111218] dark:text-white">Tolak Proposal Jadwal</h3>
                <button @click="closeRejectModal" class="text-gray-400 hover:text-gray-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form id="rejectionForm" @submit.prevent="submitRejection" class="p-6 space-y-4">
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase text-gray-500 tracking-wide">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea x-model="alasanPenolakan" required 
                              class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all placeholder-gray-400 min-h-[100px]"
                              placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <h4 class="text-xs font-bold text-gray-600 uppercase tracking-wide mb-3">Usulan Jadwal Alternatif (Wajib)</h4>
                    <div class="grid grid-cols-1 gap-3 mb-3">
                        <select x-model="hariPengganti" @change="fetchSlots" required class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                            <option value="">Pilih Hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 gap-3">
                         <div>
                             <label class="flex items-center gap-2 text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-2">
                                 <span class="material-symbols-outlined text-gray-400 text-sm">schedule</span>
                                 Jam Perkuliahan <span class="text-red-500">*</span>
                             </label>
                             <select x-model="jamMulaiPengganti" @change="updateSelesai" :disabled="!hariPengganti" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all disabled:bg-gray-100 font-medium">
                                <option value="">Pilih Jam Perkuliahan</option>
                                <template x-for="slot in availableSlots" :key="slot.jam_mulai">
                                    <option :value="slot.jam_mulai" x-text="slot.label"></option>
                                </template>
                            </select>
                            <input x-model="jamSelesaiPengganti" type="hidden" required>
                         </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="closeRejectModal" class="px-4 py-2 rounded-lg text-sm font-bold text-gray-500 hover:bg-gray-100 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-bold shadow-md transition-colors">Tolak Proposal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection