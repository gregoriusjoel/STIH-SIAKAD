@extends('layouts.app')

@section('title', 'Auto Generate Jadwal')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Auto Generate Jadwal</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Auto Generate Jadwal</li>
    </ol>

    @include('admin.jadwal._generator_partial')

</div>

@endsection

@push('scripts')
{{-- If the generator page needs extra scripts, include them here. The partial pushes minimal script already. --}}
@endpush