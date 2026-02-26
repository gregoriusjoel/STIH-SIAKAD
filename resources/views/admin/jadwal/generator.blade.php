@extends('layouts.app')

@section('title', 'Auto Generate Jadwal')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Auto Generate Jadwal</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Auto Generate Jadwal</li>
    </ol>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            @if(session('stats'))
                <div class="mt-2 pt-2 border-top">
                    <strong>Detail Ketersediaan Dosen:</strong>
                    <ul class="mb-0 mt-1">
                        <li><i class="fas fa-check text-success"></i> <strong>{{ session('stats.within_availability') }}</strong> jadwal sesuai ketersediaan waktu dosen</li>
                        <li><i class="fas fa-exclamation-triangle text-warning"></i> <strong>{{ session('stats.outside_availability') }}</strong> jadwal menggunakan fallback slot</li>
                    </ul>
                    @if(session('stats.reasons'))
                        <div class="mt-2 small">
                            <strong>Alasan fallback:</strong>
                            <ul class="mb-0">
                                @foreach(session('stats.reasons') as $reason => $count)
                                    <li>{{ $reason }}: {{ $count }} kelas</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(session('stats.dosen_fallback_list'))
                        <div class="mt-2 small">
                            <strong>Dosen dengan jadwal fallback:</strong>
                            <ul class="mb-0">
                                @foreach(session('stats.dosen_fallback_list') as $dosen)
                                    <li>{{ $dosen['name'] }}: {{ $dosen['count'] }} kelas</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('failed_items'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-triangle me-2"></i>Gagal Generate untuk Mata Kuliah Berikut:</strong>
            <ul class="mb-0 mt-2">
                @foreach(session('failed_items') as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @include('admin.jadwal._generator_partial')

</div>

@endsection

@push('scripts')
{{-- If the generator page needs extra scripts, include them here. The partial pushes minimal script already. --}}
@endpush