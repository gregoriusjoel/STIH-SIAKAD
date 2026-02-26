@extends('layouts.admin')
@section('title', 'Kelas Mata Kuliah')
@section('page-title', 'Kelas Mata Kuliah')
@section('content')
<div x-data="{ 
    showDetail: false,
    selectedKelasId: null,
    selectedMeeting: 1,
    loadingData: false,
    attendanceData: null,
    toggleDetail(id) {
        if (this.selectedKelasId === id) {
            this.showDetail = !this.showDetail;
        } else {
            this.selectedKelasId = id;
            this.showDetail = true;
            this.loadAttendanceData();
            this.$nextTick(() => $refs.detailCard.scrollIntoView({ behavior: 'smooth', block: 'start' }));
        }
    },
    loadAttendanceData() {
        if (!this.selectedKelasId) return;
        this.loadingData = true;
        this.attendanceData = null; // Reset data
        fetch(`/admin/kelas-mata-kuliah/${this.selectedKelasId}/attendance?pertemuan=${this.selectedMeeting}`)
            .then(res => {
                if (!res.ok) throw new Error('Server error: ' + res.status);
                return res.json();
            })
            .then(data => {
                this.attendanceData = data;
                this.loadingData = false;
            })
            .catch(err => {
                console.error('Error loading attendance:', err);
                this.attendanceData = null;
                this.loadingData = false;
                alert('Gagal memuat data kehadiran. Silakan coba lagi.');
            });
    }
}" x-init="$watch('selectedMeeting', () => { if (showDetail) loadAttendanceData(); })">
    <div class="mb-6 flex items-start justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-800 flex items-center"><i class="fas fa-chalkboard-teacher text-maroon mr-2"></i>Daftar Kelas Mata Kuliah</h3>
            <p class="text-sm text-gray-600 mt-1">Kelola pengelompokan kelas per mata kuliah (Hanya menampilkan kelas dengan jadwal aktif)</p>
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
                        <td class="px-6 py-4">{{ $k->semester->nama_semester }}</td>
                        <td class="px-6 py-4">
                            @if($k->jadwal)
                            <div class="text-sm">
                                <div class="font-semibold text-gray-900">{{ $k->jadwal->hari }}</div>
                                <div class="text-xs text-gray-500">{{ substr($k->jadwal->jam_mulai, 0, 5) }} - {{ substr($k->jadwal->jam_selesai, 0, 5) }}</div>
                            </div>
                            @else
                            <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4"><span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">{{ $k->kapasitas }}</span></td>
                        <td class="px-6 py-4">{{ $k->ruang ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-inbox text-4xl mb-3"></i><p class="text-lg font-semibold">Belum ada kelas mata kuliah dengan jadwal aktif</p><p class="text-sm mt-2">Kelas akan ditampilkan setelah memiliki jadwal aktif</p></td></tr>
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
                </h3>
            <p class="text-sm text-gray-600 mt-1">Pantau kehadiran mahasiswa di setiap sesi pertemuan</p>
        </div>
        <div class="flex items-center gap-3">
            <label for="meetingSelect" class="text-sm font-semibold text-gray-700">Pilih Pertemuan:</label>
            <select id="meetingSelect" x-model="selectedMeeting" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent text-sm min-w-[200px]">
                <option value="1">Pertemuan 1</option>
                <option value="2">Pertemuan 2</option>
                <option value="3">Pertemuan 3</option>
                <option value="4">Pertemuan 4</option>
                <option value="5">Pertemuan 5</option>
                <option value="6">Pertemuan 6</option>
                <option value="7">Pertemuan 7</option>
                <option value="8">Pertemuan 8</option>
                <option value="9">Pertemuan 9</option>
                <option value="10">Pertemuan 10</option>
                <option value="11">Pertemuan 11</option>
                <option value="12">Pertemuan 12</option>
                <option value="13">Pertemuan 13</option>
                <option value="14">Pertemuan 14</option>
                <option value="15">Pertemuan 15</option>
                <option value="16">Pertemuan 16</option>
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
             </div>
        </div>
    </div>
</div>
@endsection