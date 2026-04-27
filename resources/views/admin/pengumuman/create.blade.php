@extends('layouts.admin')

@section('title', 'Buat Pengumuman')
@section('page-title', 'Buat Pengumuman')

@section('content')
    <div class="w-full">
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <!-- Header Section -->
            <div class="px-8 py-10 bg-gray-50/50 dark:bg-gray-900/20 border-b border-gray-100 dark:border-gray-700 relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-maroon rounded-2xl flex items-center justify-center text-white shadow-lg shadow-red-900/20">
                            <i class="fas fa-bullhorn text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                                Buat Pengumuman Baru
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400 mt-1 font-medium">Publikasikan informasi penting untuk civitas akademika</p>
                        </div>
                    </div>
                </div>
                <!-- Abstract Background Accent -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-maroon/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            </div>

            <form action="{{ route('admin.pengumuman.store') }}" method="POST" class="p-8 sm:p-12">
                @csrf

                <div class="space-y-10">
                    <!-- Section: Konten Utama -->
                    <div class="grid grid-cols-1 gap-8">
                        <div>
                            <label for="judul" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">
                                Judul Pengumuman
                            </label>
                            <input type="text" name="judul" id="judul" value="{{ old('judul') }}"
                                class="w-full px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 dark:text-gray-100 rounded-2xl focus:ring-4 focus:ring-maroon/5 focus:border-maroon focus:bg-white transition-all duration-300 @error('judul') border-red-500 @enderror"
                                placeholder="Masukkan judul yang singkat dan padat..." required>
                            @error('judul')
                                <p class="mt-2 text-sm text-red-600 font-bold ml-2 italic text-xs tracking-tight uppercase tracking-widest leading-none flex items-center"><i class="fas fa-exclamation-triangle mr-2"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="isi" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">
                                Isi Pengumuman Lengkap
                            </label>
                            <div class="editor-container">
                                <textarea name="isi" id="isi" rows="10"
                                    class="w-full @error('isi') border-red-500 @enderror"
                                    placeholder="Tuliskan detail informasi di sini...">{{ old('isi') }}</textarea>
                            </div>
                            @error('isi')
                                <p class="mt-2 text-sm text-red-600 font-bold ml-2 flex items-center italic text-xs uppercase tracking-widest"><i class="fas fa-exclamation-triangle mr-2"></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
@push('styles')
<style>
    .ck-editor__editable_inline {
        min-height: 400px;
        padding: 1.5rem 2rem !important;
        background: #fdfdfd !important;
        font-size: 0.95rem;
        line-height: 1.7;
        color: #374151;
    }
    .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
        border-color: #f3f4f6 !important;
        border-bottom-left-radius: 1.5rem !important;
        border-bottom-right-radius: 1.5rem !important;
    }
    .ck.ck-editor__editable.ck-focused {
        border-color: #800020 !important;
        box-shadow: 0 0 0 4px rgba(128, 0, 32, 0.05) !important;
        border-bottom-left-radius: 1.5rem !important;
        border-bottom-right-radius: 1.5rem !important;
    }
    .ck-toolbar {
        border-top-left-radius: 1.5rem !important;
        border-top-right-radius: 1.5rem !important;
        border-color: #f3f4f6 !important;
        background: #f9fafb !important;
        padding: 0.75rem 1rem !important;
    }
    .ck.ck-toolbar__separator {
        background: #e5e7eb !important;
    }
    .ck-content {
        border-bottom-left-radius: 1.5rem !important;
        border-bottom-right-radius: 1.5rem !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#isi'), {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'link', 'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ],
                shouldNotGroupWhenFull: true
            },
            placeholder: 'Tuliskan detail informasi di sini...'
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endpush

                    <!-- Section: Pengaturan & Jadwal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-gray-50 dark:border-gray-700">
                        <div>
                            <label for="target" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">
                                Target Audiens
                            </label>
                            <div class="relative">
                                <select name="target" id="target"
                                    class="w-full px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 dark:text-gray-100 rounded-2xl focus:ring-4 focus:ring-maroon/5 focus:border-maroon transition-all duration-300 appearance-none cursor-pointer"
                                    required>
                                    <option value="semua" {{ old('target') == 'semua' ? 'selected' : '' }}>Semua Pihak (Publik)</option>
                                    <option value="dosen" {{ old('target') == 'dosen' ? 'selected' : '' }}>Khusus Dosen</option>
                                    <option value="mahasiswa" {{ old('target') == 'mahasiswa' ? 'selected' : '' }}>Khusus Mahasiswa</option>
                                </select>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none opacity-40">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            @error('target')
                                <p class="mt-2 text-sm text-red-600 font-bold ml-2 flex items-center italic text-xs uppercase tracking-widest leading-none"><i class="fas fa-exclamation-triangle mr-2"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="published_at" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">
                                Jadwal Publikasi
                            </label>
                            <input type="datetime-local" name="published_at" id="published_at"
                                value="{{ old('published_at') }}"
                                class="w-full px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 dark:text-gray-100 rounded-2xl focus:ring-4 focus:ring-maroon/5 focus:border-maroon transition-all duration-300 cursor-pointer">
                            <p class="mt-3 mb-2 text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1 opacity-70 italic leading-none">
                                <i class="fas fa-info-circle mr-1"></i> Kosongkan jika ingin segera dipublikasikan
                            </p>
                        </div>
                    </div>

                    <!-- Premium Info Box -->
                    <div class="bg-gray-50 dark:bg-gray-900/40 p-8 rounded-[2rem] border border-gray-100 dark:border-gray-700 mt-4">
                        <div class="flex items-start gap-6">
                            <div class="w-12 h-12 rounded-xl bg-white dark:bg-gray-800 flex items-center justify-center text-maroon shadow-sm border border-gray-100 dark:border-gray-700 shrink-0">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest mb-3">Petunjuk Pengisian</h4>
                                <ul class="flex flex-wrap gap-x-8 gap-y-3">
                                    <li class="flex items-center text-sm text-gray-500 dark:text-gray-400 font-medium whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-maroon/40 mr-3 shrink-0"></span>
                                        Judul singkat & informatif
                                    </li>
                                    <li class="flex items-center text-sm text-gray-500 dark:text-gray-400 font-medium whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-maroon/40 mr-3 shrink-0"></span>
                                        Isi jelas & mudah dipahami
                                    </li>
                                    <li class="flex items-center text-sm text-gray-500 dark:text-gray-400 font-medium whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-maroon/40 mr-3 shrink-0"></span>
                                        Dapat dijadwalkan secara otomatis
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end gap-4 mt-12 pt-10 border-t border-gray-50 dark:border-gray-700">
                    <a href="{{ route('admin.pengumuman.index') }}"
                        class="px-8 py-4 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 font-bold rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all border border-gray-100 dark:border-gray-700 text-center flex items-center justify-center group/btn">
                        <i class="fas fa-arrow-left mr-3 text-xs group-hover/btn:-translate-x-1 transition-transform"></i>
                        Kembali
                    </a>
                    <button type="submit"
                        class="px-10 py-4 bg-maroon hover:bg-red-900 text-white font-black rounded-2xl transition-all shadow-xl shadow-red-900/20 flex items-center justify-center hover:scale-[1.02] active:scale-[0.98]">
                        <i class="fas fa-paper-plane mr-3"></i>
                        Publikasikan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection