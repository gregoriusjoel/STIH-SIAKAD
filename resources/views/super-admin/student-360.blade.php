@extends('layouts.super-admin')

@section('title', 'Student 360 View')
@section('page-title', 'Student 360: ' . $mahasiswa->nama)

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'academic', krsModalOpen: null, gradeModalOpen: null, invoiceModalOpen: null, internModalOpen: null, skripsiModalOpen: null }">
    <!-- Student Header Summary -->
    <div class="glass-card p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#7a1621] to-[#4c0810] text-white flex items-center justify-center font-extrabold text-2xl shadow-md border border-[#7a1621]/30">
                {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $mahasiswa->nama }}</h2>
                <p class="text-sm text-slate-500">NIM: {{ $mahasiswa->nim }} | Prodi: {{ $mahasiswa->prodiData ? $mahasiswa->prodiData->nama_prodi : '-' }}</p>
                <div class="flex gap-2 mt-1">
                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-[#7a1621]/5 border border-[#7a1621]/15 text-[#7a1621]">
                        Semester: {{ $mahasiswa->semester ?? '-' }}
                    </span>
                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                        @if($mahasiswa->status === 'aktif') bg-emerald-100 text-emerald-700 border border-emerald-200
                        @else bg-slate-100 text-slate-500 border border-slate-200 @endif">
                        Status: {{ $mahasiswa->status }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if($mahasiswa->user)
                <form action="{{ route('super-admin.impersonate', $mahasiswa->user->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn-gold px-4 py-2.5 rounded-xl text-sm font-bold shadow-sm transition flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">login</span>
                        <span>Impersonate Akun</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex border-b border-slate-200 gap-1 bg-white p-1 rounded-xl shadow-sm overflow-x-auto">
        <button @click="activeTab = 'academic'" :class="activeTab === 'academic' ? 'bg-[#7a1621] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-[#7a1621]'" 
            class="px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all duration-200 whitespace-nowrap">
            Akademik (KRS & Nilai)
        </button>
        <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'bg-[#7a1621] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-[#7a1621]'" 
            class="px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all duration-200 whitespace-nowrap">
            Profil Lengkap
        </button>
        <button @click="activeTab = 'internship'" :class="activeTab === 'internship' ? 'bg-[#7a1621] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-[#7a1621]'" 
            class="px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all duration-200 whitespace-nowrap">
            Magang ({{ $mahasiswa->internships->count() }})
        </button>
        <button @click="activeTab = 'financial'" :class="activeTab === 'financial' ? 'bg-[#7a1621] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-[#7a1621]'" 
            class="px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all duration-200 whitespace-nowrap">
            Keuangan & Invoice
        </button>
        <button @click="activeTab = 'skripsi'" :class="activeTab === 'skripsi' ? 'bg-[#7a1621] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-[#7a1621]'" 
            class="px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all duration-200 whitespace-nowrap">
            Skripsi & Wisuda
        </button>
        <button @click="activeTab = 'activity'" :class="activeTab === 'activity' ? 'bg-[#7a1621] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-[#7a1621]'" 
            class="px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all duration-200 whitespace-nowrap">
            Log Aktivitas
        </button>
    </div>

    <!-- Tab Contents -->
    <div>
        <!-- TAB 1: ACADEMIC -->
        <div x-show="activeTab === 'academic'" class="space-y-6">
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Kartu Rencana Studi (KRS) & Nilai</h3>
                    <span class="text-xs text-[#7a1621] font-semibold bg-[#7a1621]/5 px-2.5 py-1 rounded-full">Pusat Override Data Akademik</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs uppercase bg-[#7a1621]/5 text-[#7a1621] border-b border-[#7a1621]/10 rounded-lg">
                            <tr>
                                <th class="px-4 py-3">Mata Kuliah</th>
                                <th class="px-4 py-3">Tahun Ajaran</th>
                                <th class="px-4 py-3">Status KRS</th>
                                <th class="px-4 py-3">Nilai Akhir</th>
                                <th class="px-4 py-3">Grade</th>
                                <th class="px-4 py-3 text-right">Aksi Override</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($mahasiswa->krs as $k)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-4 py-3">
                                        <span class="font-semibold text-slate-800 block text-xs">
                                            {{ $k->mataKuliah ? $k->mataKuliah->nama_mata_kuliah : '-' }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 block">
                                            Kode: {{ $k->mataKuliah ? $k->mataKuliah->kode_mata_kuliah : '-' }} | SKS: {{ $k->mataKuliah ? $k->mataKuliah->sks : '0' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 text-xs">{{ $k->tahun_ajaran }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide
                                            @if($k->status === 'sudah submit') bg-emerald-100 text-emerald-700 border border-emerald-200
                                            @elseif($k->status === 'pending') bg-amber-100 text-amber-700 border border-amber-200
                                            @else bg-slate-100 text-slate-500 border border-slate-200 @endif">
                                            {{ $k->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-slate-800">
                                        {{ $k->nilai ? number_format($k->nilai->nilai_akhir, 2) : '-' }}
                                    </td>
                                    <td class="px-4 py-3 font-bold text-slate-700">
                                        {{ $k->nilai ? $k->nilai->grade : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-1 whitespace-nowrap">
                                        <!-- Override KRS Button -->
                                        <button @click="krsModalOpen = {{ $k->id }}" class="bg-[#7a1621]/10 hover:bg-[#7a1621]/20 text-[#7a1621] px-2 py-1 rounded text-xs font-bold transition">
                                            KRS Status
                                        </button>

                                        <!-- Override Grade Button (only if Nilai record exists) -->
                                        @if($k->nilai)
                                            <button @click="gradeModalOpen = {{ $k->nilai->id }}" class="bg-amber-50 hover:bg-amber-100 border border-amber-200/50 text-amber-750 px-2 py-1 rounded text-xs font-bold transition">
                                                Nilai
                                            </button>
                                        @else
                                            <span class="text-[10px] text-slate-400 italic">Nilai N/A</span>
                                        @endif

                                        <!-- Modals relocated to the bottom of the page -->
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-400">Belum ada KRS yang diambil.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 2: PROFILE -->
        <div x-show="activeTab === 'profile'" class="space-y-6" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Biodata Card -->
                <div class="glass-card p-6">
                    <h3 class="text-sm font-bold text-[#7a1621] uppercase tracking-wider mb-4 border-b border-[#7a1621]/10 pb-2">Informasi Biodata</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-400">Nama Lengkap</span>
                            <span class="font-semibold text-slate-800">{{ $mahasiswa->nama }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-400">NIM</span>
                            <span class="font-semibold text-slate-800">{{ $mahasiswa->nim }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-400">NIK</span>
                            <span class="font-semibold text-slate-800">{{ $mahasiswa->nik ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-400">Tempat, Tanggal Lahir</span>
                            <span class="font-semibold text-slate-800">{{ $mahasiswa->tempat_lahir ?? '-' }}, {{ $mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-400">Jenis Kelamin</span>
                            <span class="font-semibold text-slate-800">
                                @php
                                    $jkFirstLetter = strtoupper(substr($mahasiswa->jenis_kelamin ?? '', 0, 1));
                                @endphp
                                {{ $jkFirstLetter === 'L' ? 'Laki-laki' : ($jkFirstLetter === 'P' ? 'Perempuan' : ($mahasiswa->jenis_kelamin ?? '-')) }}
                            </span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-400">Agama</span>
                            <span class="font-semibold text-slate-800">{{ $mahasiswa->agama ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-400">Email Kampus</span>
                            <span class="font-semibold text-slate-800">{{ $mahasiswa->user ? $mahasiswa->user->email : '-' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-slate-400">No. Telepon</span>
                            <span class="font-semibold text-slate-800">{{ $mahasiswa->no_hp ?? $mahasiswa->phone ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Parents Card -->
                <div class="glass-card p-6">
                    <h3 class="text-sm font-bold text-[#7a1621] uppercase tracking-wider mb-4 border-b border-[#7a1621]/10 pb-2">Data Orang Tua</h3>
                    @forelse($mahasiswa->parents as $p)
                        <div class="space-y-3 text-sm mb-4 last:mb-0">
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-400">Nama Wali / Orang Tua</span>
                                <span class="font-semibold text-slate-800">{{ $p->nama }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-400">Hubungan</span>
                                <span class="font-semibold text-slate-800 uppercase">{{ $p->hubungan ?? 'Orang Tua' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-400">No. Telepon</span>
                                <span class="font-semibold text-slate-800">{{ $p->telepon ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-slate-400">Pekerjaan</span>
                                <span class="font-semibold text-slate-800">{{ $p->pekerjaan ?? '-' }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 italic text-center py-6">Belum ada data orang tua yang terhubung.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- TAB 3: INTERNSHIP -->
        <div x-show="activeTab === 'internship'" class="space-y-6" x-cloak>
            <div class="glass-card p-6">
                <h3 class="text-sm font-bold text-[#7a1621] uppercase tracking-wider mb-4 border-b border-[#7a1621]/10 pb-2">Riwayat Pengajuan Magang</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs uppercase bg-[#7a1621]/5 text-[#7a1621] border-b border-[#7a1621]/10 rounded-lg">
                            <tr>
                                <th class="px-4 py-3">Instansi</th>
                                <th class="px-4 py-3">Posisi</th>
                                <th class="px-4 py-3">Durasi</th>
                                <th class="px-4 py-3">Pembimbing</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Aksi Override</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($mahasiswa->internships as $intern)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-4 py-3">
                                        <span class="font-semibold text-slate-800 block text-xs">{{ $intern->instansi }}</span>
                                        <span class="text-[10px] text-slate-400 block">{{ $intern->alamat_instansi }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 text-xs">{{ $intern->posisi }}</td>
                                    <td class="px-4 py-3 text-slate-600 text-xs">
                                        {{ $intern->tanggal_mulai ? \Carbon\Carbon::parse($intern->tanggal_mulai)->format('d/m/Y') : '-' }} s.d.
                                        {{ $intern->tanggal_selesai ? \Carbon\Carbon::parse($intern->tanggal_selesai)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 text-xs">
                                        {{ $intern->dosenPembimbing ? $intern->dosenPembimbing->nama : 'Belum ditentukan' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                            @if($intern->status === 'approved') bg-emerald-100 text-emerald-700 border border-emerald-200
                                            @elseif($intern->status === 'ongoing') bg-sky-100 text-sky-700 border border-sky-200
                                            @elseif($intern->status === 'rejected') bg-rose-100 text-rose-700 border border-rose-200
                                            @else bg-slate-100 text-slate-500 border border-slate-200 @endif">
                                            {{ $intern->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <button @click="internModalOpen = {{ $intern->id }}" class="bg-[#7a1621]/10 hover:bg-[#7a1621]/20 text-[#7a1621] px-2 py-1 rounded text-xs font-bold transition">
                                            Override
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-400">Belum ada riwayat magang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 4: FINANCIAL -->
        <div x-show="activeTab === 'financial'" class="space-y-6" x-cloak>
            <div class="glass-card p-6">
                <h3 class="text-sm font-bold text-[#7a1621] uppercase tracking-wider mb-4 border-b border-[#7a1621]/10 pb-2">Tagihan Mahasiswa</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs uppercase bg-[#7a1621]/5 text-[#7a1621] border-b border-[#7a1621]/10 rounded-lg">
                            <tr>
                                <th class="px-4 py-3">No Invoice</th>
                                <th class="px-4 py-3">Deskripsi</th>
                                <th class="px-4 py-3">Jumlah</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Tanggal Tagihan</th>
                                <th class="px-4 py-3 text-right">Aksi Override</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($mahasiswa->invoices as $inv)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-4 py-3 font-semibold text-slate-800">{{ $inv->invoice_number }}</td>
                                    <td class="px-4 py-3 text-slate-600 text-xs">{{ $inv->description }}</td>
                                    <td class="px-4 py-3 font-bold text-slate-705 text-[#7a1621]">Rp {{ number_format($inv->total_tagihan, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                            @if($inv->status === 'LUNAS') bg-emerald-100 text-emerald-700 border border-emerald-200
                                            @else bg-rose-100 text-rose-700 border border-rose-200 @endif">
                                            {{ $inv->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-400 text-xs">{{ $inv->created_at->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <button @click="invoiceModalOpen = {{ $inv->id }}" class="bg-[#7a1621]/10 hover:bg-[#7a1621]/20 text-[#7a1621] px-2 py-1 rounded text-xs font-bold transition">
                                            Override
                                        </button>
                                        
                                        <!-- Modals relocated to the bottom of the page -->
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-400">Tidak ada tagihan/invoice terdata.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
 
        <!-- TAB 5: SKRIPSI & WISUDA -->
        <div x-show="activeTab === 'skripsi'" class="space-y-6" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Skripsi submissions -->
                <div class="glass-card p-6">
                    <h3 class="text-sm font-bold text-[#7a1621] uppercase tracking-wider mb-4 border-b border-[#7a1621]/10 pb-2">Status Skripsi</h3>
                    @forelse($skripsi as $skr)
                        <div class="space-y-3 text-sm">
                            <div class="py-2">
                                <span class="text-xs text-slate-400 font-bold block">JUDUL SKRIPSI</span>
                                <p class="font-semibold text-slate-800 leading-tight mt-0.5">{{ $skr->judul }}</p>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-400">Dosen Pembimbing Utama</span>
                                <span class="font-semibold text-slate-800">{{ $skr->approvedSupervisor ? $skr->approvedSupervisor->nama : '-' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-400">Status Skripsi</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 border text-slate-700">{{ $skr->status }}</span>
                            </div>
                            
                            <div class="flex justify-end pt-2">
                                <button @click="skripsiModalOpen = {{ $skr->id }}" class="bg-[#7a1621]/10 hover:bg-[#7a1621]/20 text-[#7a1621] px-3 py-1 rounded-lg text-xs font-bold transition">
                                    Override Status
                                </button>
                            </div>
                            
                            <!-- Modals relocated to the bottom of the page -->
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 italic text-center py-6">Belum mengajukan proposal skripsi.</p>
                    @endforelse
                </div>
 
                <!-- Wisuda registration -->
                <div class="glass-card p-6">
                    <h3 class="text-sm font-bold text-[#7a1621] uppercase tracking-wider mb-4 border-b border-[#7a1621]/10 pb-2">Registrasi Wisuda</h3>
                    @forelse($mahasiswa->wisudaRegistrations as $wisuda)
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-400">Tahun Lulus</span>
                                <span class="font-semibold text-slate-800">{{ $wisuda->batch ? \Carbon\Carbon::parse($wisuda->batch->tanggal)->format('Y') : '-' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-400">IPK Akhir</span>
                                <span class="font-bold text-slate-800">{{ number_format($mahasiswa->ipk, 2) }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-400">Status Pendaftaran</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">{{ $wisuda->status }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 italic text-center py-6">Belum terdaftar untuk wisuda.</p>
                    @endforelse
                </div>
            </div>
        </div>
 
        <!-- TAB 6: ACTIVITY LOGS -->
        <div x-show="activeTab === 'activity'" class="space-y-6" x-cloak>
            <div class="glass-card p-6">
                <h3 class="text-sm font-bold text-[#7a1621] uppercase tracking-wider mb-4 border-b border-[#7a1621]/10 pb-2">Log Aktivitas Mahasiswa</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs uppercase bg-[#7a1621]/5 text-[#7a1621] border-b border-[#7a1621]/10 rounded-lg">
                            <tr>
                                <th class="px-4 py-3">Waktu</th>
                                <th class="px-4 py-3">Aksi</th>
                                <th class="px-4 py-3">Detail Informasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($activities as $act)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-4 py-3 whitespace-nowrap text-xs font-semibold text-slate-600">
                                        {{ $act->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-[#7a1621]/5 border border-[#7a1621]/10 text-[#7a1621]">
                                            {{ $act->action }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs font-mono max-w-[400px] truncate" title="{{ json_encode($act->meta) }}">
                                        {{ json_encode($act->meta) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-slate-400">Tidak ada log aktivitas terdeteksi untuk mahasiswa ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- RELOCATED OVERRIDE MODALS (ROOT LEVEL)      -->
    <!-- ========================================== -->

    <!-- KRS & Grade Override Modals -->
    @foreach($mahasiswa->krs as $k)
        <!-- KRS MODAL (Alpine.js) -->
        <div x-show="krsModalOpen === {{ $k->id }}" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" @click="krsModalOpen = null"></div>
            <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl relative z-10">
                <h4 class="font-bold text-slate-800 text-sm mb-1">Override Status KRS</h4>
                <p class="text-xs text-slate-500 mb-4">{{ $k->mataKuliah ? $k->mataKuliah->nama_mata_kuliah : '' }}</p>

                <form action="{{ route('super-admin.override.krs', $k->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Status Baru</label>
                        <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#7a1621]">
                            <option value="draft" @selected($k->status === 'draft')>draft</option>
                            <option value="pending" @selected($k->status === 'pending')>pending</option>
                            <option value="sudah submit" @selected($k->status === 'sudah submit')>sudah submit</option>
                            <option value="ditolak" @selected($k->status === 'ditolak')>ditolak</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Alasan Perubahan (Masuk ke Audit Log)</label>
                        <textarea name="override_reason" rows="3" placeholder="Masukkan alasan hukum atau operasional..." class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#7a1621]" required></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="krsModalOpen = null" class="px-4 py-2 border rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                        <button type="submit" class="btn-maroon px-4 py-2 rounded-lg text-xs font-bold shadow-md">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- GRADE MODAL (Alpine.js) -->
        @if($k->nilai)
            <div x-show="gradeModalOpen === {{ $k->nilai->id }}" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" @click="gradeModalOpen = null"></div>
                <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl relative z-10">
                    <h4 class="font-bold text-slate-800 text-sm mb-1">Override Nilai Akhir</h4>
                    <p class="text-xs text-slate-500 mb-4">{{ $k->mataKuliah ? $k->mataKuliah->nama_mata_kuliah : '' }}</p>

                    <form action="{{ route('super-admin.override.nilai', $k->nilai->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nilai Akhir (0.00 - 100.00)</label>
                            <input type="number" step="0.01" min="0" max="100" name="nilai_akhir" value="{{ $k->nilai->nilai_akhir }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#7a1621]" required>
                            <span class="text-[10px] text-slate-400 block mt-1">Sistem akan mengkalkulasi otomatis Grade & Bobot IPK berdasarkan standar universitas.</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Alasan Perubahan (Masuk ke Audit Log)</label>
                            <textarea name="override_reason" rows="3" placeholder="Masukkan alasan hukum/kebijakan akademik..." class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#7a1621]" required></textarea>
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" @click="gradeModalOpen = null" class="px-4 py-2 border rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                            <button type="submit" class="btn-maroon px-4 py-2 rounded-lg text-xs font-bold shadow-md">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Internship Override Modals -->
    @foreach($mahasiswa->internships as $intern)
        <!-- Internship Override Modal -->
        <div x-show="internModalOpen === {{ $intern->id }}" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" @click="internModalOpen = null"></div>
            <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl relative z-10">
                <h4 class="font-bold text-slate-800 text-sm mb-1">Override Status Magang</h4>
                <p class="text-xs text-slate-500 mb-4">{{ $intern->instansi }}</p>

                <form action="{{ route('super-admin.override.internship', $intern->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Status Baru</label>
                        <select name="status" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#7a1621]">
                            <option value="draft" @selected($intern->status === 'draft')>Draft</option>
                            <option value="submitted" @selected($intern->status === 'submitted')>Submitted</option>
                            <option value="waiting_request_letter" @selected($intern->status === 'waiting_request_letter')>Waiting Request Letter</option>
                            <option value="request_letter_uploaded" @selected($intern->status === 'request_letter_uploaded')>Request Letter Uploaded</option>
                            <option value="under_review" @selected($intern->status === 'under_review')>Under Review</option>
                            <option value="approved" @selected($intern->status === 'approved')>Approved</option>
                            <option value="rejected" @selected($intern->status === 'rejected')>Rejected</option>
                            <option value="supervisor_assigned" @selected($intern->status === 'supervisor_assigned')>Supervisor Assigned</option>
                            <option value="acceptance_letter_ready" @selected($intern->status === 'acceptance_letter_ready')>Acceptance Letter Ready</option>
                            <option value="ongoing" @selected($intern->status === 'ongoing')>Ongoing</option>
                            <option value="completed" @selected($intern->status === 'completed')>Completed</option>
                            <option value="graded" @selected($intern->status === 'graded')>Graded</option>
                            <option value="closed" @selected($intern->status === 'closed')>Closed</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Alasan Perubahan (Masuk ke Audit Log)</label>
                        <textarea name="override_reason" rows="3" placeholder="Masukkan alasan override magang..." class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#7a1621]" required></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="internModalOpen = null" class="px-4 py-2 border rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                        <button type="submit" class="btn-maroon px-4 py-2 rounded-lg text-xs font-bold shadow-md">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <!-- Invoice Override Modals -->
    @foreach($mahasiswa->invoices as $inv)
        <!-- Invoice Override Modal -->
        <div x-show="invoiceModalOpen === {{ $inv->id }}" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" @click="invoiceModalOpen = null"></div>
            <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl relative z-10">
                <h4 class="font-bold text-slate-800 text-sm mb-1">Override Status Invoice</h4>
                <p class="text-xs text-slate-500 mb-4">{{ $inv->invoice_number }} | Rp {{ number_format($inv->total_tagihan, 0, ',', '.') }}</p>

                <form action="{{ route('super-admin.override.invoice', $inv->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Status Baru</label>
                        <select name="status" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#7a1621]">
                            <option value="DRAFT" @selected($inv->status === 'DRAFT')>DRAFT</option>
                            <option value="PUBLISHED" @selected($inv->status === 'PUBLISHED')>PUBLISHED</option>
                            <option value="IN_INSTALLMENT" @selected($inv->status === 'IN_INSTALLMENT')>IN_INSTALLMENT</option>
                            <option value="LUNAS" @selected($inv->status === 'LUNAS')>LUNAS</option>
                            <option value="CANCELLED" @selected($inv->status === 'CANCELLED')>CANCELLED</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Alasan Perubahan (Masuk ke Audit Log)</label>
                        <textarea name="override_reason" rows="3" placeholder="Masukkan alasan override keuangan..." class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#7a1621]" required></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="invoiceModalOpen = null" class="px-4 py-2 border rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                        <button type="submit" class="btn-maroon px-4 py-2 rounded-lg text-xs font-bold shadow-md">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <!-- Skripsi Override Modals -->
    @foreach($skripsi as $skr)
        <!-- Skripsi Override Modal -->
        <div x-show="skripsiModalOpen === {{ $skr->id }}" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" @click="skripsiModalOpen = null"></div>
            <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl relative z-10">
                <h4 class="font-bold text-slate-800 text-sm mb-1">Override Status Skripsi</h4>
                <p class="text-xs text-slate-500 mb-4">{{ $skr->judul }}</p>

                <form action="{{ route('super-admin.override.skripsi', $skr->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Status Baru</label>
                        <select name="status" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#7a1621]">
                            @php
                                $currentStatus = is_object($skr->status) ? $skr->status->value : (string)$skr->status;
                            @endphp
                            <option value="LOCKED" @selected($currentStatus === 'LOCKED')>Locked (Belum Syarat SKS)</option>
                            <option value="PROPOSAL_DRAFT" @selected($currentStatus === 'PROPOSAL_DRAFT')>Proposal Draft</option>
                            <option value="PROPOSAL_PENDING_SUPERVISOR" @selected($currentStatus === 'PROPOSAL_PENDING_SUPERVISOR')>Menunggu Konfirmasi Dosen</option>
                            <option value="PROPOSAL_SUBMITTED" @selected($currentStatus === 'PROPOSAL_SUBMITTED')>Proposal Dikirim</option>
                            <option value="PROPOSAL_REJECTED" @selected($currentStatus === 'PROPOSAL_REJECTED')>Proposal Ditolak</option>
                            <option value="PROPOSAL_APPROVED" @selected($currentStatus === 'PROPOSAL_APPROVED')>Proposal Disetujui</option>
                            <option value="BIMBINGAN_ACTIVE" @selected($currentStatus === 'BIMBINGAN_ACTIVE')>Bimbingan Aktif</option>
                            <option value="ELIGIBLE_SIDANG" @selected($currentStatus === 'ELIGIBLE_SIDANG')>Sidang Sidang</option>
                            <option value="SIDANG_REG_DRAFT" @selected($currentStatus === 'SIDANG_REG_DRAFT')>Draft Pendaftaran Sidang</option>
                            <option value="SIDANG_REG_SUBMITTED" @selected($currentStatus === 'SIDANG_REG_SUBMITTED')>Pendaftaran Sidang Dikirim</option>
                            <option value="SIDANG_REG_REJECTED" @selected($currentStatus === 'SIDANG_REG_REJECTED')>Pendaftaran Sidang Ditolak</option>
                            <option value="SIDANG_SCHEDULED" @selected($currentStatus === 'SIDANG_SCHEDULED')>Sidang Dijadwalkan</option>
                            <option value="SIDANG_COMPLETED" @selected($currentStatus === 'SIDANG_COMPLETED')>Sidang Selesai</option>
                            <option value="REVISION_PENDING" @selected($currentStatus === 'REVISION_PENDING')>Menunggu Upload Revisi</option>
                            <option value="REVISION_UPLOADED" @selected($currentStatus === 'REVISION_UPLOADED')>Revisi Dikirim</option>
                            <option value="REVISION_APPROVED" @selected($currentStatus === 'REVISION_APPROVED')>Revisi Disetujui</option>
                            <option value="THESIS_COMPLETED" @selected($currentStatus === 'THESIS_COMPLETED')>Skripsi Selesai</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Alasan Perubahan (Masuk ke Audit Log)</label>
                        <textarea name="override_reason" rows="3" placeholder="Masukkan alasan override skripsi..." class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#7a1621]" required></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="skripsiModalOpen = null" class="px-4 py-2 border rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                        <button type="submit" class="btn-maroon px-4 py-2 rounded-lg text-xs font-bold shadow-md">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection
