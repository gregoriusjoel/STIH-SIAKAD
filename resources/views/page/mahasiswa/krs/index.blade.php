@extends('layouts.mahasiswa')

@section('title', 'Kartu Rencana Studi (KRS)')
@section('page-title', 'Kartu Rencana Studi')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-lg p-4 sm:p-6 mb-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white mb-2">Kartu Rencana Studi (KRS)</h2>
                    <p class="text-sm sm:text-base text-gray-600 dark:text-slate-400">Semester: <span
                        class="font-semibold">{{ $semesterAktif->nama_semester ?? 'Tidak ada semester aktif' }}</span> •
                    <span class="font-semibold">Semester {{ $mahasiswaSemester ?? 1 }}</span></p>
                </div>
                <div class="text-left sm:text-right">
                    <p class="text-sm text-gray-600 dark:text-slate-400 mb-1">Status KRS:</p>
                    <span class="inline-block px-4 py-2 rounded-lg font-bold text-sm
                        @if($statusKrs === 'approved') bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                        @elseif($statusKrs === 'diajukan') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400
                        @else bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300
                        @endif">
                        {{ strtoupper($statusKrs) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Mahasiswa Profile Card --}}
        <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-lg p-4 mb-4 border border-gray-100 dark:border-slate-800">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-slate-700 overflow-hidden flex items-center justify-center">
                    @if(!empty($mahasiswa->foto))
                        <img src="{{ $mahasiswa->foto_url ?? '' }}" alt="Foto Mahasiswa" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-user text-3xl text-gray-400 dark:text-slate-500"></i>
                    @endif
                </div>
                <div>
                    <div class="text-sm text-gray-500 dark:text-slate-400 uppercase tracking-wide">Nama Mahasiswa</div>
                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $mahasiswa->nama ?? $mahasiswa->user->name ?? '-' }}</div>
                    <div class="text-sm text-gray-500 dark:text-slate-400 mt-1">NIM: <span class="font-medium text-gray-700 dark:text-slate-300">{{ $mahasiswa->nim ?? '-' }}</span></div>
                </div>
            </div>
        </div>


        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @php
            $isEditable = ($statusKrs === 'draft' || $statusKrs === null);
        @endphp

        {{-- Info Box --}}
        @if($statusKrs === 'draft' || $statusKrs === null)
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-5 mb-6">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-blue-600 text-2xl"></i>
                    <div>
                        <h4 class="font-bold text-blue-900 mb-2">Petunjuk Pengisian KRS</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Pilih <strong>Ya</strong> untuk mata kuliah yang akan Anda ambil semester ini</li>
                            <li>• Pilih <strong>Tidak</strong> untuk mata kuliah yang tidak diambil</li>
                            <li>• Maksimal beban SKS per semester: <strong>24 SKS</strong></li>
                            <li>• Klik <strong>Simpan Draft</strong> untuk menyimpan tanpa melakukan submit</li>
                            <li>• Konsultasi Terlebih dahulu Dengan <strong>Dosen PA</strong> Sebelum Mengajukan KRS</li>
                        </ul>
                    </div>
                </div>
            </div>
        @elseif($statusKrs === 'diajukan')
            <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-5 mb-6">
                <div class="flex items-start gap-3">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                    <div>
                        <h4 class="font-bold text-yellow-900 mb-2">KRS Menunggu Persetujuan</h4>
                        <p class="text-sm text-yellow-800">KRS Anda telah diajukan dan sedang menunggu persetujuan dari dosen
                            wali. Anda tidak dapat mengubah KRS selama dalam proses persetujuan.</p>
                    </div>
                </div>
            </div>
        @elseif($statusKrs === 'approved')
            <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-5 mb-6">
                <div class="flex items-start gap-3">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    <div>
                        <h4 class="font-bold text-green-900 mb-2">KRS Telah Disetujui</h4>
                        <p class="text-sm text-green-800">KRS Anda telah disetujui oleh . Anda tidak dapat mengubah KRS yang
                            sudah disetujui.</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- KRS Form --}}
        <form action="{{ route('mahasiswa.krs.store') }}" method="POST" id="krsForm">
            @csrf

            {{-- Add Mata Kuliah Section (for cross-semester courses) --}}
            <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white">Tambah Mata Kuliah Lintas Semester</h4>
                        <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                            Pilih mata kuliah tambahan yang ingin Anda ambil di luar mata kuliah wajib semester ini.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-3">
                    <select id="mataKuliahSelect"
                        class="w-full md:flex-1 px-4 py-3 border border-gray-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent text-sm bg-white dark:bg-slate-800 text-gray-800 dark:text-white"
                        @if(!$isEditable) disabled aria-disabled="true" @endif>
                        <option value="">-- Pilih Mata Kuliah Tambahan --</option>
                        @foreach($additionalMataKuliah as $mk)
                            @php
                                $alreadyTaken = $existingKrs->contains('mata_kuliah_id', $mk->id);
                            @endphp
                            @if(!$alreadyTaken)
                                <option value="{{ $mk->id }}" data-kode="{{ $mk->kode_mk }}" data-kode-id="{{ $mk->kode_id }}"
                                    data-nama="{{ $mk->nama_mk }}" data-sks="{{ $mk->sks }}" data-semester="{{ $mk->semester }}"
                                    data-jenis="{{ $mk->jenis }}" data-praktikum="{{ $mk->praktikum ?? 0 }}">
                                    [{{ $mk->kode_id }}] {{ $mk->kode_mk }} - {{ $mk->nama_mk }} ({{ $mk->sks }} SKS)
                                    @if($mk->praktikum)
                                        <span class="text-blue-600">+ Praktikum</span>
                                    @endif
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <button type="button" id="addMataKuliahBtn"
                        class="w-full md:w-auto px-6 py-3 bg-maroon text-white font-bold rounded-lg hover:bg-maroon-hover transition shadow-md hover:shadow-lg flex items-center justify-center gap-2"
                        @if(!$isEditable) disabled aria-disabled="true" class="opacity-50 cursor-not-allowed" @endif>
                        <i class="fas fa-plus"></i>
                        Tambah
                    </button>
                </div>
            </div>


            <div class="mt-4 mb-6">
                <div class="bg-white dark:bg-[#1a1d2e] rounded-lg border border-gray-200 dark:border-slate-700 p-4 flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-white">Ambil Semua Mata Kuliah (Semester Ini)</h4>
                        <p class="text-xs text-gray-500 dark:text-slate-400">Klik untuk memilih semua mata kuliah saat ini sekaligus.</p>
                    </div>
                    <div>
                        <button id="ambilSemuaBtn" type="button"
                            class="px-4 py-2 bg-maroon text-white rounded-lg hover:bg-maroon-hover transition shadow-sm"
                            @if(!$isEditable) disabled aria-disabled="true" class="opacity-50 cursor-not-allowed"
                            @endif>Ambil Semua</button>
                    </div>
                </div>
            </div>




            {{-- Calendar modal (hidden). Calendar view moved into modal; open with calendar button in table header. --}}

            @if($calendarKelas->isNotEmpty())
                <div id="krsCalendarModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6"
                    aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" id="krsCalendarBackdrop">
                    </div>
                    <div class="relative w-full max-w-6xl bg-white dark:bg-[#1a1d2e] rounded-2xl md:rounded-3xl shadow-2xl flex flex-col h-[85vh] overflow-hidden transform transition-all border border-gray-100/50">
                        {{-- Header --}}
                        <div class="flex items-center justify-between px-5 md:px-6 py-4 bg-gradient-to-r from-maroon to-[#8B1538] text-white z-20 shrink-0 border-b border-[#5C0A22]/20">
                            <div>
                                <h4 class="text-xl font-bold tracking-tight">Kalender Jadwal</h4>
                                <p class="text-[11px] md:text-sm text-white/80 mt-0.5 font-medium">Semester {{ $semesterAktif->nama_semester ?? 'Aktif' }}</p>
                            </div>
                            <button type="button" id="closeKrsCalendar" class="group p-2 rounded-full hover:bg-white/10 transition-colors focus:outline-none">
                                <div class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 group-hover:bg-white/20 transition">
                                    <i class="fas fa-times text-white"></i>
                                </div>
                            </button>
                        </div>

                        {{-- Content Wrapper: Stacked on mobile, Flex Row on XL screens --}}
                        <div class="flex flex-col xl:flex-row flex-1 overflow-y-auto xl:overflow-hidden min-h-0 custom-scrollbar">

                            {{-- LEFT COLUMN: Calendar (Top on mobile) --}}
                            <div class="flex-none xl:flex-1 w-full xl:w-auto h-[55vh] min-h-[400px] xl:h-full xl:min-h-0 flex flex-col min-w-0 bg-gray-50 dark:bg-[#151826] relative border-b xl:border-b-0 xl:border-r border-gray-200 dark:border-slate-700/50 order-1 xl:order-1">
                                 {{-- Scrollable Calendar Area --}}
                                 <div id="calendarScrollContainer" class="flex-1 overflow-auto custom-scrollbar relative bg-gray-50/50 dark:bg-transparent">
                                     @php
                                        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                        $hStart = 6;
                                        $hEnd = 22;
                                        $rowHeight = 55; 
                                     @endphp

                                     {{-- Min-width to ensure horizontal scroll if needed, w-full to expand on desktop --}}
                                     <div class="min-w-[750px] xl:min-w-0 xl:w-full p-4 md:p-6 pb-12 xl:pb-6">

                                     {{-- Grid Flex Wrapper --}}
                                     <div class="flex w-full">
                                         {{-- Time Axis --}}
                                         <div class="w-16 flex-shrink-0 flex flex-col pt-10 text-xs text-gray-400 font-medium text-right pr-4 select-none">
                                             @for($h = $hStart; $h <= $hEnd; $h++)
                                                 <div class="relative" style="height: {{ $rowHeight }}px">
                                                     <span class="absolute -top-3 right-0">{{ sprintf('%02d:00', $h) }}</span>
                                                 </div>
                                             @endfor
                                         </div>

                                         {{-- Grid Container --}}
                                         <div class="flex-1 w-full bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden"
                                             style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 1px; background-color: #f3f4f6;">
                                                 @foreach($days as $day)
                                                     @php
                                                        // 1. Calculate events FOR THIS DAY first
                                                        $events = [];
                                                        foreach ($calendarKelas as $kelasItem) {
                                                            if (!isset($kelasItem->jadwals))
                                                                continue;

                                                            foreach ($kelasItem->jadwals as $jadwal) {
                                                                // Robust Loose match: Check exact, or substring both ways
                                                                $dbHari = strtolower(trim($jadwal->hari));
                                                                $uiHari = strtolower($day);

                                                                if ($dbHari == $uiHari || str_contains($dbHari, $uiHari) || str_contains($uiHari, $dbHari)) {
                                                                    try {
                                                                        $start = \Carbon\Carbon::parse($jadwal->jam_mulai);
                                                                        $end = \Carbon\Carbon::parse($jadwal->jam_selesai);
                                                                        $startMinutes = $start->hour * 60 + $start->minute;
                                                                        $endMinutes = $end->hour * 60 + $end->minute;

                                                                        // Fallback length if something is wrong
                                                                        if ($endMinutes <= $startMinutes)
                                                                            $endMinutes = $startMinutes + 60;
                                                                    } catch (\Exception $e) {
                                                                        $startMinutes = ($hStart * 60);
                                                                        $endMinutes = ($hStart * 60) + 60;
                                                                        $start = \Carbon\Carbon::createFromTime($hStart, 0);
                                                                        $end = \Carbon\Carbon::createFromTime($hStart + 1, 0);
                                                                    }
                                                                    $events[] = (object) [
                                                                        'start' => $startMinutes,
                                                                        'end' => $endMinutes,
                                                                        'startObj' => $start,
                                                                        'endObj' => $end,
                                                                        'kelas' => $kelasItem
                                                                    ];
                                                                }
                                                            }
                                                        }

                                                        // 2. Sort
                                                        usort($events, function ($a, $b) {
                                                            return $a->start - $b->start;
                                                        });

                                                        // 3. Simple Column Allocation
                                                        $active = [];
                                                        $free = [];
                                                        $maxColumns = 0;
                                                        foreach ($events as $i => $ev) {
                                                            foreach ($active as $k => $act) {
                                                                if ($act['end'] <= $ev->start) {
                                                                    $free[] = $act['idx'];
                                                                    unset($active[$k]);
                                                                }
                                                            }
                                                            if (count($free) > 0)
                                                                $idx = array_shift($free);
                                                            else {
                                                                $used = array_column($active, 'idx');
                                                                $idx = 0;
                                                                while (in_array($idx, $used))
                                                                    $idx++;
                                                            }
                                                            $ev->col = $idx;
                                                            $active[] = ['end' => $ev->end, 'idx' => $idx];
                                                            $maxColumns = max($maxColumns, count($active));
                                                            $events[$i] = $ev;
                                                        }
                                                        foreach ($events as $i => $ev) {
                                                            $ev->colCount = max(1, $maxColumns);
                                                            $events[$i] = $ev;
                                                        }
                                                     @endphp

                                                     <div class="relative bg-white dark:bg-[#1a1d2e]"
                                                         style="height: {{ ($hEnd - $hStart + 1) * $rowHeight }}px">
                                                         {{-- Header --}}
                                                         <div class="h-10 bg-white/90 dark:bg-[#1a1d2e]/90 border-b border-gray-100 dark:border-slate-700 flex items-center justify-center sticky top-0 z-30 backdrop-blur-md shadow-sm">
                                                             <span class="font-bold text-gray-700 dark:text-gray-300 text-[11px] uppercase tracking-wider">{{ $day }}</span>
                                                         </div>

                                                         {{-- Grid Lines --}}
                                                         <div class="absolute inset-0 top-10 z-0 pointer-events-none">
                                                             @for($h = $hStart; $h <= $hEnd; $h++)
                                                                 <div class="border-b border-gray-50 w-full" style="height: {{ $rowHeight }}px"></div>
                                                             @endfor
                                                         </div>

                                                         {{-- Events Container --}}
                                                         <div class="relative top-0 w-full h-full z-10">
                                                             @foreach($events as $ev)
                                                                 @php
                                                                    $top = max(0, $ev->start - ($hStart * 60));
                                                                    $duration = max(30, $ev->end - $ev->start);
                                                                    $kelasItem = $ev->kelas;
                                                                    $jenis = $kelasItem->mataKuliah->jenis ?? 'wajib_prodi';
                                                                    $colors = [
                                                                        'wajib_nasional' => ['bg' => 'bg-blue-100', 'border' => 'border-blue-600', 'text' => 'text-blue-900', 'time' => 'text-blue-700'],
                                                                        'wajib_prodi' => ['bg' => 'bg-red-100', 'border' => 'border-red-600', 'text' => 'text-red-900', 'time' => 'text-red-700'],
                                                                        'pilihan' => ['bg' => 'bg-purple-100', 'border' => 'border-purple-600', 'text' => 'text-purple-900', 'time' => 'text-purple-700'],
                                                                        'peminatan' => ['bg' => 'bg-amber-100', 'border' => 'border-amber-600', 'text' => 'text-amber-900', 'time' => 'text-amber-700'],
                                                                        'default' => ['bg' => 'bg-cyan-100', 'border' => 'border-cyan-600', 'text' => 'text-cyan-900', 'time' => 'text-cyan-700'],
                                                                    ];
                                                                    $style = $colors[$jenis] ?? $colors['default'];
                                                                    $colCount = $ev->colCount;
                                                                    $leftPercent = ($ev->col / $colCount) * 100;
                                                                    $widthPercent = (1 / $colCount) * 100;
                                                                    $eventClass = "krs-event-item"; 
                                                                 @endphp
                                                                 <div class="{{ $eventClass }} box-border absolute rounded border-l-4 p-2 {{ $style['bg'] }} {{ $style['border'] }} hover:z-50 hover:scale-[1.02] cursor-pointer shadow-sm"
                                                                     style="top: {{ $top }}px; height: {{ $duration }}px; left: {{ $leftPercent }}%; width: calc({{ $widthPercent }}% - 2px); opacity: 0.95; z-index: 20;"
                                                                     title="{{ $kelasItem->mataKuliah->nama_mk ?? '' }}">
                                                                     <div class="font-bold text-[11px] {{ $style['text'] }} leading-tight truncate">
                                                                         {{ $kelasItem->mataKuliah->nama_mk ?? '-' }}
                                                                     </div>
                                                                     <div class="flex items-center gap-1 mt-1">
                                                                         <span class="text-[10px] font-bold {{ $style['time'] }}">
                                                                             {{ $ev->startObj->format('H:i') }} - {{ $ev->endObj->format('H:i') }}
                                                                         </span>
                                                                     </div>
                                                                 </div>
                                                             @endforeach
                                                         </div>
                                                     </div>
                                                 @endforeach
                                         </div>
                                     </div>
                                 </div>
                             </div>

                             {{-- Legend --}}
                             <div class="px-5 md:px-6 py-3 bg-white/80 dark:bg-[#1a1d2e]/80 backdrop-blur-sm border-t border-gray-200/60 dark:border-slate-700/50 flex flex-wrap gap-x-4 gap-y-2 text-[10px] md:text-[11px] font-semibold text-gray-600 dark:text-gray-400 shrink-0 z-10 sticky bottom-0">
                                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-500 shadow-sm"></span> Wajib Nasional</div>
                                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-sm"></span> Wajib Prodi</div>
                                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-purple-500 shadow-sm"></span> Pilihan</div>
                                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500 shadow-sm"></span> Peminatan</div>
                             </div>
                        </div>

                        {{-- RIGHT COLUMN: Table Details (Sidebar on Desktop) --}}
                        <div class="flex-none xl:flex-none w-full xl:w-[400px] flex flex-col bg-white dark:bg-[#1a1d2e] shadow-none xl:shadow-[-10px_0_20px_-10px_rgba(0,0,0,0.05)] z-20 order-2 xl:order-2 shrink-0 h-auto border-t xl:border-t-0 xl:border-l border-gray-200/80 dark:border-slate-700 relative">
                             <div class="px-5 xl:px-6 py-4 border-b border-gray-100 dark:border-slate-700/80 bg-white dark:bg-[#1a1d2e] flex justify-between items-center shrink-0">
                                <h5 class="font-bold text-gray-800 dark:text-gray-100 text-[15px] flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-maroon/10 dark:bg-rose-900/20 flex items-center justify-center">
                                        <i class="fas fa-list-ul text-maroon dark:text-rose-400 text-xs"></i>
                                    </div>
                                    Rincian Jadwal
                                </h5>
                             </div>

                             <div class="flex-none xl:flex-1 overflow-visible xl:overflow-y-auto p-0 custom-scrollbar bg-gray-50/30 dark:bg-[#151826]/30">
                                 <div class="overflow-x-auto overflow-y-hidden">
                                     <table class="w-full text-left align-middle">
                                        <thead class="bg-gray-50/80 dark:bg-slate-800/80 text-gray-500 dark:text-gray-400 font-semibold text-[11px] uppercase border-b border-gray-100 dark:border-slate-700/50 sticky top-0 z-10 backdrop-blur-md">
                                            <tr>
                                                <th class="px-5 py-3 tracking-wider">Mata Kuliah</th>
                                                <th class="px-3 py-3 text-center tracking-wider">Ruang</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100/80 dark:divide-slate-700/50">
                                            @forelse($calendarKelas->sortBy(function ($k) {
                                                    return $k->jadwals->first()->hari ?? 'Z';
                                                }) as $kelas)
                                                    @foreach($kelas->jadwals as $jadwal)
                                                        <tr class="hover:bg-white dark:hover:bg-[#1a1d2e] transition-colors duration-200 group">
                                                            <td class="px-5 py-4">
                                                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                                                    <div class="text-[10px] font-bold text-gray-700 dark:text-gray-300 {{ strtolower($jadwal->hari) == 'senin' ? 'text-blue-600 dark:text-blue-400' : (strtolower($jadwal->hari) == 'selasa' ? 'text-green-600 dark:text-green-400' : (strtolower($jadwal->hari) == 'rabu' ? 'text-purple-600 dark:text-purple-400' : (strtolower($jadwal->hari) == 'kamis' ? 'text-orange-600 dark:text-orange-400' : 'text-red-600 dark:text-red-400'))) }}">{{ $jadwal->hari }}</div>
                                                                    <div class="text-[9px] bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 border border-gray-200/50 dark:border-slate-700 px-1.5 py-0.5 rounded font-medium">
                                                                        {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                                                                    </div>
                                                                </div>
                                                                <div class="font-bold text-gray-800 dark:text-gray-100 text-[13px] leading-tight group-hover:text-maroon dark:group-hover:text-rose-400 transition-colors line-clamp-2 duration-200">{{ $kelas->mataKuliah->nama_mk ?? '-' }}</div>
                                                                <div class="flex items-center gap-2 mt-1.5 line-clamp-1">
                                                                    <span class="text-[9px] bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-100/50 dark:border-blue-800/30 px-1.5 py-0.5 rounded font-mono font-bold">{{ $kelas->mataKuliah->kode_mk ?? '-' }}</span>
                                                                    <span class="text-[10px] text-gray-400 dark:text-gray-500 font-bold">• {{ $kelas->mataKuliah->sks ?? 0 }} SKS</span>
                                                                    <span class="text-[10px] text-gray-400 dark:text-gray-500 ml-auto truncate flex items-center gap-1">
                                                                        <i class="fas fa-user-tie text-[9px] opacity-70"></i> {{ explode(',', $kelas->dosen?->nama ?? '-')[0] }}
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td class="px-3 py-4 text-center align-top pt-5">
                                                                <span class="text-[11px] font-bold text-gray-600 dark:text-gray-300">{{ $jadwal->ruangan ?? '-' }}</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="px-5 py-12 text-center">
                                                        <div class="w-12 h-12 rounded-full bg-gray-50 dark:bg-slate-800 flex items-center justify-center mx-auto mb-3">
                                                            <i class="fas fa-calendar-times text-gray-400 dark:text-gray-500 text-xl"></i>
                                                        </div>
                                                        <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Tidak ada jadwal.</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                 </div>
                             </div>
                        </div>

                        </div>
 
                    </div>
                </div>

                    {{-- Auto-scroll script: Aggressive retry + scrollIntoView --}}
                    <script>
                        document.getElementById('openKrsCalendarBtn')?.addEventListener('click', function() {
                            const scrollLogic = () => {
                                const container = document.getElementById('calendarScrollContainer');
                                if(!container) return;

                                const firstEvent = container.querySelector('.krs-event-item');
                                if(firstEvent) {
                                    // scrollIntoView is more reliable for hidden/modal elements
                                    firstEvent.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                } else {
                                    // Default scroll to 08:00 (approx 2h * 60px)
                                    container.scrollTo({ top: 120, behavior: 'smooth' });
                                }
                            };

                            // Run immediately and retry for animation delays
                            scrollLogic();
                            setTimeout(scrollLogic, 100);
                            setTimeout(scrollLogic, 300);
                            setTimeout(scrollLogic, 600);
                        });
                    </script>


            @endif

            <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-lg overflow-hidden">
                {{-- Table Header --}}
                <div class="bg-gradient-to-r from-maroon to-maroon-hover text-white px-6 py-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold">Daftar Mata Kuliah yang Diambil</h3>
                            <p class="text-xs mt-1 opacity-75">Debug: {{ $availableKelas->count() }} kelas tersedia</p>
                        </div>
                        <div>
                            <button type="button" id="openKrsCalendarBtn" title="Lihat Kalender" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 px-3 py-2 rounded-lg">
                                <i class="fas fa-calendar-alt text-white"></i>
                                <span class="text-sm font-semibold">Kalender</span>
                            </button>
                        </div>
                    </div>

                {{-- Table Content --}}
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1000px]" id="krsTable">
                        <thead class="bg-gray-100 dark:bg-slate-800 border-b-2 border-gray-200 dark:border-slate-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-bold text-gray-700 dark:text-slate-300">No</th>
                                <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Kode MK</th>
                                <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Mata Kuliah</th>
                                <th class="px-4 py-3 text-center text-sm font-bold text-gray-700">SKS</th>
                                <th class="px-4 py-3 text-center text-sm font-bold text-gray-700">Semester</th>
                                <th class="px-4 py-3 text-center text-sm font-bold text-gray-700">Jenis</th>
                                <th class="px-4 py-3 text-center text-sm font-bold text-gray-700">Dosen</th>
                                <th class="px-4 py-3 text-center text-sm font-bold text-gray-700">Jadwal</th>
                                <th class="px-4 py-3 text-center text-sm font-bold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="krsTableBody">
                            {{-- Display current semester mata kuliah first --}}
                            @foreach($currentSemesterMataKuliah as $mk)
                                @php
                                    $krs = $existingKrs->get($mk->id);
                                    $isChecked = $krs && $krs->ambil_mk === 'ya';
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition {{ $isChecked ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}" data-mk-id="{{ $mk->id }}">
                                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-slate-300">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-4 text-sm font-medium text-gray-800 dark:text-white">
                                        {{ $mk->kode_mk }}
                                        <span class="block text-xs text-gray-500 mt-1">{{ strtoupper($mk->kode_id) }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-800">
                                        {{ $mk->nama_mk }}
                                        @if($mk->praktikum)
                                            <span class="inline-block ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-full">Praktikum</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-center font-semibold text-gray-800">{{ $mk->sks }}</td>
                                    <td class="px-4 py-4 text-sm text-center text-gray-700">{{ $mk->semester }}</td>
                                    <td class="px-4 py-4 text-center">
                                        @php
                                            $jenisLabel = ucwords(str_replace('_', ' ', $mk->jenis));
                                            $jenisColors = [
                                                'wajib_nasional' => 'bg-blue-100 text-blue-800',
                                                'wajib_prodi' => 'bg-red-100 text-red-800',
                                                'pilihan' => 'bg-purple-100 text-purple-800',
                                                'peminatan' => 'bg-yellow-100 text-yellow-800',
                                            ];
                                            $colorClass = $jenisColors[$mk->jenis] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $colorClass }}">
                                            {{ $jenisLabel }}
                                        </span>
                                    </td>
                                    @php
                                        $kelas = $availableKelas->firstWhere('mata_kuliah_id', $mk->id);
                                        // Debug: uncomment to see data
                                        // dd($mk->id, $kelas, $availableKelas->pluck('mata_kuliah_id'));
                                    @endphp

                                    {{-- DOSEN --}}
                                    <td class="px-4 py-4 text-sm text-gray-700 text-center">
                                        {{ $kelas?->dosen?->nama ?? '-' }}
                                    </td>

                                    {{-- JADWAL --}}
                                    <td class="px-4 py-4 text-sm text-center text-gray-600">
                                        @if($kelas && $kelas->jadwals && $kelas->jadwals->count())
                                            @php $jadwal = $kelas->jadwals->first(); @endphp
                                            <div class="text-xs leading-tight">
                                                <div>{{ $jadwal->hari }}</div>
                                                <div>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</div>
                                                <div class="font-semibold">{{ $jadwal->ruangan }}</div>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" 
                                                   name="mata_kuliah[{{ $mk->id }}]" 
                                                   value="ya"
                                                   class="mk-checkbox w-5 h-5 text-maroon border-gray-300 rounded focus:ring-maroon"
                                                   data-sks="{{ $mk->sks }}"
                                                   {{ $isChecked ? 'checked' : '' }}
                                                   @if(!$isEditable) disabled aria-disabled="true" @endif>
                                            <span class="ml-2 text-sm text-gray-600 dark:text-slate-400">Ambil</span>
                                        </label>
                                    </td>
                                </tr>
                            @endforeach

                            {{-- Display additional taken courses (cross-semester) --}}
                            @foreach($existingKrs->where('ambil_mk', 'ya') as $krs)
                                @if($krs->mataKuliah && $krs->mataKuliah->kode_id !== $currentKodeId)
                                    @php $mk = $krs->mataKuliah; @endphp
                                    <tr class="hover:bg-gray-50 transition bg-green-50" data-mk-id="{{ $mk->id }}" data-additional="true">
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ $currentSemesterMataKuliah->count() + $loop->iteration }}</td>
                                        <td class="px-4 py-4 text-sm font-medium text-gray-800">
                                            {{ $mk->kode_mk }}
                                            <span class="block text-xs text-green-600 mt-1">{{ strtoupper($mk->kode_id) }} • Lintas Semester</span>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-800">
                                            {{ $mk->nama_mk }}
                                            @if($mk->praktikum)
                                                <span class="inline-block ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-full">Praktikum</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-sm text-center font-semibold text-gray-800">{{ $mk->sks }}</td>
                                        <td class="px-4 py-4 text-sm text-center text-gray-700">{{ $mk->semester }}</td>
                                        <td class="px-4 py-4 text-center">
                                            @php
                                                $jenisLabel = ucwords(str_replace('_', ' ', $mk->jenis));
                                                $jenisColors = [
                                                    'wajib_nasional' => 'bg-blue-100 text-blue-800',
                                                    'wajib_prodi' => 'bg-red-100 text-red-800',
                                                    'pilihan' => 'bg-purple-100 text-purple-800',
                                                    'peminatan' => 'bg-yellow-100 text-yellow-800',
                                                ];
                                                $colorClass = $jenisColors[$mk->jenis] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $colorClass }}">
                                                {{ $jenisLabel }}
                                            </span>
                                        </td>
                                        @php
                                            $kelas = $availableKelas->firstWhere('mata_kuliah_id', $mk->id);
                                        @endphp

                                        {{-- DOSEN --}}
                                        <td class="px-4 py-4 text-sm text-gray-700 text-center">
                                            {{ $kelas?->dosen?->nama ?? '-' }}
                                        </td>

                                        {{-- JADWAL --}}
                                        <td class="px-4 py-4 text-sm text-center text-gray-600">
                                            @if($kelas && $kelas->jadwals && $kelas->jadwals->count())
                                                @php $jadwal = $kelas->jadwals->first(); @endphp
                                                <div class="text-xs leading-tight">
                                                    <div>{{ $jadwal->hari }}</div>
                                                    <div>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</div>
                                                    <div class="font-semibold">{{ $jadwal->ruangan }}</div>
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <button type="button" class="remove-additional-btn px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition" data-mk-id="{{ $mk->id }}" @if(!$isEditable) disabled aria-disabled="true" @endif>
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                            <input type="hidden" name="mata_kuliah[{{ $mk->id }}]" value="ya">
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            @if($currentSemesterMataKuliah->isEmpty() && $existingKrs->where('ambil_mk', 'ya')->isEmpty())
                                <tr id="emptyRow">
                                    <td colspan="{{ $isEditable ? '9' : '8' }}" class="px-4 py-8 text-center text-gray-500 dark:text-slate-500">
                                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300 dark:text-slate-700"></i>
                                        <p>Belum ada mata kuliah tersedia untuk semester ini</p>
                                        <p class="text-sm text-gray-400 dark:text-slate-600 mt-1">Hubungi admin untuk menambahkan mata kuliah</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Summary Footer --}}
                <div class="bg-gray-50 dark:bg-slate-800 px-4 sm:px-6 py-5 border-t-2 border-gray-200 dark:border-slate-700">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div class="flex flex-wrap items-center gap-4 sm:gap-6 lg:gap-8">
                            <div>
                                <p class="text-xs sm:text-sm text-gray-600 dark:text-slate-400 mb-1">Total Mata Kuliah Diambil:</p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white" id="totalMk">0</p>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-600 dark:text-slate-400 mb-1">Total SKS:</p>
                                <p class="text-xl sm:text-2xl font-bold" id="totalSks">0</p>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-600 dark:text-slate-400 mb-1">Maksimal SKS:</p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-500">24</p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit" name="action" value="draft" class="w-full sm:w-auto px-4 sm:px-6 py-3 font-bold rounded-lg transition shadow-md hover:shadow-lg flex items-center justify-center gap-2 text-white border {{ !$isEditable ? 'bg-maroon border-maroon cursor-not-allowed opacity-50' : 'bg-maroon border-maroon hover:bg-red-800' }}" @if(!$isEditable) disabled aria-disabled="true" @endif>
                                <i class="fas {{ !$isEditable ? 'fa-lock' : 'fa-save' }}"></i>
                                <span class="text-sm sm:text-base">Simpan Draft</span>
                            </button>
                            <button type="submit" name="action" value="submit" class="w-full sm:w-auto px-4 sm:px-6 py-3 font-bold rounded-lg transition shadow-md hover:shadow-lg flex items-center justify-center gap-2 text-white {{ !$isEditable ? 'bg-maroon cursor-not-allowed opacity-50' : 'bg-maroon hover:bg-red-800' }}" id="submitBtn" @if(!$isEditable) disabled aria-disabled="true" @endif>
                                <i class="fas {{ !$isEditable ? 'fa-lock' : 'fa-paper-plane' }}"></i>
                                <span class="text-sm sm:text-base">Submit</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
@endsection

@push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
            // Ambil Semua button: toggle all current-semester checkboxes
            const ambilSemuaBtn = document.getElementById('ambilSemuaBtn');
            if (ambilSemuaBtn) {
                ambilSemuaBtn.addEventListener('click', function () {
                    const checkboxes = Array.from(document.querySelectorAll('.mk-checkbox'));
                    if (checkboxes.length === 0) return;
                    const anyUnchecked = checkboxes.some(cb => !cb.checked);
                    checkboxes.forEach(cb => {
                        cb.checked = anyUnchecked;
                        const row = cb.closest('tr');
                        if (row) {
                            if (anyUnchecked) row.classList.add('bg-blue-50'); else row.classList.remove('bg-blue-50');
                        }
                    });
                    calculateTotal();
                    ambilSemuaBtn.textContent = anyUnchecked ? 'Batal Ambil Semua' : 'Ambil Semua';
                });
            }
        function calculateTotal() {
            let totalSks = 0;
            let totalMk = 0;

            document.querySelectorAll('.mk-checkbox:checked').forEach(cb => {
                totalSks += parseInt(cb.dataset.sks) || 0;
                totalMk += 1;
            });

            document.querySelectorAll('#krsTableBody tr[data-additional="true"]').forEach(row => {
                totalSks += parseInt(row.querySelector('td:nth-child(4)')?.textContent.trim()) || 0;
                totalMk += 1;
            });

            document.getElementById('totalSks').textContent = totalSks;
            document.getElementById('totalMk').textContent = totalMk;

            const totalSksEl = document.getElementById('totalSks');
            totalSksEl.classList.remove('text-gray-800', 'text-red-600', 'text-green-600');
            if (totalSks > 24) totalSksEl.classList.add('text-red-600');
            else if (totalSks > 0) totalSksEl.classList.add('text-green-600');
            else totalSksEl.classList.add('text-gray-800');
        }

        // Checkbox handlers
        document.querySelectorAll('.mk-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                const row = this.closest('tr');
                if (this.checked) row.classList.add('bg-blue-50'); else row.classList.remove('bg-blue-50');
                calculateTotal();
            });
        });

        // Add additional mata kuliah
        document.getElementById('addMataKuliahBtn')?.addEventListener('click', function () {
            const select = document.getElementById('mataKuliahSelect');
            const opt = select.options[select.selectedIndex];
            if (!opt || !opt.value) { 
                showError('Silakan pilih mata kuliah terlebih dahulu!');
                return;
            }

            const mkId = opt.value;
            const kodeMk = opt.dataset.kode;
            const kodeId = opt.dataset.kodeId;
            const namaMk = opt.dataset.nama;
            const sks = parseInt(opt.dataset.sks) || 0;
            const semester = opt.dataset.semester || '-';
            const jenis = opt.dataset.jenis || '';
            const praktikum = opt.dataset.praktikum || 0;

            // SKS limit
            const currentSks = parseInt(document.getElementById('totalSks').textContent) || 0;
            if (currentSks + sks > 24) { 
                showError('Total SKS akan melebihi batas maksimal (24 SKS)!');
                return;
            }

            // Add row
            const tbody = document.getElementById('krsTableBody');
            const count = document.querySelectorAll('#krsTableBody tr:not(#emptyRow)').length;
            const praktikumBadge = praktikum && praktikum != '0' ? '<span class="inline-block ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-full">Praktikum</span>' : '';
            const jenisColors = {
                'wajib_nasional': 'bg-blue-100 text-blue-800',
                'wajib_prodi': 'bg-red-100 text-red-800',
                'pilihan': 'bg-purple-100 text-purple-800',
                'peminatan': 'bg-yellow-100 text-yellow-800'
            };
            const colorClass = jenisColors[jenis] || 'bg-gray-100 text-gray-800';

            const tr = document.createElement('tr');
            tr.setAttribute('data-additional', 'true');
            tr.setAttribute('data-mk-id', mkId);
            tr.className = 'hover:bg-gray-50 transition bg-green-50';
            tr.innerHTML = `
                <td class="px-4 py-4 text-sm text-gray-700">${count+1}</td>
                <td class="px-4 py-4 text-sm font-medium text-gray-800">${kodeMk}<span class="block text-xs text-green-600 mt-1">${kodeId.toUpperCase()} • Lintas Semester</span></td>
                <td class="px-4 py-4 text-sm text-gray-800">${namaMk}${praktikumBadge}</td>
                <td class="px-4 py-4 text-sm text-center font-semibold text-gray-800">${sks}</td>
                <td class="px-4 py-4 text-sm text-center text-gray-700">${semester}</td>
                <td class="px-4 py-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold ${colorClass}">${jenis.replace(/_/g,' ').replace(/\b\w/g,l=>l.toUpperCase())}</span></td>
                <td class="px-4 py-4 text-sm text-gray-700 text-center">-</td>
                <td class="px-4 py-4 text-sm text-center text-gray-600">-</td>
                <td class="px-4 py-4 text-center"><button type="button" class="remove-additional-btn px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition" data-mk-id="${mkId}"><i class="fas fa-trash"></i> Hapus</button><input type="hidden" name="mata_kuliah[${mkId}]" value="ya"></td>
            `;
            // remove emptyRow if present
            const empty = document.getElementById('emptyRow'); if (empty) empty.remove();
            tbody.appendChild(tr);
            opt.remove(); select.selectedIndex = 0;
            calculateTotal();

            tr.querySelector('.remove-additional-btn').addEventListener('click', function(){
                showConfirm(
                    'Apakah Anda yakin ingin menghapus mata kuliah ini?',
                    function() {
                        tr.remove();
                        // add back simple option
                        const sel = document.getElementById('mataKuliahSelect');
                        const option = document.createElement('option'); 
                        option.value = mkId; 
                        option.textContent = `${kodeMk} - ${namaMk} (${sks} SKS)`; 
                        sel.appendChild(option);
                        calculateTotal();
                    }
                );
            });
        });

        // Remove buttons for kelas-based rows
        document.querySelectorAll('.remove-btn').forEach(btn=> btn.addEventListener('click', function(e){
            const kelasId = this.dataset.kelasId;
            showConfirm(
                'Apakah Anda yakin ingin menghapus mata kuliah ini?',
                function() {
                    const row = document.querySelector(`tr[data-kelas-id="${kelasId}"]`);
                    if (row) row.remove();
                    calculateTotal();
                }
            );
        }));

        // Initial calculation
        calculateTotal();

        // Form submit validation
        document.getElementById('krsForm')?.addEventListener('submit', function(e){
            const totalSks = parseInt(document.getElementById('totalSks').textContent)||0;
            if (totalSks > 24) { 
                e.preventDefault();
                showError('Total SKS melebihi batas maksimal (24 SKS)!');
                return false;
            }
            if (totalSks === 0) { 
                e.preventDefault();
                showError('Anda belum memilih mata kuliah!');
                return false;
            }
            return true;
        });

        // Modal handlers for calendar
        const openKrsCalendarBtn = document.getElementById('openKrsCalendarBtn');
        const krsCalendarModal = document.getElementById('krsCalendarModal');
        const krsCalendarBackdrop = document.getElementById('krsCalendarBackdrop');
        const closeKrsCalendar = document.getElementById('closeKrsCalendar');

        if (openKrsCalendarBtn && krsCalendarModal) {
            openKrsCalendarBtn.addEventListener('click', function () {
                krsCalendarModal.classList.remove('hidden');
                krsCalendarModal.classList.add('flex');
            });
        }
        if (closeKrsCalendar && krsCalendarModal) {
            closeKrsCalendar.addEventListener('click', function () {
                krsCalendarModal.classList.add('hidden');
                krsCalendarModal.classList.remove('flex');
            });
        }
        if (krsCalendarBackdrop && krsCalendarModal) {
            krsCalendarBackdrop.addEventListener('click', function () {
                krsCalendarModal.classList.add('hidden');
                krsCalendarModal.classList.remove('flex');
            });
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && krsCalendarModal && !krsCalendarModal.classList.contains('hidden')) {
                krsCalendarModal.classList.add('hidden');
                krsCalendarModal.classList.remove('flex');
            }
        });
    });
    </script>
@endpush
