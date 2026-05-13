@extends('layouts.parent')

@section('title', 'Nilai Akademik - Orang Tua')
@section('page-title', 'Nilai Akademik')

@push('styles')
    <style>
        .semester-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .semester-card:hover {
            box-shadow: 0 12px 32px -8px rgba(0, 0, 0, 0.08);
        }
        .grade-a { background: #dcfce7; color: #15803d; }
        .grade-b { background: #dbeafe; color: #1d4ed8; }
        .grade-c { background: #fef9c3; color: #a16207; }
        .grade-d, .grade-e { background: #fee2e2; color: #b91c1c; }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endpush

@section('content')
    <div class="space-y-8 animate-fade-in-up">

        {{-- Header Hero --}}
        <div class="relative overflow-hidden rounded-3xl bg-linear-to-br from-[#7a1621] via-[#9b1c2a] to-[#5a0015] shadow-2xl shadow-red-900/20 text-white">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-[0.07] rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-red-300 opacity-[0.08] rounded-full blur-2xl pointer-events-none translate-y-1/2 -translate-x-1/4"></div>

            <div class="relative p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-2xl shadow-lg shrink-0">
                        <i class="fas fa-file-invoice text-white/90"></i>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black tracking-tight">Transkrip Nilai</h2>
                        <p class="text-red-200/80 text-sm mt-0.5">Rekapitulasi hasil studi {{ $mahasiswa->user->name }} per semester</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl px-5 py-3">
                    <div class="text-right">
                        <div class="text-[10px] text-red-200/80 font-bold uppercase tracking-widest">IPK Kumulatif</div>
                        <div class="text-3xl font-black tracking-tight">{{ number_format($ipk, 2) }}</div>
                    </div>
                    <div class="w-11 h-11 bg-white/15 rounded-xl flex items-center justify-center border border-white/10">
                        <i class="fas fa-award text-white text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content --}}
        @forelse($nilaiData as $semester => $items)
            @php
                $ips = $ipsPerSemester[$semester] ?? 0;
                $semesterTotalSks = $items->sum(fn($k) =>
                    $k->kelasMataKuliah?->mataKuliah?->sks ?? $k->mataKuliah?->sks ?? 0
                );
            @endphp
            <div class="semester-card bg-white dark:bg-[#1a1c23] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden" x-data="{ open: true }">
                {{-- Accordion Header --}}
                <button @click="open = !open"
                    class="w-full flex items-center justify-between p-5 md:p-6 bg-gray-50/50 dark:bg-white/2 hover:bg-gray-50 dark:hover:bg-white/4 transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-maroon/10 dark:bg-maroon/20 text-maroon dark:text-red-400 flex items-center justify-center text-sm font-bold shrink-0">
                            {{ $loop->iteration }}
                        </div>
                        <div class="text-left">
                            <h3 class="font-bold text-gray-900 dark:text-white text-base">{{ $semester }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $items->count() }} mata kuliah &bull; {{ $semesterTotalSks }} SKS</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 shrink-0">
                        <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg {{ $ips >= 3.0 ? 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800' : ($ips >= 2.0 ? 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800' : 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800') }}">
                            <span class="text-[10px] font-bold uppercase tracking-wider opacity-70">IPS</span>
                            <span class="text-sm font-black">{{ number_format($ips, 2) }}</span>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center transition-transform duration-300 group-hover:bg-gray-200 dark:group-hover:bg-gray-700"
                             :class="{ 'rotate-180': !open }">
                            <i class="fas fa-chevron-down text-gray-500 dark:text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </button>

                {{-- Table --}}
                <div x-show="open" x-collapse>
                    <div class="overflow-x-auto">
                        <table class="w-full" style="min-width: 680px;">
                            <thead>
                                <tr class="bg-gray-50/80 dark:bg-white/3 border-b border-gray-100 dark:border-gray-800">
                                    <th class="px-6 py-3.5 text-left text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Kode MK</th>
                                    <th class="px-6 py-3.5 text-left text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Mata Kuliah</th>
                                    <th class="px-6 py-3.5 text-center text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">SKS</th>
                                    <th class="px-6 py-3.5 text-center text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Nilai</th>
                                    <th class="px-6 py-3.5 text-center text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Grade</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                                @foreach($items as $item)
                                    @php
                                        $mk = $item->kelasMataKuliah?->mataKuliah ?? $item->mataKuliah;
                                        $grade = $item->nilai?->grade ?? '-';
                                        $gradeClass = match (true) {
                                            str_contains($grade, 'A') => 'grade-a',
                                            str_contains($grade, 'B') => 'grade-b',
                                            str_contains($grade, 'C') => 'grade-c',
                                            default => 'grade-d',
                                        };
                                    @endphp
                                    <tr class="hover:bg-gray-50/60 dark:hover:bg-white/2 transition-colors">
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-500 dark:text-gray-400 tabular-nums">
                                            {{ $mk?->kode_mk ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-gray-200">
                                            {{ $mk?->nama_mk ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm font-medium text-gray-500 dark:text-gray-400 tabular-nums">
                                            {{ $mk?->sks ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm font-medium text-gray-500 dark:text-gray-400 tabular-nums">
                                            {{ $item->nilai?->nilai_akhir ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-block px-3 py-1 rounded-full text-[11px] font-black tracking-wide {{ $gradeClass }}">
                                                {{ $grade }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="bg-white dark:bg-[#1a1c23] rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm p-12 md:p-16 text-center">
                <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-inner">
                    <i class="fas fa-file-invoice text-gray-300 dark:text-gray-600 text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1">Belum Ada Data Nilai</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">Mahasiswa belum memiliki riwayat nilai akademik yang dipublikasikan. Data akan muncul setelah dosen menyelesaikan penilaian.</p>
            </div>
        @endforelse

    </div>
@endsection