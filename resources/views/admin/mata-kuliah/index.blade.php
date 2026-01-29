@extends('layouts.admin')

@section('title', 'Data Mata Kuliah')
@section('page-title', 'Data Mata Kuliah')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-book mr-3 text-maroon"></i>
                Manajemen Mata Kuliah
            </h2>
            <p class="text-gray-600 text-sm mt-1">Kelola mata kuliah yang tersedia di sistem</p>
        </div>
        <a href="{{ route('admin.mata-kuliah.create') }}"
            class="bg-maroon text-white hover:bg-red-900 px-6 py-3 rounded-lg transition flex items-center shadow-md transform hover:scale-105">
            <i class="fas fa-plus mr-2"></i>
            Tambah Mata Kuliah
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
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
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($mataKuliahs as $mk)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ ($mataKuliahs->currentPage() - 1) * $mataKuliahs->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-mono text-maroon font-bold bg-opacity-10 px-2 py-1 rounded">
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
                                        <div class="text-sm font-semibold text-gray-900">{{ $mk->nama_mk }}</div>
                                        <div class="text-xs text-gray-500">
                                            <i class="fas fa-layer-group text-gray-400 mr-1"></i>
                                            Semester {{ $mk->semester }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span
                                    class="px-3 py-1 inline-flex items-center justify-center mx-auto text-sm font-bold rounded-full bg-purple-100 text-purple-800">
                                    <i class="fas fa-calculator mr-1"></i>
                                    {{ $mk->sks }} SKS
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $jenisColors = [
                                        'wajib_nasional' => 'bg-blue-100 text-blue-800',
                                        'wajib_prodi' => 'bg-red-100 text-red-800',
                                        'pilihan' => 'bg-purple-100 text-purple-800',
                                        'peminatan' => 'bg-yellow-100 text-yellow-800',
                                    ];
                                    $jenisKey = $mk->jenis ?? null;
                                    $jenisLabel = ucwords(str_replace('_', ' ', $jenisKey));
                                    $colorClass = $jenisColors[$jenisKey] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span
                                    class="px-3 py-1 inline-flex items-center justify-center mx-auto text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                    <i class="fas fa-star mr-1"></i>
                                    {{ $jenisLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-700">
                                    <i class="fas fa-university text-gray-400 mr-1"></i>
                                    {{ $mk->prodi }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    <button type="button"
                                        onclick="document.getElementById('modal-mk-{{ $mk->id }}').classList.remove('hidden')"
                                        class="text-blue-600 hover:text-blue-900 transition p-2 hover:bg-blue-50 rounded"
                                        title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.mata-kuliah.edit', $mk) }}"
                                        class="text-yellow-600 hover:text-yellow-900 transition p-2 hover:bg-yellow-50 rounded"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.mata-kuliah.destroy', $mk) }}" method="POST" class="inline delete-form">
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

                        <!-- Modal Mata Kuliah -->
                        <div id="modal-mk-{{ $mk->id }}"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden">
                            <div
                                class="bg-white rounded-lg shadow-2xl w-11/12 md:w-3/4 lg:w-2/3 max-h-[90vh] overflow-y-auto overflow-x-hidden">
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
                                <div class="p-6 text-sm text-gray-700">
                                    <div class="grid grid-cols-1 gap-2">
                                        <div><strong>Nama MK:</strong> {{ $mk->nama_mk }}</div>
                                        <div><strong>Kode MK:</strong> {{ $mk->kode_mk }}</div>
                                        <div><strong>SKS:</strong> {{ $mk->sks }}</div>
                                        <div><strong>Semester:</strong> {{ $mk->semester }}</div>
                                        <div><strong>Jenis:</strong> {{ ucwords(str_replace('_', ' ', $mk->jenis)) }}</div>
                                        <div><strong>Prodi:</strong> {{ $mk->prodi }}</div>
                                    </div>
                                </div>
                                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                                    <a href="{{ route('admin.mata-kuliah.edit', $mk) }}"
                                        class="bg-maroon text-white px-4 py-2 rounded shadow">Edit</a>
                                    <button onclick="document.getElementById('modal-mk-{{ $mk->id }}').classList.add('hidden')"
                                        class="px-4 py-2 border rounded">Tutup</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium">Belum ada data mata kuliah</p>
                                    <p class="text-sm text-gray-400 mt-1">Tambahkan mata kuliah pertama dengan klik tombol
                                        "Tambah Mata Kuliah"</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mataKuliahs->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
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
@endsection