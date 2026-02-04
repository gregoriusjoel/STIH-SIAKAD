@extends('layouts.admin')

@section('title', 'Tambah Jam Perkuliahan')
@section('page-title', 'Tambah Jam Perkuliahan')

@section('content')
<div class="px-4 py-6 md:px-8 max-w-3xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Jam Perkuliahan</h1>
        <p class="text-gray-500 mt-1">Masukkan data jam perkuliahan baru</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.jam-perkuliahan.store') }}" method="POST">
            @csrf

            <!-- Jam Ke -->
            <div class="mb-6">
                <label for="jam_ke" class="block text-sm font-semibold text-gray-700 mb-2">
                    Jam ke - <span class="text-red-500">*</span>
                </label>
                <input type="number" name="jam_ke" id="jam_ke" min="1" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon/50 focus:border-maroon @error('jam_ke') border-red-500 @enderror"
                    value="{{ old('jam_ke', $suggestedJamKe ?? '') }}" required>
                @error('jam_ke')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Contoh: 1, 2, 3, dst.</p>
            </div>

            <!-- Jam Mulai -->
            <div class="mb-6">
                <label for="jam_mulai" class="block text-sm font-semibold text-gray-700 mb-2">
                    Jam Mulai <span class="text-red-500">*</span>
                </label>
                <input type="time" name="jam_mulai" id="jam_mulai" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon/50 focus:border-maroon @error('jam_mulai') border-red-500 @enderror"
                    value="{{ old('jam_mulai', $suggestedJamMulai ?? '') }}" required>
                @error('jam_mulai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @if(isset($lastJam))
                    <p class="mt-1 text-xs text-gray-500">Jam sebelumnya berakhir pada {{ date('H.i', strtotime($lastJam->jam_selesai)) }}</p>
                @endif
            </div>

            <!-- Jam Selesai -->
            <div class="mb-6">
                <label for="jam_selesai" class="block text-sm font-semibold text-gray-700 mb-2">
                    Jam Selesai <span class="text-red-500">*</span>
                </label>
                <input type="time" name="jam_selesai" id="jam_selesai" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon/50 focus:border-maroon @error('jam_selesai') border-red-500 @enderror"
                    value="{{ old('jam_selesai') }}" required>
                @error('jam_selesai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Aktif -->
            <div class="mb-8">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked
                        class="w-4 h-4 text-maroon border-gray-300 rounded focus:ring-maroon">
                    <span class="ml-2 text-sm font-medium text-gray-700">Status Aktif</span>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.jam-perkuliahan.index') }}" 
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-2 bg-maroon text-white rounded-lg hover:bg-maroon/90 transition">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Auto calculate jam_selesai (45 minutes after jam_mulai)
    function calculateEndTime() {
        const jamMulai = document.getElementById('jam_mulai');
        if (jamMulai.value) {
            const [hours, minutes] = jamMulai.value.split(':').map(Number);
            const startTime = new Date(2000, 0, 1, hours, minutes);
            const endTime = new Date(startTime.getTime() + 45 * 60000); // Add 45 minutes
            
            const endHours = String(endTime.getHours()).padStart(2, '0');
            const endMinutes = String(endTime.getMinutes()).padStart(2, '0');
            
            document.getElementById('jam_selesai').value = `${endHours}:${endMinutes}`;
        }
    }
    
    document.getElementById('jam_mulai').addEventListener('change', calculateEndTime);
    
    // Auto-calculate on page load if there's a suggested time
    @if(isset($suggestedJamMulai) && $suggestedJamMulai)
        window.addEventListener('DOMContentLoaded', function() {
            calculateEndTime();
        });
    @endif
</script>
@endpush
@endsection
