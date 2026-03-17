@extends('layouts.admin')

@section('title', 'Detail Mahasiswa')
@section('page-title', 'Detail Mahasiswa')

@section('content')
<div class="mb-6 flex items-start justify-between">
    <div>
        <h3 class="text-2xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-user-graduate text-maroon mr-3 text-2xl"></i>
            Detail Mahasiswa
        </h3>
        <p class="text-sm text-gray-600 mt-1">Informasi lengkap mahasiswa STIH</p>
    </div>
    <a href="{{ route('admin.mahasiswa.index') }}"
        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon p-8" x-data="{ activeTab: 'akademik' }">
    @php
    $user = $mahasiswa->user ?? null;
    $parent = $mahasiswa->parents()->exists() ? $mahasiswa->parents()->first() : null;
    @endphp

    {{-- Tabs Header --}}
    <div class="flex border-b border-gray-200 mb-8 overflow-x-auto">
        <button @click="activeTab = 'akademik'"
            class="flex-1 px-6 py-4 text-center text-sm transition-all duration-200 font-semibold whitespace-nowrap"
            x-bind:style="activeTab === 'akademik'
                    ? 'border-bottom: 2px solid #8B1538; color: #8B1538; background-color: #f9fafb;'
                    : 'border-bottom: 2px solid transparent; color: #6b7280; background-color: transparent;'">
            <i class="fas fa-graduation-cap mr-2"></i>Akademik
        </button>

        <button @click="activeTab = 'data_pribadi'"
            class="flex-1 px-6 py-4 text-center text-sm transition-all duration-200 whitespace-nowrap"
            x-bind:style="activeTab === 'data_pribadi'
                    ? 'border-bottom: 2px solid #8B1538; color: #8B1538; background-color: #f9fafb; font-weight:600;'
                    : 'border-bottom: 2px solid transparent; color: #6b7280; background-color: transparent;'">
            <i class="fas fa-user mr-2"></i>Data Pribadi
        </button>

        <button @click="activeTab = 'orang_tua'"
            class="flex-1 px-6 py-4 text-center text-sm transition-all duration-200 whitespace-nowrap"
            x-bind:style="activeTab === 'orang_tua'
                    ? 'border-bottom: 2px solid #8B1538; color: #8B1538; background-color: #f9fafb; font-weight:600;'
                    : 'border-bottom: 2px solid transparent; color: #6b7280; background-color: transparent;'">
            <i class="fas fa-users mr-2"></i>Orang Tua / Wali
        </button>


    </div>

    <div class="w-full">

        {{-- TAB AKADEMIK --}}
        <div x-show="activeTab === 'akademik'" x-cloak class="space-y-8">

            <div class="flex flex-col md:flex-row gap-8 items-start border-b border-gray-100 pb-8">
                <div class="shrink-0 mx-auto md:mx-0">
                    <div class="border-2 border-gray-100 rounded-lg overflow-hidden bg-gray-50 shadow-sm"
                        style="width:160px; height:208px;">
                        @if($mahasiswa->foto)
                        <img src="{{ asset('storage/' . $mahasiswa->foto) }}" alt="Foto Mahasiswa"
                            class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <i class="fas fa-user text-5xl"></i>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="grow w-full space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 border-b pb-4">
                        <label class="text-xs font-bold text-gray-400 uppercase">Nama Lengkap</label>
                        <div class="md:col-span-2 text-lg font-semibold text-gray-800">{{ $user->name }}</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 border-b pb-4">
                        <label class="text-xs font-bold text-gray-400 uppercase">NIM</label>
                        <div class="md:col-span-2 font-mono text-gray-800">{{ $mahasiswa->nim }}</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 border-b pb-4">
                        <label class="text-xs font-bold text-gray-400 uppercase">Program Studi</label>
                        <div class="md:col-span-2 text-gray-800">{{ $mahasiswa->prodi }}</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <label class="text-xs font-bold text-gray-400 uppercase">Status</label>
                        <div class="md:col-span-2">
                            <span class="px-3 py-1 text-xs rounded-full 
                                    {{ $mahasiswa->status == 'aktif' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $mahasiswa->status == 'cuti' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $mahasiswa->status == 'lulus' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $mahasiswa->status == 'drop-out' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($mahasiswa->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-medium text-gray-800 mb-4 border-b pb-2">Kontak & Akun</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Email</label>
                        <div class="text-gray-800">{{ $user->email }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Nomor HP</label>
                        <div class="text-gray-800">{{ $mahasiswa->no_hp ?? 'Belum diisi' }}</div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-medium text-gray-800 mb-4 border-b pb-2">Detail Akademik</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Program</label>
                        <div>Reguler</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Kurikulum</label>
                        <div>Kurikulum {{ $mahasiswa->prodi }} Angkatan {{ $mahasiswa->angkatan }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Angkatan</label>
                        <div>{{ $mahasiswa->angkatan }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Penasihat Akademik</label>
                        <div>{{ $mahasiswa->dosenPa->first()->user->name ?? 'Belum ditentukan' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB DATA PRIBADI --}}
        <div x-show="activeTab === 'data_pribadi'" x-cloak class="space-y-8">
            <div>
                <h3 class="font-medium text-gray-800 border-b pb-2 mb-4">Alamat Domisili</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-gray-400 uppercase">Alamat Lengkap</label>
                        <div>{{ $mahasiswa->alamat ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">RT / RW</label>
                        <div>{{ ($mahasiswa->rt ?? '-') . ' / ' . ($mahasiswa->rw ?? '-') }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Desa/Kelurahan</label>
                        <div>{{ $mahasiswa->desa ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Kecamatan</label>
                        <div>{{ $mahasiswa->kecamatan ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Kota/Kabupaten</label>
                        <div>{{ $mahasiswa->kota ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Provinsi</label>
                        <div>{{ $mahasiswa->provinsi ?? 'Belum diisi' }}</div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-medium text-gray-800 border-b pb-2 mb-4">Alamat Sesuai KTP</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-gray-400 uppercase">Alamat Lengkap</label>
                        <div>{{ $mahasiswa->alamat_ktp ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">RT / RW</label>
                        <div>{{ ($mahasiswa->rt_ktp ?? '-') . ' / ' . ($mahasiswa->rw_ktp ?? '-') }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Desa/Kelurahan</label>
                        <div>{{ $mahasiswa->desa_ktp ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Kecamatan</label>
                        <div>{{ $mahasiswa->kecamatan_ktp ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Kota/Kabupaten</label>
                        <div>{{ $mahasiswa->kota_ktp ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Provinsi</label>
                        <div>{{ $mahasiswa->provinsi_ktp ?? 'Belum diisi' }}</div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-medium text-gray-800 border-b pb-2 mb-4">Data Pribadi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Tempat Lahir</label>
                        <div>{{ $mahasiswa->tempat_lahir ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Tanggal Lahir</label>
                        <div>
                            {{ $mahasiswa->tanggal_lahir
        ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('d F Y')
        : 'Belum diisi' }}
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Jenis Kelamin</label>
                        <div>{{ $mahasiswa->jenis_kelamin ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Agama</label>
                        <div>{{ $mahasiswa->agama ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Status Sipil</label>
                        <div>{{ $mahasiswa->status_sipil ?? 'Belum diisi' }}</div>
                    </div>
                </div>
            </div>

            <div x-data="{ previewOpen: false, previewUrl: '', previewType: '', previewTitle: '' }">
                <div class="flex items-center justify-between border-b pb-2 mb-4">
                    <h3 class="font-medium text-gray-800">Dokumen Pribadi</h3>
                    <form action="{{ route('admin.mahasiswa.toggle-dokumen', $mahasiswa) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs px-3 py-1.5 rounded-md font-bold transition-colors shadow-sm
                            {{ $mahasiswa->is_dokumen_unlocked 
                                ? 'bg-red-50 text-red-600 border border-red-200 hover:bg-red-100' 
                                : 'bg-green-50 text-green-600 border border-green-200 hover:bg-green-100' }}">
                            <i class="fas {{ $mahasiswa->is_dokumen_unlocked ? 'fa-lock' : 'fa-unlock' }} mr-1"></i>
                            {{ $mahasiswa->is_dokumen_unlocked ? 'Kunci Upload Dokumen' : 'Aktifkan Kembali Upload Dokumen' }}
                        </button>
                    </form>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Ijazah</label>
                        <div>
                            @if($mahasiswa->file_ijazah && count($mahasiswa->file_ijazah) > 0)
                            @foreach($mahasiswa->file_ijazah as $file)
                            <a href="#" @click.prevent="previewUrl = '{{ asset('storage/' . $file) }}'; previewTitle = 'Preview Ijazah'; previewType = '{{ pathinfo($file, PATHINFO_EXTENSION) }}'; previewOpen = true"
                                class="text-cyan-600 hover:underline block text-sm cursor-pointer">
                                <i class="fas fa-file-{{ in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['pdf']) ? 'pdf' : 'image' }} mr-1"></i>{{ basename($file) }}
                            </a>
                            @endforeach
                            @else
                            <span class="text-gray-400">Belum diupload</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Transkrip Nilai</label>
                        <div>
                            @if($mahasiswa->file_transkrip && count($mahasiswa->file_transkrip) > 0)
                            @foreach($mahasiswa->file_transkrip as $file)
                            <a href="#" @click.prevent="previewUrl = '{{ asset('storage/' . $file) }}'; previewTitle = 'Preview Transkrip Nilai'; previewType = '{{ pathinfo($file, PATHINFO_EXTENSION) }}'; previewOpen = true"
                                class="text-cyan-600 hover:underline block text-sm cursor-pointer">
                                <i class="fas fa-file-{{ in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['pdf']) ? 'pdf' : 'image' }} mr-1"></i>{{ basename($file) }}
                            </a>
                            @endforeach
                            @else
                            <span class="text-gray-400">Belum diupload</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Kartu Keluarga (KK)</label>
                        <div>
                            @if($mahasiswa->file_kk && count($mahasiswa->file_kk) > 0)
                            @foreach($mahasiswa->file_kk as $file)
                            <a href="#" @click.prevent="previewUrl = '{{ asset('storage/' . $file) }}'; previewTitle = 'Preview Kartu Keluarga'; previewType = '{{ pathinfo($file, PATHINFO_EXTENSION) }}'; previewOpen = true"
                                class="text-cyan-600 hover:underline block text-sm cursor-pointer">
                                <i class="fas fa-file-{{ in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['pdf']) ? 'pdf' : 'image' }} mr-1"></i>{{ basename($file) }}
                            </a>
                            @endforeach
                            @else
                            <span class="text-gray-400">Belum diupload</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">KTP</label>
                        <div>
                            @if($mahasiswa->file_ktp && count($mahasiswa->file_ktp) > 0)
                            @foreach($mahasiswa->file_ktp as $file)
                            <a href="#" @click.prevent="previewUrl = '{{ asset('storage/' . $file) }}'; previewTitle = 'Preview KTP'; previewType = '{{ pathinfo($file, PATHINFO_EXTENSION) }}'; previewOpen = true"
                                class="text-cyan-600 hover:underline block text-sm cursor-pointer">
                                <i class="fas fa-file-{{ in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['pdf']) ? 'pdf' : 'image' }} mr-1"></i>{{ basename($file) }}
                            </a>
                            @endforeach
                            @else
                            <span class="text-gray-400">Belum diupload</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Preview Modal --}}
                <div x-show="previewOpen" x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">

                    {{-- Backdrop --}}
                    <div class="absolute inset-0 bg-black/60" @click="previewOpen = false; previewUrl = ''"></div>

                    {{-- Modal Content --}}
                    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden"
                        @keydown.escape.window="previewOpen = false; previewUrl = ''">

                        {{-- Header --}}
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                            <div class="flex items-center space-x-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-maroon/10 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-eye text-maroon text-sm"></i>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-800 truncate" x-text="previewTitle"></h3>
                            </div>
                            <div class="flex items-center space-x-2 flex-shrink-0">
                                <a :href="previewUrl" target="_blank"
                                    class="px-3 py-1.5 text-xs font-bold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg transition shadow-sm flex items-center"
                                    title="Buka di tab baru">
                                    <i class="fas fa-external-link-alt md:mr-2"></i><span class="hidden md:inline">Buka Tab Baru</span>
                                </a>
                                <a :href="previewUrl" download
                                    class="px-3 py-1.5 text-xs font-bold text-white bg-[#8B1538] hover:bg-[#6D1029] rounded-lg transition shadow-sm flex items-center"
                                    title="Download">
                                    <i class="fas fa-download md:mr-2"></i><span class="hidden md:inline">Download</span>
                                </a>
                                <button @click="previewOpen = false; previewUrl = ''"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Body --}}
                        <div class="flex-1 overflow-auto p-1 bg-gray-100 relative" style="min-height: 500px; -webkit-overflow-scrolling: touch;">
                            <template x-if="previewType === 'pdf'">
                                <iframe :src="previewUrl" class="w-full h-full rounded" style="min-height: 500px;" frameborder="0"></iframe>
                            </template>
                            <template x-if="['jpg','jpeg','png','gif','webp','svg','bmp'].includes(previewType.toLowerCase())">
                                <div class="flex items-center justify-center w-full min-h-full" style="min-height: 500px;">
                                    <img :src="previewUrl" :alt="previewTitle" class="max-w-full max-h-[78vh] object-contain rounded shadow-sm">
                                </div>
                            </template>
                            <template x-if="previewType !== 'pdf' && !['jpg','jpeg','png','gif','webp','svg','bmp'].includes(previewType.toLowerCase())">
                                <div class="flex items-center justify-center w-full min-h-full" style="min-height: 500px;">
                                    <div class="text-center text-gray-500 py-12">
                                        <i class="fas fa-file-alt text-4xl mb-3"></i>
                                        <p class="text-sm">Preview tidak tersedia untuk tipe file ini.</p>
                                        <a :href="previewUrl" download class="mt-3 inline-block text-cyan-600 hover:underline text-sm">
                                            <i class="fas fa-download mr-1"></i>Download File
                                        </a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="font-medium text-gray-800 border-b pb-2 mb-4">Data Asal Sekolah</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Jenis Sekolah</label>
                        <div>{{ $mahasiswa->jenis_sekolah ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Jurusan Sekolah</label>
                        <div>{{ $mahasiswa->jurusan_sekolah ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Tahun Lulus</label>
                        <div>{{ $mahasiswa->tahun_lulus ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Nilai Kelulusan</label>
                        <div>{{ $mahasiswa->nilai_kelulusan ?? 'Belum diisi' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB ORANG TUA --}}
        <div x-show="activeTab === 'orang_tua'" x-cloak class="space-y-8">
            @if($parent)
            <div>
                <h3 class="font-medium text-gray-800 border-b pb-2 mb-4">Data Ayah</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Nama Ayah</label>
                        <div>{{ $parent->nama_ayah ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Pendidikan Ayah</label>
                        <div>{{ $parent->pendidikan_ayah ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Pekerjaan Ayah</label>
                        <div>{{ $parent->pekerjaan_ayah ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Agama Ayah</label>
                        <div>{{ $parent->agama_ayah ?? 'Belum diisi' }}</div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-medium text-gray-800 border-b pb-2 mb-4">Data Ibu</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Nama Ibu</label>
                        <div>{{ $parent->nama_ibu ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Pendidikan Ibu</label>
                        <div>{{ $parent->pendidikan_ibu ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Pekerjaan Ibu</label>
                        <div>{{ $parent->pekerjaan_ibu ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Agama Ibu</label>
                        <div>{{ $parent->agama_ibu ?? 'Belum diisi' }}</div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-medium text-gray-800 border-b pb-2 mb-4">Alamat Ayah</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Alamat</label>
                        <div>{{ $parent->alamat_ayah ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Provinsi</label>
                        <div>{{ $parent->propinsi_ayah ?? $parent->propinsi_ortu ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Kota/Kabupaten</label>
                        <div>{{ $parent->kota_ayah ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Kecamatan</label>
                        <div>{{ $parent->kecamatan_ayah ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Desa/Kelurahan</label>
                        <div>{{ $parent->desa_ayah ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Handphone</label>
                        <div>{{ $parent->handphone_ayah ?? 'Belum diisi' }}</div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-medium text-gray-800 border-b pb-2 mb-4">Alamat Ibu</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Alamat</label>
                        <div>{{ $parent->alamat_ibu ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Provinsi</label>
                        <div>{{ $parent->propinsi_ibu ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Kota/Kabupaten</label>
                        <div>{{ $parent->kota_ibu ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Kecamatan</label>
                        <div>{{ $parent->kecamatan_ibu ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Desa/Kelurahan</label>
                        <div>{{ $parent->desa_ibu ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Handphone</label>
                        <div>{{ $parent->handphone_ibu ?? 'Belum diisi' }}</div>
                    </div>
                </div>
            </div>

            {{-- Keluarga Lainnya Display --}}
            @if($parent->keluarga && count($parent->keluarga) > 0)
            <div>
                <h3 class="font-medium text-gray-800 border-b pb-2 mb-4">Data Keluarga Lainnya</h3>
                <div class="space-y-4">
                    @foreach($parent->keluarga as $idx => $member)
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase">Nama</label>
                                <div>{{ $member['nama'] ?? 'Belum diisi' }}</div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase">Hubungan</label>
                                <div>{{ $member['hubungan'] ?? 'Belum diisi' }}</div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase">Pendidikan</label>
                                <div>{{ $member['pendidikan'] ?? 'Belum diisi' }}</div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase">Pekerjaan</label>
                                <div>{{ $member['pekerjaan'] ?? 'Belum diisi' }}</div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase">Agama</label>
                                <div>{{ $member['agama'] ?? 'Belum diisi' }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Wali Display --}}
            @if($parent->nama_wali)
            <div>
                <h3 class="font-medium text-gray-800 border-b pb-2 mb-4">Data Wali</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Nama Wali</label>
                        <div>{{ $parent->nama_wali ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Hubungan</label>
                        <div>{{ $parent->hubungan_wali ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Pendidikan Wali</label>
                        <div>{{ $parent->pendidikan_wali ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Pekerjaan Wali</label>
                        <div>{{ $parent->pekerjaan_wali ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Agama Wali</label>
                        <div>{{ $parent->agama_wali ?? 'Belum diisi' }}</div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-medium text-gray-800 border-b pb-2 mb-4">Alamat Wali</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Alamat</label>
                        <div>{{ $parent->alamat_wali ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Provinsi</label>
                        <div>{{ $parent->provinsi_wali ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Kota/Kabupaten</label>
                        <div>{{ $parent->kota_wali ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Kecamatan</label>
                        <div>{{ $parent->kecamatan_wali ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Desa/Kelurahan</label>
                        <div>{{ $parent->desa_wali ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Handphone</label>
                        <div>{{ $parent->handphone_wali ?? 'Belum diisi' }}</div>
                    </div>
                </div>
            </div>
            @endif

            @if(!$parent->nama_ayah && !$parent->nama_ibu && !$parent->nama_wali)
            <p class="text-sm text-gray-500">Informasi orang tua/wali belum dilengkapi.</p>
            @endif
            @else
            <p class="text-sm text-gray-500">Informasi orang tua/wali belum dilengkapi.</p>
            @endif
        </div>

    </div>
</div>
{{-- Action Buttons --}}
<div class="mt-6 flex justify-end space-x-3">
    <a href="{{ route('admin.mahasiswa.edit', $mahasiswa) }}"
        class="btn-maroon px-6 py-3 rounded-lg hover:bg-opacity-90 transition flex items-center shadow-md">
        <i class="fas fa-edit mr-2"></i>Edit Data
    </a>
</div>
@endsection