@extends('layouts.admin')
@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')
@section('content')
    <div class="mb-6 flex flex-col items-start md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center"><i
                    class="fas fa-users-cog text-maroon mr-2"></i>Manajemen User</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola akses dan peran pengguna sistem</p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('admin.users.create') }}"
                class="bg-maroon text-white hover:bg-maroon-700 px-6 py-3 rounded-lg transition shadow-md transform hover:scale-105 flex items-center text-sm font-medium">
                <i class="fas fa-user-plus mr-2"></i>Tambah User
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg  overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-maroon text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">User</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Email</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Role</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $u)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $u->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $u->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 dark:text-gray-300"><i
                                    class="fas fa-envelope text-maroon mr-2"></i>{{ $u->email }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($u->role == 'admin')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300"><i
                                            class="fas fa-user-shield mr-1"></i>Admin</span>
                                @elseif($u->role == 'dosen')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300"><i
                                            class="fas fa-chalkboard-teacher mr-1"></i>Dosen</span>
                                @elseif($u->role == 'mahasiswa')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300"><i
                                            class="fas fa-user-graduate mr-1"></i>Mahasiswa</span>
                                @elseif($u->role == 'keuangan')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300"><i
                                            class="fas fa-money-bill-wave mr-1"></i>Keuangan</span>
                                @elseif($u->role == 'perpustakaan')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-teal-100 dark:bg-teal-900/30 text-teal-800 dark:text-teal-300"><i
                                            class="fas fa-book mr-1"></i>Perpustakaan</span>
                                @elseif($u->role == 'parent')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300"><i
                                            class="fas fa-user mr-1"></i>Parent</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300"><i
                                            class="fas fa-user mr-1"></i>{{ ucfirst($u->role) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.users.edit', $u) }}"
                                        class="bg-yellow-500 text-white p-2 rounded-lg hover:bg-yellow-600 transition"
                                        title="Edit"><i class="fas fa-edit"></i></a>
                                    @if($u->id != auth()->id())
                                        <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="delete-form">
                                            @csrf @method('DELETE')<button type="submit"
                                                class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600 transition"
                                                title="Hapus"><i class="fas fa-trash"></i></button></form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400"><i
                                    class="fas fa-inbox text-4xl mb-3"></i>
                                <p class="text-lg font-semibold">Belum ada user</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                {{ $users->links() }}</div>
        @endif
    </div>

    @push('scripts')
        <script>
            // SweetAlert Delete Confirmation
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data user ini akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7a1621',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#f3f4f6' : '#1f293b',
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