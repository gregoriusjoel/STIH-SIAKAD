@if($historyPivots->count() > 0)
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-purple-800 text-white">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase">No</th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase">Kode MK</th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase">Nama Mata Kuliah</th>
                <th class="px-4 py-3 text-center text-xs font-bold uppercase">SKS</th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase">Prodi</th>
                <th class="px-4 py-3 text-center text-xs font-bold uppercase">Status</th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase">Asal Semester</th>
                <th class="px-4 py-3 text-center text-xs font-bold uppercase">Non-aktif Sejak</th>
                <th class="px-4 py-3 text-center text-xs font-bold uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($historyPivots as $i => $pivot)
                @php $mk = $pivot->mataKuliah; @endphp
                <tr class="hover:bg-purple-50 dark:hover:bg-purple-900/10 transition {{ $pivot->status === 'archived' ? 'opacity-70' : '' }}">
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $i + 1 }}</td>
                    <td class="px-4 py-3">
                        <span class="font-mono text-xs font-bold text-purple-700 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20 px-2 py-0.5 rounded">
                            {{ $mk->kode_mk }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $mk->nama_mk }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $mk->prodi?->nama_prodi }}</div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            {{ $mk->sks }} SKS
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $mk->prodi?->nama_prodi }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($pivot->status === 'history')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">
                            <i class="fas fa-clock text-xs"></i> Histori
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                            <i class="fas fa-archive text-xs"></i> Diarsipkan
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400">
                        @if($pivot->source_semester_id)
                        <span class="flex items-center gap-1">
                            <i class="fas fa-arrow-right text-xs"></i>
                            {{ $pivot->sourceSemester?->display_label }}
                        </span>
                        @else
                        <span>—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center text-xs text-gray-500 dark:text-gray-400">
                        {{ $pivot->deactivated_at ? $pivot->deactivated_at->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        {{-- Restore button (only on active target semester and not locked) --}}
                        @if(isset($semester) && $semester)
                            @php $activeSem = app(\App\Services\SemesterService::class)->getActiveSemester(); @endphp
                            @if($activeSem && !$activeSem->is_locked)
                            <form method="POST" action="{{ route('admin.mata-kuliah-semester.restore') }}"
                                onsubmit="return confirm('Pulihkan {{ $mk->nama_mk }} ke semester aktif?')">
                                @csrf
                                <input type="hidden" name="source_semester_id" value="{{ $semester->id }}">
                                <input type="hidden" name="target_semester_id" value="{{ $activeSem->id }}">
                                <input type="hidden" name="mata_kuliah_ids[]" value="{{ $mk->id }}">
                                <button type="submit"
                                    class="px-3 py-1.5 text-xs bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 hover:bg-purple-200 dark:hover:bg-purple-900/50 rounded-lg transition font-medium">
                                    <i class="fas fa-undo mr-1"></i> Pulihkan
                                </button>
                            </form>
                            @else
                            <span class="text-gray-300 dark:text-gray-600 text-xs"><i class="fas fa-lock"></i></span>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="text-center py-16 text-gray-400 dark:text-gray-500">
    <i class="fas fa-archive text-5xl mb-4"></i>
    <p class="text-lg font-medium">Tidak ada histori mata kuliah untuk semester ini.</p>
    <p class="text-sm mt-1">Histori muncul saat semester baru diaktifkan.</p>
</div>
@endif
