@extends('layouts.app')

@section('title', 'Pengumuman')

@section('content')
    <div class="max-w-4xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Pengumuman</h1>
        @foreach($pengumumans as $p)
            <div class="p-4 mb-3 border rounded">
                <a href="{{ route('mahasiswa.pengumuman.show', $p) }}" class="text-lg font-semibold text-maroon">{{ $p->judul }}</a>
                <div class="text-xs text-gray-500">{{ $p->published_at ? \Carbon\Carbon::parse($p->published_at)->format('d M Y H:i') : $p->created_at->format('d M Y') }}</div>
                <p class="mt-2 text-sm text-gray-700">{{ \\Illuminate\\Support\\Str::limit(strip_tags($p->isi), 300) }}</p>
            </div>
        @endforeach

        <div class="mt-4">{{ $pengumumans->links() }}</div>
    </div>
@endsection
