<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIAKAD STIH') - Admin Panel</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo_stih_white.png') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1"
        rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        .sidebar {
            background-color: #7a1621;
            /* maroon */
        }

        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.06);
        }

        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.08);
            border-right: 4px solid rgba(255, 255, 255, 0.12);
        }

        .btn-maroon {
            background-color: #800020;
            color: white;
        }

        .btn-maroon:hover {
            background-color: #5a0015;
        }

        .text-maroon {
            color: #800020;
        }

        .bg-maroon {
            background-color: #800020;
        }

        .border-maroon {
            border-color: #800020;
        }

        /* Header on maroon background adjustments */
        .header-maroon {
            background-color: #7a1621;
            color: #fff;
            box-shadow: none;
        }

        .admin-topbar-row {
            height: 4.25rem;
        }

        .header-maroon .breadcrumb {
            color: rgba(255, 255, 255, 0.9);
        }

        .header-maroon .breadcrumb .muted {
            color: rgba(255, 255, 255, 0.75);
        }

        .header-maroon .search-input {
            background: #fff;
            color: #111827;
        }

        .header-maroon .user-name {
            color: #ffffff;
        }

        /* Header tweaks */
        .top-badge {
            background: linear-gradient(90deg, #ff7b7b, #b22222);
        }

        /* Modern search suggestions UI */
        #search-suggestions {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            color: #0f172a;
            border: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow:
                0 10px 25px -5px rgba(0, 0, 0, 0.08),
                0 4px 10px -2px rgba(0, 0, 0, 0.05);
            border-radius: 14px;
            overflow: hidden;
            animation: fadeInScale 0.15s ease-out;
        }

        /* Entry item */
        #search-suggestions a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            color: #0f172a;
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.15s ease;
        }

        /* Hover effect */
        #search-suggestions a:hover {
            background: rgba(128, 0, 32, 0.08);
            transform: translateX(2px);
        }

        /* Active (keyboard selection) */
        #search-suggestions a[aria-selected="true"],
        #search-suggestions a.bg-gray-100 {
            background: rgba(128, 0, 32, 0.12);
            box-shadow: inset 3px 0 0 #800020;
        }

        /* Section headers (Fitur, Users, dll) */
        #search-suggestions .border-b,
        #search-suggestions .border-t {
            background: linear-gradient(to right, #f8fafc, #f1f5f9);
            color: #334155;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            padding: 8px 14px;
        }

        /* Badge text (Buka / Lihat) */
        #search-suggestions .text-gray-500 {
            color: #64748b !important;
            font-size: 0.7rem;
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 999px;
        }

        /* Empty result */
        #search-suggestions .no-results {
            padding: 12px 14px;
            color: #94a3b8;
            font-style: italic;
        }

        /* Smooth animation */
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: translateY(-4px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes bell-ring {

            0%,
            100% {
                transform: rotate(0);
            }

            20% {
                transform: rotate(15deg);
            }

            40% {
                transform: rotate(-15deg);
            }

            60% {
                transform: rotate(10deg);
            }

            80% {
                transform: rotate(-10deg);
            }
        }

        .group-hover\:animate-bell-ring:hover,
        .group:hover .group-hover\:animate-bell-ring {
            animation: bell-ring 0.6s ease-in-out;
            transform-origin: top center;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }
    </style>


    @stack('styles')
</head>

<body class="bg-gray-100 overflow-hidden">
    @php
        $adminNotifItems = collect();
        $safeHumanTime = function ($when): string {
            try {
                return \Carbon\Carbon::parse($when)->diffForHumans();
            } catch (\Throwable $e) {
                return '-';
            }
        };

        $pushNotif = function (array $item) use (&$adminNotifItems, $safeHumanTime): void {
            $timestamp = (int) ($item['created_at_ts'] ?? 0);
            if ($timestamp <= 0) {
                $timestamp = now()->timestamp;
            }

            $adminNotifItems->push([
                'id' => $item['id'] ?? ('notif-' . uniqid()),
                'title' => $item['title'] ?? 'Notifikasi',
                'message' => $item['message'] ?? '-',
                'icon' => $item['icon'] ?? 'notifications',
                'url' => $item['url'] ?? route('admin.dashboard'),
                'human_time' => $item['human_time'] ?? $safeHumanTime($timestamp),
                'created_at_ts' => $timestamp,
                'needs_action' => (bool) ($item['needs_action'] ?? true),
            ]);
        };

        try {
            // 1) Pengajuan surat mahasiswa yang menunggu review admin
            $submittedPengajuan = \App\Models\Pengajuan::with('mahasiswa.user')
                ->where('status', \App\Models\Pengajuan::STATUS_SUBMITTED)
                ->orderByDesc('submitted_at')
                ->orderByDesc('updated_at')
                ->limit(12)
                ->get();

            foreach ($submittedPengajuan as $pengajuan) {
                $when = $pengajuan->submitted_at ?? $pengajuan->updated_at ?? $pengajuan->created_at ?? now();
                $mahasiswaName = $pengajuan->mahasiswa?->user?->name ?? 'Mahasiswa';
                $jenis = $pengajuan->jenis_label ?? strtoupper(str_replace('_', ' ', (string) $pengajuan->jenis));

                $pushNotif([
                    'id' => 'pengajuan-' . $pengajuan->id . '-' . \Carbon\Carbon::parse($when)->timestamp,
                    'title' => 'Pengajuan Surat',
                    'message' => $mahasiswaName . ' mengajukan ' . $jenis . ' untuk direview.',
                    'icon' => 'description',
                    'url' => route('admin.pengajuan.show', $pengajuan),
                    'human_time' => $safeHumanTime($when),
                    'created_at_ts' => \Carbon\Carbon::parse($when)->timestamp,
                    'needs_action' => true,
                ]);
            }
        } catch (\Throwable $e) {
            // no-op: keep header resilient
        }

        try {
            // 2) KRS pending approval (grouped per mahasiswa)
            // Note: KRS now automatically set to 'KRS sudah di isi' status without requiring approval
            // This section kept for backward compatibility with any legacy pending KRS entries
            $krsPendingGrouped = \App\Models\Krs::with('mahasiswa.user')
                ->where('status', 'pending')
                ->orderByDesc('updated_at')
                ->limit(200)
                ->get()
                ->groupBy('mahasiswa_id')
                ->take(10);

            foreach ($krsPendingGrouped as $mahasiswaId => $rows) {
                $latest = $rows->sortByDesc('updated_at')->first();
                $when = $latest?->updated_at ?? $latest?->created_at ?? now();
                $mahasiswaName = $latest?->mahasiswa?->user?->name ?? 'Mahasiswa';

                $pushNotif([
                    'id' => 'krs-pending-' . $mahasiswaId . '-' . \Carbon\Carbon::parse($when)->timestamp,
                    'title' => 'KRS Menunggu Persetujuan (Legacy)',
                    'message' => $mahasiswaName . ' memiliki ' . $rows->count() . ' mata kuliah KRS yang menunggu review.',
                    'icon' => 'assignment_turned_in',
                    'url' => route('admin.krs.index', ['status' => 'pending']),
                    'human_time' => $safeHumanTime($when),
                    'created_at_ts' => \Carbon\Carbon::parse($when)->timestamp,
                    'needs_action' => true,
                ]);
            }
        } catch (\Throwable $e) {
            // no-op: keep header resilient
        }

        try {
            // 3) Proposal skripsi yang perlu review admin
            $skripsiProposalSubmitted = \App\Models\SkripsiSubmission::with('mahasiswa.user')
                ->where('status', \App\Domain\Skripsi\Enums\SkripsiStatus::PROPOSAL_SUBMITTED)
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get();

            foreach ($skripsiProposalSubmitted as $skripsi) {
                $when = $skripsi->updated_at ?? $skripsi->created_at ?? now();
                $mahasiswaName = $skripsi->mahasiswa?->user?->name ?? 'Mahasiswa';

                $pushNotif([
                    'id' => 'skripsi-proposal-' . $skripsi->id . '-' . \Carbon\Carbon::parse($when)->timestamp,
                    'title' => 'Proposal Skripsi',
                    'message' => $mahasiswaName . ' mengirim proposal skripsi dan menunggu review admin.',
                    'icon' => 'fact_check',
                    'url' => route('admin.skripsi.index', ['tab' => 'proposal']),
                    'human_time' => $safeHumanTime($when),
                    'created_at_ts' => \Carbon\Carbon::parse($when)->timestamp,
                    'needs_action' => true,
                ]);
            }
        } catch (\Throwable $e) {
            // no-op: keep header resilient
        }

        try {
            // 4) Pendaftaran sidang (submitted/verified) untuk tindakan verifikasi/penjadwalan
            // Notifikasi tetap ditampilkan, tapi needs_action berubah otomatis berdasarkan status
            $sidangRegs = \App\Models\SkripsiSidangRegistration::with(['submission.mahasiswa.user'])
                ->whereIn('status', ['submitted', 'verified', 'SUBMITTED', 'VERIFIED'])
                ->orderByDesc('submitted_at')
                ->orderByDesc('updated_at')
                ->limit(12)
                ->get();

            $sidangItems = $sidangRegs->map(function ($reg) use ($safeHumanTime) {
                $mahasiswaName = $reg->submission?->mahasiswa?->user?->name ?? 'Mahasiswa';
                $when = $reg->submitted_at ?? $reg->updated_at ?? $reg->created_at ?? now();
                $status = strtolower((string) $reg->status);
                $statusText = $status === 'verified'
                    ? 'terverifikasi dan menunggu penjadwalan sidang'
                    : 'mengajukan pendaftaran sidang';
                // Hanya needs_action=true jika status masih 'submitted', false jika sudah 'verified'
                $needsAction = $status === 'submitted';

                return [
                    'id' => 'sidang-' . $reg->id . '-' . \Carbon\Carbon::parse($when)->timestamp,
                    'title' => 'Pendaftaran Sidang',
                    'message' => $mahasiswaName . ' ' . $statusText . '.',
                    'icon' => 'gavel',
                    'url' => route('admin.skripsi.index', ['tab' => 'sidang']),
                    'human_time' => $safeHumanTime($when),
                    'created_at_ts' => \Carbon\Carbon::parse($when)->timestamp,
                    'needs_action' => $needsAction,
                ];
            });

            $adminNotifItems = $adminNotifItems->concat($sidangItems);
        } catch (\Throwable $e) {
            // no-op: keep header resilient
        }

        try {
            // 5) Pengajuan jadwal yang menunggu keputusan admin
            $jadwalPendingAdmin = \App\Models\JadwalProposal::with(['dosen.user', 'mataKuliah'])
                ->where('status', 'pending_admin')
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get();

            foreach ($jadwalPendingAdmin as $proposal) {
                $when = $proposal->updated_at ?? $proposal->created_at ?? now();
                $dosenName = $proposal->dosen?->user?->name ?? 'Dosen';
                $mkName = $proposal->mataKuliah?->nama_mk ?? 'Mata kuliah';

                $pushNotif([
                    'id' => 'jadwal-proposal-' . $proposal->id . '-' . \Carbon\Carbon::parse($when)->timestamp,
                    'title' => 'Persetujuan Jadwal',
                    'message' => 'Pengajuan jadwal ' . $mkName . ' dari ' . $dosenName . ' menunggu keputusan admin.',
                    'icon' => 'event_note',
                    'url' => route('admin.jadwal_admin_approval.index'),
                    'human_time' => $safeHumanTime($when),
                    'created_at_ts' => \Carbon\Carbon::parse($when)->timestamp,
                    'needs_action' => true,
                ]);
            }
        } catch (\Throwable $e) {
            // no-op: keep header resilient
        }

        try {
            // 6) Reschedule jadwal/kelas yang menunggu approval admin
            $jadwalReschedules = \App\Models\JadwalReschedule::with(['jadwal.kelas.mataKuliah', 'dosen'])
                ->where('status', 'pending')
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get();

            foreach ($jadwalReschedules as $reschedule) {
                $when = $reschedule->updated_at ?? $reschedule->created_at ?? now();
                $dosenName = $reschedule->dosen?->name ?? 'Dosen';
                $mkName = $reschedule->jadwal?->kelas?->mataKuliah?->nama_mk ?? 'Mata kuliah';

                $pushNotif([
                    'id' => 'jadwal-reschedule-' . $reschedule->id . '-' . \Carbon\Carbon::parse($when)->timestamp,
                    'title' => 'Reschedule Jadwal',
                    'message' => $dosenName . ' mengajukan reschedule untuk ' . $mkName . '.',
                    'icon' => 'edit_calendar',
                    'url' => route('admin.jadwal.reschedules'),
                    'human_time' => $safeHumanTime($when),
                    'created_at_ts' => \Carbon\Carbon::parse($when)->timestamp,
                    'needs_action' => true,
                ]);
            }

            $kelasReschedules = \App\Models\KelasReschedule::with(['kelasMataKuliah.mataKuliah', 'dosen.user'])
                ->where('status', 'pending')
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get();

            foreach ($kelasReschedules as $reschedule) {
                $when = $reschedule->updated_at ?? $reschedule->created_at ?? now();
                $dosenName = $reschedule->dosen?->user?->name ?? 'Dosen';
                $mkName = $reschedule->kelasMataKuliah?->mataKuliah?->nama_mk ?? 'Mata kuliah';

                $pushNotif([
                    'id' => 'kelas-reschedule-' . $reschedule->id . '-' . \Carbon\Carbon::parse($when)->timestamp,
                    'title' => 'Reschedule Kelas',
                    'message' => $dosenName . ' mengajukan reschedule kelas untuk ' . $mkName . '.',
                    'icon' => 'calendar_month',
                    'url' => route('admin.jadwal.index'),
                    'human_time' => $safeHumanTime($when),
                    'created_at_ts' => \Carbon\Carbon::parse($when)->timestamp,
                    'needs_action' => true,
                ]);
            }
        } catch (\Throwable $e) {
            // no-op: keep header resilient
        }

        try {
            // 7) Magang yang perlu proses admin
            $internshipsNeedReview = \App\Models\Internship::with('mahasiswa.user')
                ->whereIn('status', [
                    \App\Models\Internship::STATUS_SUBMITTED,
                    \App\Models\Internship::STATUS_REQUEST_LETTER_UPLOADED,
                    \App\Models\Internship::STATUS_UNDER_REVIEW,
                ])
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get();

            foreach ($internshipsNeedReview as $internship) {
                $when = $internship->updated_at ?? $internship->created_at ?? now();
                $mahasiswaName = $internship->mahasiswa?->user?->name ?? 'Mahasiswa';

                $pushNotif([
                    'id' => 'magang-' . $internship->id . '-' . \Carbon\Carbon::parse($when)->timestamp,
                    'title' => 'Pengajuan Magang',
                    'message' => $mahasiswaName . ' memiliki proses magang dengan status "' . $internship->status_label . '" yang membutuhkan tindakan admin.',
                    'icon' => 'work_history',
                    'url' => route('admin.magang.show', $internship),
                    'human_time' => $safeHumanTime($when),
                    'created_at_ts' => \Carbon\Carbon::parse($when)->timestamp,
                    'needs_action' => true,
                ]);
            }
        } catch (\Throwable $e) {
            // no-op: keep header resilient
        }

        try {
            // 8) Permintaan cek ketersediaan dari dosen
            $availabilityChecks = \App\Models\DosenAvailabilityCheck::with(['dosen.user', 'mataKuliah'])
                ->orderByDesc('created_at')
                ->limit(8)
                ->get();

            foreach ($availabilityChecks as $check) {
                $when = $check->created_at ?? now();
                $dosenName = $check->dosen?->user?->name ?? 'Dosen';
                $mkName = $check->mataKuliah?->nama_mk ?? 'mata kuliah';

                $pushNotif([
                    'id' => 'availability-check-' . $check->id,
                    'title' => 'Permintaan Cek Ketersediaan',
                    'message' => $dosenName . ' meminta cek ketersediaan jadwal untuk ' . $mkName . '.',
                    'icon' => 'schedule_send',
                    'url' => route('admin.jadwal.index'),
                    'human_time' => $safeHumanTime($when),
                    'created_at_ts' => \Carbon\Carbon::parse($when)->timestamp,
                    'needs_action' => true,
                ]);
            }
        } catch (\Throwable $e) {
            // no-op: keep header resilient
        }

        $adminNotifItems = $adminNotifItems
            ->unique('id')
            ->sortByDesc(fn($item) => (int) ($item['created_at_ts'] ?? 0))
            ->take(20)
            ->values();

        $latestAdminNotifIds = $adminNotifItems->pluck('id')->values()->all();
    @endphp

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay"
        class="fixed inset-0 bg-black/50 z-30 hidden md:hidden transition-opacity duration-300 opacity-0"></div>

    <div class="flex h-screen overflow-hidden relative">
        <!-- Sidebar -->
        @include('admin.sidebar-admin')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden w-full">
            <!-- Top Navbar -->
            <header class="header-maroon relative z-40 overflow-visible">
                <div class="admin-topbar-row flex items-center justify-between px-6">
                    <div class="flex items-center gap-4 min-h-11">
                        <button class="text-white md:hidden focus:outline-none" id="sidebar-toggle">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <nav class="text-sm breadcrumb flex items-center leading-none">
                            <span class="mr-2 muted">Home</span>
                            <i class="fas fa-chevron-right text-xs mr-2"></i>
                            <span class="font-semibold">@yield('page-title', 'Dashboard')</span>
                        </nav>
                        <div class="ml-4 flex items-center">
                            @php
                                $activeSemester = null;
                                try {
                                    $activeSemester = \App\Models\Semester::where('status', 'aktif')->latest()->first();
                                } catch (\Throwable $e) {
                                    $activeSemester = null;
                                }
                            @endphp
                            @if($activeSemester)
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-xs font-medium text-white top-badge">{{ $activeSemester->nama_semester }}
                                    {{ $activeSemester->tahun_ajaran }}</span>
                            @else
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-xs font-medium text-white top-badge">Semester
                                    Belum Ditetapkan</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 px-6">
                        <div class="relative w-full max-w-4xl group">
                            <form action="{{ route('admin.search') }}" method="GET" class="relative"
                                id="header-search-form" data-search-url="{{ route('admin.search') }}">
                                <div class="relative">
                                    <input id="header-search-input" name="q" value="{{ request('q') }}" type="text"
                                        placeholder="Cari data..." aria-label="Cari data" autocomplete="off"
                                        class="w-full h-11 border rounded-full px-4 pl-10 focus:ring-2 focus:ring-maroon focus:border-transparent search-input" />
                                    <button type="submit" class="absolute left-0 top-0 h-full pl-3 pr-2 text-gray-600">
                                        <i class="fas fa-search"></i>
                                    </button>

                                    <!-- Suggestions dropdown -->
                                    <div id="search-suggestions"
                                        class="hidden absolute left-0 right-0 mt-2 bg-white rounded shadow-lg z-50 text-sm overflow-hidden">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 min-h-11">
                        <div class="relative" x-data="{
                            open: false,
                            readIds: JSON.parse(localStorage.getItem('read_admin_notification_ids') || '[]'),
                            latestIds: {{ json_encode($latestAdminNotifIds ?? []) }},
                            unreadCount: 0,
                            hasUnread: false,
                            updateUnreadState() {
                                this.unreadCount = this.latestIds.filter(id => !this.readIds.includes(id)).length;
                                this.hasUnread = this.unreadCount > 0;
                            },
                            init() {
                                this.updateUnreadState();
                            },
                            markAsRead(id) {
                                if (!this.readIds.includes(id)) {
                                    this.readIds.push(id);
                                    localStorage.setItem('read_admin_notification_ids', JSON.stringify(this.readIds));
                                    this.updateUnreadState();
                                }
                            }
                        }" @click.away="open = false">
                            <button @click="open = !open"
                                class="group flex items-center justify-center rounded-2xl hover:bg-white/10 text-white relative w-10 h-10 transition-all duration-300 hover:scale-105"
                                :class="{ 'bg-white/20 text-white': open }">
                                <span
                                    class="material-symbols-outlined text-2xl !text-white transition-colors group-hover:animate-bell-ring"
                                    :class="{ 'fill-current': open || hasUnread }">notifications</span>
                                <template x-if="hasUnread">
                                    <span class="absolute top-2.5 right-2.5 flex h-3.5 w-3.5">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                        <span
                                            class="relative inline-flex rounded-full h-3.5 w-3.5 bg-amber-500 border-2 border-primary shadow-lg shadow-amber-500/50"></span>
                                    </span>
                                </template>
                            </button>

                            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                                class="absolute right-0 mt-4 w-80 md:w-96 bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden z-[999]"
                                style="display: none;">

                                <div
                                    class="p-5 border-b border-slate-50 bg-slate-50/50 backdrop-blur-md flex items-center justify-between">
                                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Notifikasi
                                        Terbaru</h3>
                                    <span x-cloak x-show="unreadCount > 0" x-text="`${unreadCount} Baru`"
                                        class="px-2 py-1 bg-primary/10 text-primary text-[10px] font-black rounded-lg"></span>
                                </div>

                                <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
                                    @forelse($adminNotifItems as $notif)
                                        <a href="{{ $notif['url'] }}" @click="markAsRead(@js($notif['id']))"
                                            class="flex flex-col gap-1 p-5 hover:bg-slate-50 transition-all border-b border-slate-50 last:border-0 group relative"
                                            :class="!readIds.includes(@js($notif['id'])) ? 'bg-amber-50/30' : ''">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <template x-if="!readIds.includes(@js($notif['id']))">
                                                        <span
                                                            class="w-1.5 h-1.5 bg-amber-500 rounded-full shadow-[0_0_8px_rgba(245,158,11,0.5)]"></span>
                                                    </template>
                                                    <span
                                                        class="material-symbols-outlined text-base text-primary">{{ $notif['icon'] }}</span>
                                                    <h4
                                                        class="text-[15px] font-black text-slate-900 group-hover:text-primary transition-colors leading-tight line-clamp-1 truncate max-w-[160px] md:max-w-[200px]">
                                                        {{ $notif['title'] }}</h4>
                                                    @if(!empty($notif['needs_action']))
                                                        <span
                                                            class="px-1.5 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider bg-red-50 text-red-600 border border-red-100">Perlu
                                                            Aksi</span>
                                                    @endif
                                                </div>
                                                <span
                                                    class="text-[10px] font-bold text-slate-400 whitespace-nowrap ml-4">{{ $notif['human_time'] }}</span>
                                            </div>
                                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed font-medium mt-1">
                                                {{ $notif['message'] }}
                                            </p>
                                        </a>
                                    @empty
                                        <div class="p-10 text-center">
                                            <span
                                                class="material-symbols-outlined text-4xl text-slate-200 mb-3">notifications_off</span>
                                            <p class="text-sm font-bold text-slate-400">Belum ada notifikasi</p>
                                        </div>
                                    @endforelse
                                </div>

                                <a href="{{ route('admin.dashboard') }}"
                                    class="block p-4 text-center text-xs font-black text-primary hover:bg-primary/5 transition-all border-t border-slate-50 uppercase tracking-widest">
                                    Dashboard Admin
                                </a>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5 min-h-10">
                            <div class="text-right hidden sm:flex sm:flex-col sm:justify-center leading-tight">
                                <div class="text-sm font-medium user-name dark:text-white">{{ auth()->user()->name }}
                                </div>

                            </div>
                            <div
                                class="w-10 h-10 bg-white rounded-full flex items-center justify-center font-bold text-maroon shadow-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </header>



            <!-- Content Area -->
            <main class="relative flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <!-- Flash Messages -->
                @if(session('success') && !request()->has('_from_confirmation'))
                    <div class="mb-4">
                        <!-- Success Alert -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4 shadow-sm"
                            role="alert">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                                <button type="button" onclick="this.closest('[role=alert]').parentElement.remove();"
                                    class="ml-auto inline-flex text-green-400 hover:text-green-600">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Failed Items Alert (Collapsible) -->
                        @if(session('failed_items'))
                            <div class="mt-4">
                                <div
                                    class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-lg overflow-hidden shadow-sm">
                                    <button type="button"
                                        onclick="document.getElementById('failed-items-content').classList.toggle('hidden'); document.getElementById('failed-items-arrow').classList.toggle('rotate-180');"
                                        class="w-full px-4 py-4 flex items-center justify-between hover:bg-red-100/30 transition">
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-red-600 mr-3" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <div class="text-left">
                                                <p class="font-semibold text-red-800">Data yang gagal di-generate</p>
                                                <p class="text-sm text-red-600 mt-0.5">{{ count(session('failed_items')) }} item
                                                    gagal diproses</p>
                                            </div>
                                        </div>
                                        <svg id="failed-items-arrow" class="h-5 w-5 text-red-600 transition-transform"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    <div id="failed-items-content" class="hidden border-t border-red-200 bg-white">
                                        <div class="max-h-96 overflow-y-auto">
                                            <table class="w-full text-sm">
                                                @foreach(session('failed_items') as $key => $item)
                                                    <tr class="border-b border-red-100 hover:bg-red-50/50 transition">
                                                        <td class="px-4 py-3 text-red-700">
                                                            <div class="flex items-start">
                                                                <span
                                                                    class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-red-100 text-red-600 flex-shrink-0 mr-3 mt-0.5 text-xs font-bold">{{ $key + 1 }}</span>
                                                                <span class="break-words">{{ $item }}</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>

                                        <div
                                            class="border-t border-red-200 px-4 py-3 bg-red-50/50 flex items-center justify-between">
                                            <p class="text-xs text-red-600 font-medium">Total gagal:
                                                {{ count(session('failed_items')) }} item
                                            </p>
                                            <div class="flex gap-2">
                                                <button type="button" onclick="downloadFailedItems()"
                                                    class="px-3 py-1 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded transition">
                                                    <svg class="h-3 w-3 inline-block mr-1" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M3 17a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2zm3.172-5.172a1 1 0 011.414 0L9 10.586l1.414-1.414a1 1 0 111.414 1.414l-2 2a1 1 0 01-1.414 0l-2-2a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Download CSV
                                                </button>
                                                @if(auth()->check() && Route::has('admin.jadwal-generate-logs'))
                                                    <a href="{{ route('admin.jadwal-generate-logs') }}"
                                                        class="px-3 py-1 text-xs font-semibold text-red-600 border border-red-600 hover:bg-red-50 rounded transition">
                                                        Lihat History
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4">
                        <div class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-lg p-4 shadow-sm"
                            role="alert">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                </div>
                                <button type="button" onclick="this.closest('[role=alert]').parentElement.remove();"
                                    class="ml-auto inline-flex text-red-400 hover:text-red-600">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Auto-hide flash messages after 5 seconds
        setTimeout(function () {
            document.querySelectorAll('[role="alert"]').forEach(function (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function () {
                    alert.remove();
                }, 500);
            });
        }, 5000);
        // Sidebar toggle for small screens
        const sidebarToggleBtn = document.getElementById('sidebar-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const asideSidebar = document.querySelector('aside.sidebar');

        function toggleSidebar() {
            if (!asideSidebar) return;

            const isOpen = asideSidebar.classList.contains('mobile-open');

            if (isOpen) {
                // Close sidebar
                asideSidebar.classList.remove('mobile-open');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('opacity-100');
                    sidebarOverlay.classList.add('opacity-0');
                    setTimeout(() => {
                        sidebarOverlay.classList.add('hidden');
                    }, 300); // match transition duration
                }
            } else {
                // Open sidebar
                asideSidebar.classList.add('mobile-open');
                asideSidebar.classList.remove('hidden'); // ensure it's not hidden
                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('hidden');
                    // Force reflow
                    void sidebarOverlay.offsetWidth;
                    sidebarOverlay.classList.remove('opacity-0');
                    sidebarOverlay.classList.add('opacity-100');
                }
            }
        }

        sidebarToggleBtn?.addEventListener('click', toggleSidebar);
        sidebarOverlay?.addEventListener('click', toggleSidebar);

        // Header search typeahead (AJAX)
        (function () {
            const input = document.getElementById('header-search-input');
            const suggestions = document.getElementById('search-suggestions');
            const form = document.getElementById('header-search-form');
            if (!input || !suggestions || !form) return;


            const url = form.getAttribute('data-search-url');

            let debounceTimer = null;
            let items = [];
            let selected = -1;

            function clearSuggestions() {
                suggestions.innerHTML = '';
                suggestions.classList.add('hidden');
                selected = -1;
            }

            function render(data) {
                suggestions.innerHTML = '';
                const frag = document.createDocumentFragment();

                if (data.features && data.features.length) {
                    const header = document.createElement('div');
                    header.className = 'px-3 py-2 border-b bg-gray-50 font-medium';
                    header.textContent = 'Fitur';
                    frag.appendChild(header);

                    data.features.forEach(function (f, idx) {
                        const el = document.createElement('a');
                        el.href = f.url || '#';
                        el.className = 'block px-3 py-2 hover:bg-gray-100 flex items-center justify-between';
                        el.innerHTML = '<span>' + f.label + '</span>' + (f.url ? '<span class="text-xs text-gray-500">Buka</span>' : '');
                        frag.appendChild(el);
                    });
                }

                if (data.results) {
                    for (const [table, list] of Object.entries(data.results)) {
                        if (!list || !list.length) continue;
                        const theader = document.createElement('div');
                        theader.className = 'px-3 py-2 border-t bg-gray-50 font-medium';
                        theader.textContent = table.replace(/_/g, ' ');
                        frag.appendChild(theader);

                        list.forEach(function (it) {
                            const el = document.createElement('a');
                            el.href = it.url || (url + '?q=' + encodeURIComponent(it.display));
                            el.className = 'block px-3 py-2 hover:bg-gray-100 flex items-center justify-between';
                            el.innerHTML = '<span class="truncate">' + it.display + '</span>' + (it.url ? '<span class="text-xs text-gray-500">Lihat</span>' : '');
                            frag.appendChild(el);
                        });
                    }
                }

                if (!frag.childNodes.length) {
                    const no = document.createElement('div');
                    no.className = 'px-3 py-2 text-gray-600';
                    no.textContent = 'Tidak ada hasil';
                    frag.appendChild(no);
                }

                suggestions.appendChild(frag);
                suggestions.classList.remove('hidden');
            }

            input.addEventListener('input', function (e) {
                const v = input.value.trim();
                if (debounceTimer) clearTimeout(debounceTimer);
                if (!v) {
                    clearSuggestions();
                    return;
                }
                debounceTimer = setTimeout(function () {
                    fetch(url + '?q=' + encodeURIComponent(v), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
                        .then(function (res) { return res.json(); })
                        .then(function (json) {
                            render(json);
                        }).catch(function () {
                            clearSuggestions();
                        });
                }, 250);
            });

            // Hide when clicking outside
            document.addEventListener('click', function (e) {
                if (!form.contains(e.target)) {
                    clearSuggestions();
                }
            });

            // keyboard navigation
            input.addEventListener('keydown', function (e) {
                const links = Array.from(suggestions.querySelectorAll('a'));
                if (!links.length) return;
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    selected = Math.min(selected + 1, links.length - 1);
                    links.forEach((a, i) => a.classList.toggle('bg-gray-100', i === selected));
                    links[selected].scrollIntoView({ block: 'nearest' });
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    selected = Math.max(selected - 1, 0);
                    links.forEach((a, i) => a.classList.toggle('bg-gray-100', i === selected));
                    links[selected].scrollIntoView({ block: 'nearest' });
                } else if (e.key === 'Enter') {
                    if (selected >= 0 && links[selected]) {
                        e.preventDefault();
                        window.location = links[selected].href;
                    }
                }
            });
        })();

        // Download failed items as CSV
        function downloadFailedItems() {
            const failedItems = @json(session('failed_items') ?? []);
            if (!failedItems || failedItems.length === 0) {
                showError('Tidak ada data gagal untuk didownload');
                return;
            }

            let csv = 'No,Item Gagal\n';
            failedItems.forEach((item, index) => {
                csv += `${index + 1},"${item.replace(/"/g, '""')}"\n`;
            });

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `jadwal-generate-failed-${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
    @stack('scripts')
    <x-ui.preloader />
</body>

</html>