@extends('layouts.admin')

@section('title', 'Detail Pengajuan Jadwal - Admin')

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
                <span class="text-gray-900">Detail Pengajuan</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-file-invoice text-maroon mr-3"></i>
                Detail Pengajuan Jadwal
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
                        Pengajuan #{{ $proposal->id }}
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
                                                        'approve' => 'Disetujui',
                                                        'reject' => 'Ditolak',
                                                    ];
                                                    $statusColor = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'approve' => 'bg-green-100 text-green-800',
                                                        'reject' => 'bg-red-100 text-red-800',
                                                    ];
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full font-medium {{ $statusColor[$approval->action] ?? 'bg-gray-100' }}">
                                                    {{ $statusLabel[$approval->action] ?? $approval->action }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-gray-500">{{ $approval->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 text-gray-600">{{ $approval->alasan_penolakan ?? '-' }}</td>
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
                            @php $defaultStatus = $proposal->status === 'rejected_dosen' ? 'approved_with_changes' : 'approved'; @endphp
                            <option value="approved" {{ $defaultStatus === 'approved' ? 'selected' : '' }}>Setujui Pengajuan</option>
                            <option value="approved_with_changes" {{ $defaultStatus === 'approved_with_changes' ? 'selected' : '' }}>Setujui dengan Perubahan</option>
                            <option value="rejected" {{ $defaultStatus === 'rejected' ? 'selected' : '' }}>Tolak Pengajuan</option>
                        </select>
                    </div>

                    <div id="changes_fields" class="space-y-4 pt-4 border-t border-gray-100 mt-4" style="display: none;">
                        <p class="text-xs font-bold text-maroon uppercase">Perubahan Jadwal</p>
                        @if(isset($dosenApproval) && ($dosenApproval->hari_pengganti || $dosenApproval->jam_mulai_pengganti))
                            <div class="mb-3">
                                <button type="button" id="use_dosen_suggestion" class="px-3 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg text-sm font-semibold">Gunakan Usulan Dosen</button>
                                <span class="text-xs text-gray-500 ml-2">(Isi form dengan usulan dosen)</span>
                            </div>
                        @endif
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Hari</label>
                            @php
                                $selectedHari = $proposal->hari;
                                if(isset($dosenApproval) && $proposal->status === 'rejected_dosen' && !empty($dosenApproval->hari_pengganti)) {
                                    $selectedHari = $dosenApproval->hari_pengganti;
                                }
                            @endphp
                            <select name="hari" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                                        <option value="{{ $h }}" {{ $selectedHari == $h ? 'selected' : '' }}>{{ $h }}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="grid grid-cols-1 gap-3">
                            <div class="relative">
                                @php
                                    $jamSlots = \App\Models\JamPerkuliahan::orderBy('jam_mulai')->get(['jam_ke', 'jam_mulai', 'jam_selesai']);
                                    $sks = $proposal->mataKuliah->sks ?? 1;
                                @endphp
                                <div x-data="{
                                    sks: {{ $sks }},
                                    slots: {{ Js::from($jamSlots) }},
                                    jamMulai: '{{ substr($proposal->jam_mulai, 0, 5) }}',
                                    jamSelesai: '{{ substr($proposal->jam_selesai, 0, 5) }}',
                                    validMulaiSlots: [],
                                    
                                    init() {
                                        this.calculateValidMulai();
                                        this.$watch('jamMulai', value => this.updateSelesai(value));
                                    },
                                    
                                    calculateValidMulai() {
                                        let valid = [];
                                        for (let i = 0; i <= this.slots.length - this.sks; i++) {
                                            let sMulai = this.slots[i];
                                            let sSelesai = this.slots[i + this.sks - 1];
                                            let label = this.sks === 1 
                                                ? `Jam ke-${sMulai.jam_ke} (${sMulai.jam_mulai.substring(0, 5)} - ${sMulai.jam_selesai.substring(0, 5)})`
                                                : `Jam ke-${sMulai.jam_ke} sd ${sSelesai.jam_ke} (${sMulai.jam_mulai.substring(0, 5)} - ${sSelesai.jam_selesai.substring(0, 5)})`;
                                            
                                            valid.push({
                                                jam_mulai: sMulai.jam_mulai,
                                                label: label
                                            });
                                        }
                                        this.validMulaiSlots = valid;
                                        
                                        // Ensure current jamMulai is in the list, otherwise add it so it renders correctly
                                        if (this.jamMulai && !valid.some(s => s.jam_mulai.substring(0, 5) === this.jamMulai)) {
                                            valid.push({jam_mulai: this.jamMulai + ':00', label: 'Jam Kustom (' + this.jamMulai + ' - ' + this.jamSelesai + ')'});
                                            // re-sort based on time just in case
                                            valid.sort((a,b) => a.jam_mulai.localeCompare(b.jam_mulai));
                                        }
                                    },
                                    
                                    updateSelesai(mulaiVal) {
                                        if (!mulaiVal) {
                                            this.jamSelesai = '';
                                            return;
                                        }
                                        let sIndex = this.slots.findIndex(s => s.jam_mulai.substring(0, 5) === mulaiVal);
                                        if (sIndex !== -1 && sIndex + this.sks - 1 < this.slots.length) {
                                            this.jamSelesai = this.slots[sIndex + this.sks - 1].jam_selesai.substring(0, 5);
                                        }
                                    }
                                }">
                                    <label class="flex items-center gap-2 text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        <span class="material-symbols-outlined text-gray-400 text-sm">schedule</span>
                                        Jam Perkuliahan <span class="text-maroon">*</span>
                                    </label>
                                    <select name="jam_mulai" id="admin_jam_mulai" x-model="jamMulai" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-maroon focus:ring-1 focus:ring-maroon transition-all font-medium">
                                        <option value="">Pilih Jam Perkuliahan</option>
                                        <template x-for="slot in validMulaiSlots" :key="slot.jam_mulai">
                                            <option :value="slot.jam_mulai.substring(0, 5)" x-text="slot.label"></option>
                                        </template>
                                    </select>
                                    <input type="hidden" name="jam_selesai" id="admin_jam_selesai" x-model="jamSelesai" required>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Ruangan</label>
                            <select name="ruangan" id="admin_ruangan" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-maroon focus:ring-1 focus:ring-maroon transition-all">
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach(\App\Models\Ruangan::where('status', 'aktif')->orderBy('nama_ruangan')->get() as $ruang)
                                    <option value="{{ $ruang->kode_ruangan }}" {{ $proposal->ruangan == $ruang->kode_ruangan ? 'selected' : '' }}>
                                        {{ $ruang->nama_ruangan }} ({{ $ruang->kode_ruangan }})
                                    </option>
                                @endforeach
                            </select>
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
                <script>
                    // Intercept form submit when admin is responding to a dosen rejection
                    (function(){
                        const originalStatus = '{{ $proposal->status }}';
                        const form = document.querySelector('form[action="{{ route('admin.jadwal_admin_approval.process', $proposal->id) }}"]');
                        if (!form) return;

                        form.addEventListener('submit', function(e){
                            // If original proposal came from a dosen rejection, and admin chose "approved_with_changes",
                            // we should send the changes back to dosen (pending_dosen) via process-dosen-request route.
                            const statusSelect = document.getElementById('status_select');
                            if (originalStatus === 'rejected_dosen' && statusSelect && statusSelect.value === 'approved_with_changes') {
                                e.preventDefault();
                                const fd = new FormData();
                                fd.append('_token', '{{ csrf_token() }}');
                                fd.append('action', 'propose_new');
                                fd.append('hari', form.querySelector('[name="hari"]').value);
                                fd.append('jam_mulai', form.querySelector('[name="jam_mulai"]').value);
                                fd.append('jam_selesai', form.querySelector('[name="jam_selesai"]').value);
                                fd.append('ruangan', form.querySelector('[name="ruangan"]').value);
                                // map comments -> catatan
                                fd.append('catatan', form.querySelector('[name="comments"]').value || '');

                                fetch('{{ route('admin.jadwal_admin_approval.process_dosen_request', $proposal->id) }}', {
                                    method: 'POST',
                                    body: fd,
                                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                                }).then(r => r.json()).then(data => {
                                    if (data.success) {
                                        // reload to reflect status change
                                        location.reload();
                                    } else {
                                        showError(data.error || 'Gagal mengirim usulan perubahan');
                                    }
                                }).catch(err => showError('Error: ' + err.message));
                                // ensure changes fields visibility is updated after page load
                                setTimeout(function(){ if (typeof toggleChangesFields === 'function') toggleChangesFields(); }, 100);
                            }
                            // otherwise, let the form submit normally to existing process route
                        });
                    })();
                </script>
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
    document.addEventListener('DOMContentLoaded', function(){
        // Ensure fields visibility matches selected decision on load
        try { toggleChangesFields(); } catch (e) { /* ignore */ }
    });
    
    // Apply dosen suggestion into the changes fields when button clicked
    (function(){
        const btn = document.getElementById('use_dosen_suggestion');
        if (!btn) return;
        btn.addEventListener('click', function(){
            try {
                // Values from server-side variables
                const sugHari = '{{ $dosenApproval->hari_pengganti ?? '' }}';
                const sugMulai = '{{ isset($dosenApproval->jam_mulai_pengganti) ? substr($dosenApproval->jam_mulai_pengganti,0,5) : '' }}';
                const sugSelesai = '{{ isset($dosenApproval->jam_selesai_pengganti) ? substr($dosenApproval->jam_selesai_pengganti,0,5) : '' }}';
                const sugRuangan = '{{ $dosenApproval->ruangan_pengganti ?? '' }}';

                if (sugHari) {
                    const hariSelect = document.querySelector('[name="hari"]');
                    if (hariSelect) hariSelect.value = sugHari;
                }
                if (sugMulai) {
                    const jamMulai = document.getElementById('admin_jam_mulai');
                    if (jamMulai) jamMulai.value = sugMulai;
                }
                if (sugSelesai) {
                    const jamSelesai = document.getElementById('admin_jam_selesai');
                    if (jamSelesai) jamSelesai.value = sugSelesai;
                }
                if (sugRuangan) {
                    const ruangan = document.getElementById('admin_ruangan');
                    if (ruangan) ruangan.value = sugRuangan;
                }

                // ensure changes fields visible and set decision
                const statusSelect = document.getElementById('status_select');
                if (statusSelect) statusSelect.value = 'approved_with_changes';
                try { toggleChangesFields(); } catch(e){}
            } catch (e) {
                console.error('Failed to apply suggestion', e);
            }
        });
    })();
</script>
@endpush

@endsection
