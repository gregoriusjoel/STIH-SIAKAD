@extends('layouts.admin')

@section('title', 'Log Audit Aktivitas')
@section('page-title', 'Log Audit')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
        <i class="fas fa-history text-maroon dark:text-red-400"></i>
        Log Audit Aktivitas Sistem
    </h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
        Riwayat seluruh aktivitas krusial dalam sistem: siapa, kapan, apa, perangkat, dan detail perubahannya.
    </p>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.audit-logs.index') }}"
    class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-4 mb-5">
    <div class="flex flex-wrap gap-3 items-end">
        {{-- Aksi --}}
        <div>
            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Aksi</label>
            <select name="action" class="text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-maroon outline-none">
                <option value="">Semua Aksi</option>
                @php
                    $actionLabels = [
                        'academic event.created' => 'Kalender: Tambah Event',
                        'academic event.updated' => 'Kalender: Ubah Event',
                        'activate semester'      => 'Semester: Aktivasi',
                        'add mk to semester'     => 'Kurikulum: Tambah MK (Manual)',
                        'attach mk'              => 'Kurikulum: Gabungkan MK',
                        'carry forward'          => 'Kurikulum: Carry Forward',
                        'deactivate semester mk' => 'Kurikulum: Nonaktifkan MK',
                        'detach mk'              => 'Kurikulum: Hapus MK dari Semester',
                        'lock semester'          => 'Semester: Kunci',
                        'restore mk'             => 'Kurikulum: Kembalikan MK',
                        'unlock semester'        => 'Semester: Buka Kunci',
                        'user.login'             => 'Autentikasi: User Login',
                        'krs.submitted'          => 'KRS: Submit',
                        'krs.draft_saved'        => 'KRS: Simpan Draft',
                        'skripsi.proposal_submitted' => 'Skripsi: Ajukan Proposal',
                        'skripsi.revision_uploaded'  => 'Skripsi: Upload Revisi',
                        'skripsi.guidance_approved'  => 'Skripsi: Setujui Bimbingan',
                        'skripsi.revision_approved'  => 'Skripsi: ACC Revisi Akhir',
                        'skripsi.supervisor_accepted' => 'Skripsi: Terima Pembimbing',
                        'skripsi.supervisor_rejected' => 'Skripsi: Tolak Pembimbing',
                        'grades.published'       => 'Nilai: Publish',
                        'parent.view_dashboard'  => 'Parent: Lihat Dashboard',
                        'parent.view_grades'     => 'Parent: Lihat Nilai',
                        'parent.view_schedule'   => 'Parent: Lihat Jadwal',
                        'parent.view_attendance' => 'Parent: Lihat Presensi',
                        'parent.view_payments'   => 'Parent: Lihat Pembayaran',
                    ];
                @endphp
                @foreach($actions as $action)
                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                    {{ $actionLabels[$action] ?? ucwords(str_replace(['.', '_'], ' ', $action)) }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Role --}}
        <div>
            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Role</label>
            <select name="actor_role" class="text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-maroon outline-none">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                <option value="{{ $role }}" {{ request('actor_role') == $role ? 'selected' : '' }}>
                    {{ ucfirst($role) }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Tipe Entitas --}}
        <div>
            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Tipe Entitas</label>
            <input type="text" name="entity_type" value="{{ request('entity_type') }}" placeholder="Contoh: user, dosen, mk..."
                class="text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-1.5 w-40 focus:ring-2 focus:ring-maroon outline-none">
        </div>

        {{-- IP Address --}}
        <div>
            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">IP Address</label>
            <input type="text" name="ip_address" value="{{ request('ip_address') }}" placeholder="Contoh: 192.168..."
                class="text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-1.5 w-36 focus:ring-2 focus:ring-maroon outline-none">
        </div>

        {{-- Tanggal Mulai --}}
        <div>
            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Dari Tanggal</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                class="text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-maroon outline-none">
        </div>

        {{-- Tanggal Selesai --}}
        <div>
            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Sampai Tanggal</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                class="text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-maroon outline-none">
        </div>

        <div class="flex gap-2">
            <button type="submit"
                class="px-4 py-1.5 bg-maroon text-white rounded-lg text-sm hover:bg-red-900 transition flex items-center gap-2">
                <i class="fas fa-filter text-xs"></i> Filter
            </button>
            <a href="{{ route('admin.audit-logs.index') }}"
                class="px-4 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                Reset
            </a>
        </div>
    </div>
</form>

{{-- Logs table --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-maroon text-white">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Waktu</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Aktor</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Role</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Aksi</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Entitas</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Perangkat</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">IP Address</th>
                    <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider">Detail</th>
                </tr>
            </thead>
            @forelse($logs as $log)
            @php
                $device = $log->device_info;

                $deviceIcon = match($device['device_type']) {
                    'Mobile'  => 'fa-mobile-alt',
                    'Tablet'  => 'fa-tablet-alt',
                    default   => 'fa-desktop',
                };
                $deviceColor = match($device['device_type']) {
                    'Mobile'  => 'text-emerald-500',
                    'Tablet'  => 'text-blue-500',
                    default   => 'text-gray-500 dark:text-gray-400',
                };

                $browserIcon = match(true) {
                    str_contains(strtolower($device['browser']), 'chrome')  => 'fa-chrome',
                    str_contains(strtolower($device['browser']), 'firefox') => 'fa-firefox',
                    str_contains(strtolower($device['browser']), 'safari')  => 'fa-safari',
                    str_contains(strtolower($device['browser']), 'edge')    => 'fa-edge',
                    str_contains(strtolower($device['browser']), 'opera')   => 'fa-opera',
                    default => 'fa-globe',
                };

                $actionColors = [
                    'academic event.created'  => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400',
                    'academic event.updated'  => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400',
                    'attach_mk'               => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                    'add mk to semester'      => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                    'detach_mk'               => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
                    'deactivate semester mk'  => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
                    'restore_mk'              => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
                    'carry_forward'           => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                    'activate_semester'       => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400',
                    'lock_semester'           => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                    'unlock_semester'         => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
                    'user.login'              => 'bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-400',
                    'krs.submitted'           => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400',
                    'krs.draft_saved'         => 'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400',
                    'skripsi.proposal_submitted' => 'bg-fuchsia-100 dark:bg-fuchsia-900/30 text-fuchsia-700 dark:text-fuchsia-400',
                    'skripsi.guidance_approved'  => 'bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-400',
                    'grades.published'        => 'bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400',
                    'parent.view'             => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400',
                ];

                $normalizedAction = str_replace(['.', '_', '-'], ' ', $log->action);
                $colorClass = 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400';

                foreach($actionColors as $key => $color) {
                    if(str_replace(['.', '_', '-'], ' ', $key) === $normalizedAction || $log->action === $key) {
                        $colorClass = $color;
                        break;
                    }
                }

                if ($colorClass === 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400') {
                    if (str_ends_with($log->action, '.created'))      $colorClass = 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400';
                    elseif (str_ends_with($log->action, '.updated'))  $colorClass = 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400';
                    elseif (str_ends_with($log->action, '.deleted'))  $colorClass = 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400';
                }

                $label = $actionLabels[$log->action] ?? null;
                if (!$label) {
                    if (str_ends_with($log->action, '.created')) {
                        $entity = ucwords(str_replace(['.', '_', '-'], ' ', str_replace('.created', '', $log->action)));
                        $label  = $entity . ': Tambah';
                    } elseif (str_ends_with($log->action, '.updated')) {
                        $entity = ucwords(str_replace(['.', '_', '-'], ' ', str_replace('.updated', '', $log->action)));
                        $label  = $entity . ': Ubah';
                    } elseif (str_ends_with($log->action, '.deleted')) {
                        $entity = ucwords(str_replace(['.', '_', '-'], ' ', str_replace('.deleted', '', $log->action)));
                        $label  = $entity . ': Hapus';
                    } else {
                        $label = ucwords($normalizedAction);
                    }
                }
            @endphp
            <tbody x-data="{ open: false }" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 border-t border-gray-200 dark:border-gray-700">
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    {{-- Waktu --}}
                    <td class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                    </td>

                    {{-- Aktor --}}
                    <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">
                        {{ $log->actor?->name ?? 'System' }}
                    </td>

                    {{-- Role --}}
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-full
                            {{ $log->actor_role === 'super_admin' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400'
                                : ($log->actor_role === 'akademik' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400'
                                : ($log->actor_role === 'dosen' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'
                                : ($log->actor_role === 'mahasiswa' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400'
                                : ($log->actor_role === 'system' ? 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'
                                : 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400')))) }}">
                            {{ $log->actor_role ?? '—' }}
                        </span>
                    </td>

                    {{-- Aksi --}}
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] uppercase rounded font-bold tracking-wider {{ $colorClass }}">
                            {{ $label }}
                        </span>
                    </td>

                    {{-- Entitas --}}
                    <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">
                        <div class="font-medium text-gray-800 dark:text-gray-200">{{ class_basename($log->auditable_type) }}</div>
                        <div class="mt-0.5 text-[10px] font-mono">ID: #{{ $log->auditable_id }}</div>
                    </td>

                    {{-- Perangkat (kolom baru) --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <i class="fas {{ $deviceIcon }} {{ $deviceColor }} text-sm"></i>
                            <div>
                                <div class="flex items-center gap-1.5 text-xs font-medium text-gray-700 dark:text-gray-200">
                                    <i class="fab {{ $browserIcon }} text-[11px] text-gray-400"></i>
                                    {{ $device['browser'] !== 'Unknown' ? $device['browser'] . ($device['browser_version'] ? ' ' . $device['browser_version'] : '') : '—' }}
                                </div>
                                <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                                    {{ $device['platform'] !== 'Unknown' ? $device['platform'] : '—' }}
                                    @if($device['device_name'])
                                        · {{ $device['device_name'] }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- IP Address --}}
                    <td class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400 font-mono">
                        {{ $log->ip_address ?? '—' }}
                    </td>

                    {{-- Detail --}}
                    <td class="px-4 py-3 text-center">
                        @if($log->before || $log->after || $log->meta)
                        <button @click="open = !open"
                            class="inline-flex items-center gap-1.5 px-3 py-1 text-[11px] font-bold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition uppercase tracking-wide">
                            <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            <span x-text="open ? 'Tutup' : 'Detail'">Detail</span>
                        </button>
                        @endif
                    </td>
                </tr>

                {{-- Expanded detail row --}}
                @if($log->before || $log->after || $log->meta)
                <tr x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <td colspan="8" class="px-6 py-6 bg-gray-50 dark:bg-gray-900/20 shadow-inner">

                        {{-- Device Summary Bar --}}
                        <div class="mb-4 flex flex-wrap items-center gap-4 bg-white dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700 rounded-xl p-3 text-xs text-gray-600 dark:text-gray-400">
                            <div class="flex items-center gap-2">
                                <i class="fas {{ $deviceIcon }} {{ $deviceColor }}"></i>
                                <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $device['device_type'] }}</span>
                                @if($device['device_name'])
                                    <span class="text-gray-400">({{ $device['device_name'] }})</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="fab {{ $browserIcon }} text-gray-400"></i>
                                <span>{{ $device['browser'] }}{{ $device['browser_version'] ? ' ' . $device['browser_version'] : '' }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-laptop text-gray-400"></i>
                                <span>{{ $device['platform'] }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-network-wired text-gray-400"></i>
                                <span class="font-mono">{{ $log->ip_address ?? '—' }}</span>
                            </div>
                            @if($log->session_id)
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-fingerprint text-gray-400"></i>
                                <span class="font-mono text-[10px]">Session: {{ substr($log->session_id, 0, 12) }}...</span>
                            </div>
                            @endif
                            @if($log->user_agent)
                            <div class="flex items-center gap-1.5 max-w-xs">
                                <i class="fas fa-code text-gray-400 shrink-0"></i>
                                <span class="font-mono text-[9px] text-gray-400 truncate" title="{{ $log->user_agent }}">
                                    {{ $log->user_agent }}
                                </span>
                            </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @if($log->before)
                            <div>
                                <h4 class="text-[10px] font-bold text-red-600 dark:text-red-400 mb-3 uppercase tracking-widest flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Sebelum Perubahan
                                </h4>
                                <div class="bg-red-50/50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/20 rounded-xl p-4 max-h-80 overflow-y-auto">
                                    <dl class="space-y-4">
                                        @foreach((array)$log->before as $key => $value)
                                        <div class="border-b border-red-200/30 dark:border-red-800/20 pb-2 last:border-0 last:pb-0">
                                            <dt class="text-[10px] uppercase font-bold text-red-400 tracking-wider mb-1">{{ str_replace(['_', '.'], ' ', $key) }}</dt>
                                            <dd class="text-xs font-medium text-red-900 dark:text-red-200 break-all leading-relaxed">{{ is_array($value) || is_object($value) ? json_encode($value, JSON_PRETTY_PRINT) : ($value === null || $value === '' ? '—' : $value) }}</dd>
                                        </div>
                                        @endforeach
                                    </dl>
                                </div>
                            </div>
                            @endif

                            @if($log->after)
                            <div>
                                <h4 class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 mb-3 uppercase tracking-widest flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Setelah Perubahan
                                </h4>
                                <div class="bg-emerald-50/50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-900/20 rounded-xl p-4 max-h-80 overflow-y-auto">
                                    <dl class="space-y-4">
                                        @foreach((array)$log->after as $key => $value)
                                        <div class="border-b border-emerald-200/30 dark:border-emerald-800/20 pb-2 last:border-0 last:pb-0">
                                            <dt class="text-[10px] uppercase font-bold text-emerald-400 tracking-wider mb-1">{{ str_replace(['_', '.'], ' ', $key) }}</dt>
                                            <dd class="text-xs font-medium text-emerald-900 dark:text-emerald-200 break-all leading-relaxed">{{ is_array($value) || is_object($value) ? json_encode($value, JSON_PRETTY_PRINT) : ($value === null || $value === '' ? '—' : $value) }}</dd>
                                        </div>
                                        @endforeach
                                    </dl>
                                </div>
                            </div>
                            @endif

                            @if($log->meta)
                            <div>
                                <h4 class="text-[10px] font-bold text-blue-600 dark:text-blue-400 mb-3 uppercase tracking-widest flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Informasi Tambahan
                                </h4>
                                <div class="bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/20 rounded-xl p-4 max-h-80 overflow-y-auto">
                                    <dl class="space-y-4">
                                        @foreach((array)$log->meta as $key => $value)
                                        <div class="border-b border-blue-200/30 dark:border-blue-800/20 pb-2 last:border-0 last:pb-0">
                                            <dt class="text-[10px] uppercase font-bold text-blue-400 tracking-wider mb-1">{{ str_replace(['_', '.'], ' ', $key) }}</dt>
                                            <dd class="text-xs font-medium text-blue-900 dark:text-blue-200 break-all leading-relaxed">{{ is_array($value) || is_object($value) ? json_encode($value, JSON_PRETTY_PRINT) : ($value === null || $value === '' ? '—' : $value) }}</dd>
                                        </div>
                                        @endforeach
                                    </dl>
                                </div>
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
            @empty
            <tbody class="bg-white dark:bg-gray-800">
                <tr>
                    <td colspan="8" class="px-6 py-20 text-center text-gray-400 dark:text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-history text-5xl mb-4 opacity-20"></i>
                            <p class="text-lg font-medium">Belum ada data log aktivitas</p>
                            <p class="text-sm mt-1">Coba sesuaikan filter pencarian Anda</p>
                        </div>
                    </td>
                </tr>
            </tbody>
            @endforelse
        </table>
    </div>

    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
