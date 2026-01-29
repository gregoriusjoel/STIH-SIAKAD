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
    <a href="{{ route('admin.mahasiswa.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
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
            class="flex-1 min-w-[120px] py-4 text-center text-sm transition-all duration-200 font-semibold"
            x-bind:style="activeTab === 'akademik'
                ? 'border-bottom: 2px solid #8B1538; color: #8B1538; background-color: #f9fafb;'
                : 'border-bottom: 2px solid transparent; color: #6b7280; background-color: transparent;'">
            <i class="fas fa-graduation-cap mr-2"></i>Akademik
        </button>

        <button @click="activeTab = 'data_pribadi'"
            class="flex-shrink-0 px-6 py-4 text-center text-sm transition-all duration-200 whitespace-nowrap"
            x-bind:style="activeTab === 'data_pribadi'
                ? 'border-bottom: 2px solid #8B1538; color: #8B1538; background-color: #f9fafb; font-weight:600;'
                : 'border-bottom: 2px solid transparent; color: #6b7280; background-color: transparent;'">
            <i class="fas fa-user mr-2"></i>Data Pribadi
        </button>

        <button @click="activeTab = 'orang_tua'"
            class="flex-shrink-0 px-6 py-4 text-center text-sm transition-all duration-200 whitespace-nowrap"
            x-bind:style="activeTab === 'orang_tua'
                ? 'border-bottom: 2px solid #8B1538; color: #8B1538; background-color: #f9fafb; font-weight:600;'
                : 'border-bottom: 2px solid transparent; color: #6b7280; background-color: transparent;'">
            <i class="fas fa-users mr-2"></i>Orang Tua
        </button>

        <button @click="activeTab = 'asal_sekolah'"
            class="flex-shrink-0 px-6 py-4 text-center text-sm transition-all duration-200 whitespace-nowrap"
            x-bind:style="activeTab === 'asal_sekolah'
                ? 'border-bottom: 2px solid #8B1538; color: #8B1538; background-color: #f9fafb; font-weight:600;'
                : 'border-bottom: 2px solid transparent; color: #6b7280; background-color: transparent;'">
            <i class="fas fa-school mr-2"></i>Asal Sekolah
        </button>
    </div>

    <div class="max-w-5xl mx-auto">

        {{-- TAB AKADEMIK --}}
        <div x-show="activeTab === 'akademik'" x-cloak class="space-y-8">

            <div class="flex flex-col md:flex-row gap-8 items-start border-b border-gray-100 pb-8">
                <div class="shrink-0 mx-auto md:mx-0">
                    <div class="border-2 border-gray-100 rounded-lg overflow-hidden bg-gray-50 shadow-sm"
                        style="width:160px; height:208px;">
                        @if($mahasiswa->foto)
                            <img src="{{ asset('storage/' . $mahasiswa->foto) }}" alt="Foto Mahasiswa" class="w-full h-full object-cover">
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
                <h3 class="font-medium text-gray-800 border-b pb-2 mb-4">Informasi Alamat</h3>
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
                        <label class="text-xs font-bold text-gray-400 uppercase">Kota</label>
                        <div>{{ $mahasiswa->kota ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Propinsi</label>
                        <div>{{ $mahasiswa->propinsi ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Negara</label>
                        <div>{{ $mahasiswa->negara ?? 'Belum diisi' }}</div>
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
                        <div>{{ $mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('d F Y') : 'Belum diisi' }}</div>
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
                    <h3 class="font-medium text-gray-800 border-b pb-2 mb-4">Alamat Orang Tua</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase">Alamat</label>
                            <div>{{ $parent->alamat_ortu ?? 'Belum diisi' }}</div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase">Kota</label>
                            <div>{{ $parent->kota_ortu ?? 'Belum diisi' }}</div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase">Propinsi</label>
                            <div>{{ $parent->propinsi_ortu ?? 'Belum diisi' }}</div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase">Negara</label>
                            <div>{{ $parent->negara_ortu ?? 'Belum diisi' }}</div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase">Handphone</label>
                            <div>{{ $parent->handphone_ortu ?? 'Belum diisi' }}</div>
                        </div>
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-500">Informasi orang tua belum dilengkapi.</p>
            @endif
        </div>

        {{-- TAB ASAL SEKOLAH --}}
        <div x-show="activeTab === 'asal_sekolah'" x-cloak class="space-y-8">
            <div>
                <h3 class="font-medium text-gray-800 border-b pb-2 mb-4">Asal Sekolah</h3>
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

    </div>
</div>

{{-- Action Buttons --}}
<div class="mt-6 flex justify-end space-x-3">
    <a href="{{ route('admin.mahasiswa.edit', $mahasiswa) }}" class="btn-maroon px-6 py-3 rounded-lg hover:bg-opacity-90 transition flex items-center shadow-md">
        <i class="fas fa-edit mr-2"></i>Edit Data
    </a>
</div>
@endsection
