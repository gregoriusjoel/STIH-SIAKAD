@extends('layouts.admin')

@section('content')
<div class="max-w-full mx-auto">
        {{-- Header & Actions --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <a href="{{ route('admin.hasil-kuisioner.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm group mb-6">
                    <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                    Kembali ke Daftar
                </a>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">
                    {{ $type === 'mahasiswa_baru' ? 'Kuesioner Mahasiswa Baru' : 'Kuesioner Aktivasi Semester' }}
                </h1>
                <p class="text-slate-500 font-bold mt-1 uppercase tracking-wider flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                    {{ $stats['period'] }}
                </p>
            </div>
            
                <a href="{{ route('admin.hasil-kuisioner.export-excel', array_merge(['type' => $type], request()->all())) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3.5 bg-primary rounded-2xl text-sm font-bold text-white hover:bg-primary/90 transition-all shadow-lg shadow-primary/10">
                    <span class="material-symbols-outlined text-[20px]">description</span>
                    Download Excel
                </a>
            </div>
        </div>

        {{-- Filters Card --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 mb-8">
            <form action="{{ route('admin.hasil-kuisioner.show', $type) }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Program Studi</label>
                    <select name="prodi_id" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20">
                        <option value="">Semua Prodi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                        @endforeach
                    </select>
                </div>
                
                @if($type === 'aktivasi_semester')
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Semester</label>
                    <select name="semester_id" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20">
                        <option value="">Semua Semester</option>
                        @foreach($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ request('semester_id') == $sem->id ? 'selected' : '' }}>{{ $sem->display_label }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Angkatan</label>
                    <select name="angkatan" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20">
                        <option value="">Semua Angkatan</option>
                        @foreach($angkatans as $year)
                            <option value="{{ $year }}" {{ request('angkatan') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Rentang Tanggal</label>
                    <div class="flex items-center gap-2">
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20">
                        <span class="text-slate-300">-</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-slate-900 text-white font-bold py-3.5 rounded-xl hover:bg-slate-800 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">filter_list</span>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Main Layout Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
            {{-- Left Column: Rekap (col-span-7) --}}
            <div class="lg:col-span-7">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 h-full">
                    <h3 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">list_alt</span>
                        Rekap Per Pertanyaan
                    </h3>
                    
                    <div class="space-y-10">
                        @foreach($stats['rekap'] as $key => $item)
                            <div>
                                <div class="flex justify-between items-start mb-3">
                                    <div class="text-sm font-bold text-slate-700 max-w-[80%]">{{ $item['text'] }}</div>
                                    <div class="text-sm font-black text-primary bg-primary/5 px-2 py-0.5 rounded-lg">{{ $item['avg'] }}</div>
                                </div>
                                <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-primary rounded-full transition-all duration-1000" style="width: {{ ($item['avg'] / 5) * 100 }}%"></div>
                                </div>
                                <div class="flex justify-between mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    <span>Sangat Tidak Puas</span>
                                    <span>Sangat Puas</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right Column: Stats & Chart (col-span-5) --}}
            <div class="lg:col-span-5 flex flex-col gap-8">
                {{-- Stats Cards Horizontal in 1 Row --}}
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center text-center">
                        <div class="p-2.5 bg-primary/10 rounded-xl mb-3">
                            <span class="material-symbols-outlined text-primary text-[20px]">groups</span>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total</span>
                        <div class="text-xl font-black text-slate-900">{{ number_format($stats['total_respondents']) }}</div>
                    </div>

                    @php
                        $overallAvg = collect($stats['rekap'])->avg('avg');
                        $ratingColor = $overallAvg >= 4 ? 'text-green-500' : ($overallAvg >= 3 ? 'text-blue-500' : 'text-orange-500');
                    @endphp
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center text-center">
                        <div class="p-2.5 bg-blue-100 rounded-xl mb-3">
                            <span class="material-symbols-outlined text-blue-600 text-[20px]">star</span>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Skor</span>
                        <div class="text-xl font-black {{ $ratingColor }}">{{ round($overallAvg, 2) }}</div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center text-center">
                        <div class="p-2.5 bg-purple-100 rounded-xl mb-3">
                            <span class="material-symbols-outlined text-purple-600 text-[20px]">chat_bubble</span>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Saran</span>
                        <div class="text-xl font-black text-slate-900">{{ count($stats['suggestions']) }}</div>
                    </div>
                </div>

                {{-- Chart Visualization --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 flex flex-col h-full">
                    <h3 class="text-lg font-bold text-slate-800 mb-8 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">bar_chart</span>
                        Distribusi Jawaban
                    </h3>
                    <div class="h-[300px] mb-8">
                        <div id="distributionChart"></div>
                    </div>

                    {{-- Distribution Table to fill space --}}
                    <div class="mt-auto border-t border-slate-50 pt-8">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Data Distribusi</h4>
                        <div class="space-y-3">
                            @php
                                $totalScores = collect($stats['rekap'])->pluck('freq')->reduce(function($carry, $freq) {
                                    foreach($freq as $score => $count) {
                                        $carry[$score] = ($carry[$score] ?? 0) + $count;
                                    }
                                    return $carry;
                                }, []);
                                $overallTotal = array_sum($totalScores);
                            @endphp
                            @foreach([5 => 'Sangat Baik', 4 => 'Baik', 3 => 'Cukup', 2 => 'Buruk', 1 => 'Sangat Buruk'] as $val => $label)
                                @php 
                                    $count = $totalScores[$val] ?? 0;
                                    $percent = $overallTotal > 0 ? ($count / $overallTotal) * 100 : 0;
                                    $hexColors = [
                                        5 => '#22c55e', 
                                        4 => '#8b1538', 
                                        3 => '#3b82f6', 
                                        2 => '#f97316', 
                                        1 => '#ef4444'
                                    ];
                                @endphp
                                <div class="flex items-center gap-4">
                                    <div class="w-2 h-2 rounded-full" style="background-color: {{ $hexColors[$val] }}"></div>
                                    <div class="text-xs font-bold text-slate-600 flex-grow">{{ $label }}</div>
                                    <div class="text-xs font-black text-slate-900">{{ number_format($count) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Suggestions --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
                <h3 class="text-xl font-black text-slate-900">Kritik & Saran Responden</h3>
                <span class="px-4 py-1.5 bg-slate-200 rounded-full text-xs font-black text-slate-600">{{ count($stats['suggestions']) }} Saran</span>
            </div>
            
            <div class="max-h-[500px] overflow-y-auto custom-scrollbar p-8">
                @forelse($stats['suggestions'] as $saran)
                    <div class="p-6 bg-slate-50 rounded-2xl mb-4 last:mb-0 border border-slate-100 hover:border-primary/20 transition-all shadow-sm">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center font-black text-primary">
                                    {{ strtoupper(substr($saran['name'], 0, 1)) }}
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-sm font-black text-slate-800">{{ $saran['name'] }}</h4>
                                    <span class="text-[10px] font-bold text-slate-400 bg-white px-2 py-1 rounded-lg border border-slate-100 uppercase tracking-widest">{{ $saran['nim'] }}</span>
                                </div>
                                <p class="text-slate-600 text-sm leading-relaxed italic font-medium">"{{ $saran['text'] }}"</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <span class="material-symbols-outlined text-slate-300 text-[64px] mb-4">chat_bubble_outline</span>
                        <p class="text-slate-400 font-bold italic">Belum ada kritik dan saran untuk periode ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- ApexCharts dimuat secara global via app.js layout, tidak butuh script loader tambahan. --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate aggregate distribution from rekap
        @php
            $aggregateFreq = array_values(collect($stats['rekap'])->pluck('freq')->reduce(function($carry, $freq) {
                foreach($freq as $score => $count) {
                    $carry[$score] = ($carry[$score] ?? 0) + $count;
                }
                return $carry;
            }, [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0]));
        @endphp
        const aggregateFreq = @json($aggregateFreq);

        const options = {
            chart: {
                type: 'donut',
                height: 300,
                fontFamily: 'Inter, sans-serif'
            },
            series: aggregateFreq,
            labels: ['1 - Sangat Buruk', '2 - Buruk', '3 - Cukup', '4 - Baik', '5 - Sangat Baik'],
            colors: ['#ef4444', '#f97316', '#3b82f6', '#8b1538', '#22c55e'],
            dataLabels: {
                enabled: true,
                formatter: function (val, opts) {
                    return val.toFixed(0) + "%";
                }
            },
            legend: {
                position: 'bottom',
                fontSize: '11px',
                fontWeight: 'bold',
                itemMargin: {
                    horizontal: 8,
                    vertical: 4
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%'
                    }
                }
            }
        };

        const chart = new ApexCharts(document.getElementById('distributionChart'), options);
        chart.render();
    });
</script>
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #E2E8F0;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #CBD5E1;
    }
</style>
@endpush
@endsection
