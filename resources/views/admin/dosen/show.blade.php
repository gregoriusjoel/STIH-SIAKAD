@extends('layouts.admin')

@section('title', 'Detail Dosen')
@section('page-title', 'Detail Dosen')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon">
        <div class="p-6 border-b border-gray-200 bg-maroon text-white rounded-t-xl">
            <h3 class="text-xl font-bold flex items-center">
                <i class="fas fa-user-tie mr-3 text-2xl"></i>
                Profil Dosen
            </h3>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <h4 class="text-lg font-semibold text-gray-700">{{ $dosen->user->name }}</h4>
                    <p class="text-sm text-gray-500">{{ $dosen->user->email }}</p>
                    <div class="mt-4 text-sm text-gray-700 space-y-2">
                        <div><strong>NIDN:</strong> {{ $dosen->nidn }}</div>
                        <div><strong>Program Studi:</strong> {{ $dosen->prodi }}</div>
                        <div><strong>Pendidikan:</strong> {{ $dosen->pendidikan ?? '-' }}</div>
                        <div><strong>Telepon:</strong> {{ $dosen->phone ?? '-' }}</div>
                        <div><strong>Alamat:</strong> {{ $dosen->address ?? '-' }}</div>
                        <div><strong>Status:</strong> <span class="px-2 py-1 rounded {{ $dosen->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($dosen->status) }}</span></div>
                    </div>
                </div>
                <div class="md:col-span-1 flex items-center justify-center">
                    <div class="h-28 w-28 rounded-full bg-maroon flex items-center justify-center text-white font-bold text-2xl">
                        {{ strtoupper(substr($dosen->user->name, 0, 1)) }}
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h5 class="text-md font-semibold text-gray-700 mb-3">Kelas & Mata Kuliah</h5>
                @if($dosen->kelasMataKuliahs && $dosen->kelasMataKuliahs->count())
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($dosen->kelasMataKuliahs as $km)
                            <div class="p-4 border rounded-lg flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-semibold">{{ $km->mataKuliah->nama_mk ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">Kelas: {{ $km->nama_kelas ?? '-' }}</div>
                                </div>
                                <div class="text-xs text-gray-500">SKS: {{ $km->sks ?? '-' }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-sm text-gray-500">Belum mengajar kelas apapun.</div>
                @endif
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('admin.dosen.index') }}" class="px-5 py-2 border rounded text-gray-700 hover:bg-gray-50">Kembali</a>
                <a href="{{ route('admin.dosen.edit', $dosen) }}" class="ml-3 btn-maroon px-5 py-2 rounded">Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection
