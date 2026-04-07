@extends('layouts.mahasiswa')

@section('title', 'Edit Magang')
@section('page-title', 'Edit Data Magang')

@section('content')
<div class="max-w-[1600px] mx-auto px-4 py-8">

    {{-- Back --}}
    <div class="mb-6">
        <a href="{{ route('mahasiswa.magang.show', $internship) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-[#8B1538] transition-colors group">
            <span class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center border border-gray-100 group-hover:border-red-100 group-hover:bg-red-50 transition-colors">
                <i class="fas fa-arrow-left text-xs"></i>
            </span>
            Kembali ke Detail Magang
        </a>
    </div>

    {{-- Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8 lg:p-12 relative overflow-hidden">

        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-gradient-to-br from-[#8B1538]/5 to-transparent rounded-full blur-3xl pointer-events-none"></div>

        {{-- Header --}}
        <div class="flex items-center gap-4 mb-10 pb-6 border-b border-gray-100 dark:border-gray-700">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border border-gray-200 dark:border-gray-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-3xl text-[#8B1538]">edit_note</span>
            </div>
            <div>
                <h2 class="text-2xl font-black text-gray-900 dark:text-white leading-tight">Edit Data Magang</h2>
                <p class="text-sm text-gray-500 font-medium">Perbarui informasi pengajuan magang Anda.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-8 p-5 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm flex gap-3">
                <span class="material-symbols-outlined shrink-0 mt-0.5">error</span>
                <ul class="list-disc list-inside space-y-1 font-medium">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('mahasiswa.magang.update', $internship) }}" class="space-y-8">
            @csrf @method('PUT')

            {{-- Instansi & Posisi --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Instansi / Perusahaan <span class="text-red-500">*</span></label>
                    <input type="text" name="instansi" value="{{ old('instansi', $internship->instansi) }}" required placeholder="Contoh: PT. Adhyaksa Corp"
                           class="w-full rounded-2xl border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-5 py-3.5 text-sm focus:bg-white transition-colors focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538]">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Alamat Instansi <span class="text-red-500">*</span></label>
                    <textarea name="alamat_instansi" rows="2" required placeholder="Alamat lengkap instansi tempat magang"
                              class="w-full rounded-2xl border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-5 py-3.5 text-sm focus:bg-white transition-colors focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538]">{{ old('alamat_instansi', $internship->alamat_instansi) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Posisi / Bagian</label>
                    <input type="text" name="posisi" value="{{ old('posisi', $internship->posisi) }}" placeholder="Contoh: Legal Officer Intern"
                           class="w-full rounded-2xl border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-5 py-3.5 text-sm focus:bg-white transition-colors focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538]">
                </div>
            </div>

            {{-- Periode --}}
            <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700"
                 x-data="{
                    startDate: '{{ old('periode_mulai', $internship->periode_mulai?->format('Y-m-d')) }}',
                    endDate: '{{ old('periode_selesai', $internship->periode_selesai?->format('Y-m-d')) }}',
                    durasi: 0,
                    custom: false,
                    customMonth: '',
                    setDurasi(n) {
                        this.custom = (n === 'other');
                        if (n !== 'other') {
                            this.durasi = n;
                            this.customMonth = '';
                            this.calcEnd();
                        } else {
                            this.durasi = 0;
                        }
                    },
                    calcEnd() {
                        const m = this.custom ? parseInt(this.customMonth) : this.durasi;
                        if (!this.startDate || !m) return;
                        const d = new Date(this.startDate);
                        d.setMonth(d.getMonth() + m);
                        this.endDate = d.toISOString().split('T')[0];
                    },
                    get label() {
                        const m = this.custom ? parseInt(this.customMonth) : this.durasi;
                        return m ? m + ' Bulan' : '';
                    }
                 }">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">date_range</span> Periode Magang
                    <span x-show="label" x-text="label"
                          class="ml-auto text-[10px] font-black px-2 py-0.5 rounded-full bg-[#8B1538]/10 text-[#8B1538]"></span>
                </h3>

                {{-- Duration quick-pick --}}
                <div class="flex flex-wrap items-center gap-2 mb-5">
                    <span class="text-xs font-bold text-gray-400">Durasi:</span>
                    <template x-for="opt in [3,6,9]" :key="opt">
                        <button type="button"
                                @click="setDurasi(opt)"
                                :class="durasi === opt && !custom
                                    ? 'bg-[#8B1538] text-white border-[#8B1538]'
                                    : 'bg-white text-gray-600 border-gray-200 hover:border-[#8B1538] hover:text-[#8B1538]'"
                                class="text-xs font-bold px-3 py-1.5 rounded-lg border transition">
                            <span x-text="opt + ' Bulan'"></span>
                        </button>
                    </template>
                    <button type="button"
                            @click="setDurasi('other')"
                            :class="custom ? 'bg-[#8B1538] text-white border-[#8B1538]' : 'bg-white text-gray-600 border-gray-200 hover:border-[#8B1538] hover:text-[#8B1538]'"
                            class="text-xs font-bold px-3 py-1.5 rounded-lg border transition">Lain-lain</button>
                    <div x-show="custom" x-cloak class="flex items-center gap-1.5">
                        <input type="number" x-model.number="customMonth" @input="calcEnd()" min="1" max="60"
                               placeholder="e.g. 12"
                               class="w-20 rounded-lg border-gray-200 bg-white text-sm px-3 py-1.5 text-center focus:ring-4 focus:ring-red-100 focus:border-[#8B1538]">
                        <span class="text-xs text-gray-400 font-medium">bulan</span>
                    </div>
                </div>

                {{-- Date inputs --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="periode_mulai" x-model="startDate" @change="calcEnd()" required
                               class="w-full rounded-xl border-gray-200 bg-white dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 text-sm focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538] shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input type="date" name="periode_selesai" x-model="endDate" required
                               class="w-full rounded-xl border-gray-200 bg-white dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 text-sm focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538] shadow-sm">
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center justify-between">
                    <span>Deskripsi Kegiatan</span>
                    <span class="text-xs font-medium text-gray-400 font-normal">Opsional</span>
                </label>
                <textarea name="deskripsi" rows="3" placeholder="Jelaskan secara singkat rencana kegiatan / tugas magang Anda"
                          class="w-full rounded-2xl border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-5 py-3.5 text-sm focus:bg-white transition-colors focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538]">{{ old('deskripsi', $internship->deskripsi) }}</textarea>
            </div>

            {{-- Pembimbing Lapangan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2 border-t border-gray-100 dark:border-gray-700">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Pembimbing Lapangan (Nama)</label>
                    <input type="text" name="pembimbing_lapangan_nama" value="{{ old('pembimbing_lapangan_nama', $internship->pembimbing_lapangan_nama) }}" placeholder="Nama Mentor / Supervisor Instansi"
                           class="w-full rounded-2xl border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-5 py-3.5 text-sm focus:bg-white transition-colors focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538]">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">No. Telp Pembimbing</label>
                    <input type="text" name="pembimbing_lapangan_telp" value="{{ old('pembimbing_lapangan_telp', $internship->pembimbing_lapangan_phone) }}" placeholder="08xxxxxxxx"
                           minlength="12" maxlength="13" pattern="^[0-9]+$" title="Harus terdiri dari 12 hingga 13 angka"
                           class="w-full rounded-2xl border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-5 py-3.5 text-sm focus:bg-white transition-colors focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538]">
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 pt-8 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('mahasiswa.magang.show', $internship) }}"
                   class="w-full sm:w-auto px-6 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl transition text-center focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Batal
                </a>
                <button type="submit"
                        class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-[#8B1538] to-[#6D1029] hover:from-[#6D1029] hover:to-[#500a1c] text-white text-sm font-bold rounded-xl shadow-lg shadow-red-900/20 transition-all hover:shadow-xl hover:shadow-red-900/30 flex items-center justify-center gap-2 focus:outline-none focus:ring-4 focus:ring-red-900/30">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
