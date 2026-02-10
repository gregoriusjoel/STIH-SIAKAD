@extends('layouts.mahasiswa')

@section('title', 'Profil Mahasiswa')
@section('page-title', 'Profil Mahasiswa')

@section('content')
    <div class="space-y-6">



        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left Column: Photo & Basic Identity --}}
            <div class="space-y-6">
                <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-lg p-6 text-center h-full">
                    <div class="relative w-40 h-40 mx-auto mb-6 group">
                        <div
                            class="w-40 h-40 rounded-full overflow-hidden border-4 border-gray-100 dark:border-slate-700 shadow-sm relative">
                            @if($mahasiswa->foto)
                                <img src="{{ asset('storage/' . $mahasiswa->foto) }}" alt="Foto Profil"
                                    class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-gray-400 dark:text-slate-500">
                                    <i class="fas fa-user text-6xl"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-1">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mb-4">{{ $mahasiswa->nim }}</p>

                    <form action="{{ route('mahasiswa.profil.update') }}" method="POST" enctype="multipart/form-data"
                        class="mt-4">
                        @csrf
                        @method('PUT')
                        {{-- Hidden fields required for validation --}}
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <input type="hidden" name="no_hp" value="{{ $mahasiswa->no_hp }}">

                        <div x-data="{ fileName: '' }" class="mb-4">
                            <label class="block w-full">
                                <span class="sr-only">Pilih Foto</span>
                                <input type="file" name="foto" accept="image/*" required
                                    @change="fileName = $event.target.files[0].name" class="block w-full text-sm text-gray-500 dark:text-slate-400
                                                    file:mr-4 file:py-2 file:px-4
                                                    file:rounded-full file:border-0
                                                    file:text-xs file:font-semibold
                                                    file:bg-maroon file:text-white
                                                    hover:file:bg-maroon-hover
                                                    cursor-pointer">
                            </label>
                            <div x-show="fileName" class="text-xs text-center mt-2 text-green-600 dark:text-green-400">
                                <span x-text="fileName"></span> siap diupload
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-2 bg-maroon text-white text-sm font-bold rounded-lg hover:bg-maroon-hover transition shadow-sm">
                            <i class="fas fa-camera mr-2"></i> Update Foto
                        </button>
                    </form>
                </div>
            </div>

            {{-- Right Column: Security --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-lg p-6 h-full">
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 dark:border-slate-700 pb-4">
                        <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                            <i class="fas fa-lock text-maroon text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Keamanan Akun</h3>
                            <p class="text-sm text-gray-500 dark:text-slate-400">Ganti password akun Anda</p>
                        </div>
                    </div>

                    <form action="{{ route('mahasiswa.profil.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Password
                                    Saat Ini</label>
                                <div class="relative" x-data="{ show: false }">
                                    <input :type="show ? 'text' : 'password'" name="current_password" required
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-700 focus:ring-2 focus:ring-maroon focus:border-transparent bg-white dark:bg-slate-800 text-gray-800 dark:text-white"
                                        placeholder="Masukkan password lama">
                                    <button type="button" @click="show = !show"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Password
                                        Baru</label>
                                    <div class="relative" x-data="{ show: false }">
                                        <input :type="show ? 'text' : 'password'" name="new_password" required
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-700 focus:ring-2 focus:ring-maroon focus:border-transparent bg-white dark:bg-slate-800 text-gray-800 dark:text-white"
                                            placeholder="Minimal 8 karakter">
                                        <button type="button" @click="show = !show"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                        </button>
                                    </div>
                                    @error('new_password')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Konfirmasi
                                        Password Baru</label>
                                    <div class="relative" x-data="{ show: false }">
                                        <input :type="show ? 'text' : 'password'" name="new_password_confirmation" required
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-700 focus:ring-2 focus:ring-maroon focus:border-transparent bg-white dark:bg-slate-800 text-gray-800 dark:text-white"
                                            placeholder="Ulangi password baru">
                                        <button type="button" @click="show = !show"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit"
                                class="px-6 py-3 bg-maroon text-white font-bold rounded-lg hover:bg-maroon-hover transition shadow-md hover:shadow-lg flex items-center gap-2">
                                <i class="fas fa-save"></i>
                                Simpan Password Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Bottom Section: Account Info (Full Width) --}}
        <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-lg p-6">
            <h4
                class="text-sm font-bold text-gray-400 dark:text-slate-500 uppercase mb-6 border-b border-gray-100 dark:border-slate-700 pb-2">
                Informasi Akun
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 text-sm">
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Program Studi</span>
                    <span class="font-semibold text-gray-800 dark:text-white text-lg">{{ $mahasiswa->prodi }}</span>
                </div>

                <div class="flex flex-col gap-1">
                    <span class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Status</span>
                    <div>
                        @php
                            $displayStatus = 'Aktif'; 
                        @endphp
                        <span class="inline-block px-3 py-1 rounded text-xs font-bold bg-green-100 text-green-800">
                            {{ $displayStatus }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-col gap-1">
                    <span class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Email Saat Ini</span>
                    <span class="font-semibold text-gray-800 dark:text-white text-lg truncate"
                        title="{{ $user->email }}">{{ $user->email }}</span>
                </div>

                <div class="flex flex-col gap-1">
                    <span class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">No. HP</span>
                    <span class="font-semibold text-gray-800 dark:text-white text-lg">{{ $mahasiswa->no_hp ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection