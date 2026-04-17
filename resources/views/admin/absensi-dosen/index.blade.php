@extends('layouts.admin')

@section('title', 'Absensi Dosen')
@section('page-title', 'Absensi Dosen')

@section('content')
    <div class="mb-6 flex flex-col items-start md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                <i class="fas fa-clipboard-check mr-3 text-maroon dark:text-red-500"></i>
                Rekap Absensi Dosen
            </h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Satu baris per dosen per mata kuliah. Klik Detail untuk
                melihat riwayat setiap pertemuan.</p>
        </div>
    </div>

    {{-- Filters (client-side real-time) --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Cari Nama / MK</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" id="absen-search"
                        placeholder="Nama dosen atau mata kuliah..."
                        class="w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-maroon/30"
                        oninput="filterAbsenTable()">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Dosen</label>
                <select id="absen-dosen"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-maroon/30"
                    onchange="filterAbsenTable()">
                    <option value="">Semua Dosen</option>
                    @foreach($dosens as $d)
                        <option value="{{ $d['id'] }}" {{ request('dosen_id') == $d['id'] ? 'selected' : '' }}>
                            {{ $d['nama'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button type="button" onclick="resetAbsenFilter()"
                    class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg text-sm font-bold hover:bg-gray-50 transition-all">
                    <i class="fas fa-times mr-1"></i> Reset
                </button>
                <span id="absen-count" class="text-xs text-gray-400 dark:text-gray-500 self-center"></span>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-maroon text-white">
                    <tr>
                        <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider">No</th>
                        <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider">Dosen</th>
                        <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider">Mata Kuliah / Kelas</th>
                        <th class="px-5 py-4 text-center text-xs font-bold uppercase tracking-wider">Total Pertemuan</th>
                        <th class="px-5 py-4 text-center text-xs font-bold uppercase tracking-wider">Terakhir Absen</th>
                        <th class="px-5 py-4 text-center text-xs font-bold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($groups as $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition absen-row"
                            data-dosen-id="{{ $row->dosen_id }}"
                            data-nama="{{ strtolower($row->dosen->user->name ?? '') }}"
                            data-mk="{{ strtolower($row->kelasMataKuliah->mataKuliah->nama_mk ?? '') }}"
                            data-kode="{{ strtolower($row->kelasMataKuliah->mataKuliah->kode_mk ?? '') }}">
                            <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400 font-medium">
                                {{ ($groups->currentPage() - 1) * $groups->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-10 w-10 rounded-full bg-maroon flex items-center justify-center text-white text-sm font-bold shrink-0">
                                        {{ strtoupper(substr($row->dosen->user->name ?? 'D', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                                            {{ $row->dosen->user->name ?? 'Dosen ' . $row->dosen_id }}
                                        </p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">NIDN: {{ $row->dosen->nidn ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                                    {{ $row->kelasMataKuliah->mataKuliah->nama_mk ?? '-' }}
                                </p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                    Kelas {{ $row->kelasMataKuliah->kode_kelas ?? '-' }}
                                    &bull; {{ $row->kelasMataKuliah->mataKuliah->kode_mk ?? '-' }}
                                </p>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span
                                    class="inline-flex items-center justify-center w-9 h-9 bg-maroon/10 dark:bg-maroon/20 text-maroon dark:text-red-400 rounded-xl text-sm font-black">
                                    {{ $row->total_pertemuan }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($row->last_absen)->format('d M Y') }}
                                </p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ \Carbon\Carbon::parse($row->last_absen)->format('H:i') }} WIB
                                </p>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <a href="{{ route('admin.absensi_dosen.show', [$row->dosen_id, $row->kelas_mata_kuliah_id]) }}"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-maroon text-white rounded-lg text-xs font-bold hover:bg-red-900 transition-all">
                                    <i class="fas fa-eye text-xs"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-16 text-center text-gray-400 dark:text-gray-500">
                                <i class="fas fa-clipboard-list text-4xl mb-3 block opacity-30"></i>
                                <p class="text-sm font-medium">Belum ada data absensi dosen.</p>
                            </td>
                        </tr>
                    @endforelse
                    {{-- Empty state saat filter tidak ada hasil --}}
                    <tr id="absen-empty-row" style="display:none">
                        <td colspan="6" class="px-4 py-16 text-center text-gray-400 dark:text-gray-500">
                            <i class="fas fa-search text-4xl mb-3 block opacity-30"></i>
                            <p class="text-sm font-semibold">Tidak ada data yang sesuai.</p>
                            <p class="text-xs mt-1">Coba kata kunci atau filter lain.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($groups->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $groups->links() }}
            </div>
        @endif
    </div>

@push('scripts')
<script>
function filterAbsenTable() {
    const q       = (document.getElementById('absen-search').value || '').toLowerCase().trim();
    const dosenId = document.getElementById('absen-dosen').value;
    const rows    = document.querySelectorAll('.absen-row');
    let visible   = 0;

    rows.forEach(row => {
        const nama       = row.dataset.nama  || '';
        const mk         = row.dataset.mk    || '';
        const kode       = row.dataset.kode  || '';
        const rowDosenId = row.dataset.dosenId || '';

        const matchSearch = !q || nama.includes(q) || mk.includes(q) || kode.includes(q);
        const matchDosen  = !dosenId || rowDosenId === dosenId;

        if (matchSearch && matchDosen) {
            row.style.display = '';
            visible++;
            const noTd = row.querySelector('td:first-child');
            if (noTd) noTd.textContent = visible;
        } else {
            row.style.display = 'none';
        }
    });

    // Tampilkan empty state jika tidak ada hasil
    const emptyRow = document.getElementById('absen-empty-row');
    if (emptyRow) emptyRow.style.display = (visible === 0 && rows.length > 0) ? '' : 'none';

    const countEl = document.getElementById('absen-count');
    if (countEl) {
        countEl.textContent = (q || dosenId)
            ? `Menampilkan ${visible} dari ${rows.length} data`
            : '';
    }
}

function resetAbsenFilter() {
    const s = document.getElementById('absen-search');
    const d = document.getElementById('absen-dosen');
    if (s) s.value = '';
    if (d) d.value = '';
    filterAbsenTable();
}
</script>
@endpush

@endsection