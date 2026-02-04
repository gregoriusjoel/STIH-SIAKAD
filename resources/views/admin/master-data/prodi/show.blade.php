@extends('layouts.admin')

@section('title', 'Detail Prodi')
@section('page-title', 'Detail Prodi')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-graduation-cap mr-3 text-maroon"></i>
                Detail Prodi
            </h2>
            <p class="text-gray-600 text-sm mt-1">Informasi lengkap program studi {{ $prodi->nama_prodi }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.prodi.edit', $prodi->id) }}"
                class="bg-yellow-600 text-white hover:bg-yellow-700 px-6 py-3 rounded-lg transition flex items-center shadow-md">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <a href="{{ route('admin.prodi.index') }}"
                class="bg-gray-600 text-white hover:bg-gray-700 px-6 py-3 rounded-lg transition flex items-center shadow-md">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Prodi -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Informasi Prodi</h3>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Kode Prodi</label>
                            <div class="mt-1 text-lg font-semibold text-gray-900">
                                <i class="fas fa-code mr-2 text-maroon"></i>
                                {{ $prodi->kode_prodi }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nama Prodi</label>
                            <div class="mt-1 text-lg font-semibold text-gray-900">
                                <i class="fas fa-graduation-cap mr-2 text-maroon"></i>
                                {{ $prodi->nama_prodi }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Jenjang</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $prodi->jenjang == 'S1' ? 'bg-blue-100 text-blue-800' : 
                                       ($prodi->jenjang == 'S2' ? 'bg-green-100 text-green-800' : 
                                       ($prodi->jenjang == 'S3' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                    <i class="fas fa-layer-group mr-1"></i>
                                    {{ $prodi->jenjang }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $prodi->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas {{ $prodi->status == 'aktif' ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                    {{ ucfirst($prodi->status) }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Dibuat Pada</label>
                            <div class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $prodi->created_at->format('d F Y H:i') }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Terakhir Diubah</label>
                            <div class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $prodi->updated_at->format('d F Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div>
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Statistik</h3>
                </div>

                <div class="p-6 space-y-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-maroon">{{ $prodi->fakultas ? 1 : 0 }}</div>
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-university mr-1"></i>
                            Fakultas Terkait
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Fakultas -->
    @if($prodi->fakultas)
        <div class="mt-6 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-university mr-2"></i>
                    Fakultas Terkait
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kode Fakultas
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Fakultas
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $prodi->fakultas->kode_fakultas }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $prodi->fakultas->nama_fakultas }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $prodi->fakultas->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($prodi->fakultas->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('admin.fakultas.show', $prodi->fakultas->id) }}" 
                                    class="text-blue-600 hover:text-blue-900 transition-colors mr-2">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.fakultas.edit', $prodi->fakultas->id) }}" 
                                    class="text-yellow-600 hover:text-yellow-900 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="mt-6 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-12 text-center text-gray-500">
                <i class="fas fa-university text-4xl text-gray-300 mb-4"></i>
                <p class="text-lg font-semibold">Belum Ada Fakultas</p>
                <p class="text-sm mb-4">Belum ada fakultas yang terkait dengan prodi ini</p>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.fakultas.create') }}" 
                        class="bg-maroon text-white px-6 py-2 rounded-lg hover:bg-red-900 transition">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Fakultas
                    </a>
                @endif
            </div>
        </div>
    @endif
@endsection