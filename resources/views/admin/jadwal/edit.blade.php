@extends('layouts.admin')
@section('title', 'Edit Jadwal')
@section('page-title', 'Edit Jadwal')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-maroon text-white rounded-t-xl">
            <h3 class="text-xl font-bold flex items-center"><i class="fas fa-edit mr-3 text-2xl"></i>Edit Jadwal</h3>
        </div>
        <form action="{{ route('admin.jadwal.update', $jadwal) }}" method="POST" class="p-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-book text-gray-400 mr-1"></i>Mata Kuliah *</label>
                    <select name="mata_kuliah_id" x-model="mataKuliahId" @change="fetchDosens()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition" required>
                        <option value="">Pilih Mata Kuliah</option>
                        @foreach($mataKuliahs as $mk)
                        <option value="{{ $mk->id }}" {{ old('mata_kuliah_id', $kelasMataKuliah->mata_kuliah_id) == $mk->id ? 'selected' : '' }}>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-users text-gray-400 mr-1"></i>Nama Kelas *</label>
                    <input type="text" name="nama_kelas" value="{{ old('nama_kelas', $kelasMataKuliah->kode_kelas) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition" placeholder="A, B, C..." required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-user-tie text-gray-400 mr-1"></i>Dosen *</label>
                    
                    {{-- Loading state --}}
                    <div x-show="loading" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-500">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Memuat dosen...
                    </div>
                    
                    {{-- No mata kuliah selected --}}
                    <div x-show="!loading && !mataKuliahId" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i> Pilih mata kuliah terlebih dahulu
                    </div>
                    
                    {{-- No dosen available --}}
                    <div x-show="!loading && mataKuliahId && dosens.length === 0" class="w-full px-4 py-3 border border-red-300 rounded-lg bg-red-50 text-red-500">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Tidak ada dosen yang mengajar mata kuliah ini
                    </div>
                    
                    {{-- Single dosen (auto-select, readonly) --}}
                    <template x-if="!loading && dosens.length === 1">
                        <div>
                            <input type="hidden" name="dosen_id" :value="selectedDosenId">
                            <div class="w-full px-4 py-3 border border-green-300 rounded-lg bg-green-50 text-green-700 flex items-center justify-between">
                                <span><i class="fas fa-check-circle mr-1"></i> <span x-text="dosens[0].name"></span></span>
                                <i class="fas fa-lock text-green-400 text-xs"></i>
                            </div>
                        </div>
                    </template>
                    
                    {{-- Multiple dosens (dropdown) --}}
                    <select x-show="!loading && dosens.length > 1" name="dosen_id" x-model="selectedDosenId" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition" :required="dosens.length > 1">
                        <option value="">Pilih Dosen</option>
                        <template x-for="dosen in dosens" :key="dosen.id">
                            <option :value="dosen.id" x-text="dosen.name" :selected="dosen.id == selectedDosenId"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-user-friends text-gray-400 mr-1"></i>Kuota *</label>
                    <input type="number" name="kuota" value="{{ old('kuota', $kelasMataKuliah->kapasitas) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition" placeholder="40" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-door-open text-gray-400 mr-1"></i>Ruangan <span class="text-red-500">*</span></label>
                    <select name="ruangan_id" x-model="ruangan" @change="checkRoom()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition" required>
                        <option value="">Pilih Ruangan</option>
                        @foreach($daftarRuangan as $ruangan)
                        <option value="{{ $ruangan->id }}" {{ old('ruangan_id', $kelasMataKuliah->ruangan_id) == $ruangan->id ? 'selected' : '' }}>
                            {{ $ruangan->kode_ruangan }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-calendar-day text-gray-400 mr-1"></i>Hari <span class="text-red-500">*</span></label>
                    <select name="hari" x-model="hari" @change="checkRoom()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition" required>
                        <option value="">Pilih Hari</option>
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                        <option value="{{ $h }}" {{ old('hari', $kelasMataKuliah->hari) == $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-clock text-gray-400 mr-1"></i>Jam Mulai <span class="text-red-500">*</span></label>
                    <input type="time" name="jam_mulai" x-model="jamMulai" @change="checkRoom()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-clock text-gray-400 mr-1"></i>Jam Selesai <span class="text-red-500">*</span></label>
                    <input type="time" name="jam_selesai" x-model="jamSelesai" @change="checkRoom()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition" required>
                </div>
                
                <!-- Room Status Message -->
                <div class="md:col-span-2" x-show="!roomStatus.available">
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg flex items-start gap-2">
                        <i class="fas fa-exclamation-circle mt-0.5"></i>
                        <span x-text="roomStatus.message"></span>
                    </div>
                </div>
                
                <!-- Checking Room Indicator -->
                <div class="md:col-span-2" x-show="checkingRoom">
                    <div class="bg-blue-50 border border-blue-200 text-blue-600 px-4 py-3 rounded-lg flex items-center gap-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Memeriksa ketersediaan ruangan...</span>
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t">
                <a href="{{ route('admin.jadwal.index') }}" class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center"><i class="fas fa-times mr-2"></i>Batal</a>
                <button type="submit" 
                        :disabled="!roomStatus.available || checkingRoom || loading || (dosens.length === 0 && mataKuliahId)"
                        :class="{'opacity-50 cursor-not-allowed': !roomStatus.available || checkingRoom || loading || (dosens.length === 0 && mataKuliahId)}"
                        class="bg-maroon text-white px-6 py-3 rounded-lg hover:opacity-95 transition flex items-center shadow-md transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
