@extends('layouts.admin')

@section('title', 'Detail Fakultas')
@section('page-title', 'Detail Fakultas')

@section('content')
    @php $prodi = $fakultas->prodis->first(); @endphp
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-university mr-3 text-maroon"></i>
                Detail Fakultas
            </h2>
            <p class="text-gray-600 text-sm mt-1">Informasi lengkap fakultas {{ $fakultas->nama_fakultas }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.fakultas.edit', ['fakultas' => $fakultas->id]) }}"
                class="bg-yellow-600 text-white hover:bg-yellow-700 px-6 py-3 rounded-lg transition flex items-center shadow-md">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <a href="{{ route('admin.fakultas.index') }}"
                class="bg-gray-600 text-white hover:bg-gray-700 px-6 py-3 rounded-lg transition flex items-center shadow-md">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Fakultas -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Informasi Fakultas</h3>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Kode Fakultas</label>
                            <div class="mt-1 text-lg font-semibold text-gray-900">
                                <i class="fas fa-code mr-2 text-maroon"></i>
                                {{ $fakultas->kode_fakultas }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nama Fakultas</label>
                            <div class="mt-1 text-lg font-semibold text-gray-900">
                                <i class="fas fa-university mr-2 text-maroon"></i>
                                {{ $fakultas->nama_fakultas }}
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500">Program Studi Terkait</label>
                            @if($prodi)
                                <div class="mt-1 flex items-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $prodi->jenjang == 'S1' ? 'bg-blue-100 text-blue-800' : 
                                        ($prodi->jenjang == 'S2' ? 'bg-green-100 text-green-800' : 
                                        ($prodi->jenjang == 'S3' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                        <i class="fas fa-graduation-cap mr-1"></i>
                                        {{ $prodi->jenjang }}
                                    </span>
                                    <div class="ml-3">
                                        <div class="text-lg font-semibold text-gray-900">{{ $prodi->nama_prodi }}</div>
                                        <div class="text-sm text-gray-500">Kode: {{ $prodi->kode_prodi }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="mt-1 text-gray-500 italic">Tidak ada prodi terkait</div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $fakultas->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas {{ $fakultas->status == 'aktif' ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                    {{ ucfirst($fakultas->status) }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status Prodi</label>
                            <div class="mt-1">
                                @if($prodi)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $prodi->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="fas {{ $prodi->status == 'aktif' ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                        {{ ucfirst($prodi->status) }}
                                    </span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Dibuat Pada</label>
                            <div class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $fakultas->created_at->format('d F Y H:i') }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Terakhir Diubah</label>
                            <div class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $fakultas->updated_at->format('d F Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions & Quick Links -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Aksi Cepat</h3>
                </div>

                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.fakultas.edit', $fakultas->id) }}" 
                        class="w-full flex items-center justify-center px-4 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Fakultas
                    </a>
                    
                    @php $prodi = $fakultas->prodis->first(); @endphp
                    @if($prodi)
                        <a href="{{ route('admin.prodi.show', $prodi->id) }}" 
                        class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        Lihat Prodi
                    </a>
                    @else
                        <div class="w-full flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-500 rounded-lg">Tidak ada Prodi</div>
                    @endif

                    <form action="{{ route('admin.fakultas.destroy', ['fakultas' => $fakultas->id]) }}" 
                        method="POST" 
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus fakultas ini?')"
                        class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                            class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus Fakultas
                        </button>
                    </form>
                </div>
            </div>

            <!-- Related Info -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Informasi Terkait</h3>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-graduation-cap text-maroon mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Prodi</div>
                                    <div class="text-xs text-gray-500">{{ $prodi ? $prodi->nama_prodi : '-' }}</div>
                                </div>
                            </div>
                            @if($prodi)
                                <a href="{{ route('admin.prodi.show', $prodi->id) }}" 
                                    class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Relations Warning -->
    @if(isset($prodi) && $prodi->status == 'nonaktif')
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                <div>
                    <h3 class="text-yellow-800 font-semibold">Perhatian</h3>
                    <p class="text-yellow-700 text-sm mt-1">
                        Program Studi yang terkait dengan fakultas ini berstatus nonaktif. 
                        Hal ini mungkin mempengaruhi operasional fakultas.
                    </p>
                </div>
            </div>
        </div>
    @endif
@endsection