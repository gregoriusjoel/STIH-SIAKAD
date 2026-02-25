@extends('layouts.app')

@section('title', 'Rekap Nilai Tugas — ' . ($kelas->mataKuliah->nama_mk ?? $kelas->mataKuliah->nama ?? 'Kelas'))

@section('content')

    <div class="pt-4 px-4 md:pt-6 md:px-8 pb-12 w-full space-y-6">

        {{-- Header --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Rekap Nilai Tugas</p>
                    <h1 class="text-2xl font-extrabold text-gray-900">
                        {{ $kelas->mataKuliah->nama_mk ?? $kelas->mataKuliah->nama ?? '-' }}
                        <span class="text-xl font-semibold text-red-700">— Kelas {{ $kelas->section ?? $kelas->kode_kelas ?? '' }}</span>
                    </h1>
                    <div class="flex flex-wrap gap-3 mt-2 text-sm text-gray-500">
                        <span><i class="fas fa-tag mr-1"></i>{{ $kelas->mataKuliah->kode_mk ?? '-' }}</span>
                        <span><i class="fas fa-graduation-cap mr-1"></i>{{ $kelas->mataKuliah->sks ?? '-' }} SKS</span>
                        <span><i class="fas fa-users mr-1"></i>{{ $rekapRows->count() }} mahasiswa</span>
                        <span><i class="fas fa-clipboard-list mr-1"></i>{{ $tugasList->count() }} tugas</span>
                    </div>
                </div>
                <a href="{{ route('dosen.nilai-tugas.index', $kelas->id) }}"
                   class="shrink-0 inline-flex items-center gap-2 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left"></i> Daftar Tugas
                </a>
            </div>
        </div>

        {{-- Filter & Sort --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <form method="GET" action="{{ route('dosen.nilai-tugas.rekap', $kelas->id) }}"
                  class="flex flex-wrap items-center gap-3">
                {{-- Sort --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Urutkan</label>
                    <select name="sort" onchange="this.form.submit()"
                            class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none bg-white">
                        <option value="nama" {{ request('sort','nama') === 'nama' ? 'selected' : '' }}>Nama A–Z</option>
                        <option value="total_desc" {{ request('sort') === 'total_desc' ? 'selected' : '' }}>Total Tertinggi</option>
                        <option value="total_asc" {{ request('sort') === 'total_asc' ? 'selected' : '' }}>Total Terendah</option>
                    </select>
                </div>
                {{-- Export buttons --}}
                <div class="ml-auto flex gap-2 flex-wrap">
                    <a href="{{ route('dosen.nilai-tugas.export', $kelas->id) }}?format=csv&sort={{ request('sort','nama') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 transition shadow-sm">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </a>
                    <a href="{{ route('dosen.nilai-tugas.export', $kelas->id) }}?format=xlsx&sort={{ request('sort','nama') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-sm">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </form>
        </div>

        {{-- Statistik ringkas --}}
        @if($rekapRows->count() > 0 && $tugasList->count() > 0)
            @php
                $totalFilled   = $rekapRows->filter(fn($r) => $r['avg'] !== null)->count();
                $avgKelass     = $rekapRows->filter(fn($r) => $r['avg'] !== null)->avg('avg');
                $maxTotal      = $rekapRows->max('total');
                $minTotal      = $rekapRows->where('total', '>', 0)->min('total');
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Mahasiswa Aktif</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $rekapRows->count() }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Rata-rata Kelas</p>
                    <p class="text-3xl font-extrabold text-red-700 mt-1">{{ $avgKelass ? number_format($avgKelass, 1) : '—' }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Total Tertinggi</p>
                    <p class="text-3xl font-extrabold text-green-700 mt-1">{{ $maxTotal ? number_format($maxTotal, 1) : '—' }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Total Terendah</p>
                    <p class="text-3xl font-extrabold text-orange-600 mt-1">{{ $minTotal ? number_format($minTotal, 1) : '—' }}</p>
                </div>
            </div>
        @endif

        {{-- Tabel Rekap --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($rekapRows->isEmpty() || $tugasList->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                    <i class="fas fa-table text-6xl opacity-15 mb-4"></i>
                    <p class="text-lg font-bold text-gray-500">Belum ada data rekap</p>
                    <p class="text-sm mt-1">Buat tugas terlebih dahulu dan input nilainya.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b-2 border-gray-200">
                                <th class="sticky left-0 bg-gray-50 z-10 text-left px-4 py-4 font-extrabold text-gray-600 text-xs uppercase tracking-wider w-10">#</th>
                                <th class="sticky left-10 bg-gray-50 z-10 text-left px-4 py-4 font-extrabold text-gray-600 text-xs uppercase tracking-wider min-w-[200px]">Mahasiswa</th>
                                {{-- Kolom per tugas --}}
                                @foreach($tugasList as $t)
                                    <th class="text-center px-3 py-4 font-bold text-gray-600 text-xs tracking-wider w-24">
                                        <span class="block text-red-600 font-extrabold">P{{ $t->pertemuan }}</span>
                                        <span class="block font-medium text-gray-500 mt-0.5 truncate max-w-[80px]" title="{{ $t->title }}">
                                            {{ Str::limit($t->title, 12) }}
                                        </span>
                                        <span class="block text-gray-400 text-[11px]">/ {{ $t->max_score }}</span>
                                    </th>
                                @endforeach
                                {{-- Summary cols --}}
                                <th class="text-center px-4 py-4 font-extrabold text-gray-700 text-xs uppercase tracking-wider w-24 border-l border-gray-200">Total</th>
                                <th class="text-center px-4 py-4 font-extrabold text-gray-700 text-xs uppercase tracking-wider w-24">Rata-rata</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($rekapRows->values() as $idx => $row)
                                @php
                                    $m   = $row['mahasiswa'];
                                    $avg = $row['avg'];
                                    $tot = $row['total'];
                                @endphp
                                <tr class="{{ $idx % 2 === 0 ? 'bg-white' : 'bg-gray-50/40' }} hover:bg-red-50/30 transition-colors">
                                    <td class="sticky left-0 {{ $idx % 2 === 0 ? 'bg-white' : 'bg-gray-50/40' }} z-10 px-4 py-3.5 text-xs text-gray-400 font-mono">{{ $idx + 1 }}</td>
                                    <td class="sticky left-10 {{ $idx % 2 === 0 ? 'bg-white' : 'bg-gray-50/40' }} z-10 px-4 py-3.5">
                                        <p class="font-bold text-gray-900">{{ $m->user?->name ?? $m->nama ?? '-' }}</p>
                                        <p class="text-xs text-gray-400 font-mono">{{ $m->nim ?? '-' }}</p>
                                    </td>
                                    {{-- Nilai per tugas --}}
                                    @foreach($tugasList as $t)
                                        @php $n = $row['nilai'][$t->id] ?? null; @endphp
                                        <td class="text-center px-3 py-3.5">
                                            @if($n === null)
                                                <span class="text-gray-300 font-bold">—</span>
                                            @else
                                                <span class="font-extrabold {{ $n >= ($t->max_score * 0.75) ? 'text-green-600' : ($n >= ($t->max_score * 0.5) ? 'text-amber-600' : 'text-red-600') }}">
                                                    {{ number_format($n, 0) }}
                                                </span>
                                            @endif
                                        </td>
                                    @endforeach
                                    {{-- Total --}}
                                    <td class="text-center px-4 py-3.5 border-l border-gray-200">
                                        <span class="font-extrabold text-gray-900 text-base">{{ $tot > 0 ? number_format($tot, 1) : '—' }}</span>
                                    </td>
                                    {{-- Rata-rata --}}
                                    <td class="text-center px-4 py-3.5">
                                        @if($avg !== null)
                                            @php
                                                $maxAvgPossible = $tugasList->avg('max_score');
                                                $pct = $maxAvgPossible > 0 ? ($avg / $maxAvgPossible * 100) : 0;
                                            @endphp
                                            <span class="font-extrabold {{ $pct >= 75 ? 'text-green-600' : ($pct >= 50 ? 'text-amber-600' : 'text-red-600') }}">
                                                {{ number_format($avg, 1) }}
                                            </span>
                                        @else
                                            <span class="text-gray-300 font-bold">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        {{-- Footer rata-rata kolom --}}
                        <tfoot class="border-t-2 border-gray-200 bg-gray-100">
                            <tr>
                                <td class="sticky left-0 bg-gray-100 z-10 px-4 py-3.5 text-xs font-extrabold text-gray-600 uppercase" colspan="2">
                                    Rata-rata Kelas
                                </td>
                                @foreach($tugasList as $t)
                                    @php
                                        $colAvg = $rekapRows->map(fn($r) => $r['nilai'][$t->id] ?? null)->filter()->avg();
                                    @endphp
                                    <td class="text-center px-3 py-3.5 text-xs font-extrabold text-gray-700">
                                        {{ $colAvg !== null ? number_format($colAvg, 1) : '—' }}
                                    </td>
                                @endforeach
                                <td class="text-center px-4 py-3.5 text-xs font-extrabold text-gray-700 border-l border-gray-200">
                                    {{ $rekapRows->avg('total') ? number_format($rekapRows->avg('total'), 1) : '—' }}
                                </td>
                                <td class="text-center px-4 py-3.5 text-xs font-extrabold text-gray-700">
                                    {{ $rekapRows->filter(fn($r) => $r['avg'] !== null)->avg('avg') ? number_format($rekapRows->filter(fn($r) => $r['avg'] !== null)->avg('avg'), 1) : '—' }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Keterangan warna --}}
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap gap-4 text-xs text-gray-500">
                    <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span> ≥ 75% dari max</div>
                    <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span> 50–74%</div>
                    <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span> &lt; 50%</div>
                    <div class="flex items-center gap-1.5"><span class="text-gray-300 font-bold">—</span> Belum dinilai</div>
                </div>
            @endif
        </div>

    </div>
@endsection
