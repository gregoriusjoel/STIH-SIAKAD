@extends('layouts.admin')

@section('title', 'Data Dosen PA')
@section('page-title', 'Data Dosen PA')

@section('content')
    <div x-data="{ 
        showDetail: false,
        selectedDosenId: null,
        selectedDosenName: '',
        mahasiswaList: [],
        loading: false,
        toggleDetail(id, name) {
            if (this.selectedDosenId === id) {
                this.showDetail = !this.showDetail;
            } else {
                this.selectedDosenId = id;
                this.selectedDosenName = name;
                this.showDetail = true;
                this.loadMahasiswa(id);
                this.$nextTick(() => $refs.detailCard.scrollIntoView({ behavior: 'smooth', block: 'start' }));
            }
        },
        loadMahasiswa(dosenId) {
            this.loading = true;
            this.mahasiswaList = [];
            fetch(`/admin/dosen-pa/${dosenId}/mahasiswa`)
                .then(response => response.json())
                .then(data => {
                    this.mahasiswaList = data;
                    this.loading = false;
                })
                .catch(error => {
                    console.error('Error loading mahasiswa:', error);
                    this.loading = false;
                });
        }
    }">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-user-tie mr-3 text-maroon"></i>
                    Manajemen Dosen PA
                </h2>
                <p class="text-gray-600 text-sm mt-1">Kelola data dosen pembimbing akademik</p>
            </div>
            <a href="{{ route('admin.dosen-pa.create') }}"
                class="bg-maroon text-white hover:bg-red-900 px-6 py-3 rounded-lg transition flex items-center shadow-md transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i>
                Tambah Dosen PA
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-maroon text-white">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                <i class="fas fa-id-card mr-2"></i>NIDN
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                <i class="fas fa-user mr-2"></i>Nama Dosen PA
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                <i class="fas fa-envelope mr-2"></i>Email
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                                <i class="fas fa-users mr-2"></i>Jumlah Mahasiswa
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                                <i class="fas fa-cog mr-2"></i>Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($dosens as $dosen)
                            <tr class="hover:bg-blue-50 transition duration-200 cursor-pointer"
                                :class="{'bg-blue-50': selectedDosenId === {{ $dosen->id }} && showDetail}"
                                @click="toggleDetail({{ $dosen->id }}, '{{ addslashes($dosen->user->name) }}')">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    {{ ($dosens->currentPage() - 1) * $dosens->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-mono text-maroon font-bold">{{ $dosen->nidn }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="h-10 w-10 rounded-full bg-maroon flex items-center justify-center text-white font-bold mr-3">
                                            {{ strtoupper(substr($dosen->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $dosen->user->name }}</div>
                                            <div class="text-xs text-gray-500">
                                                <i class="fas fa-phone text-gray-400 mr-1"></i>
                                                {{ $dosen->phone ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                        {{ $dosen->user->email }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $count = $dosen->mahasiswa_pa_count;
                                        $badgeClass = $count >= 6 ? 'bg-red-100 text-red-800' : ($count >= 4 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800');
                                    @endphp
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClass }}">
                                        <i class="fas fa-users mr-1"></i>
                                        {{ $count }}/6
                                        @if($count >= 6)
                                            <span class="ml-1 font-bold">PENUH</span>
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium" @click.stop>
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('admin.dosen-pa.edit', $dosen->id) }}"
                                            class="text-yellow-600 hover:text-yellow-900 transition p-2 hover:bg-yellow-50 rounded"
                                            title="Edit Dosen PA">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.dosen-pa.destroy', $dosen->id) }}" method="POST"
                                            class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 transition p-2 hover:bg-red-50 rounded"
                                                title="Hapus Dosen PA">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                        <p class="text-lg font-medium">Belum ada data Dosen PA</p>
                                        <p class="text-sm text-gray-400 mt-1">Klik "Tambah Dosen PA" untuk menambahkan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($dosens->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $dosens->links() }}
                </div>
            @endif
        </div>

        {{-- Detail Dosen PA Card --}}
        <div x-ref="detailCard" x-show="showDetail" x-transition
            class="mt-8 bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden" style="display: none;">
            <div class="p-6 border-b border-gray-200">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-users text-maroon mr-2"></i>Detail Dosen PA
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Daftar mahasiswa bimbingan: <span
                            class="font-semibold text-maroon" x-text="selectedDosenName"></span></p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                <i class="fas fa-user-graduate mr-1"></i>Nama Mahasiswa
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                <i class="fas fa-id-badge mr-1"></i>NIM
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                <i class="fas fa-graduation-cap mr-1"></i>Program Studi
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                                <i class="fas fa-calendar-alt mr-1"></i>Semester
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-if="loading">
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-spinner fa-spin text-4xl text-maroon mb-3"></i>
                                        <p class="text-gray-500 font-medium">Memuat data mahasiswa...</p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="!loading && mahasiswaList.length === 0">
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-user-slash text-4xl text-gray-300 mb-3"></i>
                                        <p class="text-gray-500 font-medium">Belum ada mahasiswa yang dibimbing.</p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-for="(mhs, index) in mahasiswaList" :key="mhs.id">
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium"
                                    x-text="index + 1"></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold mr-3"
                                            x-text="mhs.name ? mhs.name.charAt(0).toUpperCase() : '-'"></div>
                                        <div class="text-sm font-semibold text-gray-900" x-text="mhs.name"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-mono text-maroon font-bold" x-text="mhs.nim"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900" x-text="mhs.program_studi || 'Ilmu Hukum'"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                        x-text="'Semester ' + (mhs.semester || '-')"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
                <div class="text-xs text-gray-500">Menampilkan <span x-text="mahasiswaList.length"></span> mahasiswa</div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/nim/sweetalert2@11"></script>
        <script>
            // SweetAlert Delete Confirmation
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Semua assignment mahasiswa dari Dosen PA ini akan dihapus permanen!",
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