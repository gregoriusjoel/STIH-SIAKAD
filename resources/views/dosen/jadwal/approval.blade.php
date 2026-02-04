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
    
    openRejectModal(id) {
        this.rejectProposalId = id;
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
            return;
        }
        fetch('{{ route("dosen.jadwal_approval.available_slots", ":hari") }}'.replace(':hari', this.hariPengganti))
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
        if (!confirm('Yakin ingin menyetujui proposal jadwal ini?')) return;
        
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
                location.reload();
            } else {
                alert('Gagal menyetujui proposal: ' + data.message);
            }
        })
        .catch(err => alert('Error: ' + err.message));
    },
    
    submitRejection() {
        if (!this.rejectProposalId) return;
        // Validate alternative schedule is provided
        if (!this.hariPengganti || !this.jamMulaiPengganti || !this.jamSelesaiPengganti) {
            alert('Mohon lengkapi Usulan Jadwal Alternatif (hari, jam mulai, dan jam selesai).');
            return;
        }

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
                // Try to parse JSON, otherwise return raw text
                try { return JSON.parse(text); } catch (e) { return { __raw: text, ok: res.ok }; }
            });
        })
        .then(data => {
            if (data && data.success) {
                location.reload();
                return;
            }

            // Determine message from possible keys
            let msg = 'Terjadi kesalahan';
            if (!data) msg = 'No response from server';
            else if (data.message) msg = data.message;
            else if (data.error) msg = data.error;
            else if (data.errors) {
                // Laravel validation errors
                const errs = Object.values(data.errors).flat();
                msg = errs.join(', ');
            } else if (data.__raw) {
                msg = data.__raw;
            }

            alert('Gagal menolak proposal: ' + msg);
        })
        .catch(err => {
            let msg = 'Error';
            if (err && typeof err === 'object') {
                msg = err.message || err.error || JSON.stringify(err);
            } else msg = String(err);
            alert('Gagal menolak proposal: ' + msg);
        });
    }
}">
    <!-- Header Section -->
    <div class="flex flex-col gap-1 mb-2">
        <h1 class="text-2xl font-bold text-[#111218] dark:text-white">
            Persetujuan Jadwal Kuliah
        </h1>
        <div class="flex items-center gap-2 text-xs font-medium text-gray-500">
            <a href="{{ route('dosen.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-gray-900 dark:text-white">Persetujuan Jadwal</span>
        </div>
        </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Row 1 -->
        <div class="bg-white dark:bg-[#1a1d2e] p-5 rounded-lg border border-gray-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
            <div class="flex flex-col h-full justify-between">
                <div class="mb-4">
                     <!-- No icon for this one based on screenshot preference or clean look, but can add if needed -->
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">MENUNGGU REVIEW</p>
            </div>
            <span class="text-2xl font-bold text-[#111218] dark:text-white self-start mt-1 mr-2">{{ $pendingProposals->count() }}</span>
        </div>

        <div class="bg-white dark:bg-[#1a1d2e] p-5 rounded-lg border border-gray-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
            <div class="flex flex-col h-full justify-between">
                <div class="mb-4 text-blue-500">
                     <span class="material-symbols-outlined text-xl">thumb_up</span>
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">DISETUJUI DOSEN</p>
            </div>
            <span class="text-2xl font-bold text-[#111218] dark:text-white self-start mt-1 mr-2">{{ $approvedProposals->count() }}</span>
        </div>

        <div class="bg-white dark:bg-[#1a1d2e] p-5 rounded-lg border border-gray-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
            <div class="flex flex-col h-full justify-between">
                <div class="mb-4 text-gray-400">
                     <span class="material-symbols-outlined text-xl">close</span>
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">DITOLAK DOSEN</p>
            </div>
            <span class="text-2xl font-bold text-[#111218] dark:text-white self-start mt-1 mr-2">{{ $rejectedProposals->count() }}</span>
        </div>

        <!-- Row 2 -->
        <div class="bg-white dark:bg-[#1a1d2e] p-5 rounded-lg border border-gray-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
            <div class="flex flex-col h-full justify-between">
                <div class="mb-4 text-red-100"> <!-- Using slightly different look to match screenshot vibe if needed, but sticking to clean -->
                     <span class="material-symbols-outlined text-xl text-red-300">pending_actions</span>
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">REVIEW ADMIN</p>
            </div>
            <span class="text-2xl font-bold text-[#111218] dark:text-white self-start mt-1 mr-2">{{ $inAdminReview->count() }}</span>
        </div>

        <div class="bg-white dark:bg-[#1a1d2e] p-5 rounded-lg border border-gray-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
            <div class="flex flex-col h-full justify-between">
                <div class="mb-4 text-green-500">
                     <span class="material-symbols-outlined text-xl">check_circle</span>
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">JADWAL AKTIF</p>
            </div>
            <span class="text-2xl font-bold text-[#111218] dark:text-white self-start mt-1 mr-2">{{ $finalApproved->count() }}</span>
        </div>

        <div class="bg-white dark:bg-[#1a1d2e] p-5 rounded-lg border border-gray-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
            <div class="flex flex-col h-full justify-between">
                <div class="mb-4 text-red-500">
                     <span class="material-symbols-outlined text-xl">block</span>
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">DITOLAK ADMIN</p>
            </div>
            <span class="text-2xl font-bold text-[#111218] dark:text-white self-start mt-1 mr-2">{{ $finalRejected->count() }}</span>
        </div>
    </div>

    <!-- Pending Proposals Table (Main Focus) -->
    <div class="bg-white dark:bg-[#1a1d2e] rounded-xl border border-gray-200 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col w-full border-t-4 border-t-black dark:border-t-white">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-800 flex items-center bg-white dark:bg-[#1a1d2e]">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-xl font-bold">assignment</span>
                <h3 class="text-sm font-bold uppercase tracking-wider text-[#111218] dark:text-white">PROPOSAL MENUNGGU PERSETUJUAN ANDA ({{ $pendingProposals->count() }})</h3>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-800">
                <thead class="bg-white dark:bg-[#1a1d2e]">
                    <tr>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">MATA KULIAH</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">KELAS</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">JADWAL USULAN</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">RUANGAN</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">DIBUAT OLEH</th>
                        <th class="px-6 py-4 text-center text-[11px] font-bold text-gray-400 uppercase tracking-wider">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse($pendingProposals as $proposal)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-6 py-6">
                            <div class="text-sm font-bold text-[#111218] dark:text-white">{{ $proposal->mataKuliah->nama_mk }}</div>
                            <div class="text-[10px] text-gray-400 uppercase tracking-wide mt-1">{{ $proposal->mataKuliah->kode_mk }} • {{ $proposal->mataKuliah->sks }} SKS</div>
                        </td>
                        <td class="px-6 py-6 text-sm font-medium text-gray-600 dark:text-slate-300">
                            {{ $proposal->kelas->section }}
                        </td>
                        <td class="px-6 py-6" style="vertical-align: middle;">
                            <div class="text-sm font-bold text-[#8B1538]">{{ $proposal->hari }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                {{ \Carbon\Carbon::parse($proposal->jam_mulai)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($proposal->jam_selesai)->format('H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <span class="bg-gray-100/80 text-gray-700 dark:bg-slate-800 dark:text-gray-300 px-3 py-1.5 rounded-md text-xs font-bold border border-gray-200 dark:border-slate-700">
                                {{ $proposal->ruangan ?: 'TBA' }}
                            </span>
                        </td>
                        <td class="px-6 py-6 text-xs text-gray-500 font-medium">
                            {{ $proposal->generatedBy->name ?? 'System' }} 
                            <span class="text-gray-400 block text-[10px]">Staf Akademik</span>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="approveProposal('{{ $proposal->id }}')" 
                                        class="flex items-center gap-1 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-xs font-bold transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-sm">check</span> Setujui
                                </button>
                                <button @click="openRejectModal('{{ $proposal->id }}')" 
                                        class="flex items-center gap-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-bold transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-sm">close</span> Tolak
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">
                            Tidak ada proposal yang menunggu persetujuan Anda saat ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- History Section -->
    <div class="flex flex-col gap-0 bg-white dark:bg-[#1a1d2e] rounded-xl border border-gray-200 dark:border-slate-800 overflow-hidden">
        <!-- Banner Header -->
        <div class="bg-red-600 px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2 text-white">
                <span class="material-symbols-outlined text-xl">history</span>
                <span class="text-xs font-bold uppercase tracking-widest">HISTORY PENOLAKAN ({{ $rejectedProposals->count() + $finalRejected->count() }})</span>
            </div>
            <button @click="activeTab = activeTab === 'rejected' ? '' : 'rejected'" class="text-white hover:bg-white/10 rounded-full p-1 transition-colors">
                 <span class="material-symbols-outlined" :class="activeTab === 'rejected' ? 'rotate-180' : ''">keyboard_arrow_down</span>
            </button>
        </div>
        
        <!-- Table -->
        <div x-show="true" class="overflow-x-auto">
             <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-800">
                <thead class="bg-gray-50/50 dark:bg-slate-800/30">
                    <tr>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">MATA KULIAH</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">KELAS</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">JADWAL</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">RUANGAN</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">STATUS</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">ALASAN/USULAN</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">TANGGAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800 bg-white dark:bg-[#1a1d2e]">
                    @php
                        $allRejected = $rejectedProposals->merge($finalRejected);
                    @endphp
                    @forelse($allRejected as $proposal)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-[#111218] dark:text-white">{{ $proposal->mataKuliah->nama_mk }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $proposal->kelas->section }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium">{{ $proposal->hari }}, {{ \Carbon\Carbon::parse($proposal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($proposal->jam_selesai)->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $proposal->ruangan ?: '-' }}</td>
                        <td class="px-6 py-4">
                            @if($proposal->status == 'rejected_dosen')
                                <span class="px-2 py-1 bg-red-100 text-red-600 rounded text-[10px] font-bold uppercase">Ditolak Dosen</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-600 rounded text-[10px] font-bold uppercase">Ditolak Admin</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $lastApproval = $proposal->getLatestApproval();
                            @endphp
                            <div class="text-xs text-gray-600 max-w-[200px] truncate" title="{{ $lastApproval->alasan_penolakan ?? '-' }}">
                                {{ $lastApproval->alasan_penolakan ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500">
                            {{ $proposal->updated_at->format('d/m/Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                         <td colspan="7" class="px-6 py-8 text-center text-gray-400 text-sm">
                            Tidak ada data ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
             </table>
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
                    <div class="grid grid-cols-2 gap-3">
                         <select x-model="jamMulaiPengganti" @change="updateSelesai" :disabled="!hariPengganti" required class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 disabled:bg-gray-100">
                            <option value="">Jam Mulai</option>
                            <template x-for="slot in availableSlots" :key="slot.jam_mulai">
                                <option :value="slot.jam_mulai" x-text="slot.label"></option>
                            </template>
                        </select>
                        <input x-model="jamSelesaiPengganti" type="text" readonly placeholder="Jam Selesai" required class="w-full bg-gray-100 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-500 cursor-not-allowed">
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