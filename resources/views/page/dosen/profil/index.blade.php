@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 2xl:px-16 py-8">
    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Header -->
    <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-lg p-8 mb-6">
        <div class="flex flex-col sm:flex-row items-start gap-8">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-[#8B1538] to-[#6D1029] flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
            </div>

            <!-- Profile Info -->
            <div class="flex-grow">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $user->name }}</h1>
                <div class="space-y-3 text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-envelope text-[#8B1538]"></i>
                        <span>{{ $user->email }}</span>
                    </div>
                    @if($dosen->nidn)
                        <div class="flex items-center gap-3">
                            <i class="fas fa-id-card text-[#8B1538]"></i>
                            <span>NIDN: {{ $dosen->nidn }}</span>
                        </div>
                    @endif
                    @if($dosen->phone)
                        <div class="flex items-center gap-3">
                            <i class="fas fa-phone text-[#8B1538]"></i>
                            <span>{{ $dosen->phone }}</span>
                        </div>
                    @endif
                    @if($dosen->address && trim($dosen->address) !== '')
                        <div class="flex items-center gap-3">
                            <i class="fas fa-map-marker-alt text-[#8B1538]"></i>
                            <span>{{ $dosen->address }}</span>
                        </div>
                    @endif
                    @if($fakultas)
                        <div class="flex items-center gap-3">
                            <i class="fas fa-building text-[#8B1538]"></i>
                            <span>{{ $fakultas->nama_fakultas ?? 'N/A' }}</span>
                        </div>
                    @endif
                </div>

                <!-- Edit Button -->
                <div class="mt-6">
                    <a href="{{ route('dosen.profil.edit') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#8B1538] text-white rounded-lg hover:bg-[#6D1029] transition font-semibold">
                        <i class="fas fa-edit"></i>
                        Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Section -->
    <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-lg overflow-hidden">
        <!-- Tab Navigation -->
        <div class="flex border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#0f1117]">
            <button onclick="showTab('info')" class="tab-button flex-1 px-6 py-4 text-center font-semibold text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:text-[#8B1538] active-tab" data-tab="info">
                <i class="fas fa-info-circle mr-2"></i>
                Informasi Pribadi
            </button>
            <button onclick="showTab('pendidikan')" class="tab-button flex-1 px-6 py-4 text-center font-semibold text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:text-[#8B1538]" data-tab="pendidikan">
                <i class="fas fa-graduation-cap mr-2"></i>
                Pendidikan
            </button>
            <button onclick="showTab('pengajaran')" class="tab-button flex-1 px-6 py-4 text-center font-semibold text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:text-[#8B1538]" data-tab="pengajaran">
                <i class="fas fa-chalkboard-user mr-2"></i>
                Pengajaran
            </button>
            <button onclick="showTab('pa')" class="tab-button flex-1 px-6 py-4 text-center font-semibold text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:text-[#8B1538]" data-tab="pa">
                <i class="fas fa-users mr-2"></i>
                PA/Pembimbing
            </button>
        </div>

        <!-- Tab Contents -->
        <div class="p-8">
            <!-- Tab: Info Pribadi -->
            <div id="info" class="tab-content">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Informasi Pribadi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Nama Lengkap</label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Email</label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">NIDN</label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $dosen->nidn ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">No. Telepon</label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $dosen->phone ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Alamat</label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $dosen->address ?? 'Belum diisi' }}</p>
                    </div>
                </div>
            </div>

            <!-- Tab: Pendidikan -->
            <div id="pendidikan" class="tab-content hidden">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Riwayat Pendidikan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Pendidikan Terakhir</label>
                        <p class="text-lg text-gray-900 dark:text-white">
                            @if(is_array($dosen->pendidikan_terakhir) && !empty($dosen->pendidikan_terakhir))
                                {{ implode(', ', $dosen->pendidikan_terakhir) }}
                            @elseif($dosen->pendidikan_terakhir)
                                {{ $dosen->pendidikan_terakhir }}
                            @else
                                Belum diisi
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Universitas</label>
                        <p class="text-lg text-gray-900 dark:text-white">
                            @if(is_array($dosen->universitas) && !empty($dosen->universitas))
                                {{ implode(', ', $dosen->universitas) }}
                            @elseif($dosen->universitas)
                                {{ $dosen->universitas }}
                            @else
                                Belum diisi
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Program Studi</label>
                        <p class="text-lg text-gray-900 dark:text-white">
                            @if(is_array($dosen->prodi) && !empty($dosen->prodi))
                                {{ implode(', ', $dosen->prodi) }}
                            @elseif($dosen->prodi)
                                {{ $dosen->prodi }}
                            @else
                                Belum diisi
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Jabatan Fungsional</label>
                        <p class="text-lg text-gray-900 dark:text-white">
                            @if(is_array($dosen->jabatan_fungsional) && !empty($dosen->jabatan_fungsional))
                                {{ implode(', ', $dosen->jabatan_fungsional) }}
                            @elseif($dosen->jabatan_fungsional)
                                {{ $dosen->jabatan_fungsional }}
                            @else
                                Belum diisi
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tab: Pengajaran -->
            <div id="pengajaran" class="tab-content hidden">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Mata Kuliah yang Diampu</h2>
                @if($mataKuliahs->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($mataKuliahs as $mk)
                            <div class="bg-gray-50 dark:bg-[#0f1117] p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $mk->nama_mk }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Kode: {{ $mk->kode_mk }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">SKS: {{ $mk->sks }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Semester: {{ $mk->semester }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-book text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 dark:text-gray-400">Belum ada mata kuliah yang ditugaskan</p>
                    </div>
                @endif
            </div>

            <!-- Tab: PA/Pembimbing -->
            <div id="pa" class="tab-content hidden">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Mahasiswa Bimbingan Akademik (PA)</h2>
                @if($mahasiswaPa->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-100 dark:bg-[#0f1117] border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th class="px-4 py-3 font-semibold text-gray-700 dark:text-gray-300">NIM</th>
                                    <th class="px-4 py-3 font-semibold text-gray-700 dark:text-gray-300">Nama Mahasiswa</th>
                                    <th class="px-4 py-3 font-semibold text-gray-700 dark:text-gray-300">Program Studi</th>
                                    <th class="px-4 py-3 font-semibold text-gray-700 dark:text-gray-300">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mahasiswaPa as $mahasiswa)
                                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-[#0f1117]">
                                        <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $mahasiswa->nim ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $mahasiswa->user->name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $mahasiswa->program_studi ?? '-' }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                                                {{ $mahasiswa->status === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $mahasiswa->status ?? 'Aktif' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-user-graduate text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 dark:text-gray-400">Belum ada mahasiswa bimbingan akademik</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    const contents = document.querySelectorAll('.tab-content');
    contents.forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active class from all buttons
    const buttons = document.querySelectorAll('.tab-button');
    buttons.forEach(button => {
        button.classList.remove('border-[#8B1538]', 'text-[#8B1538]');
        button.classList.add('border-transparent', 'text-gray-700', 'dark:text-gray-300');
    });

    // Show selected tab content
    document.getElementById(tabName).classList.remove('hidden');

    // Add active class to selected button
    event.target.closest('.tab-button').classList.add('border-[#8B1538]', 'text-[#8B1538]');
    event.target.closest('.tab-button').classList.remove('border-transparent', 'text-gray-700', 'dark:text-gray-300');
}

// Set initial active tab
document.addEventListener('DOMContentLoaded', function() {
    const firstButton = document.querySelector('.tab-button[data-tab="info"]');
    if (firstButton) {
        firstButton.classList.add('border-[#8B1538]', 'text-[#8B1538]');
    }
});
</script>
@endsection
