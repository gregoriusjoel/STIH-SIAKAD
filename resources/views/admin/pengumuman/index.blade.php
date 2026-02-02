@extends('layouts.admin')

@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold">Pengumuman</h2>
        <a href="{{ route('admin.pengumuman.create') }}" class="bg-maroon text-white px-4 py-2 rounded-lg">Buat Pengumuman</a>
    </div>

    <div class="bg-white rounded-xl shadow p-4">
        @if(session('success'))
            <div class="p-3 bg-green-50 border border-green-100 text-green-800 mb-4">{{ session('success') }}</div>
        @endif

        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="text-left text-sm font-semibold text-gray-700">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Judul</th>
                    <th class="px-4 py-2">Tanggal Publikasi</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengumumans as $p)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">{{ $p->judul }}</td>
                        <td class="px-4 py-3">{{ $p->published_at ? \Carbon\Carbon::parse($p->published_at)->format('d M Y H:i') : '-' }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.pengumuman.edit', $p) }}" class="text-yellow-600 mr-2">Edit</a>
                            <form action="{{ route('admin.pengumuman.destroy', $p) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">{{ $pengumumans->links() }}</div>
    </div>
@endsection
