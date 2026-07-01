@extends('layouts.super-admin')

@section('title', 'System Configuration')
@section('page-title', 'System Configuration')

@section('content')
<div class="space-y-6" x-data="{ tab: 'general' }">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621] font-bold">settings</span>
                System Settings Center
            </h2>
            <p class="text-sm text-slate-500 mt-0.5">Kelola identitas, semester aktif, gateway WhatsApp, dan pengaturan email SMTP sistem</p>
        </div>
    </div>
 
    {{-- Tabs Navigation --}}
    <div class="flex border-b border-slate-200 gap-2">
        <button @click="tab = 'general'"
            :class="tab === 'general' ? 'border-[#7a1621] text-[#7a1621] font-bold border-b-2' : 'border-transparent text-slate-500 hover:text-[#7a1621]'"
            class="px-4 py-2 text-sm transition-all pb-3">
            Umum (General)
        </button>
        <button @click="tab = 'semester'"
            :class="tab === 'semester' ? 'border-[#7a1621] text-[#7a1621] font-bold border-b-2' : 'border-transparent text-slate-500 hover:text-[#7a1621]'"
            class="px-4 py-2 text-sm transition-all pb-3">
            Akademik & Semester
        </button>
        <button @click="tab = 'wa'"
            :class="tab === 'wa' ? 'border-[#7a1621] text-[#7a1621] font-bold border-b-2' : 'border-transparent text-slate-500 hover:text-[#7a1621]'"
            class="px-4 py-2 text-sm transition-all pb-3">
            WhatsApp Gateway
        </button>
        <button @click="tab = 'smtp'"
            :class="tab === 'smtp' ? 'border-[#7a1621] text-[#7a1621] font-bold border-b-2' : 'border-transparent text-slate-500 hover:text-[#7a1621]'"
            class="px-4 py-2 text-sm transition-all pb-3">
            Email SMTP
        </button>
    </div>
 
    {{-- Main Config Form --}}
    <form action="{{ route('super-admin.system-config.update') }}" method="POST" class="glass-card p-6">
        @csrf
 
        {{-- Tab 1: General --}}
        <div x-show="tab === 'general'" class="space-y-6">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Pengaturan Umum</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Kampus / Aplikasi</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'STIH Adhyaksa' }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621]">
                    <p class="text-xs text-slate-400 mt-1">Nama universitas atau sekolah tinggi yang tampil di dashboard & kop surat.</p>
                </div>
 
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama File Logo Kampus</label>
                    <input type="text" name="site_logo" value="{{ $settings['site_logo'] ?? 'logo.png' }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621]">
                    <p class="text-xs text-slate-400 mt-1">File logo yang diletakkan pada folder public/assets.</p>
                </div>
            </div>
 
            <div class="border-t border-slate-100 pt-6">
                <label class="block text-sm font-bold text-slate-700 mb-1">Mode Pemeliharaan (Maintenance Mode)</label>
                <p class="text-xs text-slate-400 mb-3">Bila diaktifkan, pengguna biasa tidak dapat mengakses portal akademis.</p>
                
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer select-none">
                        <input type="hidden" name="maintenance_mode" value="0">
                        <input type="checkbox" name="maintenance_mode" value="1" 
                            {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }}
                            class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#7a1621] rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#7a1621]"></div>
                        <span class="ml-3 text-sm text-slate-700 font-semibold peer-checked:text-[#7a1621]">Aktifkan Maintenance Mode</span>
                    </label>
                </div>
            </div>
        </div>
 
        {{-- Tab 2: Semester --}}
        <div x-show="tab === 'semester'" class="space-y-6">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Pengaturan Semester Aktif</h3>
 
            <div class="max-w-md">
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Semester Aktif Utama</label>
                <select name="semester_aktif_id"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621] bg-white">
                    <option value="">Pilih Semester</option>
                    @foreach($semesters as $sem)
                        <option value="{{ $sem->id }}" {{ ($settings['semester_aktif_id'] ?? '') == $sem->id ? 'selected' : '' }}>
                            {{ $sem->tahun_ajaran }} - {{ $sem->nama }} {{ $sem->is_active ? '(Aktif Saat Ini)' : '' }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1.5">
                    Menentukan semester default untuk KRS baru, pengisian nilai, dan kalkulasi tagihan mahasiswa baru.
                </p>
            </div>
        </div>
 
        {{-- Tab 3: WhatsApp Gateway --}}
        <div x-show="tab === 'wa'" class="space-y-6">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">WhatsApp Gateway Config</h3>
 
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">WhatsApp Gateway URL API</label>
                    <input type="url" name="wa_gateway_url" value="{{ $settings['wa_gateway_url'] ?? '' }}"
                        placeholder="https://api.whatsapp-gateway.com/send"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621]">
                    <p class="text-xs text-slate-400 mt-1">Endpoint untuk melakukan pengiriman notifikasi instan via WA.</p>
                </div>
 
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">API Key / Token WA</label>
                    <input type="password" name="wa_api_key" value="{{ $settings['wa_api_key'] ?? '' }}"
                        placeholder="••••••••••••••••"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621]">
                    <p class="text-xs text-slate-400 mt-1">Kredensial otentikasi WhatsApp Gateway API.</p>
                </div>
            </div>
        </div>
 
        {{-- Tab 4: SMTP Email --}}
        <div x-show="tab === 'smtp'" class="space-y-6">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Pengaturan Mailer SMTP</h3>
 
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">SMTP Host</label>
                    <input type="text" name="smtp_host" value="{{ $settings['smtp_host'] ?? '' }}" placeholder="smtp.mailtrap.io"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621]">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">SMTP Port</label>
                    <input type="number" name="smtp_port" value="{{ $settings['smtp_port'] ?? '' }}" placeholder="2525"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621]">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Enkripsi (Encryption)</label>
                    <select name="smtp_encryption"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621] bg-white">
                        <option value="none" {{ ($settings['smtp_encryption'] ?? '') === 'none' ? 'selected' : '' }}>None</option>
                        <option value="tls" {{ ($settings['smtp_encryption'] ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                    </select>
                </div>
            </div>
 
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-slate-50 pt-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">SMTP Username</label>
                    <input type="text" name="smtp_username" value="{{ $settings['smtp_username'] ?? '' }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621]">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">SMTP Password</label>
                    <input type="password" name="smtp_password" value="{{ $settings['smtp_password'] ?? '' }}" placeholder="••••••••••••••••"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621]">
                </div>
            </div>
 
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-slate-50 pt-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Alamat Email Pengirim (Sender Email)</label>
                    <input type="email" name="smtp_from_address" value="{{ $settings['smtp_from_address'] ?? '' }}" placeholder="noreply@stih.ac.id"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621]">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Pengirim (Sender Name)</label>
                    <input type="text" name="smtp_from_name" value="{{ $settings['smtp_from_name'] ?? '' }}" placeholder="SIAKAD STIH Adhyaksa"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#7a1621]">
                </div>
            </div>
        </div>
 
        {{-- Submit Button --}}
        <div class="flex justify-end gap-3 mt-8 pt-5 border-t border-slate-100">
            <button type="submit"
                class="btn-maroon px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">save</span>
                Simpan Konfigurasi
            </button>
        </div>
    </form>
</div>
@endsection
