@extends('layouts.admin')

@section('title', 'Edit Jam Perkuliahan')
@section('page-title', 'Edit Jam Perkuliahan')

@section('content')
    <div class="w-full">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-maroon">
            <div class="p-6 border-b border-gray-200 bg-maroon text-white">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-edit mr-3 text-2xl"></i>
                    Edit Jam Perkuliahan
                </h3>
                <p class="text-sm mt-1 text-white text-opacity-90">Mengubah data sesi ke-{{ $jam->jam_ke }}</p>
            </div>

            <form action="{{ route('admin.jam-perkuliahan.update', $jam->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Jam Ke -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Ke- <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="jam_ke" id="jam_ke" value="{{ old('jam_ke', $jam->jam_ke) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                required min="1">
                            @error('jam_ke')
                                <p class="text-red-500 text-xs mt-1"><i
                                        class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Urutan sesi perkuliahan (1, 2, 3, ...)</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-check-circle text-maroon mr-1"></i>
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="is_active" id="is_active"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">
                                <option value="1" {{ old('is_active', $jam->is_active) ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active', $jam->is_active) ? '' : 'selected' }}>Non-Aktif
                                </option>
                            </select>
                        </div>

                        <!-- Jam Mulai -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-play-circle text-maroon mr-1"></i>
                                Jam Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="jam_mulai" id="jam_mulai"
                                value="{{ old('jam_mulai', date('H:i', strtotime($jam->jam_mulai))) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                required>
                            @error('jam_mulai')
                                <p class="text-red-500 text-xs mt-1"><i
                                        class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jam Selesai -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-stop-circle text-maroon mr-1"></i>
                                Jam Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="jam_selesai" id="jam_selesai"
                                value="{{ old('jam_selesai', date('H:i', strtotime($jam->jam_selesai))) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                required>
                            @error('jam_selesai')
                                <p class="text-red-500 text-xs mt-1"><i
                                        class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Preview Box -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-3">Preview</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-maroon rounded-lg flex items-center justify-center">
                                    <span id="preview-jam" class="text-white font-bold text-lg">{{ $jam->jam_ke }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Jam ke-<span
                                            id="preview-jam-text">{{ $jam->jam_ke }}</span></p>
                                    <p class="text-sm text-gray-500">
                                        <span id="preview-waktu">{{ date('H:i', strtotime($jam->jam_mulai)) }} -
                                            {{ date('H:i', strtotime($jam->jam_selesai)) }}</span>
                                        <span class="mx-2">•</span>
                                        @php
                                            $start = strtotime($jam->jam_mulai);
                                            $end = strtotime($jam->jam_selesai);
                                            if (substr($jam->jam_selesai, 0, 5) === '00:00') {
                                                $end = strtotime('+1 day', $end);
                                            }
                                            $duration = ($end - $start) / 60;
                                        @endphp
                                        <span id="preview-durasi">{{ $duration }} menit</span>
                                    </p>
                                </div>
                            </div>
                            <span id="preview-status"
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $jam->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                @if($jam->is_active)
                                    <i class="fas fa-check-circle mr-1"></i> Aktif
                                @else
                                    <i class="fas fa-times-circle mr-1"></i> Non-Aktif
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col-reverse md:flex-row md:justify-between mt-8 pt-6 border-t gap-4 md:gap-0">
                    <button type="button" onclick="confirmDelete()"
                        class="px-6 py-3 border-2 border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition flex items-center justify-center w-full md:w-auto">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Hapus
                    </button>

                    <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3 w-full md:w-auto">
                        <a href="{{ route('admin.jam-perkuliahan.index') }}"
                            class="px-6 py-3 bg-white border-2 border-maroon rounded-lg text-maroon font-medium shadow-md w-full md:w-auto flex items-center justify-center transform hover:scale-105 transition">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-maroon text-white px-6 py-3 rounded-lg hover:bg-red-900 transition flex items-center justify-center shadow-md w-full md:w-auto transform hover:scale-105">
                            <i class="fas fa-save mr-2"></i>
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="delete-form" action="{{ route('admin.jam-perkuliahan.destroy', $jam->id) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const jamKe = document.getElementById('jam_ke');
            const jamMulai = document.getElementById('jam_mulai');
            const jamSelesai = document.getElementById('jam_selesai');
            const isActive = document.getElementById('is_active');

            function updatePreview() {
                const jam = jamKe.value || '-';
                const mulai = jamMulai.value || '--:--';
                const selesai = jamSelesai.value || '--:--';

                document.getElementById('preview-jam').textContent = jam;
                document.getElementById('preview-jam-text').textContent = jam;
                document.getElementById('preview-waktu').textContent = mulai + ' - ' + selesai;

                // Calculate duration
                if (jamMulai.value && jamSelesai.value) {
                    const start = jamMulai.value.split(':').map(Number);
                    const end = jamSelesai.value.split(':').map(Number);
                    const startMin = start[0] * 60 + start[1];
                    let endMin = end[0] * 60 + end[1];
                    
                    if (endMin < startMin && jamSelesai.value === '00:00') {
                        endMin += (24 * 60);
                    }
                    
                    const duration = endMin - startMin;
                    document.getElementById('preview-durasi').textContent = duration + ' menit';
                }

                // Status
                const statusEl = document.getElementById('preview-status');
                if (isActive.value === '1') {
                    statusEl.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800';
                    statusEl.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Aktif';
                } else {
                    statusEl.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600';
                    statusEl.innerHTML = '<i class="fas fa-times-circle mr-1"></i> Non-Aktif';
                }
            }

            // Auto calculate
            if (jamMulai && jamSelesai) {
                jamMulai.addEventListener('change', function () {
                    if (this.value) {
                        const [hours, minutes] = this.value.split(':').map(Number);
                        const date = new Date();
                        date.setHours(hours);
                        date.setMinutes(minutes + 45);

                        const endHours = String(date.getHours()).padStart(2, '0');
                        const endMinutes = String(date.getMinutes()).padStart(2, '0');

                        jamSelesai.value = `${endHours}:${endMinutes}`;
                        updatePreview();
                    }
                });
            }

            // Add listeners
            jamKe.addEventListener('input', updatePreview);
            jamMulai.addEventListener('change', updatePreview);
            jamSelesai.addEventListener('change', updatePreview);
            isActive.addEventListener('change', updatePreview);
        });

        function confirmDelete() {
            Swal.fire({
                title: 'Hapus Jam Perkuliahan?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#7a1621',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form').submit();
                }
            });
        }
    </script>
@endpush