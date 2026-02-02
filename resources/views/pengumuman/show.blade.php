@extends('layouts.app')

@section('title', $pengumuman->judul)

@section('content')
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-2">{{ $pengumuman->judul }}</h1>
        <div class="text-xs text-gray-500 mb-4">{{ $pengumuman->published_at ? \Carbon\Carbon::parse($pengumuman->published_at)->format('d M Y H:i') : $pengumuman->created_at->format('d M Y') }}</div>
        <div class="prose max-w-none">{!! $pengumuman->isi !!}</div>
    </div>
@endsection
