@extends('layouts.guest')

@section('page-title', 'Absen Mahasiswa')

@section('content')


<div class="max-w-md w-full bg-white dark:bg-[#1a1d2e] rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-slate-800">
    <div class="p-8">
        <div class="text-center mb-6">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/logo_stih_white-clear.png') }}" class="h-20 w-auto block dark:hidden filter brightness-0" alt="Logo STIH">
                <img src="{{ asset('images/logo_stih_white-clear.png') }}" class="h-20 w-auto hidden dark:block" alt="Logo STIH">
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                Student Portal Absensi
            </h3>
        </div>
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('absen.login.post') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token ?? '' }}">

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NIM atau Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input name="identifier" 
                           type="text" 
                           class="pl-10 w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-[#8B1538] focus:border-[#8B1538] block px-4 py-3 placeholder-gray-400 dark:placeholder-gray-500 transition-colors" 
                           placeholder="Masukkan NIM atau Email" 
                           required>
                </div>
            </div>

            <div x-data="{ show: false }">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                <div class="relative text-gray-400 focus-within:text-gray-600 dark:focus-within:text-gray-300">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input name="password" 
                           :type="show ? 'text' : 'password'" 
                           class="pl-10 pr-10 w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-[#8B1538] focus:border-[#8B1538] block px-4 py-3 placeholder-gray-400 dark:placeholder-gray-500 transition-colors" 
                           placeholder="Masukkan Password" 
                           required>
                    <button type="button" 
                            @click="show = !show" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer focus:outline-none text-gray-400 hover:text-[#8B1538] transition-colors">
                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>

            @if(!empty($kelas))
                <div class="bg-gray-50 dark:bg-slate-800/50 rounded-xl p-4 border border-gray-100 dark:border-slate-700 space-y-3">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-[#8B1538]/10 flex items-center justify-center text-[#8B1538]">
                                <i class="fas fa-book text-xs"></i>
                            </div>
                        </div>
                        <div class="text-sm">
                            <p class="text-gray-500 dark:text-gray-400 font-medium text-xs uppercase tracking-wider mb-0.5">Mata Kuliah</p>
                            <div class="font-bold text-gray-900 dark:text-white">{{ $kelas->mataKuliah->nama_mk ?? '-' }}</div>
                            <div class="text-gray-500 text-xs mt-0.5">Kelas {{ $kelas->kode_kelas ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-[#8B1538]/10 flex items-center justify-center text-[#8B1538]">
                                <i class="fas fa-chalkboard-teacher text-xs"></i>
                            </div>
                        </div>
                        <div class="text-sm">
                            <p class="text-gray-500 dark:text-gray-400 font-medium text-xs uppercase tracking-wider mb-0.5">Dosen</p>
                            <div class="font-semibold text-gray-900 dark:text-white">{{ $kelas->dosen->nama ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-[#8B1538]/10 flex items-center justify-center text-[#8B1538]">
                                <i class="fas fa-clock text-xs"></i>
                            </div>
                        </div>
                        <div class="text-sm">
                            <p class="text-gray-500 dark:text-gray-400 font-medium text-xs uppercase tracking-wider mb-0.5">Waktu</p>
                            <div class="font-semibold text-gray-900 dark:text-white">
                                {{ $kelas->hari ?? '-' }}, {{ substr($kelas->jam_mulai, 0, 5) }} - {{ substr($kelas->jam_selesai, 0, 5) }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <button type="submit" 
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-br from-[#8B1538] to-[#6D1029] hover:from-[#7A1230] hover:to-[#5E0D22] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8B1538] transform transition-all duration-200 hover:scale-[1.01]">
                Login & Absen
            </button>
        </form>
    </div>
</div>
@endsection
