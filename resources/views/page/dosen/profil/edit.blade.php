@extends('layouts.app')

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil Saya')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 2xl:px-16 py-8">
    <!-- Error Messages -->
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Edit Form -->
    <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Edit Profil</h1>

        <form action="{{ route('dosen.profil.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Personal Information Section -->
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
                    Informasi Pribadi
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0f1117] text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0f1117] text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            No. Telepon
                        </label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $dosen->phone) }}" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0f1117] text-gray-900 dark:text-white focus:ring-2 focus:ring-[#8B1538] focus:border-transparent"
                            placeholder="+62...">
                    </div>

                    <!-- NIDN -->
                    <div>
                        <label for="nidn" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            NIDN
                        </label>
                        <input type="text" id="nidn" name="nidn" value="{{ old('nidn', $dosen->nidn) }}" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0f1117] text-gray-900 dark:text-white focus:ring-2 focus:ring-[#8B1538] focus:border-transparent"
                            placeholder="Nomor Induk Dosen Nasional">
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Alamat
                        </label>
                        <textarea id="address" name="address" rows="3" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0f1117] text-gray-900 dark:text-white focus:ring-2 focus:ring-[#8B1538] focus:border-transparent"
                            placeholder="Masukkan alamat lengkap">{{ old('address', $dosen->address) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Education Section -->
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
                    Informasi Pendidikan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Pendidikan Terakhir -->
                    <div>
                        <label for="pendidikan_terakhir" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Pendidikan Terakhir
                        </label>
                        <select id="pendidikan_terakhir" name="pendidikan_terakhir" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0f1117] text-gray-900 dark:text-white focus:ring-2 focus:ring-[#8B1538] focus:border-transparent">
                            <option value="">-- Pilih --</option>
                            @php
                                $pendidikanValue = old('pendidikan_terakhir', $dosen->pendidikan_terakhir);
                                if (is_array($pendidikanValue)) {
                                    $pendidikanValue = $pendidikanValue[0] ?? null;
                                }
                            @endphp
                            <option value="S1" {{ $pendidikanValue === 'S1' ? 'selected' : '' }}>S1 (Sarjana)</option>
                            <option value="S2" {{ $pendidikanValue === 'S2' ? 'selected' : '' }}>S2 (Magister)</option>
                            <option value="S3" {{ $pendidikanValue === 'S3' ? 'selected' : '' }}>S3 (Doktor)</option>
                        </select>
                    </div>

                    <!-- Universitas -->
                    <div>
                        <label for="universitas" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Universitas
                        </label>
                        @php
                            $universitasValue = old('universitas', $dosen->universitas);
                            if (is_array($universitasValue)) {
                                $universitasValue = implode(', ', $universitasValue);
                            }
                        @endphp
                        <input type="text" id="universitas" name="universitas" value="{{ $universitasValue }}" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0f1117] text-gray-900 dark:text-white focus:ring-2 focus:ring-[#8B1538] focus:border-transparent"
                            placeholder="Nama universitas">
                    </div>

                    <!-- Program Studi -->
                    <div>
                        <label for="prodi" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Program Studi
                        </label>
                        <select id="prodi" name="prodi" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0f1117] text-gray-900 dark:text-white focus:ring-2 focus:ring-[#8B1538] focus:border-transparent">
                            <option value="">-- Pilih Program Studi --</option>
                            @php
                                $prodiValue = old('prodi', $dosen->prodi);
                                if (is_array($prodiValue)) {
                                    $prodiValue = $prodiValue[0] ?? null;
                                }
                            @endphp
                            @foreach($prodi as $p)
                                <option value="{{ $p->nama_prodi }}" {{ $prodiValue === $p->nama_prodi ? 'selected' : '' }}>
                                    {{ $p->nama_prodi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jabatan Fungsional -->
                    <div>
                        <label for="jabatan_fungsional" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Jabatan Fungsional
                        </label>
                        <select id="jabatan_fungsional" name="jabatan_fungsional" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0f1117] text-gray-900 dark:text-white focus:ring-2 focus:ring-[#8B1538] focus:border-transparent">
                            <option value="">-- Pilih --</option>
                            @php
                                $jabatanValue = old('jabatan_fungsional', $dosen->jabatan_fungsional);
                                if (is_array($jabatanValue)) {
                                    $jabatanValue = $jabatanValue[0] ?? null;
                                }
                            @endphp
                            <option value="Asisten Ahli" {{ $jabatanValue === 'Asisten Ahli' ? 'selected' : '' }}>Asisten Ahli</option>
                            <option value="Lektor" {{ $jabatanValue === 'Lektor' ? 'selected' : '' }}>Lektor</option>
                            <option value="Lektor Kepala" {{ $jabatanValue === 'Lektor Kepala' ? 'selected' : '' }}>Lektor Kepala</option>
                            <option value="Profesor" {{ $jabatanValue === 'Profesor' ? 'selected' : '' }}>Profesor</option>
                        </select>
                    </div>

                    <!-- Fakultas -->
                    <div>
                        <label for="fakultas_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Fakultas
                        </label>
                        <select id="fakultas_id" name="fakultas_id" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0f1117] text-gray-900 dark:text-white focus:ring-2 focus:ring-[#8B1538] focus:border-transparent">
                            <option value="">-- Pilih Fakultas --</option>
                            @foreach($fakultas as $f)
                                <option value="{{ $f->id }}" {{ old('fakultas_id', $dosen->fakultas_id) == $f->id ? 'selected' : '' }}>
                                    {{ $f->nama_fakultas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="submit" class="px-8 py-3 bg-[#8B1538] text-white rounded-lg hover:bg-[#6D1029] transition font-semibold flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Simpan Perubahan
                </button>
                <a href="{{ route('dosen.profil.index') }}" class="px-8 py-3 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white rounded-lg hover:bg-gray-400 dark:hover:bg-gray-700 transition font-semibold flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
