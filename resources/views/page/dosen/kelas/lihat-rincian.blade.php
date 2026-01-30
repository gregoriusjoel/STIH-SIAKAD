@extends('layouts.app')

@section('title', 'Detail Pertemuan')

@section('content')
    @section('navbar_breadcrumb')
        <nav class="flex items-center gap-2 text-sm text-[#616889]">
            <a href="{{ route('dosen.kelas') }}" class="hover:text-primary transition-colors">Kelas</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a href="{{ route('dosen.kelas.detail', $kelas->id) }}" class="hover:text-primary transition-colors">Detail
                Kelas</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-[#111218] font-medium">{{ $meeting['label'] }}</span>
        </nav>
    @endsection

    <div class="px-4 py-6 max-w-[1600px] mx-auto">

        {{-- HEADER --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="size-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-3xl">event_note</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $meeting['label'] }}</h1>
                        <p class="text-gray-500">{{ $kelas->mataKuliah->nama_mk }} - Kelas {{ $kelas->section }}</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('dosen.kelas.absensi', ['id' => $kelas->id]) }}?pertemuan={{ $meeting['no'] }}"
                        class="px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">how_to_reg</span>
                        Isi Absensi
                    </a>
                    <a href="{{ route('dosen.kelas.pertemuan.materi', ['id' => $kelas->id, 'pertemuan' => $meeting['no']]) }}"
                        class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">folder_open</span>
                        Materi
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6 pt-6 border-t border-gray-100">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Tanggal</p>
                    <p class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-400 text-[18px]">calendar_today</span>
                        {{ $meeting['date'] }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Waktu</p>
                    <p class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-400 text-[18px]">schedule</span>
                        {{ $meeting['time'] }} (WIB)
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Ruangan</p>
                    <p class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-400 text-[18px]">location_on</span>
                        {{ $meeting['room'] }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Materi / Topik</p>
                    <p class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-400 text-[18px]">topic</span>
                        -
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Left: Attendance List --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 text-lg">Daftar Kehadiran</h3>
                    <span class="px-3 py-1 rounded-full bg-gray-100 text-xs font-bold text-gray-600">
                        Total Mahasiswa: {{ count($students) }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 text-xs uppercase tracking-wider text-gray-500 font-bold">
                                <th class="px-6 py-4 w-16">No</th>
                                <th class="px-6 py-4">Mahasiswa</th>
                                <th class="px-6 py-4">NIM</th>
                                <th class="px-6 py-4 text-center">Status Kehadiran</th>
                                <th class="px-6 py-4 text-center">Waktu Scan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($students as $index => $student)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-gray-500 font-medium">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800">{{ $student['name'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $student['prodi'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-sm text-gray-600">{{ $student['nim'] }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">
                                            Belum Absen
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-400 text-sm">-</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Tidak ada mahasiswa terdaftar di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Right: Daftar Tugas (50%) --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 text-lg">Daftar Tugas</h3>
                    <div class="flex items-center gap-2">
                        <button x-data x-on:click="$dispatch('open-create-tugas')"
                            class="px-3 py-1 bg-primary text-white rounded text-sm font-bold">Buat Tugas</button>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    @if(isset($tasks) && $tasks->count())
                        @foreach($tasks as $t)
                            <div class="p-4 rounded-xl border border-gray-100">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">{{ $t->title }}</p>
                                        <p class="text-xs text-gray-500">Diunggah: {{ $t->created_at->format('d M Y') }}</p>
                                        @if($t->due_date)
                                            <p class="text-xs text-orange-700 mt-1">Deadline:
                                                {{ \Carbon\Carbon::parse($t->due_date)->format('d M Y H:i') }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <a href="{{ $t->file_path ? asset('storage/' . $t->file_path) : '#' }}"
                                            class="text-xs text-primary hover:underline">Download Soal</a>
                                    </div>
                                </div>

                                <div class="mt-3 flex gap-2">
                                    <form method="POST"
                                        action="{{ route('kelas.pertemuan.tugas.destroy', ['id' => $kelas->id, 'pertemuan' => $meeting['no'], 'tugas' => $t->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-2 bg-white border border-gray-200 rounded text-xs">Hapus</button>
                                    </form>
                                    <a href="#" class="px-3 py-2 bg-white border border-gray-200 rounded text-xs">Buka Detail</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-6 text-center text-gray-500">Belum ada tugas untuk pertemuan ini.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL CREATE TUGAS --}}
    <div x-data="{ open: false }" @open-create-tugas.window="open = true">
        <div x-show="open" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            {{-- Backdrop --}}
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"
                @click="open = false"></div>

            {{-- Modal Panel --}}
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="open" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl">

                    <form method="POST" enctype="multipart/form-data"
                        action="{{ route('dosen.kelas.pertemuan.tugas.store', ['id' => $kelas->id, 'pertemuan' => $meeting['no']]) }}">
                        @csrf

                        {{-- Header --}}
                        <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900" id="modal-title">Buat Tugas Baru</h3>
                            <button type="button" @click="open = false"
                                class="text-gray-400 hover:text-gray-500 transition-colors">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>

                        {{-- Body --}}
                        <div class="px-6 py-6 space-y-5">
                            {{-- Title Input --}}
                            <div>
                                <label for="title"
                                    class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Judul
                                    Tugas</label>
                                <input type="text" name="title" id="title" required
                                    class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary/20 transition-all text-sm placeholder:text-gray-400 font-medium"
                                    placeholder="Contoh: Resume Pertemuan 1">
                            </div>

                            {{-- Description Input --}}
                            <div>
                                <label for="description"
                                    class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Deskripsi /
                                    Instruksi</label>
                                <textarea name="description" id="description" rows="3"
                                    class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary/20 transition-all text-sm placeholder:text-gray-400 font-medium"
                                    placeholder="Jelaskan instruksi pengerjaan tugas..."></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-5">
                                {{-- Due Date --}}
                                <div>
                                    <label for="due_date"
                                        class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Deadline</label>
                                    <input type="datetime-local" name="due_date" id="due_date"
                                        class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary/20 transition-all text-sm font-medium text-gray-600">
                                </div>
                            </div>

                            {{-- File Upload --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">File Soal
                                    (Opsional)</label>
                                <label
                                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-200 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors group">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <span
                                            class="material-symbols-outlined text-gray-400 group-hover:text-primary transition-colors text-3xl mb-2">cloud_upload</span>
                                        <p class="text-sm text-gray-500 font-medium"><span
                                                class="font-bold text-gray-700 group-hover:text-primary transition-colors">Klik
                                                untuk upload</span> atau drag and drop</p>
                                        <p class="text-xs text-gray-400 mt-1">PDF, DOCX, ZIP (Max. 10MB)</p>
                                    </div>
                                    <input name="file" type="file" class="hidden" />
                                </label>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div
                            class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-3 rounded-b-2xl border-t border-gray-100">
                            <button type="button" @click="open = false"
                                class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-all shadow-sm">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-primary text-white rounded-xl text-sm font-bold hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all">
                                Simpan Tugas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- TinyMCE CDN --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" integrity="sha512-6JR4bbn8rCKvrkOGMcleNghLnmwDKb8oQn6eBNZpbhaOQCSytnzeXrePOCtqhRs/qfpzjlgrYbrVuZxvni1GkWg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            tinymce.init({
                selector: '#description',
                height: 300,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
                content_style: 'body { font-family:Inter,sans-serif; font-size:14px }',
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save(); 
                    });
                }
            });
        });
    </script>
@endpush