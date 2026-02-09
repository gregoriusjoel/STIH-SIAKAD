@extends('layouts.admin')

@section('title', 'Detail User')
@section('page-title', 'Detail User')

@section('content')
    <div class="w-full px-4">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <!-- Header Section -->
            <div class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 p-6">
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0">
                        <div
                            class="w-16 h-16 rounded-lg bg-maroon text-white flex items-center justify-center text-xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    </div>

                    <div class="flex-1">
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                            {{ $user->email }}
                        </p>
                    </div>

                    <div class="flex-shrink-0">
                        <a href="{{ route('admin.users.edit', $user) }}"
                            class="inline-flex items-center gap-2 bg-maroon hover:bg-maroon/90 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                <path fill-rule="evenodd"
                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                    clip-rule="evenodd" />
                            </svg>
                            Edit User
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-6">
                <div class="mb-5">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Informasi Akun</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Rincian dan metadata akun pengguna untuk keperluan administrasi.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <!-- Role -->
                    <div class="border dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <div class="flex items-center gap-3">
                            <div class="text-maroon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium mb-0.5">Role</div>
                                <div class="text-gray-900 dark:text-gray-100 font-medium">{{ ucfirst($user->role) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="border dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <div class="flex items-center gap-3">
                            <div class="text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium mb-0.5">Email Utama</div>
                                <div class="text-gray-900 dark:text-gray-100 font-medium">{{ $user->email }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Created At -->
                    <div class="border dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <div class="flex items-center gap-3">
                            <div class="text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium mb-0.5">Terdaftar Pada</div>
                                <div class="text-gray-900 dark:text-gray-100 font-medium">{{ $user->created_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Updated At -->
                    <div class="border dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <div class="flex items-center gap-3">
                            <div class="text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium mb-0.5">Terakhir Diperbarui</div>
                                <div class="text-gray-900 dark:text-gray-100 font-medium">{{ $user->updated_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="border-t dark:border-gray-700 pt-6">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Aksi Cepat</h3>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}"
                            class="inline-flex items-center gap-2 bg-maroon hover:bg-maroon/90 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                <path fill-rule="evenodd"
                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                    clip-rule="evenodd" />
                            </svg>
                            Edit
                        </a>

                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block"
                            onsubmit="event.preventDefault(); showDeleteConfirm('user', () => this.submit());">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Hapus
                            </button>
                        </form>

                        <a href="{{ route('admin.users.index') }}"
                            class="inline-flex items-center gap-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg text-sm font-medium transition">
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="mt-6 pt-4 border-t dark:border-gray-700 flex items-center justify-between text-xs text-gray-400">
                    <div>System User ID: <span class="font-mono">{{ $user->id }}</span></div>
                    <div>Keamanan: <span class="text-green-600 dark:text-green-400 font-semibold">Terverifikasi</span></div>
                </div>
            </div>
        </div>
    </div>
@endsection