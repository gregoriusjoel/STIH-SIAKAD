@extends('layouts.mahasiswa')

@section('title', 'KHS')
@section('page-title', 'KHS - Kartu Hasil Studi')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <h3 class="text-lg font-semibold text-gray-800">Kartu Hasil Studi (Semester 1 - 8)</h3>

    @include('page.mahasiswa.khs._content')

</div>
@endsection
