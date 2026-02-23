@extends('layouts.admin')

@section('title', 'Data Orang Tua/Wali')
@section('page-title', 'Data Orang Tua/Wali')

@section('content')
    <div class="mb-6 flex flex-col items-start md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-users mr-3 text-maroon"></i>
                Manajemen Orang Tua/Wali
            </h2>
            <p class="text-gray-600 text-sm mt-1">Kelola data orang tua/wali mahasiswa</p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('admin.parents.create') }}"
                class="bg-maroon text-white hover:bg-red-900 px-6 py-3 rounded-lg transition flex items-center shadow-md transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i>
                Tambah Orang Tua
            </a>
        </div>
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
                            <i class="fas fa-user mr-2"></i>Nama Orang Tua
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-user-graduate mr-2"></i>Mahasiswa
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-heart mr-2"></i>Hubungan
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-briefcase mr-2"></i>Pekerjaan
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($parents as $parent)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ ($parents->currentPage() - 1) * $parents->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="h-10 w-10 rounded-full bg-maroon flex items-center justify-center text-white font-bold mr-3">
                                        {{ strtoupper(substr($parent->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $parent->user->name }}</div>
                                        <div class="text-xs text-gray-500">
                                            <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                            {{ $parent->user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($parent->mahasiswa)
                                    <div class="text-sm font-medium text-gray-900">{{ $parent->mahasiswa->user->name }}</div>
                                    <div class="text-xs text-gray-500">NIM: {{ $parent->mahasiswa->nim }}</div>
                                @else
                                    <div class="text-sm font-medium text-gray-400 italic">Belum Tertaut</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full 
                                        {{ $parent->hubungan == 'ayah' ? 'bg-blue-100 text-blue-800' : ($parent->hubungan == 'ibu' ? 'bg-pink-100 text-pink-800' : 'bg-gray-100 text-gray-800') }}">
                                    <i class="fas fa-user mr-2 text-sm"></i>
                                    {{ ucfirst($parent->hubungan) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $parent->pekerjaan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.parents.edit', $parent) }}"
                                        class="text-yellow-600 hover:text-yellow-900 transition p-2 hover:bg-yellow-50 rounded"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.parents.destroy', $parent) }}" method="POST" class="inline delete-form">
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
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium">Belum ada data orang tua/wali</p>
                                    <p class="text-sm text-gray-400 mt-1">Tambahkan data orang tua/wali pertama dengan klik
                                        tombol "Tambah Orang Tua"</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($parents->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $parents->links() }}
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
                        text: "Data orang tua ini akan dihapus permanen!",
                        icon: 'warning',
                        iconColor: '#7a1621',
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
    @endpush
@endsection