@extends('layouts.admin')

@section('title', 'Detail Log Import')
@section('page-title', 'Detail Log Import')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <nav class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                <a href="{{ route('admin.import.index') }}" class="hover:text-maroon dark:hover:text-red-400">Import Data</a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <a href="{{ route('admin.import.history') }}" class="hover:text-maroon dark:hover:text-red-400">Riwayat</a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <span class="text-gray-700 dark:text-gray-300">Log #{{ $log->id }}</span>
            </nav>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                <i class="fas fa-file-alt mr-3 text-maroon dark:text-red-500"></i>
                Detail Log Import #{{ $log->id }}
            </h2>
        </div>
        <a href="{{ route('admin.import.history') }}" 
            class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Summary Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">
                <i class="fas fa-chart-pie text-maroon dark:text-red-400 mr-2"></i>
                Ringkasan Import
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-gray-800 dark:text-gray-200">{{ $log->total_rows }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Baris</div>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $log->success_count }}</div>
                    <div class="text-sm text-green-700 dark:text-green-300">Berhasil</div>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $log->failed_count }}</div>
                    <div class="text-sm text-red-700 dark:text-red-300">Gagal</div>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $log->skipped_count }}</div>
                    <div class="text-sm text-yellow-700 dark:text-yellow-300">Dilewati</div>
                </div>
            </div>
        </div>

        <!-- Details -->
        @if($log->details)
        @php
            $details = is_array($log->details) ? $log->details : json_decode($log->details, true);
        @endphp

        <!-- Failed Rows -->
        @if(!empty($details['failed']))
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-red-800 dark:text-red-400 mb-4 flex items-center">
                <i class="fas fa-times-circle mr-2"></i>
                Baris Gagal ({{ count($details['failed']) }})
            </h3>
            <div class="overflow-x-auto max-h-64">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-red-50 dark:bg-red-900/30">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-bold text-red-700 dark:text-red-300 uppercase">Baris</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-red-700 dark:text-red-300 uppercase">Error</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($details['failed'] as $failed)
                        <tr class="hover:bg-red-50/50 dark:hover:bg-red-900/10">
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $failed['row'] ?? '-' }}</td>
                            <td class="px-4 py-2 text-sm text-red-600 dark:text-red-400">{{ $failed['error'] ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Skipped Rows -->
        @if(!empty($details['skipped']))
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-yellow-800 dark:text-yellow-400 mb-4 flex items-center">
                <i class="fas fa-forward mr-2"></i>
                Baris Dilewati ({{ count($details['skipped']) }})
            </h3>
            <div class="overflow-x-auto max-h-64">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-yellow-50 dark:bg-yellow-900/30">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-bold text-yellow-700 dark:text-yellow-300 uppercase">Baris</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-yellow-700 dark:text-yellow-300 uppercase">Alasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach(array_slice($details['skipped'], 0, 50) as $skipped)
                        <tr class="hover:bg-yellow-50/50 dark:hover:bg-yellow-900/10">
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $skipped['row'] ?? '-' }}</td>
                            <td class="px-4 py-2 text-sm text-yellow-600 dark:text-yellow-400">{{ $skipped['reason'] ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(count($details['skipped']) > 50)
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                Menampilkan 50 dari {{ count($details['skipped']) }} baris
            </p>
            @endif
        </div>
        @endif

        <!-- Success Rows -->
        @if(!empty($details['success']))
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-green-800 dark:text-green-400 mb-4 flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                Baris Berhasil ({{ count($details['success']) }})
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Baris yang berhasil diimport: 
                <span class="font-mono text-gray-800 dark:text-gray-200">
                    {{ implode(', ', array_slice($details['success'], 0, 50)) }}
                    @if(count($details['success']) > 50)
                        ... dan {{ count($details['success']) - 50 }} baris lainnya
                    @endif
                </span>
            </p>
        </div>
        @endif
        @endif
    </div>

    <!-- Sidebar Info -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">
                <i class="fas fa-info-circle text-maroon dark:text-red-400 mr-2"></i>
                Informasi
            </h3>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Import</dt>
                    <dd class="mt-1">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-maroon/10 dark:bg-red-900/30 text-maroon dark:text-red-400">
                            {{ $log->type_name }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama File</dt>
                    <dd class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $log->filename ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diimport Oleh</dt>
                    <dd class="mt-1 flex items-center">
                        <div class="h-8 w-8 rounded-full bg-maroon flex items-center justify-center text-white text-xs font-bold mr-2">
                            {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                        </div>
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $log->user->name ?? 'System' }}</span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Waktu Import</dt>
                    <dd class="mt-1 text-sm text-gray-700 dark:text-gray-300">
                        {{ $log->created_at->format('d M Y, H:i:s') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                    <dd class="mt-1">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $log->status_badge }}">
                            {{ $log->status_text }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
