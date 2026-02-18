@extends('layouts.admin')

@section('title', 'Riwayat Import')
@section('page-title', 'Riwayat Import')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <nav class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                <a href="{{ route('admin.import.index') }}" class="hover:text-maroon dark:hover:text-red-400">Import Data</a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <span class="text-gray-700 dark:text-gray-300">Riwayat Import</span>
            </nav>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                <i class="fas fa-history mr-3 text-maroon dark:text-red-500"></i>
                Riwayat Import Data
            </h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Daftar semua aktivitas import data</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.import.index') }}"
                class="bg-maroon hover:bg-red-900 text-white px-4 py-2 rounded-lg transition flex items-center gap-2 shadow-md">
                <i class="fas fa-file-import"></i>
                <span>Import Baru</span>
            </a>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-4">
    <form method="GET" action="{{ route('admin.import.history') }}" class="flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Tipe:</label>
            <select name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-maroon focus:border-maroon block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                <option value="">Semua Tipe</option>
                @foreach($importTypes as $type => $config)
                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                    {{ $config['title'] }}
                </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
            <i class="fas fa-filter mr-1"></i>
            Filter
        </button>
        @if(request('type'))
        <a href="{{ route('admin.import.history') }}" class="text-sm text-gray-500 hover:text-maroon">
            <i class="fas fa-times mr-1"></i>Reset
        </a>
        @endif
    </form>
</div>

<!-- Logs Table -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-maroon text-white">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">File</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Total</th>
                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Berhasil</th>
                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Gagal</th>
                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Dilewati</th>
                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        #{{ $log->id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-maroon/10 dark:bg-red-900/30 text-maroon dark:text-red-400">
                            {{ $log->type_name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                        {{ $log->filename ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-maroon flex items-center justify-center text-white text-xs font-bold mr-2">
                                {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                            </div>
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $log->user->name ?? 'System' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ $log->total_rows }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                            {{ $log->success_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $log->failed_count > 0 ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                            {{ $log->failed_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">
                            {{ $log->skipped_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $log->status_badge }}">
                            {{ $log->status_text }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        <div>{{ $log->created_at->format('d M Y') }}</div>
                        <div class="text-xs">{{ $log->created_at->format('H:i:s') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="{{ route('admin.import.log', $log) }}"
                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-inbox text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                            <p class="text-gray-500 dark:text-gray-400">Belum ada riwayat import</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        {{ $logs->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection