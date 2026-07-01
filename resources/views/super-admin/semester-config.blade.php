@extends('layouts.super-admin')
@section('title', 'Semester & Tahun Akademik')
@section('page-title', 'Semester & Tahun Akademik')
@section('content')
<div class="glass-card p-6">
    <h3 class="font-bold text-[#7a1621] mb-4">Daftar Semester</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs uppercase bg-[#7a1621]/5 text-[#7a1621] border-b border-[#7a1621]/10">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Tahun</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($semesters as $sem)
                <tr class="border-b border-slate-100">
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $sem->nama ?? $sem->name ?? 'Semester ' . $sem->id }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $sem->tahun_ajaran ?? $sem->year ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($sem->is_active ?? false)
                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Aktif</span>
                        @else
                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500">Tidak Aktif</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-4 py-8 text-center text-slate-400">Belum ada data semester.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
