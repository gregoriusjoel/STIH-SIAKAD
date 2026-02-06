@extends('layouts.guest')

@section('page-title', 'Absen Mahasiswa')

@section('content')
<div class="sm:mx-auto sm:w-full sm:max-w-md mb-6">
    <div class="flex justify-center">
         <div class="w-16 h-16 bg-gradient-to-br from-[#8B1538] to-[#6D1029] rounded-2xl flex items-center justify-center text-white shadow-lg shadow-maroon/20">
            <i class="fas fa-graduation-cap text-3xl"></i>
        </div>
    </div>
    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
        STIH Adhyaksa
    </h2>
    <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
        Student Portal Absensi
    </p>
</div>

<div class="max-w-md w-full bg-white dark:bg-[#1a1d2e] rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-slate-800">
    <div class="p-8">
        <div class="text-center mb-8">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Login Absen</h2>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Scan QR Code atau login manual</p>
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

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input name="password" 
                           type="password" 
                           class="pl-10 w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-[#8B1538] focus:border-[#8B1538] block px-4 py-3 placeholder-gray-400 dark:placeholder-gray-500 transition-colors" 
                           placeholder="Masukkan Password" 
                           required>
                </div>
            </div>

            @if(!empty($kelas))
                <div class="bg-gray-50 dark:bg-slate-800/50 rounded-xl p-4 border border-gray-100 dark:border-slate-700">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <i class="fas fa-info-circle text-[#8B1538]"></i>
                        </div>
                        <div class="text-sm">
                            <p class="text-gray-500 dark:text-gray-400 font-medium text-xs uppercase tracking-wider mb-1">Informasi Kelas</p>
                            <div class="font-semibold text-gray-900 dark:text-white">{{ $kelas->mataKuliah->nama ?? '-' }}</div>
                            <div class="text-gray-600 dark:text-gray-400 mt-0.5">Kode: {{ $kelas->kode_kelas ?? '-' }}</div>
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
