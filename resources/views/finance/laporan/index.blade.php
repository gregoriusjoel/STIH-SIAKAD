@extends('layouts.finance')

@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Keuangan')

@section('content')
<div class="space-y-8 animate-fade-in px-4 md:px-0 pb-12">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Laporan Keuangan</h1>
            <p class="text-sm text-slate-500 font-medium mt-1">Rekap penerimaan, status tagihan, dan tren pembayaran mahasiswa.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('finance.laporan.index') }}"
               class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-sm transition-all">
                <i class="fas fa-redo-alt text-xs"></i> Reset Filter
            </a>
        </div>
    </div>

    {{-- ===== FILTER BAR ===== --}}
    <form method="GET" action="{{ route('finance.laporan.index') }}"
          class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Tahun Ajaran</label>
                <select name="tahun_ajaran"
                        class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#8B1538]/30">
                    <option value="">Semua Tahun Ajaran</option>
                    @foreach($tahunAjaranOptions as $ta)
                        <option value="{{ $ta }}" {{ $tahunAjaran == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Semester</label>
                <select name="semester"
                        class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#8B1538]/30">
                    <option value="">Semua Semester</option>
                    @for($i = 1; $i <= 8; $i++)
                        <option value="{{ $i }}" {{ $semester == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Bulan Pembayaran</label>
                <input type="month" name="bulan" value="{{ $bulan }}"
                       class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#8B1538]/30">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Status Tagihan</label>
                <select name="status"
                        class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#8B1538]/30">
                    <option value="">Semua Status</option>
                    <option value="PUBLISHED" {{ $status == 'PUBLISHED' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="IN_INSTALLMENT" {{ $status == 'IN_INSTALLMENT' ? 'selected' : '' }}>Cicilan</option>
                    <option value="LUNAS" {{ $status == 'LUNAS' ? 'selected' : '' }}>Lunas</option>
                    <option value="DRAFT" {{ $status == 'DRAFT' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
        </div>
        <div class="mt-4 flex justify-end">
            <button type="submit"
                    class="flex items-center gap-2 px-6 py-2.5 bg-[#8B1538] hover:bg-[#6D1029] text-white rounded-xl font-bold text-sm transition-all shadow-md shadow-red-900/10">
                <i class="fas fa-filter text-xs"></i> Terapkan Filter
            </button>
        </div>
    </form>

    {{-- ===== KPI CARDS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        @php
            $kpis = [
                [
                    'label'    => 'Total Tagihan',
                    'value'    => 'Rp ' . number_format($totalTagihan, 0, ',', '.'),
                    'sub'      => $jumlahInvoice . ' invoice aktif',
                    'icon'     => 'fa-file-invoice-dollar',
                    'gradient' => 'from-slate-700 to-slate-900',
                    'ring'     => 'ring-slate-200',
                ],
                [
                    'label'    => 'Dana Terkumpul',
                    'value'    => 'Rp ' . number_format($totalTerkumpul, 0, ',', '.'),
                    'sub'      => number_format($totalTagihan > 0 ? ($totalTerkumpul / $totalTagihan * 100) : 0, 1) . '% dari total tagihan',
                    'icon'     => 'fa-wallet',
                    'gradient' => 'from-emerald-600 to-emerald-800',
                    'ring'     => 'ring-emerald-100',
                ],
                [
                    'label'    => 'Lunas',
                    'value'    => $jumlahLunas . ' mahasiswa',
                    'sub'      => 'Rp ' . number_format($totalLunas, 0, ',', '.'),
                    'icon'     => 'fa-check-circle',
                    'gradient' => 'from-blue-600 to-blue-800',
                    'ring'     => 'ring-blue-100',
                ],
                [
                    'label'    => 'Masih Menunggak',
                    'value'    => ($jumlahBelumBayar + $jumlahCicilan) . ' mahasiswa',
                    'sub'      => 'Rp ' . number_format($sisaTagihan, 0, ',', '.') . ' sisa',
                    'icon'     => 'fa-exclamation-circle',
                    'gradient' => 'from-red-600 to-[#8B1538]',
                    'ring'     => 'ring-red-100',
                ],
            ];
        @endphp

        @foreach($kpis as $kpi)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden ring-1 {{ $kpi['ring'] }} hover:shadow-lg transition-all group">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="size-12 rounded-2xl bg-gradient-to-br {{ $kpi['gradient'] }} flex items-center justify-center text-white shadow-md">
                            <i class="fas {{ $kpi['icon'] }} text-lg"></i>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mt-1">{{ $kpi['label'] }}</span>
                    </div>
                    <p class="text-xl font-black text-slate-800 tracking-tight leading-tight">{{ $kpi['value'] }}</p>
                    <p class="text-xs text-slate-400 font-medium mt-1">{{ $kpi['sub'] }}</p>
                </div>
                <div class="h-1 bg-gradient-to-r {{ $kpi['gradient'] }} opacity-40 group-hover:opacity-100 transition-opacity"></div>
            </div>
        @endforeach
    </div>

    {{-- ===== CHART + STATUS BREAKDOWN ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Trend Pembayaran --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-black text-slate-800 tracking-tight">Tren Pembayaran</h3>
                    <p class="text-xs text-slate-400 font-medium">12 bulan terakhir</p>
                </div>
                <div class="size-10 rounded-xl bg-[#8B1538]/10 flex items-center justify-center">
                    <i class="fas fa-chart-area text-[#8B1538]"></i>
                </div>
            </div>
            <div id="chart-trend"></div>
        </div>

        {{-- Status Breakdown --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-black text-slate-800 tracking-tight">Status Tagihan</h3>
                    <p class="text-xs text-slate-400 font-medium">Komposisi saat ini</p>
                </div>
                <div class="size-10 rounded-xl bg-[#8B1538]/10 flex items-center justify-center">
                    <i class="fas fa-chart-pie text-[#8B1538]"></i>
                </div>
            </div>

            @php
                $statusMap = [
                    'LUNAS'          => ['label' => 'Lunas',        'color' => 'bg-emerald-500', 'text' => 'text-emerald-600'],
                    'IN_INSTALLMENT' => ['label' => 'Cicilan',      'color' => 'bg-amber-400',   'text' => 'text-amber-600'],
                    'PUBLISHED'      => ['label' => 'Belum Bayar',  'color' => 'bg-blue-500',    'text' => 'text-blue-600'],
                    'DRAFT'          => ['label' => 'Draft',        'color' => 'bg-slate-300',   'text' => 'text-slate-500'],
                ];
                $grandTotal = $statusBreakdown->sum('jumlah') ?: 1;
            @endphp

            <div id="chart-donut" class="mx-auto" style="max-width:200px"></div>

            <div class="mt-6 space-y-3">
                @forelse($statusBreakdown as $item)
                    @php
                        $cfg = $statusMap[$item->status] ?? ['label' => $item->status, 'color' => 'bg-slate-300', 'text' => 'text-slate-500'];
                        $pct = round($item->jumlah / $grandTotal * 100);
                    @endphp
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full {{ $cfg['color'] }}"></div>
                            <span class="text-xs font-bold text-slate-600">{{ $cfg['label'] }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-black text-slate-800">{{ $item->jumlah }} invoice</span>
                            <span class="text-[10px] text-slate-400 ml-1">({{ $pct }}%)</span>
                        </div>
                    </div>
                    <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full {{ $cfg['color'] }}" style="width: {{ $pct }}%"></div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 text-center py-4">Tidak ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ===== DETAIL TABEL TAGIHAN ===== --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-50 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black text-slate-800 tracking-tight">Detail Tagihan</h3>
                <p class="text-xs text-slate-400 font-medium">{{ $invoices->total() }} tagihan ditemukan</p>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50">#</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50">Mahasiswa</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50">Semester / TA</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50 text-right">Tagihan</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50 text-right">Terbayar</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50 text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @php
                        $statusClasses = [
                            'DRAFT'          => 'bg-slate-100 text-slate-500 border-slate-200',
                            'PUBLISHED'      => 'bg-blue-50 text-blue-600 border-blue-100',
                            'IN_INSTALLMENT' => 'bg-amber-50 text-amber-600 border-amber-100',
                            'LUNAS'          => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                            'CANCELLED'      => 'bg-red-50 text-red-500 border-red-100',
                        ];
                        $statusLabels = [
                            'DRAFT'          => 'Draft',
                            'PUBLISHED'      => 'Belum Bayar',
                            'IN_INSTALLMENT' => 'Cicilan',
                            'LUNAS'          => 'Lunas',
                            'CANCELLED'      => 'Dibatalkan',
                        ];
                    @endphp
                    @forelse($invoices as $invoice)
                        @php
                            $terbayar  = $invoice->payments->sum('amount_approved');
                            $pctBayar  = $invoice->total_tagihan > 0
                                         ? min(100, round($terbayar / $invoice->total_tagihan * 100))
                                         : 0;
                            $sClass    = $statusClasses[$invoice->status] ?? $statusClasses['DRAFT'];
                            $sLabel    = $statusLabels[$invoice->status]  ?? $invoice->status;
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-5">
                                <span class="text-sm font-bold text-slate-400">#{{ $invoice->id }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="size-9 rounded-xl bg-gradient-to-br from-[#8B1538] to-[#6D1029] text-white flex items-center justify-center font-black text-sm shrink-0">
                                        {{ strtoupper(substr($invoice->student->user->name ?? 'M', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-800 truncate">{{ $invoice->student->user->name ?? 'N/A' }}</p>
                                        <p class="text-[10px] text-slate-400 font-medium">{{ $invoice->student->nim ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm font-bold text-slate-700">Semester {{ $invoice->semester }}</p>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $invoice->tahun_ajaran }}</p>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <p class="text-sm font-black text-slate-800">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <p class="text-sm font-black {{ $terbayar >= $invoice->total_tagihan ? 'text-emerald-600' : ($terbayar > 0 ? 'text-amber-600' : 'text-slate-400') }}">
                                    Rp {{ number_format($terbayar, 0, ',', '.') }}
                                </p>
                                <div class="mt-1.5 w-24 ml-auto h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $terbayar >= $invoice->total_tagihan ? 'bg-emerald-500' : ($terbayar > 0 ? 'bg-amber-400' : 'bg-slate-300') }}"
                                         style="width: {{ $pctBayar }}%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-2.5 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border {{ $sClass }}">
                                    {{ $sLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <a href="{{ route('finance.invoices.show', $invoice) }}"
                                   class="inline-flex size-8 items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-[#8B1538] hover:text-white transition-all">
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="size-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
                                        <i class="fas fa-chart-bar text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-400">Tidak ada data dengan filter ini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="px-6 py-5 border-t border-slate-50 bg-slate-50/30">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---- Area Chart: Tren Pembayaran ----
    const trendOptions = {
        chart: {
            type: 'area',
            height: 240,
            toolbar: { show: false },
            sparkline: { enabled: false },
            fontFamily: 'Nunito, sans-serif',
        },
        series: [{
            name: 'Dana Masuk',
            data: @json($trendValues)
        }],
        xaxis: {
            categories: @json($trendLabels),
            labels: {
                style: { fontSize: '11px', fontWeight: 700, colors: '#94a3b8' }
            },
            axisBorder: { show: false },
            axisTicks:  { show: false },
        },
        yaxis: {
            labels: {
                formatter: val => 'Rp ' + new Intl.NumberFormat('id-ID').format(val),
                style: { fontSize: '10px', fontWeight: 700, colors: '#94a3b8' }
            }
        },
        colors: ['#8B1538'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.05,
            }
        },
        stroke: { curve: 'smooth', width: 3 },
        dataLabels: { enabled: false },
        tooltip: {
            y: { formatter: val => 'Rp ' + new Intl.NumberFormat('id-ID').format(val) }
        },
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 4,
        },
    };
    new ApexCharts(document.getElementById('chart-trend'), trendOptions).render();

    // ---- Donut Chart: Status Breakdown ----
    @php
        $donutLabels = [];
        $donutValues = [];
        $donutColors = ['#10b981', '#f59e0b', '#3b82f6', '#94a3b8'];
        $statusOrder  = ['LUNAS', 'IN_INSTALLMENT', 'PUBLISHED', 'DRAFT'];
        $statusLabArr = ['LUNAS' => 'Lunas', 'IN_INSTALLMENT' => 'Cicilan', 'PUBLISHED' => 'Belum Bayar', 'DRAFT' => 'Draft'];
        $bMap = $statusBreakdown->keyBy('status');
        foreach ($statusOrder as $s) {
            if ($bMap->has($s)) {
                $donutLabels[] = $statusLabArr[$s];
                $donutValues[] = (int) $bMap[$s]->jumlah;
            }
        }
    @endphp

    @if(count($donutValues) > 0)
    const donutOptions = {
        chart: {
            type: 'donut',
            height: 200,
            fontFamily: 'Nunito, sans-serif',
        },
        series: @json($donutValues),
        labels: @json($donutLabels),
        colors: @json(array_slice($donutColors, 0, count($donutValues))),
        legend: { show: false },
        dataLabels: { enabled: false },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '12px',
                            fontWeight: 800,
                            color: '#334155',
                            formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                        }
                    }
                }
            }
        },
        tooltip: { y: { formatter: val => val + ' invoice' } },
    };
    new ApexCharts(document.getElementById('chart-donut'), donutOptions).render();
    @endif
});
</script>
@endpush
