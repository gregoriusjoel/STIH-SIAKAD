@extends('layouts.admin')

@section('title', 'Data Dosen')
@section('page-title', 'Data Dosen')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-chalkboard-teacher mr-3 text-maroon"></i>
                Manajemen Dosen
            </h2>
            <p class="text-gray-600 text-sm mt-1">Kelola data dosen pengajar di sistem</p>
        </div>
        <a href="{{ route('admin.dosen.create') }}"
            class="bg-maroon text-white hover:bg-red-900 px-6 py-3 rounded-lg transition flex items-center shadow-md transform hover:scale-105">
            <i class="fas fa-plus mr-2"></i>
            Tambah Dosen
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
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($dosens as $dosen)
                        <tr class="hover:bg-gray-50 transition">
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-graduation-cap mr-1"></i>
                                    {{ $dosen->pendidikan }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span
                                    class="px-3 py-1 inline-flex items-center justify-center mx-auto text-xs leading-5 font-semibold rounded-full 
                                        {{ $dosen->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas fa-circle text-xs mr-1"></i>
                                    {{ ucfirst($dosen->status) }}
                                </span>
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
                                    <form action="{{ route('admin.dosen.destroy', $dosen) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 transition p-2 hover:bg-red-50 rounded"
                                            onclick="return confirm('Yakin ingin menghapus dosen ini?')" title="Hapus">
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
                                class="bg-white rounded-lg shadow-2xl w-11/12 md:w-3/4 lg:w-2/3 max-h-[90vh] overflow-y-auto overflow-x-hidden">
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
                                <div class="p-6 text-sm text-gray-700">
                                    <div class="grid grid-cols-1 gap-2">
                                        <div><strong>Nama:</strong> {{ $dosen->user->name }}</div>
                                        <div><strong>Email:</strong> {{ $dosen->user->email }}</div>
                                        <div><strong>NIDN:</strong> {{ $dosen->nidn }}</div>
                                        <div><strong>Program Studi:</strong> {{ $dosen->prodi }}</div>
                                        <div><strong>Telepon:</strong> {{ $dosen->phone ?? '-' }}</div>
                                        <div><strong>Status:</strong> {{ ucfirst($dosen->status) }}</div>
                                    </div>
                                </div>
                                <div class="p-6 border-t">
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
                                                <div class="flex items-center justify-between p-2 border rounded">
                                                    <div>
                                                        <div class="text-sm font-semibold">{{ $mk->nama_mk ?? '-' }}</div>
                                                        <div class="text-xs text-gray-500">Kode: {{ $mk->kode_mk ?? '-' }}</div>
                                                    </div>
                                                    <div class="text-xs text-gray-500">SKS: {{ $mk->sks ?? '-' }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-500">Belum ada mata kuliah tercatat.</div>
                                    @endif
                                </div>
                                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                                    <a href="{{ route('admin.dosen.edit', $dosen) }}"
                                        class="bg-maroon text-white px-4 py-2 rounded shadow">Edit</a>
                                    <button
                                        onclick="document.getElementById('modal-dosen-{{ $dosen->id }}').classList.add('hidden')"
                                        class="px-4 py-2 border rounded">Tutup</button>
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
@endsection