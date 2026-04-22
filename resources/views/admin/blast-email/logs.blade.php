@extends('layouts.admin')

@section('title', 'Blast Email History')
@section('page-title', 'Blast Email History')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
    <div>
        <h3 class="text-2xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-history text-maroon mr-2"></i>Blast Email History
        </h3>
        <p class="text-sm text-gray-600 mt-1">Riwayat pengiriman email massal beserta status dan detailnya</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.blast-email.index') }}" class="px-4 py-2 bg-maroon text-white rounded-lg text-sm font-medium hover:bg-red-800 transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Buat Blast Email Baru
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center">
        <h5 class="text-lg font-bold text-gray-800 flex items-center mb-0">
            <i class="fas fa-filter text-maroon mr-2"></i>Filter
        </h5>
    </div>
    <div class="p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label for="batch_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Batch ID</label>
                <input type="text" id="batch_id" name="batch_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent" placeholder="Cari batch ID..." value="{{ request('batch_id') }}">
            </div>
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-1.5">Status</label>
                <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                    <option value="">-- Semua Status --</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Sukses</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Batch Statistics Summary -->
@if(request('batch_id'))
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center">
        <h5 class="text-lg font-bold text-gray-800 flex items-center mb-0">
            <i class="fas fa-chart-pie text-maroon mr-2"></i>Statistik Batch: {{ request('batch_id') }}
        </h5>
    </div>
    <div class="p-6" id="statsContainer">
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin text-3xl text-maroon mb-3"></i>
            <p class="text-sm text-gray-500 mt-2">Loading statistik...</p>
        </div>
    </div>
</div>
@endif

<!-- Logs Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center">
        <h5 class="text-lg font-bold text-gray-800 flex items-center mb-0">
            <i class="fas fa-table text-maroon mr-2"></i>Email Blast Logs
        </h5>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-700 text-sm border-b border-gray-200">
                    <th class="px-6 py-3 font-semibold">Batch ID</th>
                    <th class="px-6 py-3 font-semibold">Email Tujuan</th>
                    <th class="px-6 py-3 font-semibold">Subject</th>
                    <th class="px-6 py-3 font-semibold">Status</th>
                    <th class="px-6 py-3 font-semibold">Error</th>
                    <th class="px-6 py-3 font-semibold">Sent By</th>
                    <th class="px-6 py-3 font-semibold">Waktu</th>
                    <th class="px-6 py-3 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <a href="?batch_id={{ $log->batch_id }}" class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs font-mono hover:bg-gray-200 transition">
                                {{ Str::limit($log->batch_id, 12) }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $log->email_sent_to }}</td>
                        <td class="px-6 py-4 text-gray-600" title="{{ $log->subject }}">{{ Str::limit($log->subject, 30) }}</td>
                        <td class="px-6 py-4">
                            @if($log->success)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1.5"></i>Sukses
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1.5"></i>Gagal
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($log->error_message)
                                <span class="text-red-500 text-xs" title="{{ $log->error_message }}">
                                    {{ Str::limit($log->error_message, 20) }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $log->sent_by ? ($log->nama ?? 'System') : 'System' }}</td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 p-2 rounded transition" 
                                    title="Lihat Detail"
                                    onclick="showDetail('{{ $log->batch_id }}', '{{ $log->email_sent_to }}', '{{ $log->success }}', '{{ $log->created_at->format('d M Y H:i:s') }}')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                            Tidak ada data logs
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $logs->withQueryString()->links() }}
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load stats if batch_id is present
    const batchId = new URLSearchParams(window.location.search).get('batch_id');
    if (batchId) {
        loadBatchStats(batchId);
    }
});

function showDetail(batchId, email, success, time) {
    const isSuccess = success == '1' || success == 'true';
    const statusBadge = isSuccess 
        ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Sukses</span>'
        : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Gagal</span>';

    Swal.fire({
        title: '<div class="flex items-center justify-center"><i class="fas fa-envelope text-maroon mr-2"></i>&nbsp;Detail Email</div>',
        html: `
            <div class="text-left mt-4 text-sm space-y-4">
                <div class="grid grid-cols-3 gap-2 border-b border-gray-100 pb-2">
                    <div class="text-gray-500 font-semibold">Batch ID</div>
                    <div class="col-span-2"><code class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">${batchId}</code></div>
                </div>
                <div class="grid grid-cols-3 gap-2 border-b border-gray-100 pb-2">
                    <div class="text-gray-500 font-semibold">Email</div>
                    <div class="col-span-2 text-gray-800">${email}</div>
                </div>
                <div class="grid grid-cols-3 gap-2 border-b border-gray-100 pb-2">
                    <div class="text-gray-500 font-semibold">Status</div>
                    <div class="col-span-2">${statusBadge}</div>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div class="text-gray-500 font-semibold">Waktu</div>
                    <div class="col-span-2 text-gray-600">${time}</div>
                </div>
            </div>
        `,
        showConfirmButton: true,
        confirmButtonColor: '#800020',
        confirmButtonText: 'Tutup',
        customClass: {
            popup: 'rounded-xl',
            title: 'text-lg font-bold text-gray-800'
        }
    });
}

function loadBatchStats(batchId) {
    const container = document.getElementById('statsContainer');
    
    fetch(`/admin/blast-email/stats?batch_id=${batchId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const stats = data.data;
                container.innerHTML = `
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h3 class="text-2xl font-bold text-blue-600">${stats.total}</h3>
                            <p class="text-xs text-blue-800 mt-1 uppercase tracking-wider font-semibold">Total Email</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <h3 class="text-2xl font-bold text-green-600">${stats.success}</h3>
                            <p class="text-xs text-green-800 mt-1 uppercase tracking-wider font-semibold">Sukses</p>
                        </div>
                        <div class="bg-red-50 rounded-lg p-4">
                            <h3 class="text-2xl font-bold text-red-600">${stats.failed}</h3>
                            <p class="text-xs text-red-800 mt-1 uppercase tracking-wider font-semibold">Gagal</p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <h3 class="text-2xl font-bold text-yellow-600">${stats.success_rate}%</h3>
                            <p class="text-xs text-yellow-800 mt-1 uppercase tracking-wider font-semibold">Success Rate</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden flex">
                            <div class="bg-green-500 h-4 flex items-center justify-center text-[10px] text-white font-bold transition-all duration-1000" style="width: ${stats.success_rate}%">
                                ${stats.success_rate > 5 ? stats.success_rate + '%' : ''}
                            </div>
                            ${stats.failed > 0 ? `<div class="bg-red-500 h-4 flex items-center justify-center text-[10px] text-white font-bold transition-all duration-1000" style="width: ${100 - stats.success_rate}%"></div>` : ''}
                        </div>
                    </div>
                `;
            }
        })
        .catch(err => {
            console.error('Error loading stats:', err);
            container.innerHTML = `
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg p-4 text-sm flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2 text-yellow-600"></i> Gagal memuat statistik batch
                </div>
            `;
        });
}
</script>
@endsection
