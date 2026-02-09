@extends('layouts.admin')

@section('title', 'Data KRS')
@section('page-title', 'Data KRS')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                <i class="fas fa-file-alt mr-3 text-maroon dark:text-red-500"></i>
                Manajemen KRS
            </h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Kelola dan verifikasi Kartu Rencana Studi mahasiswa</p>
        </div>
    </div>

    <!-- Semester Aktif & Pengaturan KRS (two-column) -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left: Semester Aktif -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden relative flex">
                        <div class="flex w-full">
                            <div class="flex-1 p-6 relative flex flex-col justify-between">
                                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider">Semester Aktif</p>
                                @if(isset($semesterAktif))
                                    <h3 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-gray-100 mt-1 break-words">{{ $semesterAktif->nama_semester ?? '-' }} {{ $semesterAktif->tahun_ajaran ?? '' }}</h3>

                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                        @php
                                            $now = \Carbon\Carbon::now();
                                            $mulai = $semesterAktif->tanggal_mulai ? \Carbon\Carbon::parse($semesterAktif->tanggal_mulai) : null;
                                            $selesai = $semesterAktif->tanggal_selesai ? \Carbon\Carbon::parse($semesterAktif->tanggal_selesai) : null;
                                            $isRunning = $mulai && $selesai && $now->between($mulai, $selesai);
                                        @endphp

                                        @if($isRunning)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Sedang Berjalan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                                <i class="fas fa-clock mr-2"></i>
                                                Tidak Aktif
                                            </span>
                                        @endif

                                        @if($selesai)
                                            <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center mt-1 md:mt-0"><i class="fas fa-clock text-gray-400 dark:text-gray-500 mr-2"></i>Berakhir {{ $selesai->format('d M Y') }}</div>
                                        @endif
                                    </div>

                                    <hr class="my-4 border-t border-gray-100 dark:border-gray-700">
                                        <div class="mt-3 flex flex-col md:flex-row items-stretch md:items-center gap-3">
                                        <a href="{{ route('admin.semester.manage') }}" class="px-6 py-3 bg-maroon text-white rounded-full hover:bg-red-900 transition flex items-center justify-center shadow-sm"><i class="fas fa-plus mr-2"></i>Set Semester Baru</a>
                                        <a href="{{ route('admin.kalender.index') }}" class="px-5 py-3 border border-gray-300 dark:border-gray-600 rounded-full text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition flex items-center justify-center">Lihat Kalender Akademik</a>
                                    </div>
                                @else
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mt-2">Belum ada semester aktif</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Silakan atur semester aktif pada halaman Semester & Tahun Ajaran.</p>
                                    <div class="mt-4">
                                        <a href="{{ route('admin.semester.manage') }}" class="w-full md:w-auto px-4 py-3 bg-maroon text-white rounded-lg hover:bg-red-900 transition flex items-center justify-center"><i class="fas fa-cog mr-2"></i>Atur Semester</a>
                                    </div>
                                @endif
                            </div>

                            <div class="hidden md:flex w-44 pr-6 items-center justify-end relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-4 right-4 opacity-10" width="140" height="140" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: rgba(0,0,0,0.04);">
                                    <rect x="3" y="2" width="18" height="18" rx="2" />
                                    <path d="M16 2v4" />
                                    <path d="M8 2v4" />
                                    <path d="M3 10h18" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Pengaturan KRS -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">Pengaturan KRS</h3>

                        @if(isset($semesterAktif))
                        <form action="{{ route('admin.semester.update-krs-settings', $semesterAktif->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Status Pengisian KRS</span>
                                   <div class="flex items-center gap-3">
                                        <input type="checkbox"
                                            name="krs_dapat_diisi"
                                            value="1"
                                            {{ $semesterAktif->krs_dapat_diisi ? 'checked' : '' }}
                                            class="sr-only peer"
                                            id="krsToggleAdmin">
                                        <label for="krsToggleAdmin"
                                            class="relative w-11 h-6 flex-shrink-0 bg-gray-200 dark:bg-gray-600 
                                                rounded-full peer 
                                                peer-checked:bg-green-600 
                                                cursor-pointer
                                                after:content-[''] 
                                                after:absolute 
                                                after:top-[2px] 
                                                after:left-[2px] 
                                                after:bg-white 
                                                after:border-gray-300 
                                                after:border 
                                                after:rounded-full 
                                                after:h-5 
                                                after:w-5 
                                                after:transition-all
                                                peer-checked:after:translate-x-full
                                                peer-checked:after:border-white">
                                        </label>
                                    </div>
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $semesterAktif->krs_dapat_diisi ? 'Mahasiswa dapat mengisi KRS' : 'Mahasiswa tidak dapat mengisi KRS' }}</p>
                            </div>


                            <div class="mb-4">
                                <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Mulai Periode</label>
                                <input type="date" name="krs_mulai" value="{{ $semesterAktif->krs_mulai?->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm">
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Akhir Periode</label>
                                <input type="date" name="krs_selesai" value="{{ $semesterAktif->krs_selesai?->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm">
                            </div>

                            <button type="submit" class="w-full px-4 py-2 bg-maroon text-white rounded-lg hover:bg-red-900 transition flex items-center justify-center mt-4 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-maroon/30 ring-1 ring-maroon/10">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Pengaturan
                            </button>
                        </form>
                        @else
                        <div class="text-sm text-gray-500">Belum ada semester aktif. Silakan atur semester aktif pada halaman Semester & Tahun Ajaran.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="block md:hidden space-y-4">
                @forelse($krsData as $mahasiswa)
                    @php
                        $totalSks = 0;
                        $krsStatus = 'draft';
                        if (!empty($mahasiswa->krs)) {
                            foreach ($mahasiswa->krs as $k) {
                                $sks = optional(optional($k->kelas)->mataKuliah)->sks ?? optional($k->mataKuliah)->sks ?? 0;
                                $totalSks += (int) $sks;
                                // If any KRS is not draft, consider it as submitted
                                if ($k->status !== 'draft') {
                                    $krsStatus = $k->status;
                                }
                            }
                        }

                        $currentSemesterInfo = $mahasiswa->getCurrentSemesterInfo();
                        $displayStatus = ($krsStatus === 'draft') ? 'Draft' : 'Sudah Isi';
                    @endphp
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-maroon to-red-700 flex items-center justify-center text-white font-bold mr-3 text-sm">
                                    {{ strtoupper(substr(optional($mahasiswa->user)->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100 line-clamp-1">{{ optional($mahasiswa->user)->name ?? 'Nama tidak tersedia' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        NIM: {{ $mahasiswa->nim }}
                                    </div>
                                </div>
                            </div>
                            <div>
                                 @if($displayStatus === 'Draft')
                                    <span class="px-2 py-1 inline-flex items-center text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                        Draft
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex items-center text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                        Sudah Isi
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm mb-4 border-t border-b border-gray-100 dark:border-gray-700 py-3">
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase">Prodi</span>
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $mahasiswa->prodi ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase">Semester</span>
                                <span class="font-medium text-gray-800 dark:text-gray-200">Semester {{ $currentSemesterInfo->semester_number ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase">Total SKS</span>
                                <span class="font-medium text-purple-700 dark:text-purple-400 font-bold">{{ $totalSks ? $totalSks . ' SKS' : '-' }}</span>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 justify-end">
                             <button type="button" onclick="document.getElementById('modal-krs-{{ $mahasiswa->id }}').classList.remove('hidden')" class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/30 px-3 py-1.5 rounded-lg text-sm font-medium transition" title="Detail">
                                <i class="fas fa-eye mr-1"></i> Detail
                            </button>

                            <form action="{{ route('admin.krs.reopen', $mahasiswa) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                    class="bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-100 dark:hover:bg-yellow-900/30 px-3 py-1.5 rounded-lg text-sm font-medium transition" 
                                    onclick="event.preventDefault(); showConfirm('Buka kembali pengisian KRS untuk mahasiswa ini? Mahasiswa akan dapat mengedit KRS.', () => this.closest('form').submit(), null, 'Konfirmasi Edit KRS')"
                                    title="Buka Kembali">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                            </form>

                            @php
                                $firstKrs = $mahasiswa->krs->first();
                            @endphp
                            
                            <button type="button" onclick="confirmDeleteKrs({{ $mahasiswa->id }}, true)" 
                                class="bg-maroon text-white hover:bg-red-900 px-3 py-1.5 rounded-lg text-sm font-medium transition">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                            <form id="delete-krs-form-mobile-{{ $mahasiswa->id }}" action="#" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-8 text-center border border-gray-200 dark:border-gray-700">
                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                            <i class="fas fa-inbox text-3xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Belum ada data KRS</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Data KRS akan tampil ketika mahasiswa melakukan pengisian KRS</p>
                    </div>
                @endforelse
            </div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden hidden md:block">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-maroon text-white">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-hashtag mr-2"></i>No
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-user-graduate mr-2"></i>Mahasiswa
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-building mr-2"></i>Prodi
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-calendar-alt mr-2"></i>Semester
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-calculator mr-2"></i>SKS
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-info-circle mr-2"></i>Status
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-cog mr-2"></i>Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @php
                    $rowNo = ($krsData->currentPage() - 1) * $krsData->perPage();
                @endphp
                @forelse($krsData as $mahasiswa)
                    @php
                        // calculate total SKS for this mahasiswa
                        $totalSks = 0;
                        $krsStatus = 'draft';
                        if (!empty($mahasiswa->krs)) {
                            foreach ($mahasiswa->krs as $k) {
                                $sks = optional(optional($k->kelas)->mataKuliah)->sks ?? optional($k->mataKuliah)->sks ?? 0;
                                $totalSks += (int) $sks;
                                // If any KRS is not draft, consider it as submitted
                                if ($k->status !== 'draft') {
                                    $krsStatus = $k->status;
                                }
                            }
                        }
                        
                        // Get current semester info
                        $currentSemesterInfo = $mahasiswa->getCurrentSemesterInfo();
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                            {{ ++$rowNo }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-maroon to-red-700 flex items-center justify-center text-white font-bold mr-3">
                                    {{ strtoupper(substr(optional($mahasiswa->user)->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ optional($mahasiswa->user)->name ?? 'Nama tidak tersedia' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-id-card text-gray-400 dark:text-gray-500 mr-1"></i>
                                        NIM: {{ $mahasiswa->nim }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                            {{ $mahasiswa->prodi ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                <i class="fas fa-calendar text-gray-400 dark:text-gray-500 mr-1"></i>
                                Semester {{ $currentSemesterInfo->semester_number ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 inline-flex items-center justify-center mx-auto text-sm font-bold rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300">
                                <i class="fas fa-calculator mr-1"></i>
                                {{ $totalSks ? $totalSks . ' SKS' : '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                                $displayStatus = ($krsStatus === 'draft') ? 'Draft' : 'Sudah Isi';
                            @endphp
                            @if($displayStatus === 'Draft')
                                <span class="px-3 py-1 inline-flex items-center justify-center mx-auto text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                    <i class="fas fa-edit mr-1"></i>
                                    Draft
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex items-center justify-center mx-auto text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Sudah Isi
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <button type="button" onclick="document.getElementById('modal-krs-{{ $mahasiswa->id }}').classList.remove('hidden')" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition p-2 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <form action="{{ route('admin.krs.reopen', $mahasiswa) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                        class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300 transition p-2 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded" 
                                        onclick="event.preventDefault(); showConfirm('Buka kembali pengisian KRS untuk mahasiswa ini? Mahasiswa akan dapat mengedit KRS.', () => this.closest('form').submit(), null, 'Konfirmasi Edit KRS')"
                                        title="Buka Kembali (Reopen)">
                                        <i class="fas fa-edit"></i>
                                        </button>
                                    </form>
                                @php
                                    // Get the first KRS record for this mahasiswa to use for actions
                                    $firstKrs = $mahasiswa->krs->first();
                                @endphp
                                @php
                                    $firstKrs = $mahasiswa->krs->first();
                                @endphp
                                
                                <button type="button" onclick="confirmDeleteKrs({{ $mahasiswa->id }})" 
                                    class="bg-maroon text-white p-1.5 rounded hover:bg-maroon-700 transition" 
                                    title="Hapus">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                                <form id="delete-krs-form-{{ $mahasiswa->id }}" action="#" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>


                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-inbox text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                <p class="text-lg font-medium">Belum ada data KRS</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Data KRS akan tampil ketika mahasiswa melakukan pengisian KRS</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($krsData->hasPages())
        <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $krsData->links() }}
        </div>
    @endif
</div>

<!-- Modals outside responsive containers -->
@foreach($krsData as $mahasiswa)
    <div id="modal-krs-{{ $mahasiswa->id }}" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl w-11/12 md:w-3/4 lg:w-2/3 max-h-[90vh] overflow-y-auto overflow-x-hidden">
            <div class="flex items-center justify-between px-6 py-4 bg-maroon text-white">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-full bg-white bg-opacity-10 flex items-center justify-center text-white font-bold">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">Detail KRS</h3>
                        <p class="text-sm text-white text-opacity-90">Informasi KRS mahasiswa</p>
                    </div>
                </div>
                <button onclick="document.getElementById('modal-krs-{{ $mahasiswa->id }}').classList.add('hidden')" class="text-white text-xl leading-none">&times;</button>
            </div>
            <div class="p-6 text-sm text-gray-700 dark:text-gray-300">
                <div class="grid grid-cols-1 gap-2 mb-4">
                    <div><strong class="text-gray-900 dark:text-gray-100">Mahasiswa:</strong> {{ optional($mahasiswa->user)->name ?? 'Nama tidak tersedia' }} (NIM: {{ $mahasiswa->nim ?? '-' }})</div>
                    @php
                        $currentSemesterInfo = $mahasiswa->getCurrentSemesterInfo();
                    @endphp
                    <div><strong class="text-gray-900 dark:text-gray-100">Semester:</strong> Semester {{ $currentSemesterInfo->semester_number ?? '-' }}</div>
                    <div><strong class="text-gray-900 dark:text-gray-100">Total SKS:</strong> {{ $mahasiswa->krs->sum(function($k) { return optional(optional($k->kelas)->mataKuliah)->sks ?? optional(optional($k->kelasMataKuliah)->mataKuliah)->sks ?? optional($k->mataKuliah)->sks ?? 0; }) }} SKS</div>
                </div>
                <div class="border-t dark:border-gray-700 pt-4">
                    <strong class="block mb-2 text-gray-900 dark:text-gray-100">Daftar Mata Kuliah:</strong>
                    @if($mahasiswa->krs->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Mata Kuliah</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">SKS</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($mahasiswa->krs as $krsItem)
                                    <tr>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                            {{ optional(optional($krsItem->kelas)->mataKuliah)->nama_mk ?? optional(optional($krsItem->kelasMataKuliah)->mataKuliah)->nama_mk ?? optional($krsItem->mataKuliah)->nama_mk ?? '-' }}
                                        </td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-900 dark:text-gray-100">
                                            {{ optional(optional($krsItem->kelas)->mataKuliah)->sks ?? optional(optional($krsItem->kelasMataKuliah)->mataKuliah)->sks ?? optional($krsItem->mataKuliah)->sks ?? '-' }}
                                        </td>
                                        <td class="px-3 py-2 text-sm text-center">
                                            @if($krsItem->status === 'draft')
                                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">Draft</span>
                                            @else
                                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">Sudah Isi</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500 text-sm">Belum ada mata kuliah yang diambil</p>
                    @endif
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 flex justify-end">
                 <button onclick="document.getElementById('modal-krs-{{ $mahasiswa->id }}').classList.add('hidden')" class="px-4 py-2 border dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">Tutup</button>
            </div>
        </div>
    </div>
@endforeach

@push('scripts')
<script>
function confirmDeleteKrs(krsId, isMobile = false) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data KRS ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#7a1621',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const formId = isMobile ? 'delete-krs-form-mobile-' + krsId : 'delete-krs-form-' + krsId;
            const form = document.getElementById(formId);
            if (form) {
                form.submit();
            } else {
                console.error('Delete form not found for ID: ' + formId);
            }
        }
    });
}
</script>
@endpush
@endsection
