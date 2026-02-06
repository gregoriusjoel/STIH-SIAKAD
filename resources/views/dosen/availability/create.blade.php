@extends('layouts.app')
@section('title', 'Atur Ketersediaan Waktu')
@section('page-title', 'Atur Ketersediaan Waktu')

@push('styles')
<style>
    .slot-checkbox {
        appearance: none;
        width: 100%;
        height: 100%;
        cursor: pointer;
        border-radius: 0.375rem;
        transition: all 0.2s;
    }
    
    .slot-checkbox:not(:checked) {
        background-color: #f3f4f6;
        border: 2px solid #e5e7eb;
    }
    
    .slot-checkbox:not(:checked):hover {
        background-color: #e5e7eb;
        border-color: #d1d5db;
    }
    
    .slot-checkbox:checked {
        background-color: #10b981;
        border: 2px solid #059669;
    }
    
    .slot-checkbox.booked {
        background-color: #3b82f6;
        border: 2px solid #2563eb;
        cursor: not-allowed;
    }
    
    .dark .slot-checkbox:not(:checked) {
        background-color: #374151;
        border-color: #4b5563;
    }
    
    .dark .slot-checkbox:checked {
        background-color: #059669;
        border-color: #047857;
    }
</style>
@endpush

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-maroon text-white flex items-center justify-between">
                <div class="font-bold text-white text-xl flex items-center gap-3">
                    <div class="p-2 bg-white/10 rounded-lg backdrop-blur-sm">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    Atur Ketersediaan Waktu
                </div>
                <a href="{{ route('dosen.availability.index') }}" 
                   class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg font-semibold transition-all duration-200 inline-flex items-center border border-white/20">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <div class="p-6 bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-700/50 dark:to-blue-900/20">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900/30 text-blue-700 dark:text-blue-300 px-4 py-3 rounded-lg text-sm flex gap-3 items-start">
                    <i class="fas fa-info-circle mt-0.5 text-blue-500 dark:text-blue-400"></i>
                    <div>
                        <span class="font-semibold block mb-0.5">Cara Penggunaan</span>
                        Klik pada kotak waktu untuk menandai ketersediaan Anda. Kotak <span class="inline-block w-4 h-4 bg-green-500 rounded"></span> hijau = tersedia, 
                        <span class="inline-block w-4 h-4 bg-blue-500 rounded"></span> biru = sudah terjadwal (tidak bisa diubah).
                    </div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('dosen.availability.store') }}" method="POST" id="availabilityForm">
            @csrf
            <input type="hidden" name="semester_id" value="{{ $activeSemester->id }}">
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-maroon"></i>
                        Pilih Waktu Ketersediaan - {{ $activeSemester->nama_semester }} {{ $activeSemester->tahun_ajaran }}
                    </h3>
                </div>

                <div class="p-6 overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="bg-maroon text-white">
                                <th class="px-3 py-3 text-left font-semibold border border-gray-300 dark:border-gray-600 sticky left-0 bg-maroon z-10">Jam</th>
                                @foreach($days as $day)
                                    <th class="px-3 py-3 text-center font-semibold border border-gray-300 dark:border-gray-600 min-w-[100px]">{{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jamPerkuliahan as $jam)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-3 py-2 border border-gray-300 dark:border-gray-600 font-medium text-gray-700 dark:text-gray-300 sticky left-0 bg-white dark:bg-gray-800 z-10">
                                        <div class="text-xs">Jam {{ $jam->jam_ke }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ substr($jam->jam_mulai, 0, 5) }} - {{ substr($jam->jam_selesai, 0, 5) }}</div>
                                    </td>
                                    @foreach($days as $day)
                                        @php
                                            $key = "{$day}_{$jam->id}";
                                            $existing = $existingAvailabilities->get($key);
                                            $isBooked = $existing && $existing->status === 'booked';
                                            $isChecked = $existing !== null;
                                        @endphp
                                        <td class="px-2 py-2 border border-gray-300 dark:border-gray-600">
                                            <label class="block h-12 relative">
                                                <input 
                                                    type="checkbox" 
                                                    name="slots[]" 
                                                    value="{{ $day }}_{{ $jam->id }}"
                                                    class="slot-checkbox {{ $isBooked ? 'booked' : '' }}"
                                                    {{ $isChecked ? 'checked' : '' }}
                                                    {{ $isBooked ? 'disabled' : '' }}
                                                >
                                                @if($isBooked)
                                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                                        <i class="fas fa-lock text-white text-xs"></i>
                                                    </div>
                                                @endif
                                            </label>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4 text-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 bg-green-500 rounded"></div>
                                <span class="text-gray-600 dark:text-gray-400">Tersedia</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 bg-blue-500 rounded"></div>
                                <span class="text-gray-600 dark:text-gray-400">Terjadwal</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 bg-gray-200 dark:bg-gray-600 rounded border-2 border-gray-300 dark:border-gray-500"></div>
                                <span class="text-gray-600 dark:text-gray-400">Tidak Tersedia</span>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('dosen.availability.index') }}" 
                               class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2.5 bg-maroon text-white font-semibold rounded-lg hover:bg-maroon-700 transition shadow-md hover:shadow-lg flex items-center gap-2">
                                <i class="fas fa-save"></i>
                                Simpan Ketersediaan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.getElementById('availabilityForm').addEventListener('submit', function(e) {
            const checkedSlots = document.querySelectorAll('input[name="slots[]"]:checked:not(:disabled)').length;
            
            if (checkedSlots === 0) {
                e.preventDefault();
                alert('Silakan pilih minimal satu slot waktu ketersediaan.');
                return false;
            }
            
            return confirm(`Anda akan menyimpan ${checkedSlots} slot waktu ketersediaan. Lanjutkan?`);
        });
    </script>
    @endpush
@endsection
