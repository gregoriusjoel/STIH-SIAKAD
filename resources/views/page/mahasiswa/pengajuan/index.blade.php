@extends('layouts.mahasiswa')

@section('title', 'Pengajuan Mahasiswa')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pt-2 pb-8 w-full max-w-9xl mx-auto font-inter">

        {{-- Page Header --}}
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold text-text-primary tracking-tight">Pengajuan Mahasiswa</h1>
                <p class="text-sm text-text-secondary mt-1">Ajukan cuti akademik atau surat keterangan aktif kuliah dengan mudah.</p>
            </div>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <button @click="$dispatch('open-modal', 'pengajuan-modal')" 
                    class="group relative flex items-center h-10 bg-primary text-white rounded-full transition-[width] duration-300 ease-in-out w-10 hover:w-50 overflow-hidden shadow-lg active:scale-95">
                    {{-- Icon Container --}}
                    <div class="flex items-center justify-center w-10 h-10 flex-shrink-0">
                        <i class="fas fa-plus"></i>
                    </div>
                    
                    {{-- Sliding Text --}}
                    <span class="opacity-0 group-hover:opacity-100 transform translate-x-4 group-hover:translate-x-0 transition-all duration-300 ease-in-out font-bold text-sm whitespace-nowrap pr-4">
                        Buat Pengajuan Baru
                    </span>
                </button>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Total Pengajuan --}}
            <div class="relative overflow-hidden bg-white dark:bg-bg-card rounded-2xl p-6 shadow-sm border border-border-color group hover:border-blue-500/30 transition-colors">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xs font-bold text-blue-600 uppercase tracking-wider bg-blue-50 dark:bg-blue-900/20 px-2 py-1 rounded-md">Total Pengajuan</h3>
                    </div>
                    <div class="text-3xl font-bold text-text-primary group-hover:scale-105 transition-transform origin-left">{{ $allPengajuans->count() }}</div>
                    <p class="text-xs text-text-muted mt-1">Seluruh riwayat pengajuan</p>
                </div>
                <div class="absolute right-0 bottom-0 opacity-[0.03] transform translate-y-2 translate-x-2 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-file-alt text-9xl text-blue-600"></i>
                </div>
            </div>

            {{-- Menunggu Persetujuan --}}
            <div class="relative overflow-hidden bg-white dark:bg-bg-card rounded-2xl p-6 shadow-sm border border-border-color group hover:border-yellow-500/30 transition-colors">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xs font-bold text-yellow-600 uppercase tracking-wider bg-yellow-50 dark:bg-yellow-900/20 px-2 py-1 rounded-md">Menunggu Review</h3>
                    </div>
                    <div class="text-3xl font-bold text-text-primary group-hover:scale-105 transition-transform origin-left">{{ $allPengajuans->where('status', 'submitted')->count() }}</div>
                    <p class="text-xs text-text-muted mt-1">Sedang diproses admin</p>
                </div>
                <div class="absolute right-0 bottom-0 opacity-[0.03] transform translate-y-2 translate-x-2 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-clock text-9xl text-yellow-600"></i>
                </div>
            </div>

            {{-- Disetujui --}}
            <div class="relative overflow-hidden bg-white dark:bg-bg-card rounded-2xl p-6 shadow-sm border border-border-color group hover:border-green-500/30 transition-colors">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xs font-bold text-green-600 uppercase tracking-wider bg-green-50 dark:bg-green-900/20 px-2 py-1 rounded-md">Disetujui</h3>
                    </div>
                    <div class="text-3xl font-bold text-text-primary group-hover:scale-105 transition-transform origin-left">{{ $allPengajuans->where('status', 'approved')->count() }}</div>
                    <p class="text-xs text-text-muted mt-1">Permohonan diterima</p>
                </div>
                <div class="absolute right-0 bottom-0 opacity-[0.03] transform translate-y-2 translate-x-2 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-check-circle text-9xl text-green-600"></i>
                </div>
            </div>
        </div>

        {{-- History Table --}}
        <div class="bg-white dark:bg-bg-card border border-border-color rounded-2xl shadow-sm overflow-hidden">
            <header class="px-6 py-5 border-b border-border-color flex items-center justify-between bg-gray-50/30 dark:bg-transparent">
                <h2 class="font-bold text-text-primary text-lg">Riwayat Pengajuan</h2>
                <div class="text-xs text-text-muted">Menampilkan {{ $pengajuans->count() }} data terakhir</div>
            </header>
            <div class="overflow-x-auto">
                <table class="table-auto w-full" style="min-width: 900px;">
                    <thead class="bg-gray-50/50 dark:bg-bg-hover/30 border-b border-border-color">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-text-secondary text-left w-1/6">Jenis Pengajuan</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-text-secondary text-left w-1/8">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-text-secondary text-left w-1/4">Keterangan</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-text-secondary text-center w-1/8">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-text-secondary text-left w-1/6">Catatan Admin</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-text-secondary text-center w-1/8">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-color bg-white dark:bg-bg-card">
                        @forelse ($pengajuans as $p)
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-bg-hover/40 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        @php
                                            $iconMap = ['cuti'=>'fa-pause-circle text-orange-600', 'dispensasi'=>'fa-calendar-times text-purple-600', 'izin_penelitian'=>'fa-search text-green-600'];
                                            $bgMap   = ['cuti'=>'bg-orange-100', 'dispensasi'=>'bg-purple-100', 'izin_penelitian'=>'bg-green-100'];
                                            $icon    = $iconMap[$p->jenis] ?? 'fa-file-signature text-blue-600';
                                            $bg      = $bgMap[$p->jenis] ?? 'bg-blue-100';
                                        @endphp
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $bg }}">
                                            <i class="fas {{ $icon }} text-sm"></i>
                                        </div>
                                        <span class="font-medium text-text-primary text-sm">{{ $p->jenis_label }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-text-secondary font-medium">{{ $p->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-text-muted">{{ $p->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-text-secondary line-clamp-2 max-w-sm group-hover:text-text-primary transition-colors">
                                        {{ $p->keterangan }}
                                    </div>
                                    @if($p->file_path)
                                        <a href="{{ Storage::url($p->file_path) }}" target="_blank" class="inline-flex items-center gap-1 mt-1 text-xs text-primary hover:underline">
                                            <i class="fas fa-paperclip"></i> Lihat Dokumen
                                        </a>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    {!! $p->status_badge !!}
                                </td>
                                <td class="px-6 py-4">
                                    @if($p->rejected_reason)
                                        <div class="text-sm text-red-700 italic bg-red-50 rounded-md px-3 py-2 border border-red-200">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $p->rejected_reason }}
                                        </div>
                                    @elseif($p->admin_note)
                                        <div class="text-sm text-text-secondary italic bg-gray-50 dark:bg-bg-hover rounded-md px-3 py-2 border border-border-color/50">
                                            "{{ $p->admin_note }}"
                                        </div>
                                    @else
                                        <span class="text-xs text-text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                        {{-- Draft: belum generate --}}
                                        @if($p->status === 'draft')
                                            <button onclick="openPengajuanWizard({id: {{ $p->id }}, jenis: '{{ $p->jenis }}', step: 2, isRevision: false})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary text-white rounded-lg hover:bg-primary-hover text-xs font-medium transition-colors">
                                                <i class="fas fa-file-alt"></i> Lanjutkan
                                            </button>
                                            <button onclick="deletePengajuan({{ $p->id }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-700 text-white rounded-lg hover:bg-red-800 text-xs font-medium transition-colors">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>

                                        {{-- Generated: siap download --}}
                                        @elseif($p->status === 'generated')
                                            <a href="{{ route('mahasiswa.pengajuan.download-generated', $p->id) }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs font-medium transition-colors">
                                                <i class="fas fa-download"></i> Unduh Surat
                                            </a>
                                            <button onclick="openPengajuanWizard({id: {{ $p->id }}, jenis: '{{ $p->jenis }}', step: 4, isRevision: false})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 text-xs font-medium transition-colors">
                                                <i class="fas fa-upload"></i> Upload TTD
                                            </button>
                                            <button onclick="deletePengajuan({{ $p->id }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-700 text-white rounded-lg hover:bg-red-800 text-xs font-medium transition-colors">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>

                                        {{-- Submitted: menunggu admin --}}
                                        @elseif($p->status === 'submitted')
                                            @if($p->generated_doc_path)
                                                <a href="{{ route('mahasiswa.pengajuan.download-generated', $p->id) }}"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-xs font-medium transition-colors">
                                                    <i class="fas fa-file-word"></i> Lihat Surat
                                                </a>
                                            @endif
                                            <span class="text-xs text-yellow-600 font-medium"><i class="fas fa-clock mr-1"></i>Diproses admin</span>

                                        {{-- Rejected: perbaiki --}}
                                        @elseif($p->status === 'rejected')
                                            <button onclick="openPengajuanWizard({id: {{ $p->id }}, jenis: '{{ $p->jenis }}', step: 4, isRevision: true})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 text-xs font-medium transition-colors">
                                                <i class="fas fa-redo"></i> Perbaiki
                                            </button>
                                            <button onclick="deletePengajuan({{ $p->id }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-700 text-white rounded-lg hover:bg-red-800 text-xs font-medium transition-colors">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>

                                        {{-- Approved: download final --}}
                                        @elseif($p->status === 'approved' && $p->file_surat)
                                            <button @click="$dispatch('open-preview-modal', '{{ route('mahasiswa.pengajuan.preview', $p->id) }}')"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs font-medium transition-colors">
                                                <i class="fas fa-eye"></i> Lihat
                                            </button>
                                            <a href="{{ route('mahasiswa.pengajuan.download', $p->id) }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 text-xs font-medium transition-colors">
                                                <i class="fas fa-download"></i> Unduh
                                            </a>
                                        @else
                                            <span class="text-xs text-text-muted">-</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-text-secondary">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-bg-hover rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-inbox text-2xl text-text-muted"></i>
                                        </div>
                                        <h3 class="text-base font-medium text-text-primary">Belum ada pengajuan</h3>
                                        <p class="text-sm text-text-muted mt-1 max-w-xs mx-auto">Anda belum pernah membuat pengajuan surat atau cuti. Mulai dengan klik tombol di atas.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($pengajuans->hasPages())
                <div class="border-t border-border-color px-6 py-4 bg-gray-50/50">
                    {{ $pengajuans->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         MODAL: Wizard Buat / Lanjutkan Pengajuan (5 Step)
         ══════════════════════════════════════════════════════════════════ --}}

    {{-- Config passed via a script tag to avoid breaking x-data attribute parsing --}}
    <script>
        window.__pengajuanJenisConfig = @json($jenisConfig);
        window.__mahasiswa = @json(['semester' => optional(auth()->user()->mahasiswa)->semester ?? null]);
        window.__mahasiswa_courses = @json($currentCourses ?? []);
    </script>

    <div
        x-data="pengajuanWizard(window.__pengajuanJenisConfig)"
        x-show="open"
        @open-modal.window="handleOpen($event)"
        @keydown.escape.window="close()"
        @open-pengajuan-continue.window="handleContinue($event.detail)"
        class="relative z-50"
        style="display:none;">

        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" x-show="open" x-transition.opacity></div>

        {{-- Dialog --}}
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
            <div
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.outside="close()"
                class="relative w-full sm:max-w-xl bg-white dark:bg-bg-card rounded-2xl shadow-2xl border border-border-color overflow-hidden">

                {{-- Header --}}
                <div class="bg-primary/5 px-6 py-4 border-b border-primary/10 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary">
                        <i class="fas fa-pen-nib"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-bold text-text-primary" x-text="modalTitle"></h3>
                        {{-- Step indicator --}}
                        <div class="flex items-center gap-1 mt-1">
                            <template x-for="s in totalSteps" :key="s">
                                <div class="h-1 rounded-full transition-all duration-300"
                                     :class="s <= step ? 'bg-primary flex-1' : 'bg-gray-200 flex-1'"></div>
                            </template>
                            <span class="text-xs text-text-muted ml-2" x-text="`Step ${step}/${totalSteps}`"></span>
                        </div>
                    </div>
                    <button @click="close()" class="text-text-muted hover:text-text-primary ml-2">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Error alert --}}
                <div x-show="errorMsg" class="mx-6 mt-4 p-3 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700 flex items-start gap-2">
                    <i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i>
                    <span x-text="errorMsg"></span>
                </div>

                {{-- ─── STEP 1: Pilih Jenis ─────────────────────────────────── --}}
                <div x-show="step === 1" class="px-6 py-5 space-y-4">
                    <p class="text-sm text-text-secondary">Pilih jenis surat yang akan Anda ajukan. Formulir akan menyesuaikan secara otomatis.</p>
                    <div class="grid grid-cols-1 gap-3">
                        <template x-for="(cfg, key) in jenisConfig" :key="key">
                            <button
                                @click="selectJenis(key)"
                                :class="selectedJenis === key
                                    ? 'border-primary bg-primary/5 ring-2 ring-primary'
                                    : 'border-border-color hover:border-primary/50 hover:bg-gray-50'"
                                class="flex items-center gap-4 p-4 rounded-xl border text-left transition-all">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                                    <i class="fas text-primary" :class="cfg.icon || 'fa-file-alt'"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-sm text-text-primary" x-text="cfg.label"></div>
                                    <div class="text-xs text-text-muted">Klik untuk pilih</div>
                                </div>
                                <div x-show="selectedJenis === key" class="ml-auto text-primary">
                                    <i class="fas fa-check-circle text-lg"></i>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- ─── STEP 2: Isi Form Template ───────────────────────────── --}}
                <div x-show="step === 2" class="px-6 py-5 space-y-4">
                    <p class="text-sm text-text-secondary">Lengkapi data berikut. Data identitas Anda (nama, NIM, prodi, dll.) akan otomatis terisi dari sistem.</p>

                    {{-- Dynamic fields --}}
                    <template x-if="currentFields.length > 0">
                        <div class="space-y-4">
                            <template x-for="field in currentFields" :key="field.name">
                                <div class="space-y-1">
                                    <label class="block text-sm font-semibold text-text-primary">
                                        <span x-text="field.label"></span>
                                        <span x-show="field.required" class="text-red-500 ml-1">*</span>
                                    </label>
                                    {{-- Special: mata_kuliah_ditinggal → select from enrolled courses --}}
                                    <template x-if="field.name === 'mata_kuliah_ditinggal' && availableCourses.length > 0">
                                        <select :name="`payload_template[${field.name}]`" x-model="payload[field.name]"
                                                :required="field.required"
                                                class="block w-full rounded-xl border-border-color py-2.5 px-3 text-sm bg-bg-input focus:ring-2 focus:ring-primary focus:border-primary">
                                            <option value="">-- Pilih Mata Kuliah --</option>
                                            <template x-for="c in availableCourses" :key="c.id">
                                                <option :value="c.display" x-text="(c.kode ? c.kode + ' – ' : '') + c.display"></option>
                                            </template>
                                        </select>
                                    </template>
                                    <template x-if="field.name === 'mata_kuliah_ditinggal' && availableCourses.length === 0">
                                        <input type="text"
                                               :name="`payload_template[${field.name}]`"
                                               :placeholder="field.placeholder || 'Contoh: Hukum Perdata, Hukum Pidana'"
                                               :required="field.required"
                                               x-model="payload[field.name]"
                                               class="block w-full rounded-xl border-border-color py-2.5 px-3 text-sm bg-bg-input focus:ring-2 focus:ring-primary focus:border-primary">
                                    </template>
                                    {{-- Generic fields --}}
                                    <template x-if="field.name !== 'mata_kuliah_ditinggal' && field.type === 'textarea'">
                                        <textarea
                                            :name="`payload_template[${field.name}]`"
                                            :placeholder="field.placeholder || ''"
                                            :required="field.required"
                                            x-model="payload[field.name]"
                                            rows="3"
                                            class="block w-full rounded-xl border-border-color py-2.5 px-3 text-sm bg-bg-input focus:ring-2 focus:ring-primary focus:border-primary resize-none"></textarea>
                                    </template>
                                    <template x-if="field.name !== 'mata_kuliah_ditinggal' && field.type !== 'textarea'">
                                        <input
                                            :type="field.type || 'text'"
                                            :name="`payload_template[${field.name}]`"
                                            :placeholder="field.placeholder || ''"
                                            :required="field.required"
                                            x-model="payload[field.name]"
                                            class="block w-full rounded-xl border-border-color py-2.5 px-3 text-sm bg-bg-input focus:ring-2 focus:ring-primary focus:border-primary">
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Keterangan/Alasan umum --}}
                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-text-primary">
                            Keterangan / Alasan
                            <span class="text-text-muted font-normal text-xs ml-1">(opsional — otomatis terisi jika dikosongkan)</span>
                        </label>
                        <textarea
                            x-model="keterangan"
                            rows="2"
                            placeholder="Jelaskan keperluan pengajuan ini secara singkat..."
                            class="block w-full rounded-xl border-border-color py-2.5 px-3 text-sm bg-bg-input focus:ring-2 focus:ring-primary focus:border-primary resize-none"></textarea>
                    </div>
                </div>

                {{-- ─── STEP 3: Generate Dokumen ────────────────────────────── --}}
                <div x-show="step === 3" class="px-6 py-5">
                    {{-- Sedang diproses --}}
                    <div x-show="generating" class="text-center py-8">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-circle-notch fa-spin text-2xl text-primary"></i>
                        </div>
                        <p class="font-semibold text-text-primary">Sedang menyiapkan dokumen...</p>
                        <p class="text-sm text-text-muted mt-1">Harap tunggu, template surat sedang diisi data Anda.</p>
                    </div>

                    {{-- Dokumen siap --}}
                    <div x-show="!generating" class="space-y-4">
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-600 text-xl mt-0.5"></i>
                            <div>
                                <div class="font-semibold text-green-800">Dokumen berhasil dibuat!</div>
                                <div class="text-sm text-green-700 mt-0.5">Unduh surat di bawah, lalu <strong>tanda tangani</strong> dan upload kembali.</div>
                            </div>
                        </div>

                        <a :href="`{{ url('mahasiswa/pengajuan') }}/${pengajuanId}/download-generated`"
                           target="_blank"
                           class="flex items-center gap-3 p-4 bg-blue-50 border border-blue-200 rounded-xl hover:bg-blue-100 transition-colors group">
                            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shrink-0">
                                <i class="fas fa-file-word text-white"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-blue-800 text-sm">Unduh Surat (DOCX)</div>
                                <div class="text-xs text-blue-600">Klik untuk download surat yang sudah terisi</div>
                            </div>
                            <i class="fas fa-download text-blue-600 group-hover:translate-y-0.5 transition-transform"></i>
                        </a>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 text-sm text-yellow-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Setelah mengunduh dan <strong>menandatangani</strong> surat, lanjut ke step berikutnya untuk mengupload kembali.
                        </div>
                    </div>
                </div>

                {{-- ─── STEP 4: Upload Signed Document ─────────────────────── --}}
                <div x-show="step === 4" class="px-6 py-5 space-y-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 text-sm text-yellow-800">
                        <i class="fas fa-stamp mr-2"></i>
                        Upload surat yang telah Anda <strong>tanda tangani</strong> (format PDF atau DOCX, maks. 5 MB).
                    </div>

                    <div
                        class="group relative flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-border-color px-6 py-8 hover:bg-gray-50 hover:border-primary/50 transition-all cursor-pointer"
                        @dragover.prevent
                        @drop.prevent="handleFileDrop($event)"
                        @click="$refs.signedInput.click()">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-cloud-upload-alt text-2xl text-primary"></i>
                        </div>
                        <p class="font-semibold text-primary text-sm">Klik atau drag & drop</p>
                        <p class="text-xs text-text-muted mt-1">PDF, DOCX (maks. 5 MB)</p>
                        <p x-show="signedFileName" x-text="signedFileName"
                           class="mt-2 text-sm text-green-700 font-medium bg-green-50 px-3 py-1 rounded-lg border border-green-200"></p>
                        <input type="file" x-ref="signedInput" class="sr-only" accept=".pdf,.docx,.doc"
                               @change="handleFileSelect($event)">
                    </div>

                    {{-- Upload progress --}}
                    <div x-show="uploading" class="space-y-2">
                        <div class="flex justify-between text-xs text-text-muted">
                            <span>Mengupload...</span>
                            <span x-text="`${uploadProgress}%`"></span>
                        </div>
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full transition-all duration-300" :style="`width:${uploadProgress}%`"></div>
                        </div>
                    </div>

                    {{-- Success --}}
                    <div x-show="signedUploaded && !uploading" class="flex items-center gap-2 text-green-700 text-sm">
                        <i class="fas fa-check-circle"></i>
                        <span>Dokumen TTD berhasil diupload. Siap untuk dikirim.</span>
                    </div>
                </div>

                {{-- ─── STEP 5: Konfirmasi Submit ───────────────────────────── --}}
                <div x-show="step === 5" class="px-6 py-5 space-y-4">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
                        <i class="fas fa-paper-plane text-green-600 text-xl mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-green-800">Siap dikirim ke admin!</div>
                            <div class="text-sm text-green-700 mt-0.5">Dokumen bertanda tangan sudah terupload. Klik "Kirim Pengajuan" untuk memproses.</div>
                        </div>
                    </div>

                    {{-- Jika ini adalah revisi --}}
                    <div x-show="isRevision" class="space-y-1">
                        <label class="block text-sm font-semibold text-text-primary">Catatan Revisi (opsional)</label>
                        <textarea
                            x-model="revisionNote"
                            rows="2"
                            placeholder="Tuliskan perbaikan yang Anda lakukan sesuai catatan admin..."
                            class="block w-full rounded-xl border-border-color py-2.5 px-3 text-sm bg-bg-input focus:ring-2 focus:ring-primary focus:border-primary resize-none"></textarea>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="bg-gray-50 dark:bg-bg-hover/30 px-6 py-4 flex flex-col sm:flex-row-reverse gap-2 border-t border-border-color">

                    {{-- Step 1 --}}
                    <template x-if="step === 1">
                        <button @click="goStep2()" :disabled="!selectedJenis"
                            class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-primary hover:bg-primary-hover shadow-md disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                            Selanjutnya <i class="fas fa-arrow-right"></i>
                        </button>
                    </template>

                    {{-- Step 2 --}}
                    <template x-if="step === 2">
                        <div class="flex gap-2 w-full sm:w-auto flex-row-reverse">
                            <button @click="submitDraft()" :disabled="submitting"
                                class="flex-1 sm:flex-none inline-flex justify-center items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-primary hover:bg-primary-hover shadow-md disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                                <template x-if="submitting"><i class="fas fa-circle-notch fa-spin"></i></template>
                                Generate Surat
                            </button>
                            <button @click="step = 1" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary bg-white ring-1 ring-border-color hover:bg-gray-50 transition-colors">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        </div>
                    </template>

                    {{-- Step 3 --}}
                    <template x-if="step === 3">
                        <div class="flex gap-2 w-full sm:w-auto flex-row-reverse">
                            <button @click="step = 4" :disabled="generating"
                                class="flex-1 sm:flex-none inline-flex justify-center items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-primary hover:bg-primary-hover shadow-md disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                                Upload TTD <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </template>

                    {{-- Step 4 --}}
                    <template x-if="step === 4">
                        <div class="flex gap-2 w-full sm:w-auto flex-row-reverse">
                            <button @click="doUploadSigned()" :disabled="!signedFile || uploading"
                                class="flex-1 sm:flex-none inline-flex justify-center items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-primary hover:bg-primary-hover shadow-md disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                                <template x-if="uploading"><i class="fas fa-circle-notch fa-spin"></i></template>
                                Upload & Lanjut <i x-show="!uploading" class="fas fa-arrow-right"></i>
                            </button>
                            <button @click="step = 3" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary bg-white ring-1 ring-border-color hover:bg-gray-50 transition-colors">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        </div>
                    </template>

                    {{-- Step 5 --}}
                    <template x-if="step === 5">
                        <div class="flex gap-2 w-full sm:w-auto flex-row-reverse">
                            <button @click="doSubmit()" :disabled="submitting"
                                class="flex-1 sm:flex-none inline-flex justify-center items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-green-600 hover:bg-green-700 shadow-md disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                                <template x-if="submitting"><i class="fas fa-circle-notch fa-spin"></i></template>
                                <i x-show="!submitting" class="fas fa-paper-plane"></i>
                                Kirim Pengajuan
                            </button>
                            <button @click="step = 4" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary bg-white ring-1 ring-border-color hover:bg-gray-50 transition-colors">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        </div>
                    </template>

                    {{-- Batal (selalu tampil) --}}
                    <button @click="close()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-sm font-semibold text-text-secondary bg-white ring-1 ring-border-color hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Preview Modal --}}
    <div x-data="{ openConfig: false, previewUrl: '' }"
         @open-preview-modal.window="openConfig = true; previewUrl = $event.detail"
         @keydown.escape.window="openConfig = false"
         style="display: none;"
         x-show="openConfig"
         class="relative z-[60]">
        <div class="fixed inset-0 bg-gray-900/90 backdrop-blur-md" x-show="openConfig" x-transition.opacity></div>
        <div class="fixed inset-0 z-[60] overflow-y-auto flex items-center justify-center p-4">
            <div x-show="openConfig"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                 @click.outside="openConfig = false"
                 class="relative w-full max-w-5xl h-[85vh] rounded-xl overflow-hidden shadow-2xl">
                <div class="absolute top-0 right-0 p-4 z-50">
                    <button @click="openConfig = false" class="text-white/70 hover:text-white bg-black/20 rounded-full p-2 hover:bg-black/40">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <i class="fas fa-circle-notch fa-spin text-4xl text-white/30"></i>
                </div>
                <iframe :src="previewUrl" class="w-full h-full bg-white relative z-10"></iframe>
            </div>
        </div>
    </div>

@push('scripts')
<script>
function pengajuanWizard(jenisConfig) {
    return {
        open: false,
        step: 1,
        totalSteps: 5,
        jenisConfig,
        selectedJenis: '',
        availableCourses: window.__mahasiswa_courses ?? [],
        currentFields: [],
        payload: {},
        keterangan: '',
        pengajuanId: null,
        generating: false,
        generatedReady: false,
        signedFile: null,
        signedFileName: '',
        signedUploaded: false,
        uploading: false,
        uploadProgress: 0,
        submitting: false,
        isRevision: false,
        revisionNote: '',
        errorMsg: '',

        get modalTitle() {
            const titles = {
                1: 'Pilih Jenis Pengajuan',
                2: 'Isi Data Surat',
                3: 'Unduh Surat Terisi',
                4: 'Upload Surat Bertanda Tangan',
                5: this.isRevision ? 'Kirim Revisi' : 'Konfirmasi Pengiriman',
            };
            return titles[this.step] || 'Buat Pengajuan';
        },

        handleOpen(e) {
            if (e.detail === 'pengajuan-modal') {
                this.reset();
                this.open = true;
            }
        },

        handleContinue(detail) {
            this.reset();
            this.pengajuanId = detail.id;
            this.isRevision  = detail.isRevision || false;
            this.selectedJenis = detail.jenis;
            const startStep = detail.step || 3;
            if (startStep >= 3) {
                this.generatedReady = true;
                this.step = startStep;
            }
            this.open = true;
        },

        reset() {
            this.step = 1;
            this.selectedJenis = '';
            this.currentFields = [];
            this.payload = {};
            this.keterangan = '';
            this.pengajuanId = null;
            this.generating = false;
            this.generatedReady = false;
            this.signedFile = null;
            this.signedFileName = '';
            this.signedUploaded = false;
            this.uploading = false;
            this.uploadProgress = 0;
            this.submitting = false;
            this.isRevision = false;
            this.revisionNote = '';
            this.errorMsg = '';
        },

        close() {
            this.open = false;
        },

        selectJenis(key) {
            this.selectedJenis = key;
        },

        goStep2() {
            if (!this.selectedJenis) return;
            this.currentFields = this.jenisConfig[this.selectedJenis]?.fields || [];
            this.payload = {};

            // Prefill defaults for certain jenis
            if (this.selectedJenis === 'cuti') {
                // semester_cuti default to mahasiswa current semester
                try {
                    const sem = window.__mahasiswa?.semester ?? null;
                    this.payload['semester_cuti'] = sem ? `Semester ${sem}` : '';

                    // tanggal_mulai_cuti = today, tanggal_selesai_cuti = +6 months
                    const today = new Date();
                    const pad = (n) => String(n).padStart(2, '0');
                    const toYmd = (d) => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
                    this.payload['tanggal_mulai_cuti'] = toYmd(today);
                    const end = new Date(today);
                    end.setMonth(end.getMonth() + 6);
                    this.payload['tanggal_selesai_cuti'] = toYmd(end);
                } catch (e) {
                    // ignore
                }
            }

            this.step = 2;
        },

        // Step 2 → Buat draft + dispatch generate
        async submitDraft() {
            this.errorMsg = '';

            // Validasi semua required dynamic fields
            const missingFields = this.currentFields
                .filter(f => f.required && !String(this.payload[f.name] ?? '').trim())
                .map(f => f.label);
            if (missingFields.length) {
                this.errorMsg = 'Harap isi field berikut: ' + missingFields.join(', ') + '.';
                return;
            }

            // Auto-fill keterangan dari label jenis jika kosong
            if (!this.keterangan.trim()) {
                this.keterangan = this.jenisConfig[this.selectedJenis]?.label ?? this.selectedJenis;
            }

            this.submitting = true;

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('jenis', this.selectedJenis);
            formData.append('keterangan', this.keterangan);
            for (const [k, v] of Object.entries(this.payload)) {
                formData.append(`payload_template[${k}]`, v);
            }

            try {
                const resp = await fetch('{{ route("mahasiswa.pengajuan.store") }}', {
                    method: 'POST', body: formData,
                });
                const data = await resp.json();
                if (!resp.ok) { this.errorMsg = Object.values(data.errors || {}).flat().join(', ') || 'Gagal membuat draft.'; return; }
                this.pengajuanId = data.pengajuan_id;

                // Dispatch generate
                await fetch(`{{ url('mahasiswa/pengajuan') }}/${this.pengajuanId}/generate`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                });

                this.step = 3;
                this.generating = true;
                this.pollStatus();
            } catch (err) {
                this.errorMsg = 'Terjadi kesalahan. Coba lagi.';
            } finally {
                this.submitting = false;
            }
        },

        async pollStatus() {
            const maxAttempts = 30;
            let attempt = 0;
            const check = async () => {
                if (attempt >= maxAttempts) { this.errorMsg = 'Generate timeout. Coba refresh halaman.'; this.generating = false; return; }
                attempt++;
                try {
                    const resp = await fetch(`{{ url('mahasiswa/pengajuan') }}/${this.pengajuanId}/status`);
                    const data = await resp.json();
                    if (data.generated_doc_ready) {
                        this.generating = false;
                        this.generatedReady = true;
                        return;
                    }
                } catch (_) {}
                setTimeout(check, 2000);
            };
            setTimeout(check, 2000);
        },

        handleFileSelect(e) {
            const file = e.target.files[0];
            if (file) { this.signedFile = file; this.signedFileName = file.name; }
        },

        handleFileDrop(e) {
            const file = e.dataTransfer.files[0];
            if (file) { this.signedFile = file; this.signedFileName = file.name; }
        },

        async doUploadSigned() {
            if (!this.signedFile) return;
            this.uploading = true;
            this.uploadProgress = 0;
            this.errorMsg = '';

            const formData = new FormData();
            formData.append('signed_doc', this.signedFile);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            const xhr = new XMLHttpRequest();
            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) this.uploadProgress = Math.round(e.loaded * 100 / e.total);
            });
            xhr.onload = () => {
                this.uploading = false;
                if (xhr.status === 200) {
                    this.signedUploaded = true;
                    setTimeout(() => { this.step = 5; }, 600);
                } else {
                    const err = JSON.parse(xhr.responseText || '{}');
                    this.errorMsg = err.error || 'Upload gagal.';
                }
            };
            xhr.onerror = () => { this.uploading = false; this.errorMsg = 'Upload gagal, coba lagi.'; };
            xhr.open('POST', `{{ url('mahasiswa/pengajuan') }}/${this.pengajuanId}/upload-signed`);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(formData);
        },

        async doSubmit() {
            this.submitting = true;
            this.errorMsg = '';
            try {
                const resp = await fetch(`{{ url('mahasiswa/pengajuan') }}/${this.pengajuanId}/submit`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ revision_note: this.revisionNote }),
                });
                const data = await resp.json();
                if (!resp.ok) { this.errorMsg = data.error || 'Gagal submit.'; return; }
                this.close();
                window.location.reload();
            } catch (err) {
                this.errorMsg = 'Terjadi kesalahan. Coba lagi.';
            } finally {
                this.submitting = false;
            }
        },
    };
}

// Tombol "Lanjutkan" / "Perbaiki" dari tabel membuka wizard dengan state yang tepat
document.addEventListener('DOMContentLoaded', () => {
    window.openPengajuanWizard = (data) => {
        window.dispatchEvent(new CustomEvent('open-pengajuan-continue', { detail: data }));
    };
    window.deletePengajuan = async (id) => {
        if (!confirm('Hapus pengajuan ini? Tindakan ini tidak dapat dibatalkan.')) return;
        try {
            const resp = await fetch(`{{ url('mahasiswa/pengajuan') }}/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            });
            const data = await resp.json();
            if (!resp.ok) { alert(data.error || 'Gagal menghapus pengajuan.'); return; }
            // reload to refresh list
            window.location.reload();
        } catch (err) {
            alert('Terjadi kesalahan saat menghapus. Coba lagi.');
        }
    };
});
</script>
@endpush

@endsection
