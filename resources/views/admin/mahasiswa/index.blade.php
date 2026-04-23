@extends('layouts.admin')

@section('title', 'Data Mahasiswa')
@section('page-title', 'Data Mahasiswa')

@section('content')
    <div class="mb-6 flex flex-col items-start md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                <i class="fas fa-user-graduate text-maroon dark:text-red-500 mr-3 text-2xl"></i>
                Daftar Mahasiswa STIH
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola data mahasiswa kampus STIH</p>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden')"
                class="bg-white dark:bg-gray-800 text-maroon dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10 border-2 border-maroon/10 hover:border-maroon px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 shadow-sm text-sm font-bold">
                <i class="fas fa-file-import text-xs"></i>
                Import Data Mahasiswa
            </button>
            <a href="{{ route('admin.mahasiswa.create') }}"
                class="bg-maroon text-white hover:bg-red-900 px-4 py-2 rounded-lg transition flex items-center shadow-md transform hover:scale-105 text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>
                Tambah Mahasiswa
            </a>
        </div>
    </div>

    <div x-data="{ selectedMahasiswa: null }" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg  overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border-separate"
                style="border-spacing: 0;">
                <thead class="bg-maroon text-white rounded-t-xl">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider rounded-tl-xl">
                            <i class=""></i>No
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-id-card mr-2"></i>NIM
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Nama
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-envelope mr-2"></i>Email (Pribadi / Kampus)
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-graduation-cap mr-2"></i>Prodi
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-calendar mr-2"></i>Angkatan
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-2"></i>Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider rounded-tr-xl">
                            <i class="fas fa-cog mr-2"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($mahasiswas as $mahasiswa)
                                    <tr class="hover:bg-blue-50 dark:hover:bg-blue-900/20 transition cursor-pointer" @click="selectedMahasiswa = {{ Js::from([
                            'nim' => $mahasiswa->nim,
                            'name' => $mahasiswa->user->name,
                            'email' => $mahasiswa->user->email,
                            'no_hp' => $mahasiswa->no_hp ?? '-',
                            'prodi' => $mahasiswa->prodi,
                            'angkatan' => $mahasiswa->angkatan,
                            'status' => ucfirst($mahasiswa->status),
                            'foto' => $mahasiswa->foto ? \App\Helpers\FileHelper::fileUrl($mahasiswa->foto) : null,
                        ]) }}"
                                        :class="{ 'bg-blue-50 dark:bg-blue-900/30': selectedMahasiswa && selectedMahasiswa.nim === '{{ $mahasiswa->nim }}' }">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                                            {{ ($mahasiswas->currentPage() - 1) * $mahasiswas->perPage() + $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-maroon dark:text-red-400">
                                            {{ $mahasiswa->nim }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="bg-maroon text-white rounded-full w-10 h-10 flex items-center justify-center font-bold mr-3">
                                                    {{ strtoupper(substr($mahasiswa->user->name, 0, 1)) }}
                                                </div>
                                                <span
                                                    class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $mahasiswa->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            <div class="space-y-1">
                                                <div class="flex items-center">
                                                    <i class="fas fa-envelope text-blue-400 dark:text-blue-500 mr-1"></i>
                                                    <span class="font-medium">{{ $mahasiswa->email_kampus }}</span>
                                                    <span
                                                        class="ml-2 px-2 py-0.5 bg-green-100 text-green-800 text-xs rounded-full font-semibold">Login</span>
                                                </div>
                                                @if($mahasiswa->email_pribadi)
                                                    <div class="flex items-center">
                                                        <i class="fas fa-envelope text-gray-400 dark:text-gray-500 mr-1"></i>
                                                        <span class="text-sm">{{ $mahasiswa->email_pribadi }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <span
                                                class="bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 px-2 py-1 rounded-full text-xs font-medium">
                                                {{ $mahasiswa->prodi }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-calendar-alt text-gray-400 dark:text-gray-500 mr-1"></i>
                                            {{ $mahasiswa->angkatan }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span
                                                class="px-3 py-1 inline-flex items-center justify-center mx-auto text-xs leading-5 font-semibold rounded-full 
                                                                {{ $mahasiswa->status == 'aktif' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : '' }}
                                                                {{ $mahasiswa->status == 'cuti' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' : '' }}
                                                                {{ $mahasiswa->status == 'lulus' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' : '' }}
                                                                {{ $mahasiswa->status == 'drop-out' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' : '' }}">
                                                <i class="fas fa-circle text-xs mr-1"></i>
                                                {{ ucfirst($mahasiswa->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2" @click.stop>
                                                <a href="{{ route('admin.mahasiswa.show', $mahasiswa) }}"
                                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition"
                                                    title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.mahasiswa.edit', $mahasiswa) }}"
                                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.mahasiswa.destroy', $mahasiswa) }}" method="POST"
                                                    class="inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition"
                                                        title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox text-gray-300 dark:text-gray-600 text-5xl mb-3"></i>
                                <p class="text-lg">Belum ada data mahasiswa</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $mahasiswas->links() }}
        </div>
    </div>

    {{-- ═══════════════ MODAL: IMPORT MAHASISWA ═══════════════ --}}
    <div id="importModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden"
        onclick="if(event.target===this) closeImportModal()">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col mx-4">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 bg-maroon text-white rounded-t-2xl flex-shrink-0">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <i class="fas fa-file-import"></i> Import Data Mahasiswa
                </h3>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.import.template', ['type' => 'mahasiswa', 'format' => 'xlsx']) }}"
                        data-no-loader
                        class="text-xs bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg transition flex items-center gap-1.5">
                        <i class="fas fa-file-excel"></i> Template Excel
                    </a>
                    <a href="{{ route('admin.import.template', ['type' => 'mahasiswa', 'format' => 'csv']) }}"
                        data-no-loader
                        class="text-xs bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg transition flex items-center gap-1.5">
                        <i class="fas fa-file-csv"></i> Template CSV
                    </a>
                    <button onclick="closeImportModal()"
                        class="text-white text-xl hover:text-white/80 transition">&times;</button>
                </div>
            </div>

            {{-- Body (scrollable) --}}
            <div class="flex-1 overflow-y-auto p-6 space-y-5">
                {{-- Drag & Drop Area --}}
                <div id="imp-dropzone"
                    class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center transition-all duration-300 hover:border-maroon dark:hover:border-red-500 hover:bg-maroon/5 dark:hover:bg-red-900/10 cursor-pointer">
                    <input type="file" id="imp-file-input" class="hidden" accept=".csv,.xlsx,.xls">
                    <div id="imp-dropzone-content">
                        <div
                            class="w-14 h-14 mx-auto mb-3 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <h4 class="text-base font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Drag & drop file di sini
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">atau klik untuk memilih file</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Format: CSV, XLSX | Maksimal: 10MB</p>
                    </div>
                    <div id="imp-file-selected" class="hidden">
                        <div class="flex items-center justify-center gap-3">
                            <i class="fas fa-file-alt text-3xl text-maroon dark:text-red-400"></i>
                            <div class="text-left">
                                <p id="imp-filename" class="text-sm font-semibold text-gray-700 dark:text-gray-300"></p>
                                <p id="imp-filesize" class="text-xs text-gray-500 dark:text-gray-400"></p>
                            </div>
                            <button type="button" onclick="impClearFile()" class="ml-4 text-red-500 hover:text-red-700">
                                <i class="fas fa-times-circle text-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Options & Actions --}}
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="imp-skip-duplicates" checked
                            class="rounded border-gray-300 dark:border-gray-600 text-maroon focus:ring-maroon dark:focus:ring-red-500">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Lewati data duplikat</span>
                    </label>
                    <div class="flex items-center gap-3">
                        <button type="button" id="imp-btn-preview" onclick="impPreviewFile()" disabled
                            class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-5 py-2 rounded-lg transition flex items-center gap-2 text-sm font-medium">
                            <i class="fas fa-eye"></i> Preview
                        </button>
                        <button type="button" id="imp-btn-import" onclick="impImportData()" disabled
                            class="bg-maroon hover:bg-red-900 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-5 py-2 rounded-lg transition flex items-center gap-2 text-sm font-medium">
                            <i class="fas fa-file-import"></i> Import
                        </button>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div id="imp-progress" class="hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span id="imp-progress-text" class="text-sm text-gray-600 dark:text-gray-400">Memproses...</span>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">...</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                        <div id="imp-progress-bar" class="bg-maroon h-2.5 rounded-full animate-pulse" style="width: 100%">
                        </div>
                    </div>
                </div>

                {{-- Kolom Template Info --}}
                <div id="imp-columns-info"
                    class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                        <i class="fas fa-columns text-maroon dark:text-red-400"></i> Kolom Template
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $columns = ['nim' => 'nim', 'nama' => 'nama', 'email' => 'email_pribadi', 'prodi' => 'prodi', 'angkatan' => 'angkatan', 'semester' => 'semester', 'jenis_kelamin' => 'jenis_kelamin', 'telefon' => 'phone', 'alamat' => 'address'];
                            $required = ['nim', 'nama', 'email_pribadi', 'prodi', 'angkatan'];
                        @endphp
                        @foreach($columns as $display => $actual)
                            <span
                                class="px-2 py-1 text-xs rounded-lg {{ in_array($actual, $required) ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 font-semibold' : 'bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-400' }}">
                                {{ $display }}{{ in_array($actual, $required) ? '*' : '' }}
                            </span>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2"><span class="text-red-500">*</span> = kolom
                        wajib</p>
                </div>

                {{-- Preview Table --}}
                <div id="imp-preview-container" class="hidden">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                            <h4 class="text-sm font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                <i class="fas fa-table text-maroon dark:text-red-400"></i> Preview Data
                            </h4>
                            <div id="imp-preview-stats" class="flex items-center gap-3 text-xs"></div>
                        </div>
                        <div class="overflow-x-auto max-h-64">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0">
                                    <tr id="imp-preview-header"></tr>
                                </thead>
                                <tbody id="imp-preview-body"
                                    class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"></tbody>
                            </table>
                        </div>
                        <div id="imp-preview-more"
                            class="hidden p-3 text-center text-xs text-gray-500 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700">
                        </div>
                    </div>
                </div>

                {{-- Validation Errors --}}
                <div id="imp-validation-errors" class="hidden">
                    <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-5 border border-red-200 dark:border-red-800">
                        <h4 class="text-sm font-bold text-red-800 dark:text-red-300 flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle"></i> Error Validasi
                        </h4>
                        <ul id="imp-error-list" class="mt-3 space-y-1.5 max-h-40 overflow-y-auto"></ul>
                    </div>
                </div>

                {{-- Import Results --}}
                <div id="imp-results" class="hidden">
                    <div id="imp-results-container" class="rounded-xl p-5 border">
                        <h4 id="imp-results-title" class="text-sm font-bold flex items-center gap-2"></h4>
                        <div id="imp-results-summary" class="mt-3 grid grid-cols-3 gap-3"></div>
                        <div id="imp-results-details" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // SweetAlert Delete Confirmation
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data mahasiswa ini akan dihapus permanen!",
                        icon: 'warning',
                        iconColor: '#7a1621',
                        showCancelButton: true,
                        confirmButtonColor: '#7a1621',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: '#ffffff',
                        showLoaderOnConfirm: false,
                        allowOutsideClick: true,
                        customClass: {
                            confirmButton: 'btn btn-danger',
                            cancelButton: 'btn btn-secondary'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>

        @if(session('success'))
            <script>
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false,
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-xl'
                    }
                });
            </script>
        @endif

        {{-- ═══════════ Import Modal JavaScript ═══════════ --}}
        <script>
            let impSelectedFile = null;
            let impPreviewData = null;

            // ── Dropzone ──
            const impDropzone = document.getElementById('imp-dropzone');
            const impFileInput = document.getElementById('imp-file-input');

            impDropzone.addEventListener('click', (e) => {
                if (e.target.closest('button')) return; // don't trigger on clear button
                impFileInput.click();
            });

            impDropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                impDropzone.classList.add('border-maroon', 'bg-maroon/10');
            });

            impDropzone.addEventListener('dragleave', () => {
                impDropzone.classList.remove('border-maroon', 'bg-maroon/10');
            });

            impDropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                impDropzone.classList.remove('border-maroon', 'bg-maroon/10');
                if (e.dataTransfer.files.length > 0) {
                    impHandleFileSelect(e.dataTransfer.files[0]);
                }
            });

            impFileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    impHandleFileSelect(e.target.files[0]);
                }
            });

            function impHandleFileSelect(file) {
                const ext = file.name.split('.').pop().toLowerCase();
                if (!['csv', 'xlsx', 'xls'].includes(ext)) {
                    Swal.fire('Format Tidak Didukung', 'Gunakan file CSV atau XLSX.', 'error');
                    return;
                }
                if (file.size > 10 * 1024 * 1024) {
                    Swal.fire('File Terlalu Besar', 'Maksimal 10MB.', 'error');
                    return;
                }

                impSelectedFile = file;
                document.getElementById('imp-dropzone-content').classList.add('hidden');
                document.getElementById('imp-file-selected').classList.remove('hidden');
                document.getElementById('imp-filename').textContent = file.name;
                document.getElementById('imp-filesize').textContent = impFormatSize(file.size);
                document.getElementById('imp-btn-preview').disabled = false;

                // Reset states
                document.getElementById('imp-preview-container').classList.add('hidden');
                document.getElementById('imp-validation-errors').classList.add('hidden');
                document.getElementById('imp-results').classList.add('hidden');
                document.getElementById('imp-btn-import').disabled = true;
            }

            function impClearFile() {
                impSelectedFile = null;
                impPreviewData = null;
                impFileInput.value = '';
                document.getElementById('imp-dropzone-content').classList.remove('hidden');
                document.getElementById('imp-file-selected').classList.add('hidden');
                document.getElementById('imp-btn-preview').disabled = true;
                document.getElementById('imp-btn-import').disabled = true;
                document.getElementById('imp-preview-container').classList.add('hidden');
                document.getElementById('imp-validation-errors').classList.add('hidden');
                document.getElementById('imp-results').classList.add('hidden');
            }

            function impFormatSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            function closeImportModal() {
                document.getElementById('importModal').classList.add('hidden');
            }

            // Close on Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeImportModal();
            });

            // ── Preview ──
            async function impPreviewFile() {
                if (!impSelectedFile) return;

                const formData = new FormData();
                formData.append('file', impSelectedFile);

                impShowProgress('Membaca file...');

                try {
                    const response = await fetch('/admin/import/mahasiswa/preview', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    const result = await response.json();
                    impHideProgress();

                    if (result.success) {
                        impPreviewData = result.data;
                        impShowPreview(result.data);

                        const validation = result.data.validation || {};
                        if (validation.valid) {
                            document.getElementById('imp-btn-import').disabled = false;
                            document.getElementById('imp-validation-errors').classList.add('hidden');
                        } else {
                            impShowValidationErrors(validation.errors || []);
                            const validatedCount = validation.validated_data ? validation.validated_data.length : 0;
                            document.getElementById('imp-btn-import').disabled = validatedCount === 0;
                        }
                    } else {
                        Swal.fire('Error', result.message || 'Error saat membaca file', 'error');
                    }
                } catch (error) {
                    impHideProgress();
                    Swal.fire('Error', 'Error: ' + error.message, 'error');
                }
            }

            function impShowPreview(data) {
                const header = document.getElementById('imp-preview-header');
                const body = document.getElementById('imp-preview-body');
                const stats = document.getElementById('imp-preview-stats');
                const more = document.getElementById('imp-preview-more');

                header.innerHTML = '';
                body.innerHTML = '';

                const columns = data.columns || [];
                const preview = data.preview || [];

                if (columns.length === 0) {
                    Swal.fire('Error', 'Tidak dapat membaca kolom dari file', 'error');
                    return;
                }

                // Header
                columns.forEach(col => {
                    const th = document.createElement('th');
                    th.className = 'px-3 py-2 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap';
                    th.textContent = col;
                    header.appendChild(th);
                });

                // Rows
                preview.slice(0, 50).forEach(row => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-50 dark:hover:bg-gray-700/50';
                    columns.forEach(col => {
                        const td = document.createElement('td');
                        td.className = 'px-3 py-2 text-xs text-gray-700 dark:text-gray-300 whitespace-nowrap';
                        td.textContent = row[col] || '-';
                        tr.appendChild(td);
                    });
                    body.appendChild(tr);
                });

                // Stats
                const validation = data.validation || {};
                const validRows = validation.valid_rows || 0;
                const duplicateRows = validation.duplicate_rows || 0;

                stats.innerHTML = `
                            <span class="px-2 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-medium">
                                Total: ${data.total_rows || 0}
                            </span>
                            <span class="px-2 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 font-medium">
                                Valid: ${validRows}
                            </span>
                            ${duplicateRows > 0 ? `
                            <span class="px-2 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 font-medium">
                                Duplikat: ${duplicateRows}
                            </span>` : ''}
                        `;

                if (data.total_rows > 50) {
                    more.textContent = `Menampilkan 50 dari ${data.total_rows} baris`;
                    more.classList.remove('hidden');
                } else {
                    more.classList.add('hidden');
                }

                document.getElementById('imp-preview-container').classList.remove('hidden');
            }

            function impShowValidationErrors(errors) {
                const list = document.getElementById('imp-error-list');
                list.innerHTML = '';

                if (!errors || !Array.isArray(errors) || errors.length === 0) {
                    document.getElementById('imp-validation-errors').classList.add('hidden');
                    return;
                }

                errors.slice(0, 20).forEach(err => {
                    const li = document.createElement('li');
                    li.className = 'flex items-start gap-2 text-xs text-red-700 dark:text-red-300';
                    const msg = Array.isArray(err.errors) ? err.errors.join(', ') : (err.errors || err.message || 'Error');
                    li.innerHTML = `<i class="fas fa-times-circle mt-0.5 flex-shrink-0"></i><span><strong>Baris ${err.row || '?'}:</strong> ${msg}</span>`;
                    list.appendChild(li);
                });

                if (errors.length > 20) {
                    const li = document.createElement('li');
                    li.className = 'text-xs text-red-600 dark:text-red-400 italic';
                    li.textContent = `... dan ${errors.length - 20} error lainnya`;
                    list.appendChild(li);
                }

                document.getElementById('imp-validation-errors').classList.remove('hidden');
            }

            // ── Import ──
            async function impImportData() {
                if (!impSelectedFile) return;

                Swal.fire({
                    title: 'Konfirmasi Import',
                    text: 'Apakah Anda yakin ingin mengimport data mahasiswa ini?',
                    icon: 'question',
                    iconColor: '#7a1621',
                    showCancelButton: true,
                    confirmButtonColor: '#7a1621',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Import!',
                    cancelButtonText: 'Batal',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        await impPerformImport();
                    }
                });
            }

            async function impPerformImport() {
                const formData = new FormData();
                formData.append('file', impSelectedFile);
                formData.append('skip_duplicates', document.getElementById('imp-skip-duplicates').checked ? '1' : '0');

                impShowProgress('Mengimport data mahasiswa...');
                document.getElementById('imp-btn-import').disabled = true;

                try {
                    const response = await fetch('/admin/import/mahasiswa/import', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    const result = await response.json();
                    impHideProgress();
                    impShowResults(result);
                } catch (error) {
                    impHideProgress();
                    Swal.fire('Error', 'Error: ' + error.message, 'error');
                    document.getElementById('imp-btn-import').disabled = false;
                }
            }

            function impShowResults(result) {
                const container = document.getElementById('imp-results-container');
                const title = document.getElementById('imp-results-title');
                const summary = document.getElementById('imp-results-summary');
                const details = document.getElementById('imp-results-details');

                if (result.success) {
                    container.className = 'rounded-xl p-5 border bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800';
                    title.innerHTML = '<i class="fas fa-check-circle text-green-600 dark:text-green-400"></i><span class="text-green-800 dark:text-green-300">Import Berhasil!</span>';
                } else {
                    container.className = 'rounded-xl p-5 border bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800';
                    title.innerHTML = '<i class="fas fa-times-circle text-red-600 dark:text-red-400"></i><span class="text-red-800 dark:text-red-300">Import Gagal</span>';
                }

                const s = result.result?.summary || {};
                summary.innerHTML = `
                            <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-lg">
                                <div class="text-xl font-bold text-gray-800 dark:text-gray-200">${s.total || 0}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Total</div>
                            </div>
                            <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-lg">
                                <div class="text-xl font-bold text-green-600 dark:text-green-400">${s.success || 0}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Berhasil</div>
                            </div>
                            <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-lg">
                                <div class="text-xl font-bold text-red-600 dark:text-red-400">${s.failed || 0}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Gagal</div>
                            </div>
                        `;

                const failedRows = result.result?.results?.failed || [];
                if (failedRows.length > 0) {
                    details.innerHTML = `
                                <h5 class="font-semibold text-red-700 dark:text-red-400 mb-1 text-xs">Detail Error:</h5>
                                <ul class="space-y-1 text-xs text-red-600 dark:text-red-300 max-h-32 overflow-y-auto">
                                    ${failedRows.map(f => `<li>Baris ${f.row}: ${f.error}</li>`).join('')}
                                </ul>
                            `;
                } else {
                    details.innerHTML = '';
                }

                document.getElementById('imp-results').classList.remove('hidden');

                // If success, reload page after delay so user can see the result
                if (result.success) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 2500);
                }
            }

            // ── Progress helpers ──
            function impShowProgress(text) {
                document.getElementById('imp-progress').classList.remove('hidden');
                document.getElementById('imp-progress-text').textContent = text;
            }

            function impHideProgress() {
                document.getElementById('imp-progress').classList.add('hidden');
            }
        </script>
    @endpush
@endsection