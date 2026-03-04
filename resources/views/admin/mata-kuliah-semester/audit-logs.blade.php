@extends('layouts.admin')

@section('title', 'Audit Log - Manajemen MK Semester')
@section('page-title', 'Audit Log')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
            <i class="fas fa-clipboard-list text-maroon dark:text-red-400"></i>
            Audit Log — Manajemen Mata Kuliah Semester
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Setiap aksi penting tercatat: siapa, kapan, apa, dan perubahannya.
        </p>
    </div>
    <a href="{{ route('admin.mata-kuliah.index', ['tab' => 'ta-aktif']) }}"
        class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 transition shadow-sm">
        <i class="fas fa-arrow-left"></i> Kembali ke Mata Kuliah
    </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.mata-kuliah-semester.audit-logs') }}"
    class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-4 mb-5 flex flex-wrap gap-3 items-end">
    <div>
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Aksi</label>
        <select name="action" class="text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-1.5">
            <option value="">Semua Aksi</option>
            @php
                $actionLabels = [
                    'academic event.created' => 'Tambah Kalender Akademik',
                    'academic event.updated' => 'Ubah Kalender Akademik',
                    'activate semester' => 'Aktivasi Semester',
                    'add mk to semester' => 'Tambah MK ke Semester (Satu per satu)',
                    'attach mk' => 'Tambah MK ke Semester',
                    'carry forward' => 'Carry Forward MK',
                    'deactivate semester mk' => 'Nonaktifkan MK dari Semester',
                    'detach mk' => 'Keluarkan MK dari Semester',
                    'lock semester' => 'Kunci Semester',
                    'restore mk' => 'Kembalikan MK dari Histori',
                    'unlock semester' => 'Buka Kunci Semester',
                ];
            @endphp
            @foreach($actions as $action)
            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                {{ $actionLabels[$action] ?? ucwords(str_replace('_', ' ', $action)) }}
            </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Tipe Entitas</label>
        <input type="text" name="entity_type" value="{{ request('entity_type') }}" placeholder="semester / mata_kuliah..."
            class="text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-1.5 w-48">
    </div>
    <div class="flex gap-2">
        <button type="submit"
            class="px-4 py-1.5 bg-maroon text-white rounded-lg text-sm hover:bg-red-900 transition">
            <i class="fas fa-filter mr-1"></i> Filter
        </button>
        <a href="{{ route('admin.mata-kuliah-semester.audit-logs') }}"
            class="px-4 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-300 dark:hover:bg-gray-600 transition">
            Reset
        </a>
    </div>
</form>

{{-- Logs table --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-maroon text-white">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase">Waktu</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase">Aktor</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase">Role</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase">Aksi</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase">Entitas</th>
                    <th class="px-4 py-3 text-center text-xs font-bold uppercase">Before/After</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase">IP</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($logs as $log)
                <tr x-data="{ open: false }" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <td class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">
                        {{ $log->actor?->name ?? 'System' }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 text-xs rounded-full
                            {{ $log->actor_role === 'admin' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400'
                                : ($log->actor_role === 'system' ? 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'
                                : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400') }}">
                            {{ $log->actor_role ?? '—' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @php
                        $actionColors = [
                            'academic event.created' => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400',
                            'academic event.updated' => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400',
                            'attach_mk' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                            'add mk to semester' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                            'detach_mk' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
                            'deactivate semester mk' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
                            'restore_mk' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
                            'carry_forward' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                            'activate_semester' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400',
                            'lock_semester' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                            'unlock_semester' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
                        ];
                        // fallback to normalized string if action is missing from our label array
                        $label = $actionLabels[$log->action] ?? ucwords(str_replace('_', ' ', $log->action));
                        
                        $filteredColors = array_filter($actionColors, function($v, $k) use ($log) {
                            return str_replace('_', ' ', $log->action) === str_replace('_', ' ', $k) || $log->action === $k; 
                        }, ARRAY_FILTER_USE_BOTH);
                        
                        $colorClass = reset($filteredColors) ?: 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400';
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] uppercase rounded font-bold tracking-wider {{ $colorClass }}">
                            {{ $label }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">
                        <div>{{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}</div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($log->before || $log->after || $log->meta)
                        <button @click="open = !open"
                            class="px-3 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            Detail
                        </button>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $log->ip_address }}</td>
                </tr>

                {{-- Expanded detail row --}}
                @if($log->before || $log->after || $log->meta)
                <tr x-show="open" x-cloak>
                    <td colspan="7" class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50">
                        @php
                            $cols = 0;
                            if($log->before) $cols++;
                            if($log->after) $cols++;
                            if($log->meta) $cols++;
                            $gridClass = $cols === 1 ? 'md:grid-cols-1' : ($cols === 2 ? 'md:grid-cols-2' : 'md:grid-cols-3');
                        @endphp
                        <div class="grid grid-cols-1 {{ $gridClass }} gap-6 text-xs">
                            @if($log->before)
                            <div>
                                <div class="text-xs font-bold text-red-600 dark:text-red-400 mb-2 uppercase tracking-wide flex items-center gap-1.5"><i class="fas fa-minus-circle"></i> Sebelum Perubahan</div>
                                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-900/30 rounded-xl p-4 max-h-60 overflow-y-auto shadow-inner">
                                    <ul class="space-y-3">
                                        @foreach((array)$log->before as $key => $value)
                                        <li class="flex flex-col items-start gap-1 border-b border-red-200/50 dark:border-red-800/50 pb-2 last:border-0 last:pb-0">
                                            <span class="text-[10px] uppercase font-bold text-red-500/80 dark:text-red-400/80 tracking-wider">{{ str_replace('_', ' ', $key) }}</span>
                                            <span class="text-sm font-medium text-red-900 dark:text-red-200 break-all">{{ is_array($value) || is_object($value) ? json_encode($value) : ($value === null || $value === '' ? '—' : $value) }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                            @if($log->after)
                            <div>
                                <div class="text-xs font-bold text-emerald-600 dark:text-emerald-400 mb-2 uppercase tracking-wide flex items-center gap-1.5"><i class="fas fa-plus-circle"></i> Setelah Perubahan</div>
                                <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-900/30 rounded-xl p-4 max-h-60 overflow-y-auto shadow-inner">
                                    <ul class="space-y-3">
                                        @foreach((array)$log->after as $key => $value)
                                        <li class="flex flex-col items-start gap-1 border-b border-emerald-200/50 dark:border-emerald-800/50 pb-2 last:border-0 last:pb-0">
                                            <span class="text-[10px] uppercase font-bold text-emerald-500/80 dark:text-emerald-400/80 tracking-wider">{{ str_replace('_', ' ', $key) }}</span>
                                            <span class="text-sm font-medium text-emerald-900 dark:text-emerald-200 break-all">{{ is_array($value) || is_object($value) ? json_encode($value) : ($value === null || $value === '' ? '—' : $value) }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                            @if($log->meta)
                            <div>
                                <div class="text-xs font-bold text-blue-600 dark:text-blue-400 mb-2 uppercase tracking-wide flex items-center gap-1.5"><i class="fas fa-info-circle"></i> Info Tambahan</div>
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900/30 rounded-xl p-4 max-h-60 overflow-y-auto shadow-inner">
                                    <ul class="space-y-3">
                                        @foreach((array)$log->meta as $key => $value)
                                        <li class="flex flex-col items-start gap-1 border-b border-blue-200/50 dark:border-blue-800/50 pb-2 last:border-0 last:pb-0">
                                            <span class="text-[10px] uppercase font-bold text-blue-500/80 dark:text-blue-400/80 tracking-wider">{{ str_replace('_', ' ', $key) }}</span>
                                            <span class="text-sm font-medium text-blue-900 dark:text-blue-200 break-all">{{ is_array($value) || is_object($value) ? json_encode($value) : ($value === null || $value === '' ? '—' : $value) }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                        </div>
                        @if($log->user_agent)
                        <div class="mt-4 text-[11px] text-gray-500 dark:text-gray-400 break-all leading-relaxed bg-gray-100 dark:bg-gray-800/50 p-2.5 rounded-lg border border-gray-200 dark:border-gray-700">
                            <i class="fas fa-desktop text-gray-400 dark:text-gray-500 mr-2"></i> {{ $log->user_agent }}
                        </div>
                        @endif
                    </td>
                </tr>
                @endif

                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center text-gray-400 dark:text-gray-500">
                        <i class="fas fa-clipboard text-4xl mb-3"></i>
                        <p>Belum ada audit log.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
