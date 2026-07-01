@extends('layouts.super-admin')

@section('title', 'Backup & Recovery')
@section('page-title', 'Backup & Recovery')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621] font-bold">backup</span>
                Backup & Recovery Center
            </h2>
            <p class="text-sm text-slate-500 mt-0.5">Kelola salinan basis data sistem untuk menjamin keselamatan dan keberlangsungan data</p>
        </div>
 
        {{-- Create Backup button --}}
        <form action="{{ route('super-admin.backup.create') }}" method="POST" onsubmit="return confirm('Apakah Anda ingin membuat backup database saat ini? Aksi ini akan mengunci pembacaan tabel sementara.')">
            @csrf
            <button type="submit"
                class="btn-gold px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition flex items-center gap-2">
                <span class="material-symbols-outlined text-sm font-bold">cloud_upload</span>
                Backup Database Sekarang
            </button>
        </form>
    </div>
 
    {{-- Alert --}}
    <div class="p-4 bg-[#7a1621]/5 border border-[#7a1621]/15 rounded-xl flex items-start gap-3">
        <span class="material-symbols-outlined text-[#7a1621] text-xl mt-0.5">security</span>
        <div class="text-xs text-[#7a1621]">
            <p class="font-bold text-[#7a1621]">Kebijakan Keamanan Data</p>
            <p class="mt-0.5 text-slate-655">Backup data yang dihasilkan disimpan di dalam direktori internal server (`storage/app/backups`) dan hanya dapat diunduh oleh akun dengan peran Super Admin. Harap simpan berkas SQL di media eksternal yang aman secara berkala.</p>
        </div>
    </div>
 
    {{-- Backups List Table --}}
    <div class="glass-card overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center gap-2">
            <h3 class="font-bold text-[#7a1621] flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621]">list_alt</span>
                Berkas Backup Tersimpan
            </h3>
            <span class="text-xs text-slate-400">({{ count($files) }} file)</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs font-bold uppercase text-[#7a1621] bg-[#7a1621]/5 border-b border-[#7a1621]/10">
                    <tr>
                        <th class="px-5 py-3.5">Nama File Backup</th>
                        <th class="px-5 py-3.5 text-center">Ukuran File</th>
                        <th class="px-5 py-3.5 text-center">Waktu Dibuat</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($files as $file)
                    <tr class="hover:bg-[#7a1621]/5 transition-colors">
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-2 text-slate-705 font-medium">
                                <span class="material-symbols-outlined text-[#7a1621] text-sm">settings_backup_restore</span>
                                {{ $file['filename'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center text-xs text-slate-600 font-bold">
                            {{ $file['size'] }}
                        </td>
                        <td class="px-5 py-3.5 text-center text-xs text-slate-550">
                            {{ $file['created_at'] }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <div class="inline-flex items-center justify-center gap-2">
                                {{-- Download button --}}
                                <a href="{{ route('super-admin.backup.download', $file['filename']) }}" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#7a1621]/10 hover:bg-[#7a1621]/20 text-[#7a1621] text-xs font-bold rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-sm">download</span>
                                    Download
                                </a>
 
                                {{-- Delete button --}}
                                <form action="{{ route('super-admin.backup.delete', $file['filename']) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus berkas backup ini secara permanen?')">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-lg transition-colors">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl block mb-2 text-slate-300">backup</span>
                            Belum ada file backup database yang dibuat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
