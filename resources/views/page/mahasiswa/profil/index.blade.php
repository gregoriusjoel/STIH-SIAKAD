@extends('layouts.mahasiswa')

@section('title', 'Profil Mahasiswa')
@section('page-title', 'Profil Mahasiswa')

@section('content')
    <div class="bg-white rounded-lg shadow-sm p-8" x-data="{ activeTab: 'akademik' }">

        {{-- Header with Edit Button --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            {{-- Tabs Header --}}
            <div class="flex border-b border-gray-200 overflow-x-auto w-full md:w-auto">
                <button @click="activeTab = 'akademik'"
                    class="flex-1 min-w-[120px] py-4 text-center text-sm transition-all duration-200 font-semibold"
                    style="border-bottom: 2px solid #8B1538; color: #8B1538; background-color: #f9fafb;"
                    x-bind:style="activeTab === 'akademik'
                        ? 'border-bottom: 2px solid #8B1538; color: #8B1538; background-color: #f9fafb;'
                        : 'border-bottom: 2px solid transparent; color: #6b7280; background-color: transparent;'">
                    Akademik
                </button>

                <button @click="activeTab = 'data_pribadi'"
                    class="flex-shrink-0 px-6 py-4 text-center text-sm transition-all duration-200 whitespace-nowrap"
                    x-bind:style="activeTab === 'data_pribadi'
                        ? 'border-bottom: 2px solid #8B1538; color: #8B1538; background-color: #f9fafb; font-weight:600;'
                        : 'border-bottom: 2px solid transparent; color: #6b7280; background-color: transparent;'">
                    Data Pribadi
                </button>

                <button @click="activeTab = 'orang_tua'"
                    class="flex-shrink-0 px-6 py-4 text-center text-sm transition-all duration-200 whitespace-nowrap"
                    x-bind:style="activeTab === 'orang_tua'
                        ? 'border-bottom: 2px solid #8B1538; color: #8B1538; background-color: #f9fafb; font-weight:600;'
                        : 'border-bottom: 2px solid transparent; color: #6b7280; background-color: transparent;'">
                    Orang Tua
                </button>

                <button @click="activeTab = 'asal_sekolah'"
                    class="flex-shrink-0 px-6 py-4 text-center text-sm transition-all duration-200 whitespace-nowrap"
                    x-bind:style="activeTab === 'asal_sekolah'
                        ? 'border-bottom: 2px solid #8B1538; color: #8B1538; background-color: #f9fafb; font-weight:600;'
                        : 'border-bottom: 2px solid transparent; color: #6b7280; background-color: transparent;'">
                    Asal Sekolah
                </button>
            </div>

            <a href="{{ route('mahasiswa.profil.manajemen') }}"
                class="inline-flex items-center gap-2 px-6 py-2.5 text-white font-medium rounded-full shadow-sm transition-all text-sm whitespace-nowrap"
                style="background-color:#8B1538;"
                onmouseover="this.style.backgroundColor='#6D1029'"
                onmouseout="this.style.backgroundColor='#8B1538'">
                <i class="fas fa-edit"></i> Edit Profil
            </a>
        </div>

        <div class="max-w-5xl mx-auto">

            {{-- TAB AKADEMIK --}}
            <div x-show="activeTab === 'akademik'" x-cloak class="space-y-10">

                <div class="flex flex-col md:flex-row gap-8 items-start border-b border-gray-100 pb-8">
                    <div class="shrink-0 mx-auto md:mx-0">
                        <div
    class="border-2 border-gray-100 rounded-lg overflow-hidden bg-gray-50 shadow-sm"
    style="width:160px; height:208px; min-width:160px; min-height:208px; max-width:160px; max-height:208px;"
>
    @if($mahasiswa->foto)
        <img
            src="{{ asset('storage/' . $mahasiswa->foto) }}"
            alt="Foto Mahasiswa"
            loading="lazy"
            style="
                width:160px;
                height:208px;
                object-fit:cover;
                display:block;
            "
        >
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
                            <div class="md:col-span-2 font-mono text-gray-800">{{ $mahasiswa->npm }}</div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 border-b pb-4">
                            <label class="text-xs font-bold text-gray-400 uppercase">Program Studi</label>
                            <div class="md:col-span-2 text-gray-800">{{ $mahasiswa->prodi }}</div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3">
                            <label class="text-xs font-bold text-gray-400 uppercase">Status Akun</label>
                            <div class="md:col-span-2">
                                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    {{ ucfirst($mahasiswa->status_akun) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="font-medium text-gray-800 mb-6 border-b pb-2">Kontak & Akun</h3>
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
                    <h3 class="font-medium text-gray-800 mb-6 border-b pb-2">Detail Akademik</h3>
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
                            <div>Dosen PA</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB DATA PRIBADI --}}
            <div x-show="activeTab === 'data_pribadi'" x-cloak class="space-y-10">
                <h3 class="font-medium text-gray-800 border-b pb-2">Informasi Alamat</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Alamat Lengkap</label>
                        <div>{{ $mahasiswa->alamat ?? 'Belum diisi' }}</div>
                    </div>
                </div>

                <h3 class="font-medium text-gray-800 border-b pb-2">Data Pribadi</h3>
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
                </div>
            </div>

            {{-- TAB ORANG TUA --}}
            <div x-show="activeTab === 'orang_tua'" x-cloak class="space-y-10">
                <h3 class="font-medium text-gray-800 border-b pb-2">Data Orang Tua</h3>
                <p class="text-sm text-gray-500">Informasi orang tua belum dilengkapi.</p>
            </div>

            {{-- TAB ASAL SEKOLAH --}}
            <div x-show="activeTab === 'asal_sekolah'" x-cloak class="space-y-10">
                <h3 class="font-medium text-gray-800 border-b pb-2">Asal Sekolah</h3>
                <p class="text-sm text-gray-500">Data asal sekolah belum tersedia.</p>
            </div>

        </div>
    </div>
@endsection
