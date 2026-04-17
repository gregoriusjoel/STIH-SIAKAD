@extends('layouts.admin')
@section('title', 'Kelas Mata Kuliah')
@section('page-title', 'Kelas Mata Kuliah')
@section('content')

<script>
function kelasMatKuliahData() {
    return {
        showDetail: false,
        showLinksTable: true,
        selectedKelasId: null,
        selectedMeeting: 'kuliah:1',
        loadingData: false,
        loadingLinks: false,
        attendanceData: null,
        allPertemuanLinks: null,
        generalMeetingLink: '',
        onlineMeetingLink: '',
        savingLink: false,
        savingGeneralLink: false,
        editingLinkId: null,
        meetingSlots: [
            { value: 'kuliah:1', label: 'Pertemuan 1', tipe: 'kuliah' },
            { value: 'kuliah:2', label: 'Pertemuan 2', tipe: 'kuliah' },
            { value: 'kuliah:3', label: 'Pertemuan 3', tipe: 'kuliah' },
            { value: 'kuliah:4', label: 'Pertemuan 4', tipe: 'kuliah' },
            { value: 'kuliah:5', label: 'Pertemuan 5', tipe: 'kuliah' },
            { value: 'kuliah:6', label: 'Pertemuan 6', tipe: 'kuliah' },
            { value: 'kuliah:7', label: 'Pertemuan 7', tipe: 'kuliah' },
            { value: 'uts:1', label: '📝 UTS (Ujian Tengah Semester)', tipe: 'uts' },
            { value: 'kuliah:8', label: 'Pertemuan 8', tipe: 'kuliah' },
            { value: 'kuliah:9', label: 'Pertemuan 9', tipe: 'kuliah' },
            { value: 'kuliah:10', label: 'Pertemuan 10', tipe: 'kuliah' },
            { value: 'kuliah:11', label: 'Pertemuan 11', tipe: 'kuliah' },
            { value: 'kuliah:12', label: 'Pertemuan 12', tipe: 'kuliah' },
            { value: 'kuliah:13', label: 'Pertemuan 13', tipe: 'kuliah' },
            { value: 'kuliah:14', label: 'Pertemuan 14', tipe: 'kuliah' },
            { value: 'uas:1', label: '📝 UAS (Ujian Akhir Semester)', tipe: 'uas' },
        ],
        toggleDetail(id) {
            if (this.selectedKelasId === id) {
                this.showDetail = !this.showDetail;
                this.showLinksTable = !this.showLinksTable;
            } else {
                this.selectedKelasId = id;
                this.showDetail = true;
                this.showLinksTable = true;
                this.loadAllPertemuanLinks();
                this.loadAttendanceData();
                this.$nextTick(() => this.$refs.detailCard.scrollIntoView({ behavior: 'smooth', block: 'start' }));
            }
        },
        loadAllPertemuanLinks() {
            if (!this.selectedKelasId) return;
            this.loadingLinks = true;
            fetch(`/admin/kelas-mata-kuliah/${this.selectedKelasId}/all-pertemuan-links`)
                .then(res => {
                    if (!res.ok) throw new Error('Server error: ' + res.status);
                    return res.json();
                })
                .then(data => {
                    this.allPertemuanLinks = data.pertemuans;
                    this.generalMeetingLink = data.kelas?.online_meeting_link || '';
                    this.loadingLinks = false;
                })
                .catch(err => {
                    console.error('Error loading all pertemuan links:', err);
                    this.allPertemuanLinks = null;
                    this.generalMeetingLink = '';
                    this.loadingLinks = false;
                    alert('Gagal memuat link pertemuan. Silakan coba lagi.');
                });
        },
        savePertemuanLink(pertemuanId, link) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            this.savingLink = pertemuanId;
            
            fetch(`/admin/kelas-mata-kuliah/${this.selectedKelasId}/update-online-meeting-link`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    pertemuan_id: pertemuanId,
                    online_meeting_link: link || null,
                })
            })
            .then(res => {
                if (!res.ok) throw new Error('Server error: ' + res.status);
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    const notification = document.createElement('div');
                    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    notification.textContent = data.message || 'Link zoom berhasil diperbarui';
                    document.body.appendChild(notification);
                    setTimeout(() => notification.remove(), 3000);
                    
                    const pertemuan = this.allPertemuanLinks.find(p => p.id === pertemuanId);
                    if (pertemuan) {
                        pertemuan.online_meeting_link = link || null;
                    }
                }
                this.savingLink = null;
                this.editingLinkId = null;
            })
            .catch(err => {
                console.error('Error saving meeting link:', err);
                this.savingLink = null;
                alert('Gagal menyimpan link zoom. Silakan coba lagi.');
            });
        },
        saveGeneralMeetingLink() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            this.savingGeneralLink = true;
            
            fetch(`/admin/kelas-mata-kuliah/${this.selectedKelasId}/update-general-meeting-link`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    online_meeting_link: this.generalMeetingLink || null,
                })
            })
            .then(res => {
                if (!res.ok) throw new Error('Server error: ' + res.status);
                return res.json();
            })
            .then(data => {
                this.savingGeneralLink = false;
                if (data.success) {
                    const notification = document.createElement('div');
                    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    notification.textContent = data.message || 'Link zoom umum berhasil disimpan';
                    document.body.appendChild(notification);
                    setTimeout(() => notification.remove(), 3000);
                }
            })
            .catch(err => {
                console.error('Error saving general meeting link:', err);
                this.savingGeneralLink = false;
                alert('Gagal menyimpan link zoom umum. Silakan coba lagi.');
            });
        },
        loadAttendanceData() {
            if (!this.selectedKelasId) return;
            this.loadingData = true;
            this.attendanceData = null;
            this.onlineMeetingLink = '';
            const [tipe, nomor] = this.selectedMeeting.split(':');
            fetch(`/admin/kelas-mata-kuliah/${this.selectedKelasId}/attendance?tipe=${tipe}&nomor=${nomor}`)
                .then(res => {
                    if (!res.ok) throw new Error('Server error: ' + res.status);
                    return res.json();
                })
                .then(data => {
                    this.attendanceData = data;
                    this.onlineMeetingLink = data.pertemuan?.online_meeting_link || '';
                    this.loadingData = false;
                })
                .catch(err => {
                    console.error('Error loading attendance:', err);
                    this.attendanceData = null;
                    this.onlineMeetingLink = '';
                    this.loadingData = false;
                    alert('Gagal memuat data kehadiran. Silakan coba lagi.');
                });
        },
        saveOnlineMeetingLink() {
            if (!this.attendanceData?.pertemuan?.id) return;
            this.savingLink = true;
            
            fetch(`/admin/kelas-mata-kuliah/${this.selectedKelasId}/update-online-meeting-link`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    pertemuan_id: this.attendanceData.pertemuan.id,
                    online_meeting_link: this.onlineMeetingLink || null,
                })
            })
            .then(res => {
                if (!res.ok) throw new Error('Server error: ' + res.status);
                return res.json();
            })
            .then(data => {
                this.savingLink = false;
                this.editingLinkId = null; // Close edit mode
                if (data.success) {
                    this.attendanceData.pertemuan.online_meeting_link = this.onlineMeetingLink; // Update local data
                    const notification = document.createElement('div');
                    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    notification.textContent = data.message || 'Link zoom berhasil diperbarui';
                    document.body.appendChild(notification);
                    setTimeout(() => notification.remove(), 3000);
                }
            })
            .catch(err => {
                console.error('Error saving meeting link:', err);
                this.savingLink = false;
                alert('Gagal menyimpan link zoom. Silakan coba lagi.');
            });
        }
    }
}
</script>

<div x-data="kelasMatKuliahData()" x-init="$watch('selectedMeeting', () => { if (this.showDetail) this.loadAttendanceData(); })">
    <div class="mb-6 flex items-start justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-800 flex items-center"><i class="fas fa-chalkboard-teacher text-maroon mr-2"></i>Daftar Kelas Mata Kuliah</h3>
            <p class="text-sm text-gray-600 mt-1">Kelola pengelompokan kelas per mata kuliah (Hanya menampilkan kelas dengan jadwal aktif atau yang memiliki jadwal sendiri)</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-maroon text-white">
                    <tr><th class="px-6 py-4 text-left text-sm font-semibold">Mata Kuliah</th><th class="px-6 py-4 text-left text-sm font-semibold">Nama Kelas</th><th class="px-6 py-4 text-left text-sm font-semibold">Dosen</th><th class="px-6 py-4 text-left text-sm font-semibold">Semester</th><th class="px-6 py-4 text-left text-sm font-semibold">Jadwal</th><th class="px-6 py-4 text-left text-sm font-semibold">Kuota</th><th class="px-6 py-4 text-left text-sm font-semibold">Ruangan</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($kelasMatKul as $k)
                    <tr class="hover:bg-blue-50 transition duration-200 cursor-pointer" 
                        :class="{'bg-blue-50': selectedKelasId === {{ $k->id }} && showDetail}"
                        @click="toggleDetail({{ $k->id }})">
                        <td class="px-6 py-4"><div class="font-semibold text-gray-900">{{ $k->mataKuliah->nama_mk }}</div><div class="text-sm text-gray-500">{{ $k->mataKuliah->kode_mk }}</div></td>
                        <td class="px-6 py-4"><span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"><i class="fas fa-users mr-1"></i>{{ $k->nama_kelas }}</span></td>
                        <td class="px-6 py-4">{{ $k->dosen->user->name }}</td>
                        <td class="px-6 py-4">{{ $k->jadwal?->kelas?->semester_type ?? $k->semester?->nama_semester ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($k->jadwal)
                            <div class="text-sm">
                                <div class="font-semibold text-gray-900">{{ $k->jadwal->hari }}</div>
                                <div class="text-xs text-gray-500">{{ substr($k->jadwal->jam_mulai, 0, 5) }} - {{ substr($k->jadwal->jam_selesai, 0, 5) }}</div>
                            </div>
                            @elseif($k->hari && $k->jam_mulai && $k->jam_selesai)
                            <div class="text-sm">
                                <div class="font-semibold text-gray-900">{{ $k->hari }}</div>
                                <div class="text-xs text-gray-500">{{ substr($k->jam_mulai, 0, 5) }} - {{ substr($k->jam_selesai, 0, 5) }}</div>
                            </div>
                            @else
                            <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4"><span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">{{ $k->kapasitas }}</span></td>
                        <td class="px-6 py-4">{{ $k->ruang ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-inbox text-4xl mb-3"></i><p class="text-lg font-semibold">Belum ada kelas mata kuliah dengan jadwal aktif</p><p class="text-sm mt-2">Kelas akan ditampilkan setelah memiliki jadwal aktif atau jam kuliah tersedia</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kelasMatKul->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">{{ $kelasMatKul->links() }}</div>
        @endif
    </div>

    {{-- Attendance Detail Card --}}
    <div x-ref="detailCard" x-show="showDetail" x-transition class="mt-8 bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden" style="display: none;">
        <div class="p-6 border-b border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-clipboard-list text-maroon mr-2"></i>Detail Kehadiran per Pertemuan
                    <template x-if="attendanceData?.pertemuan?.tipe === 'uts'">
                        <span class="ml-2 px-2 py-0.5 text-xs font-bold bg-amber-100 text-amber-800 rounded-full">UTS</span>
                    </template>
                    <template x-if="attendanceData?.pertemuan?.tipe === 'uas'">
                        <span class="ml-2 px-2 py-0.5 text-xs font-bold bg-red-100 text-red-800 rounded-full">UAS</span>
                    </template>
                </h3>
            <p class="text-sm text-gray-600 mt-1">Pantau kehadiran mahasiswa di setiap sesi pertemuan</p>
        </div>
        <div class="flex items-center gap-3">
            <label for="meetingSelect" class="text-sm font-semibold text-gray-700">Pilih Pertemuan:</label>
            <select id="meetingSelect" x-model="selectedMeeting" @change="loadAttendanceData()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent text-sm min-w-[280px]">
                <option value="">-- Pilih Pertemuan --</option>
                <option value="kuliah:1">Pertemuan 1</option>
                <option value="kuliah:2">Pertemuan 2</option>
                <option value="kuliah:3">Pertemuan 3</option>
                <option value="kuliah:4">Pertemuan 4</option>
                <option value="kuliah:5">Pertemuan 5</option>
                <option value="kuliah:6">Pertemuan 6</option>
                <option value="kuliah:7">Pertemuan 7</option>
                <option value="uts:1">📝 UTS (Ujian Tengah Semester)</option>
                <option value="kuliah:8">Pertemuan 8</option>
                <option value="kuliah:9">Pertemuan 9</option>
                <option value="kuliah:10">Pertemuan 10</option>
                <option value="kuliah:11">Pertemuan 11</option>
                <option value="kuliah:12">Pertemuan 12</option>
                <option value="kuliah:13">Pertemuan 13</option>
                <option value="kuliah:14">Pertemuan 14</option>
                <option value="uas:1">📝 UAS (Ujian Akhir Semester)</option>
            </select>
        </div>
    </div>

    {{-- Meeting Details --}}
    <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-4 gap-6 border-b border-gray-200 bg-gray-50">
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tanggal</p>
            <p class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-calendar-day text-gray-400"></i>
                <span x-show="!loadingData && attendanceData?.pertemuan?.tanggal" x-text="attendanceData?.pertemuan?.tanggal ? new Date(attendanceData.pertemuan.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-'"></span>
                <span x-show="loadingData || !attendanceData?.pertemuan?.tanggal">-</span>
            </p>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Waktu</p>
            <p class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-clock text-gray-400"></i>
                <span x-show="!loadingData && attendanceData?.jadwal" x-text="`${attendanceData?.jadwal?.jam_mulai || '-'} - ${attendanceData?.jadwal?.jam_selesai || '-'} WIB`"></span>
                <span x-show="loadingData || !attendanceData?.jadwal">-</span>
            </p>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Ruangan</p>
            <p class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-door-open text-gray-400"></i>
                <span x-show="!loadingData" x-text="attendanceData?.jadwal?.ruangan || '-'"></span>
                <span x-show="loadingData">-</span>
            </p>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Materi / Topik</p>
            <p class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-book-open text-gray-400"></i>
                <span x-show="!loadingData" x-text="attendanceData?.materi_topik || '-'"></span>
                <span x-show="loadingData">-</span>
            </p>
        </div>
    </div>

    {{-- General Meeting Link Section --}}
    <div x-show="showLinksTable" x-transition class="px-6 py-4 border-b border-gray-200 bg-blue-50">
        <div class="flex items-center gap-4 mb-3">
            <div class="flex-1">
                <label class="block text-sm font-bold text-gray-800 mb-2">
                    <i class="fas fa-broadcast-tower text-blue-600 mr-2"></i>Link Zoom Umum (Berlaku Untuk Semua Pertemuan)
                </label>
                <div class="flex gap-3">
                    <input type="url" x-model="generalMeetingLink"
                           placeholder="https://zoom.us/j/..."
                           class="flex-1 px-4 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           x-bind:disabled="savingGeneralLink">
                    <button @click="saveGeneralMeetingLink()"
                            x-bind:disabled="savingGeneralLink"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center gap-2">
                        <template x-if="!savingGeneralLink">
                            <><i class="fas fa-save"></i> Simpan Umum</>
                        </template>
                        <template x-if="savingGeneralLink">
                            <><i class="fas fa-spinner fa-spin"></i> Menyimpan...</>
                        </template>
                    </button>
                </div>
                <p class="text-xs text-blue-700 mt-2">Link ini akan otomatis digunakan untuk semua pertemuan. Namun setiap pertemuan tetap bisa memiliki link custom sendiri.</p>
            </div>
        </div>
    </div>

    {{-- All Pertemuan Links Table --}}
    <div x-show="showLinksTable && attendanceData?.pertemuan" x-transition class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center gap-4 mb-3">
            <div class="flex-1">
                <label class="block text-sm font-bold text-gray-800 mb-2">
                    <i class="fas fa-list text-maroon mr-2"></i>Link Zoom untuk Pertemuan yang Dipilih
                </label>
                <p class="text-xs text-gray-600">Kosongkan untuk menggunakan link umum, atau isi untuk menggunakan link custom untuk pertemuan ini</p>
            </div>
        </div>
        
        <template x-if="loadingData">
            <div class="flex items-center justify-center py-8">
                <i class="fas fa-spinner fa-spin text-2xl text-gray-300 mr-3"></i>
                <p class="text-gray-500">Memuat data pertemuan...</p>
            </div>
        </template>

        <template x-if="!loadingData && attendanceData?.pertemuan">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-white">
                        <tr class="border-b border-gray-300">
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Pertemuan</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Tanggal</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Topik</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Link Zoom / Meeting</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-200 hover:bg-white transition">
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900" x-text="attendanceData.pertemuan.label"></div>
                            </td>
                            <td class="px-4 py-3 text-gray-600" x-text="attendanceData.pertemuan.tanggal || '-'"></td>
                            <td class="px-4 py-3 text-gray-600" x-text="attendanceData.materi_topik || '-'"></td>
                            <td class="px-4 py-3">
                                <template x-if="editingLinkId === attendanceData.pertemuan.id">
                                    <input type="url" x-model="onlineMeetingLink"
                                           placeholder="https://zoom.us/j/..."
                                           class="w-full px-3 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                                </template>
                                <template x-if="editingLinkId !== attendanceData.pertemuan.id">
                                    <div class="flex items-center gap-2">
                                        <template x-if="attendanceData.pertemuan.online_meeting_link">
                                            <span class="text-green-600 font-semibold text-xs">✓ Custom</span>
                                            <a :href="attendanceData.pertemuan.online_meeting_link" target="_blank" 
                                               class="text-blue-600 hover:text-blue-800 text-xs font-semibold">Lihat</a>
                                        </template>
                                        <template x-if="!attendanceData.pertemuan.online_meeting_link && generalMeetingLink">
                                            <span class="text-blue-600 font-semibold text-xs">📡 Default</span>
                                            <a :href="generalMeetingLink" target="_blank" 
                                               class="text-blue-600 hover:text-blue-800 text-xs font-semibold">Lihat</a>
                                        </template>
                                        <template x-if="!attendanceData.pertemuan.online_meeting_link && !generalMeetingLink">
                                            <span class="text-gray-400 text-xs">- Tidak ada link -</span>
                                        </template>
                                    </div>
                                </template>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <template x-if="editingLinkId === attendanceData.pertemuan.id">
                                    <div class="flex gap-2 justify-center">
                                        <button type="button" @click.stop="saveOnlineMeetingLink()"
                                                x-bind:disabled="savingLink"
                                                class="px-3 py-1 bg-green-600 text-white rounded text-sm font-semibold hover:bg-green-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center gap-1">
                                            <i class="fas fa-check"></i>
                                            <span>Simpan</span>
                                        </button>
                                        <button type="button" @click.stop="editingLinkId = null"
                                                class="px-3 py-1 bg-gray-400 text-white rounded text-sm font-semibold hover:bg-gray-500 transition">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </template>
                                <template x-if="editingLinkId !== attendanceData.pertemuan.id">
                                    <button type="button" @click.stop="editingLinkId = attendanceData.pertemuan.id"
                                            class="px-3 py-1 bg-maroon text-white rounded text-sm font-semibold hover:bg-red-700 transition">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </button>
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Mahasiswa</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">NIM</th>
                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Status Kehadiran</th>
                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Waktu Scan</th>
                </tr>
            </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Loading State -->
                    <template x-if="loadingData">
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-spinner fa-spin text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-500 font-medium">Memuat data...</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                    
                    <!-- No Data State -->
                    <template x-if="!loadingData && (!attendanceData || !attendanceData.students || attendanceData.students.length === 0)">
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-user-slash text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-500 font-medium">Tidak ada mahasiswa terdaftar di kelas ini.</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                    
                    <!-- Data Rows -->
                    <template x-if="!loadingData && attendanceData?.students && attendanceData.students.length > 0">
                        <template x-for="student in attendanceData.students" :key="student.nim">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-sm text-gray-900" x-text="student.no"></td>
                                <td class="px-6 py-3">
                                    <div class="text-sm font-semibold text-gray-900" x-text="student.nama"></div>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600" x-text="student.nim"></td>
                                <td class="px-6 py-3 text-center">
                                    <span :class="{
                                        'bg-green-100 text-green-800': student.status === 'hadir',
                                        'bg-red-100 text-red-800': student.status === 'tidak hadir',
                                        'bg-yellow-100 text-yellow-800': student.status === 'izin',
                                        'bg-gray-100 text-gray-800': student.status !== 'hadir' && student.status !== 'tidak hadir' && student.status !== 'izin'
                                    }" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold" x-text="student.status.toUpperCase()"></span>
                                    <template x-if="student.status === 'hadir' && student.distance_meters !== null && student.distance_meters !== undefined">
                                        <div class="text-[10px] text-gray-500 mt-0.5" x-text="'(' + Math.round(student.distance_meters) + 'm dari kampus)'"></div>
                                    </template>
                                </td>
                                <td class="px-6 py-3 text-center text-sm text-gray-600" x-text="student.waktu_scan"></td>
                            </tr>
                        </template>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
             <div class="text-xs text-gray-500">
                <span x-show="!loadingData" x-text="`Menampilkan ${attendanceData?.students?.length || 0} dari ${attendanceData?.total_students || 0} mahasiswa`"></span>
                <span x-show="loadingData">Memuat...</span>
                <span x-show="!loadingData && attendanceData && typeof attendanceData.total_hadir !== 'undefined'" class="ml-4 font-semibold text-green-600" x-text="`Hadir: ${attendanceData?.total_hadir || 0}`"></span>
                <span x-show="!loadingData && attendanceData && typeof attendanceData.total_tidak_hadir !== 'undefined'" class="ml-4 font-semibold text-red-600" x-text="`Tidak Hadir: ${attendanceData?.total_tidak_hadir || 0}`"></span>
             </div>
        </div>
    </div>
</div>
@endsection