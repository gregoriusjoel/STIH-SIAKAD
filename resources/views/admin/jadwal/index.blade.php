@extends('layouts.admin')
@section('title', 'Jadwal Perkuliahan')
@section('page-title', 'Jadwal Perkuliahan')

@push('styles')
<style>
    .tab-btn.active { border-color: #8B1538; color: #8B1538; background-color: #FEF2F2; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
</style>
@endpush

@section('content')
<div class="space-y-6">
    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
    @endif

    {{-- Tabs Navigation --}}
    <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex flex-wrap gap-2">
            <button type="button" class="tab-btn active px-4 py-2 rounded-lg border-2 border-transparent font-semibold text-sm transition-all" data-tab="pending">
                <i class="fas fa-hourglass-half mr-2"></i>Menunggu Approval
                @if($pendingJadwals->count() > 0)
                <span class="ml-2 px-2 py-0.5 bg-amber-500 text-white text-xs rounded-full">{{ $pendingJadwals->count() }}</span>
                @endif
            </button>
            <button type="button" class="tab-btn px-4 py-2 rounded-lg border-2 border-transparent font-semibold text-sm transition-all" data-tab="approved">
                <i class="fas fa-clock mr-2"></i>Menunggu Ruangan
                @if($approvedJadwals->count() > 0)
                <span class="ml-2 px-2 py-0.5 bg-blue-500 text-white text-xs rounded-full">{{ $approvedJadwals->count() }}</span>
                @endif
            </button>
            <button type="button" class="tab-btn px-4 py-2 rounded-lg border-2 border-transparent font-semibold text-sm transition-all" data-tab="active">
                <i class="fas fa-check-circle mr-2"></i>Jadwal Aktif
            </button>
            <a href="{{ route('admin.jadwal.create') }}" class="ml-auto bg-maroon text-white px-4 py-2 rounded-lg hover:bg-maroon-700 transition shadow-md flex items-center text-sm font-semibold">
                <i class="fas fa-plus mr-2"></i>Tambah Jadwal
            </a>
        </div>

        {{-- Tab: Pending Approval --}}
        <div id="tab-pending" class="tab-content active p-6">
            @if($pendingJadwals->count() > 0)
            <div class="grid gap-4">
                @foreach($pendingJadwals as $jadwal)
                <div class="border border-amber-200 bg-amber-50 rounded-lg p-4">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-bold text-gray-800">{{ $jadwal->kelas->mataKuliah->nama_mk }}</h3>
                                <span class="bg-gray-200 text-gray-700 px-2 py-0.5 rounded text-xs font-semibold">{{ $jadwal->kelas->mataKuliah->kode_mk }}</span>
                                <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs font-semibold">{{ $jadwal->kelas->mataKuliah->sks }} SKS</span>
                            </div>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p><i class="fas fa-user-tie text-maroon mr-2"></i><strong>Dosen:</strong> {{ $jadwal->kelas->dosen->name ?? 'N/A' }}</p>
                                <p><i class="fas fa-calendar-day text-maroon mr-2"></i><strong>Hari:</strong> {{ $jadwal->hari }}</p>
                                <p><i class="fas fa-clock text-maroon mr-2"></i><strong>Waktu:</strong> {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</p>
                                @if($jadwal->catatan_dosen)
                                <p class="italic text-gray-500"><i class="fas fa-comment text-maroon mr-2"></i>"{{ $jadwal->catatan_dosen }}"</p>
                                @endif
                                <p class="text-xs text-gray-400">Diajukan {{ $jadwal->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.jadwal.approve', $jadwal) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition flex items-center gap-2 text-sm font-semibold" onclick="return confirm('Setujui jadwal ini?')">
                                    <i class="fas fa-check"></i> Setujui
                                </button>
                            </form>
                            <button type="button" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition flex items-center gap-2 text-sm font-semibold" onclick="openRejectModal({{ $jadwal->id }})">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-3"></i>
                <p class="text-lg font-semibold">Tidak ada jadwal yang menunggu approval</p>
            </div>
            @endif
        </div>

        {{-- Tab: Approved (Waiting for Room) --}}
        <div id="tab-approved" class="tab-content p-6">
            @if($approvedJadwals->count() > 0)
            <div class="grid gap-4">
                @foreach($approvedJadwals as $jadwal)
                <div class="border border-blue-200 bg-blue-50 rounded-lg p-4">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-bold text-gray-800">{{ $jadwal->kelas->mataKuliah->nama_mk }}</h3>
                                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-semibold">Disetujui</span>
                            </div>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p><i class="fas fa-user-tie text-maroon mr-2"></i><strong>Dosen:</strong> {{ $jadwal->kelas->dosen->name ?? 'N/A' }}</p>
                                <p><i class="fas fa-calendar-day text-maroon mr-2"></i><strong>Hari:</strong> {{ $jadwal->hari }}, {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</p>
                                <p class="text-xs text-gray-400">Disetujui oleh {{ $jadwal->approvedBy->name ?? 'Admin' }} {{ $jadwal->approved_at?->diffForHumans() }}</p>
                            </div>
                        </div>
                        <form action="{{ route('admin.jadwal.assignRoom', $jadwal) }}" method="POST" class="flex flex-col sm:flex-row gap-2">
                            @csrf
                            <input type="text" name="section" placeholder="Kelas (A, B, C...)" required class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-32" maxlength="10">
                            <input type="text" name="ruangan" placeholder="Ruangan (R.101)" required class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-36" maxlength="100">
                            <button type="submit" class="bg-maroon text-white px-4 py-2 rounded-lg hover:bg-maroon-700 transition flex items-center gap-2 text-sm font-semibold whitespace-nowrap">
                                <i class="fas fa-door-open"></i> Assign
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-3"></i>
                <p class="text-lg font-semibold">Tidak ada jadwal yang menunggu ruangan</p>
            </div>
            @endif
        </div>

        {{-- Tab: Active Schedules --}}
        <div id="tab-active" class="tab-content">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-maroon text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Hari</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Jam</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Kelas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Mata Kuliah</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Dosen</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Ruangan</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($activeJadwals as $j)
                        <tr class="hover:bg-maroon-50 transition duration-200">
                            <td class="px-6 py-4"><span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-maroon-100 text-maroon-800"><i class="fas fa-calendar-day mr-1"></i>{{ $j->hari }}</span></td>
                            <td class="px-6 py-4"><div class="font-semibold text-gray-900"><i class="fas fa-clock text-maroon-600 mr-1"></i>{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</div></td>
                            <td class="px-6 py-4"><span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">{{ $j->kelas->section ?? 'TBD' }}</span></td>
                            <td class="px-6 py-4"><div class="font-semibold text-gray-900">{{ $j->kelas->mataKuliah->nama_mk }}</div><div class="text-sm text-gray-500">{{ $j->kelas->mataKuliah->kode_mk }}</div></td>
                            <td class="px-6 py-4">{{ $j->kelas->dosen->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4"><i class="fas fa-door-open text-maroon-600 mr-1"></i>{{ $j->ruangan }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.jadwal.edit', $j) }}" class="bg-yellow-500 text-white p-2 rounded-lg hover:bg-yellow-600 transition" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.jadwal.destroy', $j) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">@csrf @method('DELETE')<button type="submit" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600 transition" title="Hapus"><i class="fas fa-trash"></i></button></form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-inbox text-4xl mb-3"></i><p class="text-lg font-semibold">Belum ada jadwal aktif</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($activeJadwals->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">{{ $activeJadwals->links() }}</div>
            @endif
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-times-circle text-red-500 mr-2"></i>Tolak Jadwal</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea name="catatan" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Jelaskan alasan penolakan..."></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">Tolak Jadwal</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
        });
    });

    // Reject modal
    function openRejectModal(jadwalId) {
        document.getElementById('rejectForm').action = '/admin/jadwal/' + jadwalId + '/reject';
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
