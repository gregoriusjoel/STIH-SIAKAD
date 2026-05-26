@extends('layouts.admin')
@section('title', 'Kelas Perkuliahan')
@section('page-title', 'Kelas Perkuliahan')
@section('content')

    <div x-data="{ showGenerateModal: false }">
        {{-- Header --}}
        <div class="mb-6 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-layer-group text-maroon mr-2"></i>Master Data Kelas Perkuliahan
                </h3>
                <p class="text-sm text-gray-600 mt-1">Kelola data kelas berdasarkan angkatan dan program studi. Format: <code
                        class="px-1.5 py-0.5 bg-gray-100 rounded text-maroon font-semibold">[2 DIGIT ANGKATAN][Kode Prodi][Kode Kelas]</code>
                </p>
            </div>
            <div class="flex flex-wrap gap-2">

                <button @click="showGenerateModal = true"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition flex items-center gap-2">
                    <i class="fas fa-magic"></i> Generate Massal
                </button>
                <a href="{{ route('admin.kelas-perkuliahan.create') }}"
                    class="px-4 py-2 bg-maroon text-white rounded-lg text-sm font-medium hover:bg-red-800 transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Tambah Manual
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('admin.kelas-perkuliahan.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama kelas, kode prodi..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                </div>
                <div class="min-w-[160px]">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Prodi</label>
                    <select name="prodi_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                        <option value="">Semua Prodi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-[160px]">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Angkatan</label>
                    <select name="angkatan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                        <option value="">Semua</option>
                        @php
                            $currentYear = (int) date('Y');
                            $startYear = 2000;
                        @endphp
                        @for($year = $currentYear; $year >= $startYear; $year--)
                            <option value="{{ $year }}" {{ request('angkatan') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="min-w-[200px]">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tahun Akademik</label>
                    <select name="tahun_akademik_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                        <option value="">Semua</option>
                        @foreach($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ request('tahun_akademik_id') == $sem->id ? 'selected' : '' }}>
                                {{ $sem->nama_semester }} {{ $sem->tahun_ajaran }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-maroon text-white rounded-lg text-sm font-medium hover:bg-red-800 transition">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.kelas-perkuliahan.index') }}"
                        class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow-lg  overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-maroon text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nama Kelas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Angkatan</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Prodi</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Kode Kelas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Tahun Akademik</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($kelasPerkuliahans as $kp)
                            <tr class="hover:bg-blue-50 transition duration-200">
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-blue-100 text-blue-800">
                                        <i class="fas fa-layer-group mr-1.5"></i>{{ $kp->nama_kelas }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
                                        {{ $kp->angkatan }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $kp->prodi?->nama_prodi ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $kp->kode_prodi }}</div>
                                </td>
                                <td class="px-6 py-4 font-mono font-semibold text-gray-700">{{ $kp->kode_kelas }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if($kp->tahunAkademik)
                                        {{ $kp->tahunAkademik->nama_semester }} {{ $kp->tahunAkademik->tahun_ajaran }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.kelas-perkuliahan.show', $kp) }}"
                                            class="action-btn w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center hover:bg-blue-100 dark:hover:bg-blue-900/40"
                                            title="Detail">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('admin.kelas-perkuliahan.edit', $kp) }}"
                                            class="action-btn w-8 h-8 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 flex items-center justify-center hover:bg-yellow-100 dark:hover:bg-yellow-900/40"
                                            title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.kelas-perkuliahan.destroy', $kp) }}"
                                            class="inline"
                                            onsubmit="return confirm('Yakin hapus kelas {{ $kp->nama_kelas }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="action-btn w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 flex items-center justify-center hover:bg-red-100 dark:hover:bg-red-900/40"
                                                title="Hapus">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-3"></i>
                                    <p class="text-lg font-semibold">Belum ada data kelas perkuliahan</p>
                                    <p class="text-sm mt-2">Gunakan tombol "Tambah Manual" atau "Generate Massal" untuk
                                        menambahkan data.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($kelasPerkuliahans->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">{{ $kelasPerkuliahans->links() }}</div>
            @endif
        </div>

        {{-- Generate Modal --}}
        <div x-show="showGenerateModal" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-2"
            style="display: none;">
            <div @click.away="showGenerateModal = false"
                class="bg-gray-50 rounded-3xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.25)] w-full max-w-4xl max-h-[95vh] overflow-y-auto flex flex-col transform transition-all">
                <!-- Header -->
                <div class="px-6 py-5 border-b border-maroon/20 sticky top-0 z-10 bg-maroon text-white flex items-center justify-between shadow-sm">
                    <div>
                        <h3 class="text-xl font-black text-white flex items-center">
                            <div class="w-10 h-10 rounded-full bg-white/20 text-white flex items-center justify-center mr-3 shadow-sm border border-white/10">
                                <i class="fas fa-magic"></i>
                            </div>
                            Generate Kelas Massal
                        </h3>
                        <p class="text-sm font-medium text-red-100 mt-1 ml-13">Otomatisasi pembuatan kelas perkuliahan berdasarkan program studi</p>
                    </div>
                    <button @click="showGenerateModal = false" type="button" class="w-10 h-10 flex items-center justify-center text-red-200 hover:text-white hover:bg-white/10 rounded-full transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.kelas-perkuliahan.generate-bulk') }}"
                    class="flex-1 overflow-y-auto">
                    @csrf
                    <div class="p-6 space-y-8">
                        {{-- Validation Errors Display --}}
                        @if ($errors->any())
                            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                                    </div>
                                    <div class="ml-3">
                                        @foreach ($errors->all() as $error)
                                            <p class="text-sm font-medium text-red-700">• {{ $error }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Basic Info Section -->
                        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
                            <h4 class="text-xs font-black text-gray-400 mb-4 uppercase tracking-wider flex items-center gap-2">
                                <i class="fas fa-info-circle"></i> Informasi Dasar
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Program Studi <span class="text-red-500">*</span></label>
                                    <select name="prodi_id" required
                                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-4 focus:ring-maroon/10 focus:border-maroon transition-all shadow-sm @error('prodi_id') border-red-500 @enderror"
                                        value="{{ old('prodi_id') }}">
                                        <option value="">Pilih Prodi</option>
                                        @foreach($prodis as $prodi)
                                            <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                                        @endforeach
                                    </select>
                                    @error('prodi_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div x-data="angkatanMultiSelect()" class="relative">
                                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Angkatan <span class="text-red-500">*</span></label>
                                    
                                    <!-- Hidden Select for Form Submission -->
                                    <select name="angkatan[]" multiple required x-model="selected" class="hidden">
                                        @php
                                            $currentYear = (int) date('Y');
                                            $startYear = 2000;
                                            $selectedAngkatans = is_array(old('angkatan')) ? old('angkatan') : [];
                                        @endphp
                                        @for($year = $currentYear; $year >= $startYear; $year--)
                                            <option value="{{ $year }}" {{ in_array($year, $selectedAngkatans) || (empty($selectedAngkatans) && $year == $currentYear) ? 'selected' : '' }}>{{ $year }}</option>
                                        @endfor
                                    </select>

                                    <!-- Custom Dropdown UI -->
                                    <div class="relative">
                                        <!-- Display Button -->
                                        <button type="button" @click="open = !open" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-4 focus:ring-maroon/10 focus:border-maroon transition-all shadow-sm text-left flex items-center justify-between @error('angkatan') border-red-500 @enderror" :class="{ 'border-red-500 ring-4 ring-red-500/10': error && !selected.length }">
                                            <div class="flex flex-wrap gap-1.5 flex-1">
                                                <template x-if="selected.length === 0">
                                                    <span class="text-gray-400">Pilih Angkatan</span>
                                                </template>
                                                <template x-if="selected.length > 0">
                                                    <template x-for="(year, idx) in selected.slice(0, 2)" :key="idx">
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-maroon/10 text-maroon text-xs font-bold border border-maroon/20">
                                                            <span x-text="year"></span>
                                                            <button type="button" @click.stop="removeYear(year)" class="hover:text-maroon/70">
                                                                <i class="fas fa-times text-xs"></i>
                                                            </button>
                                                        </span>
                                                    </template>
                                                </template>
                                                <template x-if="selected.length > 2">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200">
                                                        <span x-text="`+${selected.length - 2} lagi`"></span>
                                                    </span>
                                                </template>
                                            </div>
                                            <div class="flex-shrink-0 text-gray-400 transition-transform" :class="{ 'rotate-180': open }">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </div>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div x-show="open" @click.away="open = false" x-transition class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-xl shadow-lg z-50 max-h-64 overflow-y-auto">
                                            <div class="p-2 border-b border-gray-100 sticky top-0 bg-white">
                                                <input type="text" x-model="search" placeholder="Cari tahun..." class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:ring-2 focus:ring-maroon/10 focus:border-maroon outline-none">
                                            </div>
                                            
                                            <div class="divide-y divide-gray-100 max-h-56 overflow-y-auto">
                                                <template x-for="year in filteredYears" :key="year">
                                                    <label class="flex items-center gap-3 px-4 py-2.5 hover:bg-blue-50 cursor-pointer transition-colors group">
                                                        <input type="checkbox" :value="String(year)" :checked="selected.includes(String(year))" @change="toggleYear(String(year))" class="w-4 h-4 text-maroon rounded border-gray-300 focus:ring-maroon cursor-pointer">
                                                        <span class="flex-1 text-sm font-medium text-gray-700 group-hover:text-gray-900" x-text="year"></span>
                                                        <span x-show="selected.includes(String(year))" class="text-maroon text-sm">
                                                            <i class="fas fa-check"></i>
                                                        </span>
                                                    </label>
                                                </template>
                                            </div>

                                            <!-- Select All / Clear All -->
                                            <div class="p-2 border-t border-gray-100 sticky bottom-0 bg-gray-50 flex gap-2">
                                                <button type="button" @click.stop="selectAll()" class="flex-1 px-2 py-1.5 text-xs font-bold text-maroon bg-maroon/10 hover:bg-maroon/20 rounded-lg transition-colors">
                                                    Pilih Semua
                                                </button>
                                                <button type="button" @click.stop="clearAll()" class="flex-1 px-2 py-1.5 text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                                    Bersihkan
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    @error('angkatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Tahun Akademik</label>
                                    <select name="tahun_akademik_id"
                                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-4 focus:ring-maroon/10 focus:border-maroon transition-all shadow-sm @error('tahun_akademik_id') border-red-500 @enderror">
                                        <option value="">Tidak terikat</option>
                                        @foreach($semesters as $sem)
                                            <option value="{{ $sem->id }}" {{ old('tahun_akademik_id') == $sem->id ? 'selected' : '' }}>{{ $sem->nama_semester }} {{ $sem->tahun_ajaran }}</option>
                                        @endforeach
                                    </select>
                                    @error('tahun_akademik_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Generation Mode Section -->
                        <div x-data="{ mode: 'manual', maxPerKelas: 40, jumlahMahasiswa: 0 }" x-effect="triggerCalculationOnModeChange(mode)" class="space-y-5">
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-wider flex items-center gap-2">
                                <i class="fas fa-cogs"></i> Mode Generate
                            </h4>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label
                                    class="relative flex flex-col bg-white p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5"
                                    :class="mode === 'manual' ? 'border-maroon ring-1 ring-maroon shadow-md' : 'border-gray-100 hover:border-gray-200 shadow-sm'">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-3">
                                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors"
                                                :class="mode === 'manual' ? 'border-maroon' : 'border-gray-300'">
                                                <div class="w-2.5 h-2.5 rounded-full bg-maroon transition-transform scale-0"
                                                    :class="mode === 'manual' ? 'scale-100' : ''"></div>
                                            </div>
                                            <span class="font-black text-gray-900">Manual</span>
                                        </div>
                                        <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center" :class="mode === 'manual' ? 'bg-maroon/10 text-maroon' : 'text-gray-400'">
                                            <i class="fas fa-hand-pointer"></i>
                                        </div>
                                    </div>
                                    <p class="text-xs font-medium text-gray-500 ml-8 leading-relaxed">Jumlah kelas ditentukan sama untuk semua tingkat secara pukul rata.</p>
                                    <input type="radio" name="mode" value="manual" x-model="mode" class="hidden">
                                </label>
                                
                                <label
                                    class="relative flex flex-col bg-white p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5"
                                    :class="mode === 'auto' ? 'border-maroon ring-1 ring-maroon shadow-md' : 'border-gray-100 hover:border-gray-200 shadow-sm'">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-3">
                                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors"
                                                :class="mode === 'auto' ? 'border-maroon' : 'border-gray-300'">
                                                <div class="w-2.5 h-2.5 rounded-full bg-maroon transition-transform scale-0"
                                                    :class="mode === 'auto' ? 'scale-100' : ''"></div>
                                            </div>
                                            <span class="font-black text-gray-900">Otomatis</span>
                                        </div>
                                        <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center" :class="mode === 'auto' ? 'bg-maroon/10 text-maroon' : 'text-gray-400'">
                                            <i class="fas fa-robot"></i>
                                        </div>
                                    </div>
                                    <p class="text-xs font-medium text-gray-500 ml-8 leading-relaxed">Hitung kelas otomatis per tingkat berdasarkan kuota mahasiswa.</p>
                                    <input type="radio" name="mode" value="auto" x-model="mode" class="hidden">
                                </label>
                            </div>

                            <!-- Manual Mode -->
                            <div x-show="mode === 'manual'" x-collapse class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
                                <div class="max-w-xs">
                                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Kelas per Angkatan <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="number" name="kelas_per_angkatan" value="{{ old('kelas_per_angkatan', 1) }}" min="1" max="20"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-maroon/10 focus:border-maroon focus:bg-white transition-all shadow-inner [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none @error('kelas_per_angkatan') border-red-500 @enderror">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Kelas</span>
                                        </div>
                                    </div>
                                    @error('kelas_per_angkatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- Auto Mode -->
                            <div x-show="mode === 'auto'" x-collapse class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm space-y-6">
                                <div class="flex flex-wrap items-end gap-4">
                                    <div class="w-full max-w-[200px]">
                                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Jumlah Mahasiswa <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="number" name="jumlah_mahasiswa" x-model="jumlahMahasiswa" min="0" max="10000" value="{{ old('jumlah_mahasiswa', 0) }}"
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-maroon/10 focus:border-maroon focus:bg-white transition-all shadow-inner text-blue-700 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none @error('jumlah_mahasiswa') border-red-500 @enderror">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                                <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">MHS</span>
                                            </div>
                                        </div>
                                        @error('jumlah_mahasiswa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="w-full max-w-[200px]">
                                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Maks Siswa/Kelas <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="number" name="max_students_per_class" x-model="maxPerKelas" min="1" max="100" value="{{ old('max_students_per_class', 40) }}"
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-maroon/10 focus:border-maroon focus:bg-white transition-all shadow-inner text-blue-700 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none @error('max_students_per_class') border-red-500 @enderror">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                                <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Siswa</span>
                                            </div>
                                        </div>
                                        @error('max_students_per_class') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="pb-3 text-xs font-medium text-gray-500 flex items-center bg-blue-50/50 px-3 py-1.5 rounded-lg border border-blue-100">
                                        <i class="fas fa-info-circle mr-2 text-blue-500"></i> Pembagi dasar perhitungan jumlah kelas
                                    </div>
                                </div>
                            </div>

                            <!-- Overwrite Strategy -->
                            <div x-data="{ overwrite: false }" class="mt-8">
                                <h4 class="text-xs font-black text-gray-400 uppercase tracking-wider flex items-center gap-2 mb-4">
                                    <i class="fas fa-shield-alt"></i> Strategi Eksekusi
                                </h4>
                                
                                <input type="hidden" name="overwrite" value="0">
                                
                                <div class="bg-white rounded-2xl border-2 border-gray-100 p-5 shadow-sm hover:border-gray-200 hover:shadow-md transition-all flex flex-col sm:flex-row sm:items-center gap-5 cursor-pointer group" @click="overwrite = !overwrite">
                                    <!-- Toggle Switch -->
                                    <button type="button" 
                                        class="relative inline-flex h-7 w-14 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300 ease-in-out focus:outline-none focus:ring-4 focus:ring-offset-0"
                                        :class="overwrite ? 'bg-red-500 focus:ring-red-500/20' : 'bg-blue-500 focus:ring-blue-500/20'"
                                        role="switch" aria-checked="false">
                                        <span aria-hidden="true" 
                                            class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-md ring-0 transition duration-300 ease-in-out"
                                            :class="overwrite ? 'translate-x-7' : 'translate-x-0'"></span>
                                    </button>
                                    <input type="checkbox" name="overwrite" value="1" x-model="overwrite" class="hidden">
                                    
                                    <div class="flex-1">
                                        <div class="font-black text-gray-900 text-base" x-text="overwrite ? 'Timpa Data yang Sudah Ada' : 'Hanya Tambah Data Baru'"></div>
                                        <div class="text-sm mt-1 font-medium" :class="overwrite ? 'text-red-600' : 'text-blue-600'">
                                            <span x-show="!overwrite"><i class="fas fa-check-circle mr-1"></i> Data kelas lama <strong>aman</strong>, sistem hanya menambah yang belum ada.</span>
                                            <span x-show="overwrite" style="display: none;"><i class="fas fa-exclamation-triangle mr-1"></i> <strong>Awas!</strong> Data lama akan dihapus dan diganti baru.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="summaryBox" class="mt-8 bg-gradient-to-br from-maroon to-red-900 rounded-2xl p-5 text-white shadow-xl relative overflow-hidden" style="display: none;">
                                <div class="absolute right-0 top-0 w-32 h-32 bg-white opacity-5 rounded-full blur-2xl -mr-10 -mt-10 pointer-events-none"></div>
                                <div class="flex items-start gap-4 relative z-10">
                                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0 mt-0.5 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                        <i class="fas fa-calculator text-xl text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-[11px] text-red-200 uppercase tracking-wider mb-2">Estimasi Generate</h5>
                                        <div id="calculationSummary" class="text-sm font-medium leading-relaxed text-white"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer / Actions -->
                    <div class="px-6 py-5 bg-white border-t border-gray-200 flex items-center justify-end gap-3 sticky bottom-0 rounded-b-3xl">
                        <button type="button" @click="showGenerateModal = false"
                            class="px-6 py-2.5 bg-white border-2 border-gray-200 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-8 py-2.5 bg-gradient-to-r from-maroon to-red-800 text-white rounded-xl text-sm font-bold hover:from-red-800 hover:to-maroon transition-all shadow-lg shadow-maroon/20 flex items-center gap-2 group">
                            <i class="fas fa-magic group-hover:rotate-12 transition-transform"></i> Mulai Generate
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Angkatan Multi-Select Component
            function angkatanMultiSelect() {
                return {
                    open: false,
                    search: '',
                    selected: @json(is_array(old('angkatan')) ? old('angkatan') : [date('Y')]),
                    allYears: [
                        @php
                            $currentYear = (int) date('Y');
                            $startYear = 2000;
                            $years = [];
                            for ($year = $currentYear; $year >= $startYear; $year--) {
                                $years[] = $year;
                            }
                            echo implode(',', $years);
                        @endphp
                    ],
                    error: false,

                    get filteredYears() {
                        if (!this.search) return this.allYears;
                        return this.allYears.filter(year => String(year).includes(this.search));
                    },

                    toggleYear(year) {
                        const index = this.selected.indexOf(String(year));
                        if (index > -1) {
                            this.selected.splice(index, 1);
                        } else {
                            this.selected.push(String(year));
                        }
                        this.updateHiddenSelect();
                        this.validateSelection();
                    },

                    removeYear(year) {
                        const index = this.selected.indexOf(String(year));
                        if (index > -1) {
                            this.selected.splice(index, 1);
                        }
                        this.updateHiddenSelect();
                        this.validateSelection();
                    },

                    selectAll() {
                        this.selected = this.allYears.map(y => String(y));
                        this.updateHiddenSelect();
                        this.validateSelection();
                    },

                    clearAll() {
                        this.selected = [];
                        this.updateHiddenSelect();
                        this.validateSelection();
                    },

                    updateHiddenSelect() {
                        const hiddenSelect = document.querySelector('select[name="angkatan[]"]');
                        if (hiddenSelect) {
                            Array.from(hiddenSelect.options).forEach(option => {
                                option.selected = this.selected.includes(option.value);
                            });
                            // Trigger change event to update calculation
                            hiddenSelect.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                        // Trigger calculation update
                        this.triggerCalculationUpdate();
                    },

                    triggerCalculationUpdate() {
                        // Trigger the updateCalculation function
                        const form = document.querySelector('form[action*="generate-bulk"]');
                        if (form) {
                            const modeInputs = form.querySelectorAll('input[name="mode"]');
                            const summary = document.getElementById('calculationSummary');
                            const summaryBox = document.getElementById('summaryBox');
                            const hiddenSelect = form.querySelector('select[name="angkatan[]"]');
                            
                            const selectedAngkatans = Array.from(hiddenSelect.selectedOptions).length;
                            const mode = form.querySelector('input[name="mode"]:checked').value;
                            let summaryText = '';

                            if (mode === 'manual') {
                                const kelasPerAngkatan = parseInt(form.querySelector('input[name="kelas_per_angkatan"]')?.value) || 0;
                                
                                if (selectedAngkatans > 0 && kelasPerAngkatan > 0) {
                                    const totalKelas = selectedAngkatans * kelasPerAngkatan;
                                    summaryText = `<strong>Total ${totalKelas} kelas:</strong> ${selectedAngkatans} angkatan × ${kelasPerAngkatan} kelas per angkatan`;
                                }
                            } else if (mode === 'auto') {
                                const jumlahMahasiswa = parseInt(form.querySelector('input[name="jumlah_mahasiswa"]')?.value) || 0;
                                const maxPerKelas = parseInt(form.querySelector('input[name="max_students_per_class"]')?.value) || 40;

                                if (selectedAngkatans > 0 && jumlahMahasiswa > 0) {
                                    const kelasPerAngkatan = Math.ceil(jumlahMahasiswa / maxPerKelas);
                                    const totalKelas = selectedAngkatans * kelasPerAngkatan;
                                    const avgMahasiswaPerKelas = Math.ceil(jumlahMahasiswa / kelasPerAngkatan);
                                    summaryText = `<strong>Total ${totalKelas} kelas:</strong> ${selectedAngkatans} angkatan × ${kelasPerAngkatan} kelas/angkatan (${jumlahMahasiswa} MHS / 40 siswa/kelas)<br><span class="text-red-100 text-xs mt-1 inline-block">Perkiraan ~ ${avgMahasiswaPerKelas} mahasiswa per kelas</span>`;
                                }
                            }

                            summary.innerHTML = summaryText || '-';
                            summaryBox.style.display = summaryText ? 'block' : 'none';
                        }
                    },

                    validateSelection() {
                        this.error = this.selected.length === 0;
                    }
                };
            }
        </script>

        <script>
            // Global helper function for Alpine
            window.triggerCalculationOnModeChange = function(mode) {
                // Trigger the calculation whenever mode changes
                const form = document.querySelector('form[action*="generate-bulk"]');
                if (form) {
                    const event = new Event('change', { bubbles: true });
                    const hiddenSelect = form.querySelector('select[name="angkatan[]"]');
                    if (hiddenSelect) {
                        hiddenSelect.dispatchEvent(event);
                    }
                }
            };

            document.addEventListener('DOMContentLoaded', function () {
                const form = document.querySelector('form[action*="generate-bulk"]');
                if (!form) return;

                const modeInputs = form.querySelectorAll('input[name="mode"]');
                const summary = document.getElementById('calculationSummary');
                const summaryBox = document.getElementById('summaryBox');
                const hiddenSelect = form.querySelector('select[name="angkatan[]"]');

                function getSelectedAngkatanCount() {
                    // Count selected options in the hidden select
                    return Array.from(hiddenSelect.selectedOptions).length;
                }

                function updateCalculation() {
                    const mode = form.querySelector('input[name="mode"]:checked').value;
                    let summaryText = '';
                    const selectedAngkatans = getSelectedAngkatanCount();

                    if (mode === 'manual') {
                        const kelasPerAngkatan = parseInt(form.querySelector('input[name="kelas_per_angkatan"]')?.value) || 0;
                        
                        if (selectedAngkatans > 0 && kelasPerAngkatan > 0) {
                            const totalKelas = selectedAngkatans * kelasPerAngkatan;
                            summaryText = `<strong>Total ${totalKelas} kelas:</strong> ${selectedAngkatans} angkatan × ${kelasPerAngkatan} kelas per angkatan`;
                        }
                    } else if (mode === 'auto') {
                        const jumlahMahasiswa = parseInt(form.querySelector('input[name="jumlah_mahasiswa"]')?.value) || 0;
                        const maxPerKelas = parseInt(form.querySelector('input[name="max_students_per_class"]')?.value) || 40;

                        if (selectedAngkatans > 0 && jumlahMahasiswa > 0) {
                            const kelasPerAngkatan = Math.ceil(jumlahMahasiswa / maxPerKelas);
                            const totalKelas = selectedAngkatans * kelasPerAngkatan;
                            const avgMahasiswaPerKelas = Math.ceil(jumlahMahasiswa / kelasPerAngkatan);
                            summaryText = `<strong>Total ${totalKelas} kelas:</strong> ${selectedAngkatans} angkatan × ${kelasPerAngkatan} kelas/angkatan (${jumlahMahasiswa} MHS / 40 siswa/kelas)<br><span class="text-red-100 text-xs mt-1 inline-block">Perkiraan ~ ${avgMahasiswaPerKelas} mahasiswa per kelas</span>`;
                        }
                    }

                    summary.innerHTML = summaryText || '-';
                    summaryBox.style.display = summaryText ? 'block' : 'none';
                }

                // Event listeners
                form.querySelectorAll('input[name="kelas_per_angkatan"], input[name="jumlah_mahasiswa"], input[name="max_students_per_class"]').forEach(el => {
                    el.addEventListener('change', updateCalculation);
                    el.addEventListener('input', updateCalculation);
                });

                hiddenSelect.addEventListener('change', updateCalculation);

                modeInputs.forEach(input => {
                    input.addEventListener('change', updateCalculation);
                });

                // Initial calculation with slight delay to ensure Alpine has initialized
                setTimeout(updateCalculation, 100);
            });
        </script>


    </div>
@endsection
