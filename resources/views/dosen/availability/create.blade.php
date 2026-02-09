@extends('layouts.app')
@section('title', 'Atur Ketersediaan Waktu')
@section('page-title', 'Atur Ketersediaan Waktu')

@push('styles')
<style>
    .slot-cell {
        position: relative;
        height: 50px;
        cursor: pointer;
        border-radius: 0.5rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }
    
    .slot-cell.unchecked {
        background: #f3f4f6;
        border: 2px solid #e5e7eb;
    }
    
    .slot-cell.unchecked:hover {
        background: #e5e7eb;
        border-color: #d1d5db;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .slot-cell.checked {
        background: #10b981;
        border: 2px solid #059669;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .slot-cell.checked:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    }
    
    .slot-cell.booked {
        background: #3b82f6;
        border: 2px solid #2563eb;
        cursor: not-allowed;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .slot-cell.dragging-over {
        border: 3px solid #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
    }
    
    .slot-checkbox {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    
    .slot-icon {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        transition: all 0.2s;
    }
    
    .slot-cell.checked .slot-icon {
        color: white;
        font-size: 1.25rem;
    }
    
    .slot-cell.booked .slot-icon {
        color: white;
        font-size: 1rem;
    }
    
    /* Dark mode */
    .dark .slot-cell.unchecked {
        background: #374151;
        border-color: #6b7280;
    }
    
    .dark .slot-cell.unchecked:hover {
        background: #4b5563;
        border-color: #9ca3af;
    }
    
    .dark .slot-cell.checked {
        background: #059669;
        border-color: #047857;
    }
    
    /* Selection counter animation */
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .selection-counter {
        animation: pulse 0.3s ease-in-out;
    }
    
    /* Quick select buttons */
    .quick-select-btn {
        transition: all 0.2s;
    }
    
    .quick-select-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
</style>
@endpush

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-maroon text-white flex items-center justify-between">
                <div class="font-bold text-white text-xl flex items-center gap-4">
                    <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm">
                        <i class="fas fa-calendar-check text-lg"></i>
                    </div>
                    <div>
                        <div>Atur Ketersediaan Waktu</div>
                        <div class="text-sm font-normal text-white/80 mt-1">{{ $activeSemester->nama_semester }} {{ $activeSemester->tahun_ajaran }}</div>
                    </div>
                </div>
                <a href="{{ route('dosen.availability.index') }}" 
                   class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-lg font-semibold transition-all duration-200 inline-flex items-center gap-2 border border-white/20">
                    <i class="fas fa-arrow-left"></i>Kembali
                </a>
            </div>

            <div class="px-6 py-5 bg-blue-50 dark:bg-gray-700/50">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900/30 text-blue-700 dark:text-blue-300 px-5 py-4 rounded-xl text-sm flex gap-4 items-start">
                    <i class="fas fa-info-circle mt-0.5 text-blue-500 dark:text-blue-400 text-lg"></i>
                    <div>
                        <span class="font-semibold block mb-2">Cara Penggunaan</span>
                        <ul class="space-y-1 text-sm">
                            <li><i class="fas fa-mouse-pointer text-blue-500 mr-2"></i><strong>Klik</strong> pada kotak waktu untuk memilih/membatalkan</li>
                            <li><i class="fas fa-hand-pointer text-blue-500 mr-2"></i><strong>Drag (Seret)</strong> untuk memilih banyak slot sekaligus</li>
                            <li><i class="fas fa-square text-green-500 mr-2"></i>Hijau = Tersedia | <i class="fas fa-square text-blue-500 mr-2"></i>Biru = Terjadwal (terkunci)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('dosen.availability.store') }}" method="POST" id="availabilityForm">
            @csrf
            <input type="hidden" name="semester_id" value="{{ $activeSemester->id }}">
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200 flex items-center gap-3">
                        <i class="fas fa-calendar-alt text-maroon text-lg"></i>
                        Pilih Waktu Ketersediaan
                    </h3>
                    <div class="flex items-center gap-4">
                        <div class="selection-counter px-4 py-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                            <span class="text-sm font-semibold text-green-700 dark:text-green-400">
                                <i class="fas fa-check-circle mr-1"></i>
                                <span id="selectedCount">0</span> slot dipilih
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6 overflow-x-auto">
                    <table class="w-full border-collapse" id="availabilityTable">
                        <thead>
                            <tr class="bg-maroon text-white">
                                <th class="px-4 py-4 text-left font-semibold border border-gray-300 dark:border-gray-600 sticky left-0 bg-maroon z-20 rounded-tl-lg">
                                    <div class="text-sm">Jam</div>
                                </th>
                                @foreach($days as $day)
                                    <th class="px-4 py-4 text-center font-semibold border border-gray-300 dark:border-gray-600 min-w-[120px] {{ $loop->last ? 'rounded-tr-lg' : '' }}">
                                        <div class="mb-2">{{ $day }}</div>
                                        <div class="flex gap-1 justify-center">
                                            <button type="button" 
                                                    class="quick-select-btn px-2 py-1 bg-white/20 hover:bg-white/30 rounded text-xs transition"
                                                    onclick="selectDay('{{ $day }}', true)">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                            <button type="button" 
                                                    class="quick-select-btn px-2 py-1 bg-white/20 hover:bg-white/30 rounded text-xs transition"
                                                    onclick="selectDay('{{ $day }}', false)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jamPerkuliahan as $jam)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-4 py-3 border border-gray-300 dark:border-gray-600 font-medium text-gray-700 dark:text-gray-300 sticky left-0 bg-white dark:bg-gray-800 z-10">
                                        <div class="text-sm font-bold">Jam {{ $jam->jam_ke }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ substr($jam->jam_mulai, 0, 5) }} - {{ substr($jam->jam_selesai, 0, 5) }}</div>
                                    </td>
                                    @foreach($days as $day)
                                        @php
                                            $key = "{$day}_{$jam->id}";
                                            $existing = $existingAvailabilities->get($key);
                                            $isBooked = $existing && $existing->status === 'booked';
                                            $isChecked = $existing !== null;
                                        @endphp
                                        <td class="px-3 py-3 border border-gray-300 dark:border-gray-600">
                                            <label class="block relative">
                                                <input 
                                                    type="checkbox" 
                                                    name="slots[]" 
                                                    value="{{ $day }}_{{ $jam->id }}"
                                                    class="slot-checkbox"
                                                    data-day="{{ $day }}"
                                                    data-jam="{{ $jam->id }}"
                                                    {{ $isChecked ? 'checked' : '' }}
                                                    {{ $isBooked ? 'disabled' : '' }}
                                                >
                                                <div class="slot-cell {{ $isChecked ? 'checked' : 'unchecked' }} {{ $isBooked ? 'booked' : '' }}"
                                                     data-slot="{{ $day }}_{{ $jam->id }}">
                                                    <div class="slot-icon">
                                                        @if($isBooked)
                                                            <i class="fas fa-lock"></i>
                                                        @elseif($isChecked)
                                                            <i class="fas fa-check-circle"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            </label>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-5 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-6 text-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-green-500 rounded-lg shadow-sm"></div>
                                <span class="text-gray-700 dark:text-gray-300 font-medium">Tersedia</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-blue-500 rounded-lg shadow-sm"></div>
                                <span class="text-gray-700 dark:text-gray-300 font-medium">Terjadwal</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-gray-200 dark:bg-gray-600 rounded-lg border-2 border-gray-400 dark:border-gray-500"></div>
                                <span class="text-gray-700 dark:text-gray-300 font-medium">Tidak Tersedia</span>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('dosen.availability.index') }}" 
                               class="px-6 py-2.5 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2.5 bg-maroon hover:bg-red-800 text-white font-semibold rounded-lg transition shadow-lg hover:shadow-xl flex items-center gap-2">
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
        // Drag selection variables
        let isDragging = false;
        let dragMode = null; // 'select' or 'deselect'
        let startCell = null;

        // Get all slot cells
        const slotCells = document.querySelectorAll('.slot-cell:not(.booked)');
        const table = document.getElementById('availabilityTable');

        // Update selection counter
        function updateCounter() {
            const count = document.querySelectorAll('input[name="slots[]"]:checked:not(:disabled)').length;
            const counter = document.getElementById('selectedCount');
            counter.textContent = count;
            counter.parentElement.parentElement.classList.add('selection-counter');
            setTimeout(() => {
                counter.parentElement.parentElement.classList.remove('selection-counter');
            }, 300);
        }

        // Toggle slot
        function toggleSlot(cell, forceState = null) {
            if (cell.classList.contains('booked')) return;
            
            const checkbox = cell.parentElement.querySelector('input[type="checkbox"]');
            if (!checkbox || checkbox.disabled) return;
            
            if (forceState !== null) {
                checkbox.checked = forceState;
            } else {
                checkbox.checked = !checkbox.checked;
            }
            
            // Update visual state
            if (checkbox.checked) {
                cell.classList.remove('unchecked');
                cell.classList.add('checked');
                cell.querySelector('.slot-icon').innerHTML = '<i class="fas fa-check-circle"></i>';
            } else {
                cell.classList.remove('checked');
                cell.classList.add('unchecked');
                cell.querySelector('.slot-icon').innerHTML = '';
            }
            
            updateCounter();
        }

        // Mouse events for drag selection
        slotCells.forEach(cell => {
            // Mouse down - start drag
            cell.addEventListener('mousedown', (e) => {
                e.preventDefault();
                isDragging = true;
                startCell = cell;
                
                const checkbox = cell.parentElement.querySelector('input[type="checkbox"]');
                dragMode = checkbox.checked ? 'deselect' : 'select';
                
                toggleSlot(cell);
            });

            // Mouse enter - continue drag
            cell.addEventListener('mouseenter', (e) => {
                if (isDragging) {
                    cell.classList.add('dragging-over');
                    toggleSlot(cell, dragMode === 'select');
                }
            });

            // Mouse leave - remove highlight
            cell.addEventListener('mouseleave', (e) => {
                cell.classList.remove('dragging-over');
            });

            // Click - toggle single
            cell.addEventListener('click', (e) => {
                if (!isDragging) {
                    toggleSlot(cell);
                }
            });
        });

        // Mouse up - end drag
        document.addEventListener('mouseup', () => {
            isDragging = false;
            dragMode = null;
            startCell = null;
            slotCells.forEach(cell => cell.classList.remove('dragging-over'));
        });

        // Touch events for mobile
        slotCells.forEach(cell => {
            cell.addEventListener('touchstart', (e) => {
                e.preventDefault();
                isDragging = true;
                const checkbox = cell.parentElement.querySelector('input[type="checkbox"]');
                dragMode = checkbox.checked ? 'deselect' : 'select';
                toggleSlot(cell);
            });

            cell.addEventListener('touchmove', (e) => {
                e.preventDefault();
                if (isDragging) {
                    const touch = e.touches[0];
                    const element = document.elementFromPoint(touch.clientX, touch.clientY);
                    const touchedCell = element?.closest('.slot-cell');
                    if (touchedCell && !touchedCell.classList.contains('booked')) {
                        toggleSlot(touchedCell, dragMode === 'select');
                    }
                }
            });

            cell.addEventListener('touchend', () => {
                isDragging = false;
                dragMode = null;
            });
        });

        // Quick select functions
        window.selectDay = function(day, select) {
            const checkboxes = document.querySelectorAll(`input[data-day="${day}"]:not(:disabled)`);
            checkboxes.forEach(checkbox => {
                checkbox.checked = select;
                const cell = checkbox.parentElement.querySelector('.slot-cell');
                if (select) {
                    cell.classList.remove('unchecked');
                    cell.classList.add('checked');
                    cell.querySelector('.slot-icon').innerHTML = '<i class="fas fa-check-circle"></i>';
                } else {
                    cell.classList.remove('checked');
                    cell.classList.add('unchecked');
                    cell.querySelector('.slot-icon').innerHTML = '';
                }
            });
            updateCounter();
        };

        // Form submission
        document.getElementById('availabilityForm').addEventListener('submit', function(e) {
            const checkedSlots = document.querySelectorAll('input[name="slots[]"]:checked:not(:disabled)').length;
            
            if (checkedSlots === 0) {
                e.preventDefault();
                showError('Silakan pilih minimal satu slot waktu ketersediaan.');
                return false;
            }
            
            e.preventDefault();
            showConfirm(
                `Anda akan menyimpan ${checkedSlots} slot waktu ketersediaan. Lanjutkan?`,
                () => this.submit(),
                null,
                'Konfirmasi Simpan'
            );
            return false;
        });

        // Initialize counter
        updateCounter();
    </script>
    @endpush
@endsection
