@extends('layouts.admin')

@section('title', 'Data Mata Kuliah')
@section('page-title', 'Data Mata Kuliah')

@section('content')
    <div class="mb-6 flex flex-col items-start md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                <i class="fas fa-book mr-3 text-maroon dark:text-red-500"></i>
                Manajemen Mata Kuliah
            </h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Kelola mata kuliah yang tersedia di sistem</p>
        </div>
        <div class="flex-shrink-0 flex gap-2">
            <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                class="group bg-white dark:bg-gray-800 text-maroon dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10 border-2 border-maroon/10 hover:border-maroon px-4 py-2 rounded-xl transition-all duration-300 flex items-center justify-center gap-3 shadow-sm hover:shadow-lg hover:shadow-maroon/10 whitespace-nowrap">
                <div class="w-6 h-6 rounded-lg bg-maroon/5 dark:bg-maroon/10 group-hover:bg-maroon/10 flex items-center justify-center transition-colors">
                    <i class="fas fa-file-import text-xs"></i>
                </div>
                <span class="font-bold text-sm tracking-wide">Import Mata Kuliah</span>
            </button>
            <a href="{{ route('admin.mata-kuliah.create') }}"
                class="bg-maroon text-white hover:bg-red-900 px-4 py-2 rounded-lg transition flex items-center shadow-md transform hover:scale-105 text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>
                Tambah Mata Kuliah
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
                            <i class=""></i>Kode MK
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-book-open mr-2"></i>Nama Mata Kuliah
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-calculator mr-2"></i>SKS
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-tags mr-2"></i>Jenis
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-graduation-cap mr-2"></i>Prodi
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($mataKuliahs as $mk)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                                {{ ($mataKuliahs->currentPage() - 1) * $mataKuliahs->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-mono text-maroon dark:text-red-400 font-bold bg-opacity-10 dark:bg-opacity-20 px-2 py-1 rounded">
                                    {{ $mk->kode_mk }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="h-10 w-10 rounded-lg bg-maroon flex items-center justify-center text-white font-bold mr-3">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $mk->nama_mk }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-layer-group text-gray-400 dark:text-gray-500 mr-1"></i>
                                            Semester {{ $mk->semester }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span
                                    class="px-3 py-1 inline-flex items-center justify-center mx-auto text-sm font-bold rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300">
                                    <i class="fas fa-calculator mr-1"></i>
                                    {{ $mk->sks }} SKS
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $jenisColors = [
                                        'wajib_nasional' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300',
                                        'wajib_prodi' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
                                        'pilihan' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300',
                                        'peminatan' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
                                    ];
                                    $jenisKey = $mk->jenis ?? null;
                                    $jenisLabel = ucwords(str_replace('_', ' ', $jenisKey));
                                    $colorClass = $jenisColors[$jenisKey] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300';
                                @endphp
                                <span
                                    class="px-3 py-1 inline-flex items-center justify-center mx-auto text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                    <i class="fas fa-star mr-1"></i>
                                    {{ $jenisLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        {{ $mk->prodi->jenjang == 'S1' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' : 
                                           ($mk->prodi->jenjang == 'S2' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 
                                           ($mk->prodi->jenjang == 'S3' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300' : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300')) }}">
                                        {{ $mk->prodi->jenjang }}
                                    </span>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $mk->prodi->nama_prodi }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-university mr-1"></i>
                                            {{ $mk->fakultas->nama_fakultas }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    <button type="button"
                                        onclick="document.getElementById('modal-mk-{{ $mk->id }}').classList.remove('hidden')"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition p-2 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded"
                                        title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.mata-kuliah.edit', $mk) }}"
                                        class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300 transition p-2 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.mata-kuliah.destroy', $mk) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition p-2 hover:bg-red-50 dark:hover:bg-red-900/20 rounded"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Mata Kuliah -->
                        <div id="modal-mk-{{ $mk->id }}"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden">
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl w-11/12 md:w-3/4 lg:w-2/3 max-h-[90vh] overflow-y-auto overflow-x-hidden">
                                <div class="flex items-center justify-between px-6 py-4 bg-maroon text-white">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="h-10 w-10 rounded-full bg-white bg-opacity-10 flex items-center justify-center text-white font-bold">
                                            <i class="fas fa-book-open"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold">Detail Mata Kuliah</h3>
                                            <p class="text-sm text-white text-opacity-90">Informasi mata kuliah</p>
                                        </div>
                                    </div>
                                    <button onclick="document.getElementById('modal-mk-{{ $mk->id }}').classList.add('hidden')"
                                        class="text-white text-xl leading-none">&times;</button>
                                </div>
                                <div class="p-6 text-sm text-gray-700 dark:text-gray-300">
                                    <div class="grid grid-cols-1 gap-2">
                                        <div><strong>Nama MK:</strong> {{ $mk->nama_mk }}</div>
                                        <div><strong>Kode MK:</strong> {{ $mk->kode_mk }}</div>
                                        <div><strong>SKS:</strong> {{ $mk->sks }}</div>
                                        <div><strong>Semester:</strong> {{ $mk->semester }}</div>
                                        <div><strong>Jenis:</strong> {{ ucwords(str_replace('_', ' ', $mk->jenis)) }}</div>
                                        <div><strong>Prodi:</strong> {{ $mk->prodi }}</div>
                                    </div>
                                </div>
                                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 flex justify-end space-x-3">
                                    <a href="{{ route('admin.mata-kuliah.edit', $mk) }}"
                                        class="bg-maroon text-white px-4 py-2 rounded shadow">Edit</a>
                                    <button onclick="document.getElementById('modal-mk-{{ $mk->id }}').classList.add('hidden')"
                                        class="px-4 py-2 border dark:border-gray-600 rounded text-gray-700 dark:text-gray-300">Tutup</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                    <p class="text-lg font-medium">Belum ada data mata kuliah</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Tambahkan mata kuliah pertama dengan klik tombol
                                        "Tambah Mata Kuliah"</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mataKuliahs->hasPages())
            <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $mataKuliahs->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            // SweetAlert Delete Confirmation
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data mata kuliah ini akan dihapus permanen!",
                        icon: 'warning',
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
    @endpush

    <!-- Import Modal -->
    <div id="importModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); background-color: rgba(0, 0, 0, 0.4);">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="bg-maroon text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-file-upload mr-2"></i>
                    Import Mata Kuliah dari CSV
                </h3>
                <button onclick="document.getElementById('importModal').classList.add('hidden')" 
                    class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.mata-kuliah.import') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900/30 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-900 dark:text-blue-300 mb-2 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Format CSV
                        </h4>
                        <p class="text-sm text-blue-800 dark:text-blue-400 mb-2">File CSV harus menggunakan delimiter <strong>koma (,)</strong> dengan kolom:</p>
                        <ul class="text-sm text-blue-800 dark:text-blue-400 list-disc list-inside space-y-1">
                            <li><strong>kode_id</strong>: ID semester (contoh: sms1)</li>
                            <li><strong>kode_mk</strong>: Kode mata kuliah unik (contoh: ADH10010)</li>
                            <li><strong>nama_matkul</strong>: Nama mata kuliah</li>
                            <li><strong>praktikum</strong>: Jam praktikum (kosongkan jika tidak ada)</li>
                            <li><strong>sks</strong>: Jumlah SKS (1-6)</li>
                            <li><strong>semester</strong>: Semester (1-8)</li>
                        </ul>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-file-csv mr-1"></i>
                            Pilih File CSV *
                        </label>
                        <input type="file" name="file" accept=".csv" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maksimal 5MB, format .csv dengan delimiter koma (,)</p>
                    </div>

                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-900/30 rounded-lg p-4">
                        <h4 class="font-semibold text-yellow-900 dark:text-yellow-300 mb-2 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Catatan Penting
                        </h4>
                        <ul class="text-sm text-yellow-800 dark:text-yellow-400 list-disc list-inside space-y-1">
                            <li>Mata kuliah dengan <strong>kode_mk</strong> yang sudah ada akan <strong>diperbarui</strong></li>
                            <li>Mata kuliah baru akan ditambahkan dengan prodi dan fakultas default</li>
                            <li>Pastikan format file sesuai template</li>
                        </ul>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t dark:border-gray-700">
                        <a href="{{ route('admin.mata-kuliah.download-template') }}" 
                            class="text-maroon dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 font-medium flex items-center text-sm">
                            <i class="fas fa-download mr-2"></i>
                            Download Template CSV
                        </a>
                        <div class="flex gap-3">
                            <button type="button" 
                                onclick="document.getElementById('importModal').classList.add('hidden')"
                                class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-maroon text-white rounded-lg hover:bg-red-900">
                                <i class="fas fa-upload mr-2"></i>
                                Import
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(session('import_errors') && count(session('import_errors')) > 0)
        <div class="fixed bottom-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg max-w-md z-50">
            <div class="flex justify-between items-start">
                <div>
                    <strong class="font-bold">Import Errors:</strong>
                    <ul class="mt-2 text-sm list-disc list-inside max-h-40 overflow-y-auto">
                        @foreach(session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif
@endsection