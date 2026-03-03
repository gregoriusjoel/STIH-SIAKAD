@extends('layouts.admin')

@section('title', 'Detail Dosen')
@section('page-title', 'Detail Dosen')

@section('content')
<div x-data="dosenDetail()" class="w-full max-w-6xl mx-auto space-y-6">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                <span class="text-green-700">{{ session('success') }}</span>
            </div>
        </div>
    @endif
    @if(session('warning'))
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                <span class="text-yellow-700">{{ session('warning') }}</span>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-times-circle text-red-500 mr-2"></i>
                <span class="text-red-700">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    {{-- Tab Navigation --}}
    <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button @click="activeTab = 'profil'"
                    :class="activeTab === 'profil' ? 'border-maroon text-maroon' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-user-tie mr-2"></i>Profil
                </button>
                <button @click="activeTab = 'penugasan'"
                    :class="activeTab === 'penugasan' ? 'border-maroon text-maroon' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-book mr-2"></i>Penugasan TA Aktif
                    @if($activeSemester)
                        <span class="ml-1 text-xs bg-maroon text-white px-2 py-0.5 rounded-full">{{ $activeSemester->nama_semester }} {{ $activeSemester->tahun_ajaran }}</span>
                    @endif
                </button>
                <button @click="activeTab = 'histori'"
                    :class="activeTab === 'histori' ? 'border-maroon text-maroon' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-history mr-2"></i>Histori Mengajar
                </button>
            </nav>
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- TAB 1: PROFIL --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div x-show="activeTab === 'profil'" x-transition class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <h4 class="text-lg font-semibold text-gray-700">{{ $dosen->user->name }}</h4>
                    <p class="text-sm text-gray-500">{{ $dosen->user->email }}</p>
                    <div class="mt-4 text-sm text-gray-700 space-y-2">
                        <div><strong>NIDN:</strong> {{ $dosen->nidn }}</div>
                        <div><strong>Program Studi:</strong>
                            {{ is_array($dosen->prodi) ? implode(', ', $dosen->prodi) : $dosen->prodi }}</div>
                        <div><strong>Pendidikan:</strong> {{ $dosen->pendidikan ?? '-' }}</div>
                        @if(is_array($dosen->pendidikan_terakhir) && count($dosen->pendidikan_terakhir))
                            <div><strong>Riwayat Pendidikan:</strong> {{ implode(' → ', $dosen->pendidikan_terakhir) }}</div>
                        @endif
                        @if(is_array($dosen->universitas) && count($dosen->universitas))
                            <div><strong>Universitas:</strong> {{ implode(', ', $dosen->universitas) }}</div>
                        @endif
                        <div><strong>Dosen Tetap:</strong> {{ $dosen->dosen_tetap ? 'Ya' : 'Tidak' }}</div>
                        @if(is_array($dosen->jabatan_fungsional) && count($dosen->jabatan_fungsional))
                            <div><strong>Jabatan Fungsional:</strong> {{ implode(', ', $dosen->jabatan_fungsional) }}</div>
                        @endif
                        <div><strong>Telepon:</strong> {{ $dosen->phone ?? '-' }}</div>
                        <div><strong>Alamat:</strong> {{ $dosen->address ?? '-' }}</div>
                        <div><strong>Kuota Kelas:</strong> {{ $dosen->kuota ?? 6 }}</div>
                        <div><strong>Status:</strong>
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $dosen->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($dosen->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-1 flex items-start justify-center">
                    <div class="h-28 w-28 rounded-full bg-maroon flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                        {{ strtoupper(substr($dosen->user->name, 0, 1)) }}
                    </div>
                </div>
            </div>

            {{-- Current kelas summary --}}
            <div class="mt-6">
                <h5 class="text-md font-semibold text-gray-700 mb-3">
                    <i class="fas fa-chalkboard-teacher mr-2 text-maroon"></i>Kelas Aktif
                </h5>
                @if($dosen->kelasMataKuliahs && $dosen->kelasMataKuliahs->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($dosen->kelasMataKuliahs as $km)
                            <div class="p-3 border rounded-lg flex items-center justify-between bg-gray-50">
                                <div>
                                    <div class="text-sm font-semibold">{{ $km->mataKuliah->nama_mk ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $km->kode_kelas ?? '-' }} · {{ $km->hari ?? '-' }} {{ $km->jam_mulai ?? '' }}-{{ $km->jam_selesai ?? '' }}</div>
                                </div>
                                <div class="text-xs text-gray-500">{{ $km->mataKuliah->sks ?? '-' }} SKS</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-sm text-gray-500 italic">Belum ada kelas aktif.</div>
                @endif
            </div>

            <div class="flex justify-end mt-6 space-x-3">
                <a href="{{ route('admin.dosen.index') }}" class="px-5 py-2 border rounded text-gray-700 hover:bg-gray-50 text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
                <a href="{{ route('admin.dosen.edit', $dosen) }}" class="btn-maroon px-5 py-2 rounded text-sm">
                    <i class="fas fa-edit mr-1"></i>Edit Profil
                </a>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- TAB 2: PENUGASAN TA AKTIF --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div x-show="activeTab === 'penugasan'" x-transition class="p-6">
            @if(!$activeSemester)
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-calendar-times text-4xl mb-3"></i>
                    <p>Tidak ada semester aktif. Silakan aktifkan semester terlebih dahulu.</p>
                </div>
            @else
                <div class="space-y-6">
                    {{-- Header --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h5 class="text-lg font-semibold text-gray-700">
                                Penugasan Mengajar — {{ $activeSemester->nama_semester }} {{ $activeSemester->tahun_ajaran }}
                            </h5>
                            <p class="text-sm text-gray-500">Total: {{ $currentAssignments->count() }} MK · {{ $currentAssignments->sum('sks') }} SKS</p>
                        </div>

                        {{-- Copy from previous TA --}}
                        @if($previousSemester && $previousAssignments->count() > 0)
                            <form method="POST" action="{{ route('admin.dosen.assignments.copy', $dosen) }}"
                                onsubmit="return confirm('Salin {{ $previousAssignments->count() }} MK dari {{ $previousSemester->nama_semester }} {{ $previousSemester->tahun_ajaran }}?')">
                                @csrf
                                <input type="hidden" name="source_semester_id" value="{{ $previousSemester->id }}">
                                <input type="hidden" name="target_semester_id" value="{{ $activeSemester->id }}">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                    <i class="fas fa-copy mr-2"></i>Copy dari {{ $previousSemester->nama_semester }} {{ $previousSemester->tahun_ajaran }}
                                    <span class="ml-1 bg-blue-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $previousAssignments->count() }}</span>
                                </button>
                            </form>
                        @endif
                    </div>

                    {{-- Current Assignments Table --}}
                    @if($currentAssignments->count() > 0)
                        <div class="overflow-x-auto border rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode MK</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Mata Kuliah</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">SKS</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($currentAssignments as $i => $mk)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-700">{{ $i + 1 }}</td>
                                            <td class="px-4 py-3 text-sm font-mono text-gray-700">{{ $mk->kode_mk }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">{{ $mk->nama_mk }}</td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-700">{{ $mk->sks }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <form method="POST"
                                                    action="{{ route('admin.dosen.assignments.destroy', [$dosen, $mk]) }}"
                                                    onsubmit="return confirm('Hapus penugasan {{ $mk->nama_mk }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="semester_id" value="{{ $activeSemester->id }}">
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm" title="Hapus">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 text-sm font-semibold text-gray-700 text-right">Total SKS</td>
                                        <td class="px-4 py-2 text-sm text-center font-bold text-maroon">{{ $currentAssignments->sum('sks') }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400 border-2 border-dashed rounded-lg">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p class="text-sm">Belum ada penugasan untuk semester ini.</p>
                        </div>
                    @endif

                    {{-- ─── Add Assignment (Draft → Save) ─── --}}
                    <div class="border-t pt-6">
                        <h6 class="text-md font-semibold text-gray-700 mb-4">
                            <i class="fas fa-plus-circle mr-2 text-maroon"></i>Tambah Penugasan
                        </h6>

                        <form method="POST" action="{{ route('admin.dosen.assignments.store', $dosen) }}" x-ref="assignForm">
                            @csrf
                            <input type="hidden" name="semester_id" value="{{ $activeSemester->id }}">

                            {{-- Search & Select MK --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cari & Pilih Mata Kuliah</label>
                                <input type="text"
                                    x-model="mkSearch"
                                    placeholder="Ketik kode atau nama mata kuliah..."
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-maroon focus:border-maroon text-sm px-4 py-2">
                            </div>

                            {{-- Available MK Grid --}}
                            <div class="max-h-64 overflow-y-auto border rounded-lg p-2 mb-4 bg-gray-50">
                                <template x-for="mk in filteredMataKuliah" :key="mk.id">
                                    <label class="flex items-center px-3 py-2 hover:bg-white rounded cursor-pointer transition"
                                        :class="draftIds.includes(mk.id) ? 'bg-blue-50 border border-blue-200' : ''"
                                        x-show="!currentIds.includes(mk.id)">
                                        <input type="checkbox"
                                            :value="mk.id"
                                            x-model.number="draftIds"
                                            class="rounded border-gray-300 text-maroon focus:ring-maroon mr-3">
                                        <div class="flex-1">
                                            <span class="text-sm font-medium" x-text="mk.kode_mk"></span>
                                            <span class="text-sm text-gray-600 ml-2" x-text="mk.nama_mk"></span>
                                        </div>
                                        <span class="text-xs text-gray-500" x-text="mk.sks + ' SKS'"></span>
                                    </label>
                                </template>
                                <div x-show="filteredMataKuliah.length === 0" class="text-center py-4 text-gray-400 text-sm">
                                    Tidak ada mata kuliah ditemukan.
                                </div>
                            </div>

                            {{-- Draft Preview --}}
                            <div x-show="draftIds.length > 0" class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg" x-transition>
                                <div class="flex items-center justify-between mb-2">
                                    <h6 class="text-sm font-semibold text-blue-800">
                                        <i class="fas fa-clipboard-list mr-1"></i>Draft Penugasan
                                        (<span x-text="draftIds.length"></span> MK · <span x-text="draftSks"></span> SKS)
                                    </h6>
                                    <button type="button" @click="draftIds = []" class="text-xs text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-times mr-1"></i>Hapus Semua
                                    </button>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="id in draftIds" :key="id">
                                        <span class="inline-flex items-center bg-white border border-blue-300 rounded-full px-3 py-1 text-xs">
                                            <span x-text="getMkName(id)"></span>
                                            <button type="button" @click="draftIds = draftIds.filter(d => d !== id)" class="ml-2 text-red-400 hover:text-red-600">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </span>
                                    </template>
                                </div>
                            </div>

                            {{-- Hidden inputs for form submission --}}
                            <template x-for="id in draftIds" :key="'hidden-'+id">
                                <input type="hidden" name="mata_kuliah_ids[]" :value="id">
                            </template>

                            {{-- Submit --}}
                            <div class="flex justify-end">
                                <button type="submit"
                                    :disabled="draftIds.length === 0"
                                    :class="draftIds.length === 0 ? 'bg-gray-300 cursor-not-allowed' : 'btn-maroon hover:opacity-90'"
                                    class="px-6 py-2 rounded-lg text-white text-sm font-medium transition">
                                    <i class="fas fa-save mr-2"></i>Simpan Penugasan
                                    <span x-show="draftIds.length > 0" x-text="'(' + draftIds.length + ' MK)'" class="ml-1"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- TAB 3: HISTORI MENGAJAR --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div x-show="activeTab === 'histori'" x-transition class="p-6">
            <h5 class="text-lg font-semibold text-gray-700 mb-4">
                <i class="fas fa-history mr-2 text-maroon"></i>Histori Penugasan Mengajar
            </h5>

            @if($historySemesters->count() > 0)
                {{-- Semester Selector --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Semester</label>
                    <select x-model="selectedHistorySemester" @change="loadHistory()"
                        class="border-gray-300 rounded-lg shadow-sm focus:ring-maroon focus:border-maroon text-sm w-full sm:w-auto">
                        <option value="">-- Pilih Semester --</option>
                        @foreach($historySemesters as $sem)
                            <option value="{{ $sem->id }}">
                                {{ $sem->nama_semester }} {{ $sem->tahun_ajaran }}
                                ({{ $sem->assignment_count }} MK, {{ $sem->kelas_count }} Kelas)
                                @if($sem->is_current) ★ Aktif @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- History Content --}}
                <div x-show="historyLoading" class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-maroon"></i>
                    <p class="text-sm text-gray-500 mt-2">Memuat data...</p>
                </div>

                <div x-show="!historyLoading && historyData" x-transition>
                    <div class="mb-3 text-sm text-gray-600">
                        <span class="font-medium" x-text="historyData?.semester?.nama_semester + ' ' + historyData?.semester?.tahun_ajaran"></span>
                        — <span x-text="historyData?.assignments?.length + ' MK'"></span>
                        · <span x-text="historyData?.total_sks + ' SKS'"></span>
                    </div>

                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode MK</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Mata Kuliah</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">SKS</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="(mk, idx) in historyData?.assignments ?? []" :key="mk.id">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-700" x-text="idx + 1"></td>
                                        <td class="px-4 py-3 text-sm font-mono text-gray-700" x-text="mk.kode_mk"></td>
                                        <td class="px-4 py-3 text-sm text-gray-700" x-text="mk.nama_mk"></td>
                                        <td class="px-4 py-3 text-sm text-center text-gray-700" x-text="mk.sks"></td>
                                        <td class="px-4 py-3 text-sm text-center">
                                            <span x-show="mk.kelas_count > 0" class="text-green-600 font-medium" x-text="mk.kelas_count + ' kelas'"></span>
                                            <span x-show="mk.kelas_count === 0" class="text-gray-400 text-xs">-</span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-4 py-2 text-sm font-semibold text-gray-700 text-right">Total SKS</td>
                                    <td class="px-4 py-2 text-sm text-center font-bold text-maroon" x-text="historyData?.total_sks"></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div x-show="!historyLoading && !historyData && selectedHistorySemester === ''"
                    class="text-center py-8 text-gray-400 border-2 border-dashed rounded-lg">
                    <i class="fas fa-search text-3xl mb-2"></i>
                    <p class="text-sm">Pilih semester untuk melihat histori penugasan.</p>
                </div>
            @else
                <div class="text-center py-12 text-gray-400">
                    <i class="fas fa-folder-open text-4xl mb-3"></i>
                    <p class="text-sm">Belum ada histori penugasan mengajar.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function dosenDetail() {
    return {
        activeTab: 'profil',
        mkSearch: '',
        draftIds: [],
        selectedHistorySemester: '',
        historyData: null,
        historyLoading: false,

        // All available MK (not yet assigned in current semester)
        allMataKuliah: @json($availableMataKuliah->map(fn($mk) => ['id' => $mk->id, 'kode_mk' => $mk->kode_mk, 'nama_mk' => $mk->nama_mk, 'sks' => $mk->sks])),

        // Currently assigned MK IDs
        currentIds: @json($currentAssignments->pluck('id')->values()),

        get filteredMataKuliah() {
            if (!this.mkSearch.trim()) return this.allMataKuliah;
            const q = this.mkSearch.toLowerCase();
            return this.allMataKuliah.filter(mk =>
                mk.kode_mk.toLowerCase().includes(q) ||
                mk.nama_mk.toLowerCase().includes(q)
            );
        },

        get draftSks() {
            return this.draftIds.reduce((sum, id) => {
                const mk = this.allMataKuliah.find(m => m.id === id);
                return sum + (mk ? mk.sks : 0);
            }, 0);
        },

        getMkName(id) {
            const mk = this.allMataKuliah.find(m => m.id === id);
            return mk ? mk.kode_mk + ' - ' + mk.nama_mk : 'MK #' + id;
        },

        async loadHistory() {
            if (!this.selectedHistorySemester) {
                this.historyData = null;
                return;
            }
            this.historyLoading = true;
            try {
                const url = `{{ url('admin/dosen/' . $dosen->id . '/assignments/history') }}/${this.selectedHistorySemester}`;
                const resp = await fetch(url, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                this.historyData = await resp.json();
            } catch (e) {
                console.error('Failed to load history', e);
                this.historyData = null;
            } finally {
                this.historyLoading = false;
            }
        }
    };
}
</script>
@endpush
