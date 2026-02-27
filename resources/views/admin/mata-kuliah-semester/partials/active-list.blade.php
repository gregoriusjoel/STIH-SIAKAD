@if($pivots->count() > 0)
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-maroon text-white">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase w-8">
                    <input type="checkbox" id="select-all-active" class="w-4 h-4 rounded border-white/30 bg-white/10 text-white"
                        onchange="document.querySelectorAll('.active-mk-checkbox').forEach(c => c.checked = this.checked)">
                </th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase">No</th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase">Kode MK</th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase">Nama Mata Kuliah</th>
                <th class="px-4 py-3 text-center text-xs font-bold uppercase">SKS</th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase">Prodi</th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase">Asal</th>
                <th class="px-4 py-3 text-center text-xs font-bold uppercase">Aktif Sejak</th>
                <th class="px-4 py-3 text-center text-xs font-bold uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($pivots as $i => $pivot)
                @php $mk = $pivot->mataKuliah; @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <td class="px-4 py-3">
                        <input type="checkbox" name="mk_ids[]" value="{{ $mk->id }}"
                            class="active-mk-checkbox w-4 h-4 rounded border-gray-300 text-maroon">
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $i + 1 }}</td>
                    <td class="px-4 py-3">
                        <span class="font-mono text-xs font-bold text-maroon dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-2 py-0.5 rounded">
                            {{ $mk->kode_mk }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $mk->nama_mk }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Semester {{ $mk->semester }}</div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400">
                            {{ $mk->sks }} SKS
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $mk->prodi?->nama_prodi }}</td>
                    <td class="px-4 py-3">
                        @if($pivot->source_semester_id)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400"
                            title="Carry-forward dari {{ $pivot->sourceSemester?->display_label }}">
                            <i class="fas fa-copy text-xs"></i>
                            {{ $pivot->sourceSemester?->display_label }}
                        </span>
                        @else
                        <span class="text-xs text-gray-400 dark:text-gray-500">Manual</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center text-xs text-gray-500 dark:text-gray-400">
                        {{ $pivot->activated_at ? $pivot->activated_at->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($semester && !$semester->is_locked)
                        <form method="POST" action="{{ route('admin.mata-kuliah-semester.detach') }}"
                            onsubmit="return confirm('Pindahkan {{ $mk->nama_mk }} ke histori?')">
                            @csrf
                            <input type="hidden" name="semester_id" value="{{ $semester->id }}">
                            <input type="hidden" name="mata_kuliah_ids[]" value="{{ $mk->id }}">
                            <button type="submit"
                                class="p-2 text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-300 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded transition"
                                title="Pindah ke Histori">
                                <i class="fas fa-archive text-xs"></i>
                            </button>
                        </form>
                        @else
                        <span class="text-gray-300 dark:text-gray-600"><i class="fas fa-lock"></i></span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Bulk detach --}}
@if($semester && !$semester->is_locked)
<div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-t dark:border-gray-700">
    <form method="POST" action="{{ route('admin.mata-kuliah-semester.detach') }}"
        onsubmit="return confirm('Pindahkan semua MK yang dipilih ke histori?')" id="bulk-detach-form">
        @csrf
        <input type="hidden" name="semester_id" value="{{ $semester->id }}">
        <div id="bulk-detach-hidden"></div>
        <button type="submit" onclick="prepareBulkDetach()"
            class="px-4 py-2 text-xs bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition flex items-center gap-1">
            <i class="fas fa-archive"></i> Pindah Histori (Terpilih)
        </button>
    </form>
</div>
<script>
function prepareBulkDetach() {
    const checked = document.querySelectorAll('.active-mk-checkbox:checked');
    const container = document.getElementById('bulk-detach-hidden');
    container.innerHTML = '';
    checked.forEach(c => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'mata_kuliah_ids[]';
        input.value = c.value;
        container.appendChild(input);
    });
}
</script>
@endif

@else
<div class="text-center py-16 text-gray-400 dark:text-gray-500">
    <i class="fas fa-book-open text-5xl mb-4"></i>
    <p class="text-lg font-medium">Belum ada mata kuliah aktif untuk semester ini.</p>
    <p class="text-sm mt-1">Tambahkan atau gunakan Carry Forward dari semester sebelumnya.</p>
</div>
@endif
