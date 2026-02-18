@extends('layouts.admin')

@section('title', 'Data Dosen')
@section('page-title', 'Data Dosen')

@section('content')
<div class="mb-6 flex flex-col items-start md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
            <i class="fas fa-chalkboard-teacher mr-3 text-maroon dark:text-red-500"></i>
            Manajemen Dosen
        </h2>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Kelola data dosen pengajar di sistem</p>
    </div>
    <div class="flex flex-row items-center gap-3">
        <button type="button" onclick="document.getElementById('modal-import-dosen').classList.remove('hidden')"
            class="group bg-white dark:bg-gray-800 text-maroon dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10 border-2 border-maroon/10 hover:border-maroon px-4 py-2 rounded-xl transition-all duration-300 flex items-center justify-center gap-3 shadow-sm hover:shadow-lg hover:shadow-maroon/10 whitespace-nowrap">
            <div class="w-6 h-6 rounded-lg bg-maroon/5 dark:bg-maroon/10 group-hover:bg-maroon/10 flex items-center justify-center transition-colors">
                <i class="fas fa-file-import text-xs"></i>
            </div>
            <span class="font-bold text-sm tracking-wide">Import Data Dosen</span>
        </button>

        <a href="{{ route('admin.dosen.create') }}"
            class="bg-maroon text-white hover:bg-red-900 px-4 py-2 rounded-lg transition flex items-center justify-center shadow-md transform hover:scale-105 text-sm font-medium whitespace-nowrap">
            <i class="fas fa-plus mr-2"></i>
            Tambah Dosen
        </a>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-maroon text-white">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class=""></i>No
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-id-card mr-2"></i>NIDN
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-user mr-2"></i>Nama Dosen
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-graduation-cap mr-2"></i>Pendidikan
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-toggle-on mr-2"></i>Status
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-cog mr-2"></i>Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($dosens as $dosen)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                        {{ ($dosens->currentPage() - 1) * $dosens->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-mono text-maroon dark:text-red-400 font-bold">{{ $dosen->nidn }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div
                                class="h-10 w-10 rounded-full bg-maroon flex items-center justify-center text-white font-bold mr-3">
                                {{ strtoupper(substr($dosen->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $dosen->user->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-phone text-gray-400 dark:text-gray-500 mr-1"></i>
                                    {{ $dosen->phone ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-gray-100">
                            <i class="fas fa-envelope text-gray-400 dark:text-gray-500 mr-1"></i>
                            {{ $dosen->user->email }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="px-3 py-1 inline-flex items-center leading-none text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                            <i class="fas fa-graduation-cap mr-1"></i>
                            {{ $dosen->pendidikan }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <form action="{{ route('admin.dosen.toggle-status', $dosen) }}" method="POST"
                            id="toggle-status-{{ $dosen->id }}">
                            @csrf
                            <button type="button"
                                onclick="confirmToggleStatus('{{ $dosen->id }}', '{{ $dosen->status }}', '{{ $dosen->user->name }}')"
                                class="px-3 py-1 inline-flex items-center justify-center mx-auto text-xs leading-5 font-semibold rounded-full cursor-pointer hover:shadow-md transition-all
                                                                        {{ $dosen->status == 'aktif' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 hover:bg-green-200' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 hover:bg-red-200' }}"
                                title="Klik untuk mengubah status">
                                <i
                                    class="fas {{ $dosen->status == 'aktif' ? 'fa-check-circle' : 'fa-times-circle' }} text-xs mr-1"></i>
                                {{ ucfirst($dosen->status) }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-2">
                            <button type="button"
                                onclick="document.getElementById('modal-dosen-{{ $dosen->id }}').classList.remove('hidden')"
                                class="text-blue-600 hover:text-blue-900 transition p-2 hover:bg-blue-50 rounded"
                                title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="{{ route('admin.dosen.edit', $dosen) }}"
                                class="text-yellow-600 hover:text-yellow-900 transition p-2 hover:bg-yellow-50 rounded"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.dosen.destroy', $dosen) }}" method="POST"
                                class="inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-600 hover:text-red-900 transition p-2 hover:bg-red-50 rounded"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- Modal Dosen -->
                <div id="modal-dosen-{{ $dosen->id }}"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl w-11/12 md:w-3/4 lg:w-2/3 max-h-[90vh] overflow-y-auto overflow-x-hidden">
                        <div class="flex items-center justify-between px-6 py-4 bg-maroon text-white">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="h-10 w-10 rounded-full bg-white bg-opacity-10 flex items-center justify-center text-white font-bold">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Profil Dosen</h3>
                                    <p class="text-sm text-white text-opacity-90">Informasi dan kelas yang diampu</p>
                                </div>
                            </div>
                            <button
                                onclick="document.getElementById('modal-dosen-{{ $dosen->id }}').classList.add('hidden')"
                                class="text-white text-xl leading-none">&times;</button>
                        </div>
                        <div class="p-6 text-sm text-gray-700 dark:text-gray-300">
                            <div class="grid grid-cols-1 gap-2">
                                <div><strong>Nama:</strong> {{ $dosen->user->name }}</div>
                                <div><strong>Email:</strong> {{ $dosen->user->email }}</div>
                                <div><strong>NIDN:</strong> {{ $dosen->nidn }}</div>
                                <div><strong>Program Studi:</strong>
                                    @php
                                    $prodiCodes = is_array($dosen->prodi) ? $dosen->prodi : [$dosen->prodi];
                                    $prodiNames = \App\Models\Prodi::whereIn('kode_prodi', $prodiCodes)->pluck('nama_prodi', 'kode_prodi');
                                    $displayProdi = collect($prodiCodes)->map(fn($code) => $prodiNames[$code] ?? $code)->implode(', ');
                                    @endphp
                                    {{ $displayProdi }}
                                </div>
                                <div><strong>Telepon:</strong> {{ $dosen->phone ?? '-' }}</div>
                                <div><strong>Status:</strong> {{ ucfirst($dosen->status) }}</div>
                            </div>
                        </div>
                        <div class="p-6 border-t dark:border-gray-700">
                            <h5 class="text-sm font-semibold mb-2">Mata Kuliah yang Diampu</h5>
                            @php
                            $assigned = collect();
                            if (!empty($dosen->mata_kuliah_ids) && is_array($dosen->mata_kuliah_ids) && count($dosen->mata_kuliah_ids) > 0) {
                            $assigned = \App\Models\MataKuliah::whereIn('id', $dosen->mata_kuliah_ids)->get();
                            }
                            // merge kelasMataKuliahs' mataKuliah
                            if ($dosen->relationLoaded('kelasMataKuliahs') && $dosen->kelasMataKuliahs->count()) {
                            $dosen->kelasMataKuliahs->each(function ($km) use (&$assigned) {
                            if ($km->mataKuliah)
                            $assigned->push($km->mataKuliah);
                            });
                            }
                            $assigned = $assigned->unique('id')->values();
                            @endphp

                            @if($assigned->count())
                            <div class="grid grid-cols-1 gap-2">
                                @foreach($assigned as $mk)
                                <div class="flex items-center justify-between p-2 border dark:border-gray-700 rounded">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $mk->nama_mk ?? '-' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Kode: {{ $mk->kode_mk ?? '-' }}</div>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">SKS: {{ $mk->sks ?? '-' }}</div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-sm text-gray-500">Belum ada mata kuliah tercatat.</div>
                            @endif
                        </div>
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 flex justify-end space-x-3">
                            <a href="{{ route('admin.dosen.edit', $dosen) }}"
                                class="bg-maroon text-white px-4 py-2 rounded shadow">Edit</a>
                            <button
                                onclick="document.getElementById('modal-dosen-{{ $dosen->id }}').classList.add('hidden')"
                                class="px-4 py-2 border dark:border-gray-600 rounded text-gray-700 dark:text-gray-300">Tutup</button>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                            <p class="text-lg font-medium">Belum ada data dosen</p>
                            <p class="text-sm text-gray-400 mt-1">Tambahkan dosen pertama dengan klik tombol "Tambah
                                Dosen"</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($dosens->hasPages())
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        {{ $dosens->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Flash Message SweetAlert
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 2000,
        background: '#ffffff',
        iconColor: '#1d7f35',
        color: '#333333'
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: "{{ session('error') }}",
        background: '#ffffff',
        iconColor: '#d33',
        confirmButtonColor: '#7a1621'
    });
    @endif

    @if(session('import_errors'))
    Swal.fire({
        icon: 'warning',
        title: 'Peringatan Import',
        html: `
                        <div class="text-left text-sm max-h-60 overflow-y-auto">
                            <ul class="list-disc list-inside">
                                @foreach(session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    `,
        background: '#ffffff',
        iconColor: '#f0ad4e',
        confirmButtonColor: '#7a1621'
    });
    @endif

    // SweetAlert Delete Confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data dosen ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#7a1621',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#ffffff',
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

    // SweetAlert Status Toggle Confirmation
    function confirmToggleStatus(id, currentStatus, name) {
        const isActive = currentStatus === 'aktif';
        const actionText = isActive ? 'menonaktifkan' : 'mengaktifkan';
        const confirmButtonColor = isActive ? '#d33' : '#1d7f35'; // Red for deactivate, Green for activate

        Swal.fire({
            title: isActive ? 'Nonaktifkan Dosen?' : 'Aktifkan Dosen?',
            html: `Apakah Anda yakin ingin ${actionText} dosen <strong>${name}</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: isActive ? 'Ya, Nonaktifkan!' : 'Ya, Aktifkan!',
            cancelButtonText: 'Batal',
            background: '#ffffff'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('toggle-status-' + id).submit();
            }
        });
    }
</script>
<script>
    // Ensure drag-and-drop handlers attach after DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('file-input');

        if (!dropArea || !fileInput) return;

        function addHighlight() {
            dropArea.classList.add('bg-gray-100', 'ring-2', 'ring-maroon-300');
        }

        function removeHighlight() {
            dropArea.classList.remove('bg-gray-100', 'ring-2', 'ring-maroon-300');
        }

        ['dragenter', 'dragover'].forEach(evt => {
            dropArea.addEventListener(evt, (e) => {
                e.preventDefault();
                e.stopPropagation();
                addHighlight();
            });
        });

        ['dragleave', 'drop'].forEach(evt => {
            dropArea.addEventListener(evt, (e) => {
                e.preventDefault();
                e.stopPropagation();
                removeHighlight();
            });
        });

        dropArea.addEventListener('click', () => fileInput.click());

        dropArea.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files && files.length) {
                fileInput.files = files;
                updateFilename();
            }
        });

        fileInput.addEventListener('change', updateFilename);

        function updateFilename() {
            const name = fileInput.files[0] ? fileInput.files[0].name : '';
            const p = dropArea.querySelector('p');
            if (p) p.textContent = name || 'Tarik dan lepas file CSV di sini, atau klik untuk memilih';
        }

        // If modal already open and file was pre-selected, show filename
        updateFilename();
    });
</script>
<!-- Import Modal -->
<div id="modal-import-dosen"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden transition-all duration-300">
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-11/12 md:w-3/4 lg:w-1/2 max-h-[85vh] flex flex-col overflow-hidden transform scale-100 transition-all">
        <!-- Header -->
        <div
            class="flex-none flex items-center justify-between px-8 py-6 bg-gradient-to-r from-maroon to-red-900 text-white">
            <div class="flex items-center space-x-4">
                <div
                    class="h-12 w-12 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center text-white shadow-inner">
                    <i class="fas fa-cloud-upload-alt text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold tracking-tight">Import Data Dosen</h3>
                    <p class="text-sm text-red-100/90 font-medium">Unggah file CSV untuk menambahkan data dosen secara
                        massal</p>
                </div>
            </div>
        </div>

        <form id="form-import-dosen" action="{{ route('admin.dosen.import') }}" method="POST"
            enctype="multipart/form-data" class="flex-1 overflow-y-auto">
            @csrf
            <div class="p-8">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                    <!-- Left Column: Upload Area -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <span class="w-1 h-4 bg-maroon rounded-full"></span>
                            Upload File CSV
                        </label>

                        <div id="drop-area"
                            class="flex-1 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-8 bg-gray-50/50 dark:bg-gray-700/30 hover:bg-red-50/30 dark:hover:bg-red-900/10 hover:border-maroon/50 transition-all cursor-pointer group min-h-[200px]">

                            <div
                                class="w-16 h-16 bg-white dark:bg-gray-700 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-file-csv text-3xl text-maroon/80"></i>
                            </div>

                            <p
                                class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center mb-1 group-hover:text-maroon transition-colors">
                                Klik untuk upload atau drag & drop
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 text-center">
                                Maksimal ukuran file 5MB
                            </p>
                        </div>

                        <input id="file-input" type="file" name="file" accept=".csv" required class="hidden" />
                    </div>

                    <!-- Right Column: Instructions -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <span class="w-1 h-4 bg-blue-600 rounded-full"></span>
                            Petunjuk Import
                        </label>

                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-900/30 rounded-xl p-5 flex-1">
                            <ul
                                class="text-xs text-blue-800 dark:text-blue-300 space-y-2 list-disc list-inside opacity-80 leading-relaxed">
                                <li>File harus berformat <strong>.CSV</strong> (Comma Separated Values)</li>
                                <li>Pastikan kolom wajib terisi: <strong>nidn, name, email</strong></li>
                                <li>Untuk dosen dengan banyak prodi, pisahkan dengan tanda pipa <code>|</code> (contoh:
                                    <code>Hukum Tata Negara|Hukum Bisnis</code>)
                                </li>
                            </ul>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-900/30 border border-gray-200 dark:border-gray-700 rounded-xl p-5 flex flex-col gap-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Template
                                    File</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Gunakan template ini agar data terbaca dengan benar oleh
                                sistem.</p>
                            <a href="{{ route('admin.dosen.import-template') }}" data-no-loader="true" onclick="showDownloadLoader()"
                                class="flex items-center justify-center gap-2 w-full py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-600 hover:text-maroon dark:hover:text-red-400 hover:border-maroon/30 transition-all shadow-sm">
                                <i class="fas fa-download"></i> Download Template
                            </a>
                            <script>
                                function showDownloadLoader() {
                                    const loader = document.getElementById('global-loader');
                                    if (loader) {
                                        loader.style.display = 'flex';
                                        // Force reflow
                                        void loader.offsetWidth;
                                        loader.style.opacity = '1';

                                        // Hide after 3 seconds (estimated time for download to start)
                                        setTimeout(() => {
                                            loader.style.opacity = '0';
                                            setTimeout(() => {
                                                loader.style.display = 'none';
                                            }, 300);
                                        }, 3000);
                                    }
                                }
                            </script>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                    <button type="button"
                        onclick="document.getElementById('modal-import-dosen').classList.add('hidden')"
                        class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 font-semibold text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="btn-upload"
                        class="px-6 py-2.5 bg-maroon text-white rounded-xl font-semibold text-sm shadow-lg shadow-red-900/20 hover:bg-red-900 hover:shadow-red-900/30 transform active:scale-95 transition-all flex items-center gap-2">
                        <i class="fas fa-cloud-upload-alt"></i>
                        Proses Import
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endpush
@endsection