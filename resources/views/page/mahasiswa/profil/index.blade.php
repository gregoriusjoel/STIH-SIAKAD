@extends('layouts.mahasiswa')

@section('title', 'Profil')
@section('page-title', 'Profil Mahasiswa')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    
    {{-- Profile Header Card --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-maroon to-red-800 h-32"></div>
        <div class="px-8 pb-8">
            <div class="flex flex-col md:flex-row items-center md:items-end gap-6 -mt-16">
                {{-- Photo --}}
                <div class="relative">
                    <div class="w-32 h-32 rounded-full border-4 border-white shadow-xl bg-white flex items-center justify-center overflow-hidden">
                        @if($mahasiswa->foto)
                            <img src="{{ asset('storage/' . $mahasiswa->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-maroon to-red-900 flex items-center justify-center text-white text-4xl font-bold">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <button onclick="document.getElementById('fotoInput').click()" class="absolute bottom-0 right-0 w-10 h-10 bg-blue-600 rounded-full text-white hover:bg-blue-700 transition shadow-lg flex items-center justify-center">
                        <i class="fas fa-camera"></i>
                    </button>
                </div>

                {{-- Info --}}
                <div class="flex-grow text-center md:text-left mt-4 md:mt-0">
                    <h2 class="text-2xl font-bold text-gray-800 mb-1">{{ $user->name }}</h2>
                    <p class="text-gray-600 mb-2">{{ $mahasiswa->npm }}</p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-3">
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold">
                            {{ $mahasiswa->prodi ?? 'Hukum' }}
                        </span>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-sm font-semibold">
                            Angkatan {{ $mahasiswa->angkatan ?? '-' }}
                        </span>
                        <span class="px-3 py-1 
                            @if($mahasiswa->status_akun === 'aktif') bg-green-100 text-green-700
                            @elseif($mahasiswa->status_akun === 'tidak_aktif') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700
                            @endif
                            rounded-lg text-sm font-semibold">
                            {{ strtoupper($mahasiswa->status_akun) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Profile Form --}}
    <form action="{{ route('mahasiswa.profil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <input type="file" id="fotoInput" name="foto" accept="image/*" class="hidden" onchange="this.form.submit()">
        
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Informasi Pribadi</h3>
            </div>

            <div class="p-6 space-y-6">
                {{-- Nama Lengkap --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent"
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent"
                           required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- NPM (Read Only) --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">NPM</label>
                        <input type="text" value="{{ $mahasiswa->npm }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100"
                               readonly>
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $mahasiswa->no_hp) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent">
                        @error('no_hp')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                    <textarea name="alamat" rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent">{{ old('alamat', $mahasiswa->alamat) }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-maroon text-white rounded-lg hover:bg-maroon-hover transition font-semibold flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- Change Password Form --}}
    <form action="{{ route('mahasiswa.profil.update-password') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Ubah Password</h3>
            </div>

            <div class="p-6 space-y-6">
                {{-- Current Password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password Lama</label>
                    <input type="password" name="current_password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent"
                           required>
                    @error('current_password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- New Password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                    <input type="password" name="new_password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent"
                           required>
                    @error('new_password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent"
                           required>
                </div>

                {{-- Submit Button --}}
                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold flex items-center gap-2">
                        <i class="fas fa-lock"></i>
                        Ubah Password
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- Additional Info --}}
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Akademik</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Program Studi</p>
                <p class="font-bold text-blue-600">{{ $mahasiswa->prodi ?? 'Hukum' }}</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Angkatan</p>
                <p class="font-bold text-green-600">{{ $mahasiswa->angkatan ?? '-' }}</p>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Status</p>
                <p class="font-bold text-purple-600">{{ strtoupper($mahasiswa->status_akun) }}</p>
            </div>
            <div class="text-center p-4 bg-orange-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">NPM</p>
                <p class="font-bold text-orange-600">{{ $mahasiswa->npm }}</p>
            </div>
        </div>
    </div>

</div>
@endsection
