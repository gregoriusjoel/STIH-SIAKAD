@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@push('styles')
    <style>
        .dashboard-hero {
            background: linear-gradient(135deg, #5f0a1f 0%, #800020 60%, #931b2e 100%);
        }

        .dashboard-soft-glow {
            background-image:
                radial-gradient(circle at 18% 25%, rgba(255, 255, 255, 0.06) 0%, transparent 42%),
                radial-gradient(circle at 78% 12%, rgba(255, 255, 255, 0.05) 0%, transparent 36%);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    @php
        $todayLabel = \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y');
        $requestedMonth = request()->query('month');
        try {
            $selectedMonth = $requestedMonth
                ? \Carbon\Carbon::createFromFormat('Y-m', $requestedMonth)->startOfMonth()
                : now()->startOfMonth();
        } catch (\Throwable $e) {
            $selectedMonth = now()->startOfMonth();
        }
        $prevMonthKey = $selectedMonth->copy()->subMonth()->format('Y-m');
        $nextMonthKey = $selectedMonth->copy()->addMonth()->format('Y-m');
        $prevMonthUrl = route('admin.dashboard', array_merge(request()->query(), ['month' => $prevMonthKey]));
        $nextMonthUrl = route('admin.dashboard', array_merge(request()->query(), ['month' => $nextMonthKey]));
        $calendarActivePeriods = $calendar_active_periods ?? collect();
    @endphp

    <!-- Header Section -->
    <div class="dashboard-hero dashboard-soft-glow relative overflow-hidden rounded-2xl mb-6 p-5 md:p-6 text-white shadow-xl"
        style="background-color:#800020;">
        <div class="absolute -top-14 -right-14 w-44 h-44 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-16 -left-12 w-48 h-48 bg-black/20 rounded-full blur-2xl"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-[11px] uppercase tracking-[0.2em] font-bold text-white/85">Panel Administrasi</p>
                <h2 class="text-2xl font-extrabold mt-1">Dashboard Admin</h2>
                <p class="text-sm text-white/85 mt-1">{{ $todayLabel }} - Ringkasan cepat kondisi akademik STIH</p>
            </div>
        </div>
    </div>

    <!-- Statistik Cards - 3 Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Group 1: Mahasiswa & Orang Tua -->
        <div
            class="bg-[#eef2ff] dark:bg-blue-900/20 rounded-xl p-4 border border-blue-100 dark:border-blue-800/50 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-full -mr-16 -mt-16"></div>
            <div class="flex items-center gap-3 mb-4 relative z-10">
                <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center shadow-sm">
                    <i class="fas fa-hourglass-half text-white text-sm"></i>
                </div>
                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 whitespace-nowrap">Data Mahasiswa</h3>
            </div>

            <div class="grid grid-cols-2 gap-3 relative z-10">
                <!-- Mahasiswa Card -->
                <a href="{{ route('admin.mahasiswa.index') }}" class="group block">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow h-full flex flex-col justify-between border border-transparent hover:border-blue-200 dark:hover:border-blue-700">
                        <div>
                            <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center mb-3">
                                <i class="fas fa-user-graduate text-white text-sm"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-[11px] font-semibold mb-1">Mahasiswa</p>
                            <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $total_mahasiswa }}</h4>
                        </div>
                        <p class="text-blue-600 dark:text-blue-400 text-xs font-bold flex items-center">
                            Detail <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                        </p>
                    </div>
                </a>

                <!-- Orang Tua Card -->
                <a href="{{ route('admin.parents.index') }}" class="group block">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow h-full flex flex-col justify-between border border-transparent hover:border-orange-200 dark:hover:border-orange-700">
                        <div>
                            <div class="w-8 h-8 rounded-lg bg-[#f97316] flex items-center justify-center mb-3">
                                <i class="fas fa-users text-white text-sm"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-[11px] font-semibold mb-1">Orang Tua</p>
                            <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $total_parent }}</h4>
                        </div>
                        <p class="text-[#f97316] dark:text-orange-400 text-xs font-bold flex items-center">
                            Detail <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                        </p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Group 2: Data Akademik -->
        <div
            class="bg-[#ecfdf5] dark:bg-green-900/20 rounded-xl p-4 border border-green-100 dark:border-green-800/50 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/5 rounded-full -mr-16 -mt-16"></div>
            <div class="flex items-center gap-3 mb-4 relative z-10">
                <div class="w-8 h-8 rounded-lg bg-[#10b981] flex items-center justify-center shadow-sm">
                    <i class="fas fa-chalkboard-teacher text-white text-sm"></i>
                </div>
                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 whitespace-nowrap">Data Akademik</h3>
            </div>

            <div class="grid grid-cols-2 gap-3 relative z-10">
                <!-- Dosen Card -->
                <a href="{{ route('admin.dosen.index') }}" class="group block">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow h-full flex flex-col justify-between border border-transparent hover:border-green-200 dark:hover:border-green-700">
                        <div>
                            <div class="w-8 h-8 rounded-lg bg-[#10b981] flex items-center justify-center mb-3">
                                <i class="fas fa-chalkboard-teacher text-white text-sm"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-[11px] font-semibold mb-1">Dosen</p>
                            <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $total_dosen }}</h4>
                        </div>
                        <p class="text-[#10b981] dark:text-green-400 text-xs font-bold flex items-center">
                            Detail <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                        </p>
                    </div>
                </a>

                <!-- Mata Kuliah Card -->
                <a href="{{ route('admin.mata-kuliah.index') }}" class="group block">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow h-full flex flex-col justify-between border border-transparent hover:border-purple-200 dark:hover:border-purple-700">
                        <div>
                            <div class="w-8 h-8 rounded-lg bg-[#a855f7] flex items-center justify-center mb-3">
                                <i class="fas fa-book text-white text-sm"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-[11px] font-semibold mb-1">Mata Kuliah</p>
                            <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $total_mata_kuliah }}</h4>
                        </div>
                        <p class="text-[#a855f7] dark:text-purple-400 text-xs font-bold flex items-center">
                            Detail <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                        </p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Group 3: Manajemen Kelas -->
        <div
            class="bg-[#f3e8ff] dark:bg-purple-900/20 rounded-xl p-4 border border-purple-100 dark:border-purple-800/50 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/5 rounded-full -mr-16 -mt-16"></div>
            <div class="flex items-center gap-3 mb-4 relative z-10">
                <div class="w-8 h-8 rounded-lg bg-[#6366f1] flex items-center justify-center shadow-sm">
                    <i class="fas fa-door-open text-white text-sm"></i>
                </div>
                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 whitespace-nowrap">Manajemen Kelas</h3>
            </div>

            <div class="grid grid-cols-2 gap-3 relative z-10">
                <!-- Kelas Card -->
                <a href="{{ route('admin.kelas-mata-kuliah.index') }}" class="group block">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow h-full flex flex-col justify-between border border-transparent hover:border-indigo-200 dark:hover:border-indigo-700">
                        <div>
                            <div class="w-8 h-8 rounded-lg bg-[#6366f1] flex items-center justify-center mb-3">
                                <i class="fas fa-door-open text-white text-sm"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-[11px] font-semibold mb-1">Kelas</p>
                            <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $total_kelas }}</h4>
                        </div>
                        <p class="text-[#6366f1] dark:text-indigo-400 text-xs font-bold flex items-center">
                            Detail <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                        </p>
                    </div>
                </a>

                <!-- Jadwal Perkuliahan Card -->
                <a href="{{ route('admin.jadwal.index') }}" class="group block">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow h-full flex flex-col justify-between border border-transparent hover:border-red-200 dark:hover:border-red-700">
                        <div>
                            <div class="w-8 h-8 rounded-lg bg-[#ef4444] flex items-center justify-center mb-3">
                                <i class="fas fa-calendar-alt text-white text-sm"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-[11px] font-semibold mb-1">Jadwal Perkuliahan
                            </p>
                            <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $total_jadwal }}</h4>
                        </div>
                        <p class="text-[#ef4444] dark:text-red-500 text-xs font-bold flex items-center">
                            Detail <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mt-2">
        @if($calendarActivePeriods->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-6 h-6 rounded bg-maroon flex items-center justify-center">
                        <i class="fas fa-wave-square text-white text-xs"></i>
                    </div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">Periode yang Akan Datang (Kalender Akademik)</h3>
                    <span
                        class="ml-auto text-xs font-semibold px-2.5 py-1 rounded-full bg-maroon/10 text-maroon dark:bg-red-900/30 dark:text-red-200">
                        {{ $calendarActivePeriods->count() }} periode
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-3">
                    @foreach($calendarActivePeriods as $period)
                        @php
                            $startDate = $period->start_date ? \Carbon\Carbon::parse($period->start_date) : null;
                            $endDate = $period->end_date ? \Carbon\Carbon::parse($period->end_date) : null;
                            $daysLeft = $endDate ? max(0, (int) now()->startOfDay()->diffInDays($endDate->copy()->startOfDay(), false)) : null;
                            $dateRangeText = $startDate && $endDate
                                ? $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y')
                                : 'Tanggal belum lengkap';
                        @endphp
                        <div
                            class="rounded-lg border border-maroon/20 dark:border-red-900/40 bg-maroon/[0.04] dark:bg-red-900/10 p-3">
                            <p class="text-sm font-bold text-gray-800 dark:text-gray-100 line-clamp-1" title="{{ $period->title }}">
                                {{ $period->title }}</p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1.5">
                                <i class="fas fa-clock text-maroon/70"></i>
                                <span class="truncate">{{ $dateRangeText }}</span>
                            </p>
                            <div class="mt-2 flex items-center justify-between gap-2">
                                <span
                                    class="inline-flex px-2.5 py-1 text-[10px] font-bold tracking-wide uppercase rounded-full bg-maroon/10 text-maroon dark:bg-red-900/30 dark:text-red-200 border border-maroon/20">
                                    {{ $period->type_label }}
                                </span>
                                @if($daysLeft !== null)
                                    <span class="text-[10px] font-semibold text-maroon dark:text-red-200">
                                        {{ $daysLeft }} hari lagi
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 {{ $calendarActivePeriods->count() > 0 ? '' : 'xl:col-span-2' }}">
            <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-red-50/30 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded bg-maroon dark:bg-red-900 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-xs"></i>
                    </div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">Kalender Akademik</h3>
                </div>
                <a href="{{ route('admin.kalender.index') }}"
                    class="inline-flex items-center px-3 py-1 bg-white dark:bg-gray-800 border border-maroon text-maroon dark:text-red-500 rounded-full text-xs font-semibold hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                    Lihat Semua
                    <i class="fas fa-arrow-right ml-1.5 text-[10px]"></i>
                </a>
            </div>
            <div class="p-4">
                @if($academic_events->count() > 0 || $calendarActivePeriods->count() > 0)
                    @php
                        $agendaKey = static function ($event): string {
                            return implode('|', [
                                strtolower(trim((string) ($event->title ?? $event->name ?? ''))),
                                strtolower((string) ($event->event_type ?? '')),
                                (string) ($event->start_date ?? ''),
                                (string) ($event->end_date ?? ''),
                                (string) ($event->date ?? ''),
                            ]);
                        };

                        $calendarSource = collect($academic_events)
                            ->concat($calendarActivePeriods)
                            ->filter()
                            ->unique(fn($event) => $agendaKey($event))
                            ->values();
                        $today = now()->startOfDay();
                        $anchorDate = $selectedMonth->copy();

                        $monthStart = $anchorDate->copy()->startOfMonth();
                        $monthEnd = $anchorDate->copy()->endOfMonth();
                        $gridStart = $monthStart->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
                        $gridEnd = $monthEnd->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);

                        $eventsByDate = [];
                        $monthAgendaMap = [];
                        foreach ($calendarSource as $event) {
                            $eventKey = $agendaKey($event);
                            $eventStart = $event->start_date
                                ? \Carbon\Carbon::parse($event->start_date)->startOfDay()
                                : ($event->date ? \Carbon\Carbon::parse($event->date)->startOfDay() : null);

                            $eventEnd = $event->end_date
                                ? \Carbon\Carbon::parse($event->end_date)->endOfDay()
                                : ($event->date ? \Carbon\Carbon::parse($event->date)->endOfDay() : null);

                            if (!$eventStart || !$eventEnd) {
                                continue;
                            }

                            if ($eventEnd->lt($monthStart) || $eventStart->gt($monthEnd)) {
                                continue;
                            }

                            $monthAgendaMap[$eventKey] = true;

                            $cursor = $eventStart->copy()->greaterThan($monthStart) ? $eventStart->copy() : $monthStart->copy();
                            $last = $eventEnd->copy()->lessThan($monthEnd) ? $eventEnd->copy() : $monthEnd->copy();

                            while ($cursor->lte($last)) {
                                $dateKey = $cursor->format('Y-m-d');
                                if (!isset($eventsByDate[$dateKey])) {
                                    $eventsByDate[$dateKey] = [];
                                }
                                $eventsByDate[$dateKey][$eventKey] = $event;
                                $cursor->addDay();
                            }
                        }

                        $monthAgendaCount = count($monthAgendaMap);

                        $weekDays = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                        $dayCells = [];
                        for ($day = $gridStart->copy(); $day->lte($gridEnd); $day->addDay()) {
                            $key = $day->format('Y-m-d');
                            $dayCells[] = [
                                'date' => $day->copy(),
                                'in_month' => $day->month === $monthStart->month,
                                'is_today' => $day->isSameDay($today),
                                'events' => isset($eventsByDate[$key]) ? array_values($eventsByDate[$key]) : [],
                            ];
                        }
                    @endphp

                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ $prevMonthUrl }}"
                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg border border-maroon/20 text-maroon hover:bg-maroon/10 dark:text-red-200 dark:border-red-900/40 dark:hover:bg-red-900/30"
                                title="Bulan sebelumnya">
                                <i class="fas fa-chevron-left text-[10px]"></i>
                            </a>
                            <h4 class="text-sm font-bold text-gray-800 dark:text-gray-100 min-w-[110px] text-center">
                                {{ $monthStart->translatedFormat('F Y') }}</h4>
                            <a href="{{ $nextMonthUrl }}"
                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg border border-maroon/20 text-maroon hover:bg-maroon/10 dark:text-red-200 dark:border-red-900/40 dark:hover:bg-red-900/30"
                                title="Bulan berikutnya">
                                <i class="fas fa-chevron-right text-[10px]"></i>
                            </a>
                        </div>
                        <span
                            class="text-[11px] font-semibold px-2 py-1 rounded-full bg-maroon/10 text-maroon dark:bg-red-900/30 dark:text-red-200">
                            {{ $monthAgendaCount }} agenda
                        </span>
                    </div>

                    <div class="grid grid-cols-7 gap-1 mb-1">
                        @foreach($weekDays as $dayName)
                            <div
                                class="text-[10px] font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400 text-center py-1">
                                {{ $dayName }}</div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-7 gap-1">
                        @foreach($dayCells as $cell)
                            @php
                                $eventCount = count($cell['events']);
                            @endphp
                            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false"
                                class="relative min-h-[78px] rounded-lg border p-1.5 {{ $cell['in_month'] ? 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700' : 'bg-gray-50 dark:bg-gray-900/30 border-gray-100 dark:border-gray-800 opacity-70' }} {{ $cell['is_today'] ? 'ring-1 ring-maroon/40 border-maroon/30 dark:border-red-800' : '' }}">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-[11px] font-bold {{ $cell['in_month'] ? 'text-gray-700 dark:text-gray-200' : 'text-gray-400 dark:text-gray-500' }} {{ $cell['is_today'] ? 'text-maroon dark:text-red-200' : '' }}">{{ $cell['date']->format('j') }}</span>
                                    @if($eventCount > 0)
                                        <button type="button" @click.stop="open = !open"
                                            class="inline-flex items-center gap-1 rounded-full border border-maroon/20 bg-maroon/10 px-1.5 py-0.5"
                                            title="Lihat agenda">
                                            @for($i = 0; $i < min($eventCount, 3); $i++)
                                                <span class="w-1.5 h-1.5 rounded-full bg-maroon"></span>
                                            @endfor
                                        </button>
                                    @endif
                                </div>

                                @if($eventCount > 0)
                                    <div x-show="open" x-cloak @click.outside="open = false"
                                        class="absolute left-1 top-7 z-20 w-72 rounded-lg border border-maroon/20 bg-white dark:bg-gray-800 shadow-xl p-2.5">
                                        <p class="text-[10px] font-bold text-maroon uppercase tracking-wide mb-1">
                                            {{ $cell['date']->translatedFormat('d M Y') }}</p>
                                        <div class="space-y-1.5">
                                            @foreach(collect($cell['events'])->take(3) as $eventItem)
                                                <div class="text-[10px] text-gray-700 dark:text-gray-200 leading-tight">
                                                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-maroon mr-1 align-middle"></span>
                                                    <span class="break-words">{{ $eventItem->title ?? $eventItem->name ?? 'Agenda' }}</span>
                                                </div>
                                            @endforeach
                                            @if($eventCount > 3)
                                                <p class="text-[10px] font-semibold text-gray-500 dark:text-gray-400">+{{ $eventCount - 3 }}
                                                    agenda lainnya</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div
                        class="text-center py-8 flex flex-col items-center justify-center h-full rounded-xl border border-dashed border-gray-200 dark:border-gray-600 bg-gray-50/60 dark:bg-gray-900/20">
                        <div
                            class="bg-maroon/10 dark:bg-red-900/30 rounded-full w-10 h-10 flex items-center justify-center mb-3">
                            <i class="fas fa-calendar text-maroon/60 dark:text-red-200/70"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 font-bold text-[13px] mt-1">Belum ada acara akademik</p>
                        <p class="text-gray-400 dark:text-gray-500 text-[11px] mt-0.5">Acara akan ditampilkan di sini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection