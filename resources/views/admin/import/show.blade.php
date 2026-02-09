@extends('layouts.admin')

@section('title', $importConfig['title'])
@section('page-title', $importConfig['title'])

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <nav class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                <a href="{{ route('admin.import.index') }}" class="hover:text-maroon dark:hover:text-red-400">Import Data</a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <span class="text-gray-700 dark:text-gray-300">{{ $importConfig['title'] }}</span>
            </nav>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                <i class="fas {{ $importConfig['icon'] }} mr-3 text-maroon dark:text-red-500"></i>
                {{ $importConfig['title'] }}
            </h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">{{ $importConfig['description'] }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.import.template', ['type' => $type, 'format' => 'xlsx']) }}" 
                data-no-loader
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2 shadow-md hover:shadow-lg">
                <i class="fas fa-file-excel"></i>
                <span>Template Excel</span>
            </a>
            <a href="{{ route('admin.import.template', ['type' => $type, 'format' => 'csv']) }}" 
                data-no-loader
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2 shadow-md hover:shadow-lg">
                <i class="fas fa-file-csv"></i>
                <span>Template CSV</span>
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Upload Area -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">
                <i class="fas fa-upload text-maroon dark:text-red-400 mr-2"></i>
                Upload File
            </h3>

            <!-- Drag & Drop Area -->
            <div id="dropzone" 
                class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center transition-all duration-300 hover:border-maroon dark:hover:border-red-500 hover:bg-maroon/5 dark:hover:bg-red-900/10 cursor-pointer">
                <input type="file" id="file-input" class="hidden" accept=".csv,.xlsx,.xls">
                <div id="dropzone-content">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Drag & drop file di sini
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        atau klik untuk memilih file
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        Format: CSV, XLSX | Maksimal: 10MB
                    </p>
                </div>
                <div id="file-selected" class="hidden">
                    <div class="flex items-center justify-center gap-3">
                        <i class="fas fa-file-alt text-3xl text-maroon dark:text-red-400"></i>
                        <div class="text-left">
                            <p id="filename" class="text-sm font-semibold text-gray-700 dark:text-gray-300"></p>
                            <p id="filesize" class="text-xs text-gray-500 dark:text-gray-400"></p>
                        </div>
                        <button type="button" onclick="clearFile()" class="ml-4 text-red-500 hover:text-red-700">
                            <i class="fas fa-times-circle text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Options -->
            <div class="mt-4 flex items-center gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="skip-duplicates" checked 
                        class="rounded border-gray-300 dark:border-gray-600 text-maroon focus:ring-maroon dark:focus:ring-red-500">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Lewati data duplikat</span>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex items-center gap-4">
                <button type="button" id="btn-preview" onclick="previewFile()" disabled
                    class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-6 py-2 rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-eye"></i>
                    <span>Preview Data</span>
                </button>
                <button type="button" id="btn-import" onclick="importData()" disabled
                    class="bg-maroon hover:bg-red-900 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-6 py-2 rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-file-import"></i>
                    <span>Import Data</span>
                </button>
            </div>

            <!-- Progress Bar -->
            <div id="progress-container" class="hidden mt-6">
                <div class="flex items-center justify-between mb-2">
                    <span id="progress-text" class="text-sm text-gray-600 dark:text-gray-400">Memproses...</span>
                    <span id="progress-percent" class="text-sm font-semibold text-gray-700 dark:text-gray-300">0%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                    <div id="progress-bar" class="bg-maroon h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Preview Table -->
        <div id="preview-container" class="hidden mt-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">
                        <i class="fas fa-table text-maroon dark:text-red-400 mr-2"></i>
                        Preview Data
                    </h3>
                    <div id="preview-stats" class="flex items-center gap-4 text-sm"></div>
                </div>
                <div class="overflow-x-auto max-h-96">
                    <table id="preview-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0">
                            <tr id="preview-header"></tr>
                        </thead>
                        <tbody id="preview-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        </tbody>
                    </table>
                </div>
                <div id="preview-more" class="hidden p-4 text-center text-sm text-gray-500 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700">
                </div>
            </div>
        </div>

        <!-- Validation Errors -->
        <div id="validation-errors" class="hidden mt-6">
            <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-6 border border-red-200 dark:border-red-800">
                <h4 class="text-lg font-bold text-red-800 dark:text-red-300 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Error Validasi
                </h4>
                <ul id="error-list" class="mt-4 space-y-2 max-h-60 overflow-y-auto"></ul>
            </div>
        </div>

        <!-- Import Results -->
        <div id="import-results" class="hidden mt-6">
            <div id="results-container" class="rounded-xl p-6 border">
                <h4 id="results-title" class="text-lg font-bold flex items-center"></h4>
                <div id="results-summary" class="mt-4 grid grid-cols-3 gap-4"></div>
                <div id="results-details" class="mt-4"></div>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="lg:col-span-1">
        <!-- Template Columns -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">
                <i class="fas fa-columns text-maroon dark:text-red-400 mr-2"></i>
                Kolom Template
            </h3>
            <ul class="space-y-2">
                @foreach($importConfig['template_columns'] as $column)
                <li class="flex items-center text-sm">
                    @if(in_array($column, ['nim', 'nama', 'nidn', 'kode_mk', 'nama_matkul', 'sks', 'semester', 'kode_ruangan', 'nama_ruangan', 'kapasitas', 'prodi', 'angkatan', 'nidn_dosen_pa']))
                        <i class="fas fa-asterisk text-red-500 mr-2 text-xs"></i>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $column }}</span>
                        <span class="ml-2 text-xs text-red-500">(wajib)</span>
                    @else
                        <i class="fas fa-circle text-gray-300 dark:text-gray-600 mr-2 text-xs"></i>
                        <span class="text-gray-600 dark:text-gray-400">{{ $column }}</span>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Recent Imports for this type -->
        @if($recentLogs->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">
                <i class="fas fa-history text-maroon dark:text-red-400 mr-2"></i>
                Import Terbaru
            </h3>
            <ul class="space-y-3">
                @foreach($recentLogs as $log)
                <li class="flex items-center justify-between text-sm py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                    <div>
                        <span class="text-gray-700 dark:text-gray-300">{{ $log->filename ?? 'File' }}</span>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                            {{ $log->success_count }}
                        </span>
                        @if($log->failed_count > 0)
                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                            {{ $log->failed_count }}
                        </span>
                        @endif
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    const importType = '{{ $type }}';
    let selectedFile = null;
    let previewData = null;

    // Dropzone functionality
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('file-input');

    dropzone.addEventListener('click', () => fileInput.click());

    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('border-maroon', 'bg-maroon/10');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('border-maroon', 'bg-maroon/10');
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('border-maroon', 'bg-maroon/10');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileSelect(files[0]);
        }
    });

    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });

    function handleFileSelect(file) {
        const validTypes = ['text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];
        const validExtensions = ['csv', 'xlsx', 'xls'];
        const extension = file.name.split('.').pop().toLowerCase();

        if (!validExtensions.includes(extension)) {
            showError('Format file tidak didukung. Gunakan CSV atau XLSX.');
            return;
        }

        if (file.size > 10 * 1024 * 1024) {
            showError('Ukuran file terlalu besar. Maksimal 10MB.');
            return;
        }

        selectedFile = file;
        
        document.getElementById('dropzone-content').classList.add('hidden');
        document.getElementById('file-selected').classList.remove('hidden');
        document.getElementById('filename').textContent = file.name;
        document.getElementById('filesize').textContent = formatFileSize(file.size);
        
        document.getElementById('btn-preview').disabled = false;
        
        // Reset states
        document.getElementById('preview-container').classList.add('hidden');
        document.getElementById('validation-errors').classList.add('hidden');
        document.getElementById('import-results').classList.add('hidden');
        document.getElementById('btn-import').disabled = true;
    }

    function clearFile() {
        selectedFile = null;
        previewData = null;
        fileInput.value = '';
        
        document.getElementById('dropzone-content').classList.remove('hidden');
        document.getElementById('file-selected').classList.add('hidden');
        document.getElementById('btn-preview').disabled = true;
        document.getElementById('btn-import').disabled = true;
        document.getElementById('preview-container').classList.add('hidden');
        document.getElementById('validation-errors').classList.add('hidden');
        document.getElementById('import-results').classList.add('hidden');
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    async function previewFile() {
        if (!selectedFile) return;

        const formData = new FormData();
        formData.append('file', selectedFile);

        showProgress('Membaca file...');

        try {
            const response = await fetch(`/admin/import/${importType}/preview`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();
            hideProgress();

            if (result.success) {
                previewData = result.data;
                showPreview(result.data);
                
                const validation = result.data.validation || {};
                if (validation.valid) {
                    document.getElementById('btn-import').disabled = false;
                    document.getElementById('validation-errors').classList.add('hidden');
                } else {
                    showValidationErrors(validation.errors || []);
                    const validatedCount = validation.validated_data ? validation.validated_data.length : 0;
                    document.getElementById('btn-import').disabled = validatedCount === 0;
                }
            } else {
                showError(result.message || 'Error saat membaca file');
            }
        } catch (error) {
            hideProgress();
            showError('Error: ' + error.message);
        }
    }

    function showPreview(data) {
        const container = document.getElementById('preview-container');
        const header = document.getElementById('preview-header');
        const body = document.getElementById('preview-body');
        const stats = document.getElementById('preview-stats');
        const more = document.getElementById('preview-more');

        // Clear previous
        header.innerHTML = '';
        body.innerHTML = '';

        // Safety check for required data
        const columns = data.columns || [];
        const preview = data.preview || [];
        
        if (columns.length === 0) {
            container.classList.add('hidden');
            showError('Tidak dapat membaca kolom dari file');
            return;
        }

        // Build header
        columns.forEach(col => {
            const th = document.createElement('th');
            th.className = 'px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider';
            th.textContent = col;
            header.appendChild(th);
        });

        // Build rows
        preview.slice(0, 50).forEach((row, index) => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 dark:hover:bg-gray-700/50';
            
            columns.forEach(col => {
                const td = document.createElement('td');
                td.className = 'px-4 py-2 text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap';
                td.textContent = row[col] || '-';
                tr.appendChild(td);
            });
            
            body.appendChild(tr);
        });

        // Stats with safety checks
        const validation = data.validation || {};
        const validRows = validation.valid_rows || 0;
        const duplicateRows = validation.duplicate_rows || 0;
        
        stats.innerHTML = `
            <span class="px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-medium">
                Total: ${data.total_rows || 0} baris
            </span>
            <span class="px-3 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium">
                Valid: ${validRows} baris
            </span>
            ${duplicateRows > 0 ? `
            <span class="px-3 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 text-xs font-medium">
                Duplikat: ${duplicateRows} baris
            </span>
            ` : ''}
        `;

        // Show more indicator
        if (data.total_rows > 50) {
            more.textContent = `Menampilkan 50 dari ${data.total_rows} baris`;
            more.classList.remove('hidden');
        } else {
            more.classList.add('hidden');
        }

        container.classList.remove('hidden');
    }

    function showValidationErrors(errors) {
        const container = document.getElementById('validation-errors');
        const list = document.getElementById('error-list');
        
        list.innerHTML = '';
        
        // Safety check
        if (!errors || !Array.isArray(errors) || errors.length === 0) {
            container.classList.add('hidden');
            return;
        }
        
        errors.slice(0, 20).forEach(err => {
            const li = document.createElement('li');
            li.className = 'flex items-start gap-2 text-sm text-red-700 dark:text-red-300';
            // Handle both array and string error formats
            const errorMsg = Array.isArray(err.errors) ? err.errors.join(', ') : (err.errors || err.message || 'Error tidak diketahui');
            li.innerHTML = `
                <i class="fas fa-times-circle mt-0.5 flex-shrink-0"></i>
                <span><strong>Baris ${err.row || '?'}:</strong> ${errorMsg}</span>
            `;
            list.appendChild(li);
        });

        if (errors.length > 20) {
            const li = document.createElement('li');
            li.className = 'text-sm text-red-600 dark:text-red-400 italic';
            li.textContent = `... dan ${errors.length - 20} error lainnya`;
            list.appendChild(li);
        }

        container.classList.remove('hidden');
    }

    async function importData() {
        if (!selectedFile) return;

        showConfirm(
            'Apakah Anda yakin ingin mengimport data ini?',
            async function() {
                await performImport();
            },
            null,
            'Konfirmasi Import'
        );
    }

    async function performImport() {

        const formData = new FormData();
        formData.append('file', selectedFile);
        formData.append('skip_duplicates', document.getElementById('skip-duplicates').checked ? '1' : '0');

        showProgress('Mengimport data...');
        document.getElementById('btn-import').disabled = true;

        try {
            const response = await fetch(`/admin/import/${importType}/import`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();
            hideProgress();

            showImportResults(result);
        } catch (error) {
            hideProgress();
            showError('Error: ' + error.message);
            document.getElementById('btn-import').disabled = false;
        }
    }

    function showImportResults(result) {
        const container = document.getElementById('import-results');
        const resultsContainer = document.getElementById('results-container');
        const title = document.getElementById('results-title');
        const summary = document.getElementById('results-summary');
        const details = document.getElementById('results-details');

        if (result.success) {
            resultsContainer.className = 'rounded-xl p-6 border bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800';
            title.innerHTML = '<i class="fas fa-check-circle text-green-600 dark:text-green-400 mr-2"></i><span class="text-green-800 dark:text-green-300">Import Berhasil!</span>';
        } else {
            resultsContainer.className = 'rounded-xl p-6 border bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800';
            title.innerHTML = '<i class="fas fa-times-circle text-red-600 dark:text-red-400 mr-2"></i><span class="text-red-800 dark:text-red-300">Import Gagal</span>';
        }

        const summaryData = result.result?.summary || {};
        summary.innerHTML = `
            <div class="text-center p-4 bg-white dark:bg-gray-800 rounded-lg">
                <div class="text-2xl font-bold text-gray-800 dark:text-gray-200">${summaryData.total || 0}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Total</div>
            </div>
            <div class="text-center p-4 bg-white dark:bg-gray-800 rounded-lg">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">${summaryData.success || 0}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Berhasil</div>
            </div>
            <div class="text-center p-4 bg-white dark:bg-gray-800 rounded-lg">
                <div class="text-2xl font-bold text-red-600 dark:text-red-400">${summaryData.failed || 0}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Gagal</div>
            </div>
        `;

        // Show failed details if any
        const failedRows = result.result?.results?.failed || [];
        if (failedRows.length > 0) {
            details.innerHTML = `
                <h5 class="font-semibold text-red-700 dark:text-red-400 mb-2">Detail Error:</h5>
                <ul class="space-y-1 text-sm text-red-600 dark:text-red-300 max-h-40 overflow-y-auto">
                    ${failedRows.map(f => `<li>Baris ${f.row}: ${f.error}</li>`).join('')}
                </ul>
            `;
        } else {
            details.innerHTML = '';
        }

        container.classList.remove('hidden');

        // Scroll to results
        container.scrollIntoView({ behavior: 'smooth' });
    }

    function showProgress(text) {
        document.getElementById('progress-container').classList.remove('hidden');
        document.getElementById('progress-text').textContent = text;
        document.getElementById('progress-bar').style.width = '100%';
        document.getElementById('progress-bar').classList.add('animate-pulse');
    }

    function hideProgress() {
        document.getElementById('progress-container').classList.add('hidden');
        document.getElementById('progress-bar').classList.remove('animate-pulse');
    }
</script>
@endpush
@endsection
