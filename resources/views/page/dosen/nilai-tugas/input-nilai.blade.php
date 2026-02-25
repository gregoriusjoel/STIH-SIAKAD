@extends('layouts.app')

@section('title', 'Input Nilai Tugas — ' . $tugas->title)

@section('content')

    <div class="pt-4 px-4 md:pt-6 md:px-8 pb-12 w-full space-y-6"
         x-data="inputNilaiApp(@js($mahasiswas->map(fn($m) => [
             'id'    => $m->id,
             'nama'  => $m->user?->name ?? $m->nama ?? '-',
             'nim'   => $m->nim ?? '-',
             'score' => $submissions->get($m->id)?->score ?? '',
             'comments' => $submissions->get($m->id)?->comments ?? '',
             'submitted' => $submissions->get($m->id) ? true : false,
             'file_path' => $submissions->get($m->id)?->file_path ?? null,
             'text_submission' => $submissions->get($m->id)?->text_submission ?? null,
             'submission_date' => $submissions->get($m->id)?->created_at ? \Carbon\Carbon::parse($submissions->get($m->id)->created_at)->format('d M Y H:i') : null,
         ])->values()), {{ $tugas->max_score }})">

        {{-- Header --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Input Nilai Tugas</p>
                    <h1 class="text-xl font-extrabold text-gray-900">{{ $tugas->title }}</h1>
                    <div class="flex flex-wrap gap-3 mt-2 text-sm text-gray-500">
                        <span class="px-2.5 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-bold">Pertemuan {{ $tugas->pertemuan }}</span>
                        <span><i class="fas fa-book mr-1"></i>{{ $kelas->mataKuliah->nama_mk ?? $kelas->mataKuliah->nama ?? '-' }}</span>
                        <span><i class="fas fa-star mr-1"></i>Max: <strong>{{ $tugas->max_score }}</strong></span>
                        @if($tugas->deadline)
                            <span><i class="fas fa-calendar-alt mr-1"></i>Deadline: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y') }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex gap-2 flex-wrap shrink-0">
                    <a href="{{ route('dosen.nilai-tugas.index', $kelas->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-left"></i> Daftar Tugas
                    </a>
                    {{-- Ganti tugas --}}
                    <div x-data="{ openSwitch: false }" class="relative">
                        <button @click="openSwitch=!openSwitch"
                                class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                            <i class="fas fa-exchange-alt"></i> Ganti Tugas
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div x-show="openSwitch" @click.outside="openSwitch=false"
                             x-transition class="absolute right-0 mt-2 w-64 bg-white border border-gray-100 rounded-xl shadow-xl z-30 overflow-hidden max-h-96 overflow-y-auto">
                            @foreach($tugasList as $other)
                                <a href="{{ route('dosen.nilai-tugas.input', [$kelas->id, $other->id]) }}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-50 transition
                                          {{ $other->id === $tugas->id ? 'bg-red-50 font-bold text-red-700' : 'text-gray-700' }}">
                                    <span class="text-xs font-bold bg-red-100 text-red-600 px-2 py-0.5 rounded-full">P{{ $other->pertemuan }}</span>
                                    {{ Str::limit($other->title, 30) }}
                                    @if($other->id === $tugas->id)
                                        <i class="fas fa-check ml-auto text-red-600 text-xs"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Progress bar --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-bold text-gray-700">Progress Penilaian</h3>
                <span class="text-sm font-extrabold text-red-700">
                    <span x-text="sudahDinilai()"></span> / {{ $mahasiswas->count() }} mahasiswa
                </span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-700 h-3 rounded-full transition-all duration-500"
                     :style="`width: ${progressPct()}%`"></div>
            </div>
            <p class="text-xs text-gray-400 mt-1.5" x-text="`${progressPct()}% selesai`"></p>
        </div>

        {{-- Toolbar --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                {{-- Search --}}
                <div class="relative flex-1 max-w-xs">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" x-model="search" placeholder="Cari nama atau NIM..."
                           class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                </div>
                {{-- Isi nilai sama semua --}}
                <div class="flex items-center gap-2">
                    <input type="number" x-model="bulkNilai" min="0" :max="maxScore" step="0.5" placeholder="Nilai"
                           class="w-28 px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none text-center">
                    <button @click="isiSemua()"
                            class="px-4 py-2.5 bg-orange-500 text-white rounded-xl text-sm font-bold hover:bg-orange-600 transition">
                        <i class="fas fa-fill-drip mr-1"></i> Isi Semua
                    </button>
                </div>
                {{-- Status dirty --}}
                <div x-show="isDirty" class="flex items-center gap-1.5 text-amber-600 text-xs font-bold bg-amber-50 px-3 py-2 rounded-xl border border-amber-200">
                    <i class="fas fa-circle text-[8px]"></i> Ada perubahan belum disimpan
                </div>
            </div>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
                 class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl text-sm font-medium">
                <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
                <button @click="show=false" class="ml-auto"><i class="fas fa-times text-green-500"></i></button>
            </div>
        @endif

        {{-- Tabel Input Nilai --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="sticky left-0 bg-gray-50 z-10 text-left px-5 py-3.5 font-bold text-gray-600 text-xs uppercase tracking-wider w-10">#</th>
                            <th class="sticky left-10 bg-gray-50 z-10 text-left px-4 py-3.5 font-bold text-gray-600 text-xs uppercase tracking-wider min-w-[200px]">Mahasiswa</th>
                            <th class="text-center px-4 py-3.5 font-bold text-gray-600 text-xs uppercase tracking-wider w-48">Pengumpulan</th>
                            <th class="text-left px-4 py-3.5 font-bold text-gray-600 text-xs uppercase tracking-wider w-40">
                                Nilai <span class="text-gray-400">(0–{{ $tugas->max_score }})</span>
                            </th>
                            <th class="text-left px-4 py-3.5 font-bold text-gray-600 text-xs uppercase tracking-wider">Catatan</th>
                            <th class="text-center px-4 py-3.5 font-bold text-gray-600 text-xs uppercase tracking-wider w-20">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <template x-for="(row, idx) in filteredRows" :key="row.id">
                            <tr :class="{'bg-amber-50/40': rowDirty(row.id), 'hover:bg-gray-50': !rowDirty(row.id)}" class="transition-colors">
                                <td class="sticky left-0 bg-inherit z-10 px-5 py-3 text-xs text-gray-400 font-mono" x-text="idx+1"></td>
                                <td class="sticky left-10 bg-inherit z-10 px-4 py-3">
                                    <p class="font-bold text-gray-900" x-text="row.nama"></p>
                                    <p class="text-xs text-gray-400 font-mono" x-text="row.nim"></p>
                                </td>
                                    <div class="flex flex-col items-center gap-2">
                                        <template x-if="row.submitted">
                                            <div class="flex flex-col items-center gap-1.5 w-full">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-green-100 text-green-700 w-full justify-center">
                                                    <i class="fas fa-check-circle"></i> Sudah Dikumpulkan
                                                </span>
                                                <span class="text-[10px] text-gray-400" x-text="row.submission_date"></span>
                                                <template x-if="row.file_path">
                                                    <a :href="'/storage/' + row.file_path" target="_blank" download
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 transition w-full justify-center">
                                                        <i class="fas fa-download"></i> Download File
                                                    </a>
                                                </template>
                                                <template x-if="row.text_submission && !row.file_path">
                                                    <button @click="$dispatch('open-text-submission', { text: row.text_submission, nama: row.nama })"
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-purple-50 text-purple-700 hover:bg-purple-100 transition w-full justify-center">
                                                        <i class="fas fa-file-alt"></i> Lihat Teks
                                                    </button>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="!row.submitted">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-red-50 text-red-600">
                                                <i class="fas fa-times-circle"></i> Belum Dikumpulkan
                                            </span>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                <td class="px-4 py-3">
                                    <input type="number"
                                           :value="getRow(row.id).score"
                                           @input="setScore(row.id, $event.target.value)"
                                           :min="0" :max="maxScore" step="0.5"
                                           :placeholder="`0–${maxScore}`"
                                           :class="rowDirty(row.id) ? 'border-amber-400 bg-amber-50' : 'border-gray-200'"
                                           class="w-28 px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none text-center font-bold transition-colors">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text"
                                           :value="getRow(row.id).comments"
                                           @input="setComments(row.id, $event.target.value)"
                                           placeholder="opsional"
                                           class="w-full min-w-[160px] px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-colors">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <template x-if="getRow(row.id).score !== '' && getRow(row.id).score !== null">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                            <i class="fas fa-check text-[9px]"></i> Dinilai
                                        </span>
                                    </template>
                                    <template x-if="getRow(row.id).score === '' || getRow(row.id).score === null">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-400">
                                            Kosong
                                        </span>
                                    </template>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            {{-- Empty state --}}
            <div x-show="filteredRows.length === 0"
                 class="flex flex-col items-center justify-center py-16 text-gray-400">
                <i class="fas fa-search text-4xl opacity-20 mb-3"></i>
                <p class="font-medium">Mahasiswa tidak ditemukan</p>
                <p class="text-xs">Coba kata kunci lain</p>
            </div>

            {{-- Footer aksi --}}
            <div class="border-t border-gray-100 px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/50">
                <div class="text-xs text-gray-500 flex gap-4">
                    <span>Total mahasiswa: <strong class="text-gray-800">{{ $mahasiswas->count() }}</strong></span>
                    <span>Sudah dinilai: <strong class="text-green-700" x-text="sudahDinilai()"></strong></span>
                    <span>Belum dinilai: <strong class="text-red-600" x-text="{{ $mahasiswas->count() }} - sudahDinilai()"></strong></span>
                </div>
                <div class="flex gap-3">
                    {{-- Reset --}}
                    <form action="{{ route('dosen.nilai-tugas.reset', [$kelas->id, $tugas->id]) }}" method="POST"
                          onsubmit="return confirm('Reset semua nilai? Tindakan ini tidak bisa dibatalkan!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-500 hover:bg-gray-50 hover:text-red-600 hover:border-red-200 transition">
                            <i class="fas fa-undo mr-1"></i> Reset Semua
                        </button>
                    </form>

                    {{-- Simpan --}}
                    <button @click="simpan()" :disabled="saving"
                            :class="saving ? 'opacity-60 cursor-wait' : 'hover:bg-red-800'"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-red-700 text-white rounded-xl text-sm font-bold transition shadow-sm">
                        <template x-if="!saving">
                            <span><i class="fas fa-save mr-1"></i> Simpan Perubahan</span>
                        </template>
                        <template x-if="saving">
                            <span><i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...</span>
                        </template>
                    </button>
                </div>
            </div>
        </div>

        {{-- Toast notifikasi --}}
        <div x-show="toast.show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4"
             :class="toast.type === 'success' ? 'bg-green-600' : 'bg-red-600'"
             class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-3.5 text-white rounded-xl shadow-xl text-sm font-bold"
             style="display:none">
            <i :class="toast.type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'"></i>
            <span x-text="toast.message"></span>
        </div>

    </div>

    {{-- MODAL VIEW TEXT SUBMISSION --}}
    <div x-data="{ open: false, submissionText: '', mahasiswaNama: '' }" 
         @open-text-submission.window="
            open = true;
            submissionText = $event.detail.text;
            mahasiswaNama = $event.detail.nama;
         "
         x-show="open" 
         style="display: none;" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         role="dialog" 
         aria-modal="true">
        
        {{-- Backdrop --}}
        <div x-show="open" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" 
             @click="open = false"></div>

        {{-- Modal Panel --}}
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">

                {{-- Header --}}
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="size-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
                                <i class="fas fa-file-alt text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Jawaban Tugas (Teks)</h3>
                                <p class="text-sm text-white/90 mt-0.5" x-text="mahasiswaNama"></p>
                            </div>
                        </div>
                        <button type="button" @click="open = false" class="text-white/80 hover:text-white transition-colors p-2 hover:bg-white/10 rounded-lg">
                            <i class="fas fa-times text-2xl"></i>
                        </button>
                    </div>
                </div>

                {{-- Content --}}
                <div class="px-8 py-6 max-h-[60vh] overflow-y-auto">
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap" x-text="submissionText"></p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-8 pb-6 pt-4 border-t border-gray-100 flex justify-end">
                    <button type="button" @click="open = false"
                            class="px-5 py-2.5 bg-gray-600 text-white rounded-xl text-sm font-bold hover:bg-gray-700 transition">
                        <i class="fas fa-times mr-2"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function inputNilaiApp(initialRows, maxScore) {
    return {
        maxScore,
        search: '',
        bulkNilai: '',
        saving: false,
        isDirty: false,
        toast: { show: false, type: 'success', message: '' },

        // State utama: array of { id, nama, nim, score, comments }
        rows: JSON.parse(JSON.stringify(initialRows)),
        // Snapshot untuk dirty tracking
        original: JSON.parse(JSON.stringify(initialRows)),

        get filteredRows() {
            const q = this.search.toLowerCase().trim();
            if (!q) return this.rows;
            return this.rows.filter(r =>
                r.nama.toLowerCase().includes(q) ||
                r.nim.toString().toLowerCase().includes(q)
            );
        },

        getRow(id) {
            return this.rows.find(r => r.id === id) ?? { score: '', comments: '' };
        },

        setScore(id, val) {
            const row = this.rows.find(r => r.id === id);
            if (row) { row.score = val; this.isDirty = true; }
        },

        setComments(id, val) {
            const row = this.rows.find(r => r.id === id);
            if (row) { row.comments = val; this.isDirty = true; }
        },

        rowDirty(id) {
            const cur = this.rows.find(r => r.id === id);
            const ori = this.original.find(r => r.id === id);
            if (!cur || !ori) return false;
            return String(cur.score) !== String(ori.score) || String(cur.comments) !== String(ori.comments);
        },

        sudahDinilai() {
            return this.rows.filter(r => r.score !== '' && r.score !== null && r.score !== undefined).length;
        },

        progressPct() {
            if (!this.rows.length) return 0;
            return Math.round((this.sudahDinilai() / this.rows.length) * 100);
        },

        isiSemua() {
            const v = parseFloat(this.bulkNilai);
            if (isNaN(v) || v < 0 || v > this.maxScore) {
                this.showToast('error', `Nilai harus antara 0 dan ${this.maxScore}`);
                return;
            }
            this.rows.forEach(r => r.score = v);
            this.isDirty = true;
        },

        async simpan() {
            this.saving = true;
            const payload = {
                _token: document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                scores: this.rows.map(r => ({
                    mahasiswa_id: r.id,
                    score: r.score,
                    comments: r.comments ?? '',
                })),
            };

            try {
                const res = await fetch('{{ route('dosen.nilai-tugas.simpan', [$kelas->id, $tugas->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': payload._token,
                    },
                    body: JSON.stringify(payload),
                });

                if (res.ok) {
                    this.original = JSON.parse(JSON.stringify(this.rows));
                    this.isDirty = false;
                    this.showToast('success', 'Nilai berhasil disimpan!');
                } else {
                    const err = await res.json().catch(() => ({}));
                    this.showToast('error', err.message ?? 'Gagal menyimpan nilai. Coba lagi.');
                }
            } catch (e) {
                this.showToast('error', 'Terjadi kesalahan jaringan.');
            } finally {
                this.saving = false;
            }
        },

        showToast(type, message) {
            this.toast = { show: true, type, message };
            setTimeout(() => this.toast.show = false, 3500);
        },
    }
}
</script>
@endpush
