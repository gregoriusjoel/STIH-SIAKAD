@extends('layouts.admin')

@section('title', 'Tambah Jam Perkuliahan')
@section('page-title', 'Tambah Jam Perkuliahan')

@section('content')
    <div class="w-full">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-maroon">
            <div class="p-6 border-b border-gray-200 bg-maroon text-white">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-plus-circle mr-3 text-2xl"></i>
                    Tambah Jam Perkuliahan
                </h3>
                <p class="text-sm mt-1 text-white text-opacity-90">Tambahkan sesi perkuliahan baru ke dalam sistem</p>
            </div>

            <form action="{{ route('admin.jam-perkuliahan.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Jam Ke -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Ke- <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="jam_ke" id="jam_ke" value="{{ old('jam_ke', $suggestedJamKe) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                placeholder="Contoh: 1" required min="1">
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
                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                        </div>

                        <!-- Jam Mulai -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-play-circle text-maroon mr-1"></i>
                                Jam Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="jam_mulai" id="jam_mulai"
                                value="{{ old('jam_mulai', $suggestedJamMulai) }}"
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
                            <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                required>
                            @error('jam_selesai')
                                <p class="text-red-500 text-xs mt-1"><i
                                        class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Otomatis terisi 45 menit setelah jam mulai</p>
                        </div>
                    </div>

                    <!-- Preview Box -->
                    <div id="preview-box" class="hidden p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-maroon rounded-lg flex items-center justify-center">
                                    <span id="preview-jam" class="text-white font-bold text-lg">-</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Jam ke-<span id="preview-jam-text">-</span></p>
                                    <p class="text-sm text-gray-500">
                                        <span id="preview-waktu">--:-- - --:--</span>
                                        <span class="mx-2">•</span>
                                        <span id="preview-durasi">-- menit</span>
                                    </p>
                                </div>
                            </div>
                            <span id="preview-status"
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Aktif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t">
                    <a href="{{ route('admin.jam-perkuliahan.index') }}"
                        class="px-6 py-3 bg-white border-2 border-maroon rounded-lg text-maroon font-medium shadow-md transform hover:scale-105 transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-maroon text-white px-6 py-3 rounded-lg hover:bg-red-900 transition flex items-center shadow-md transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const jamKe = document.getElementById('jam_ke');
            const jamMulai = document.getElementById('jam_mulai');
            const jamSelesai = document.getElementById('jam_selesai');
            const isActive = document.getElementById('is_active');
            const previewBox = document.getElementById('preview-box');

            // Helper: parse time string robustly (shared scope)
            function parseTimeStr(value) {
                if (!value) return null;
                let v = String(value).trim();
                v = v.replace(/\./g, ':').replace(/,/g, ':');
                if (v.indexOf(':') === -1) {
                    if (/^\d{3,4}$/.test(v)) {
                        v = v.slice(0, v.length - 2) + ':' + v.slice(-2);
                    } else if (/^\d{1,2}$/.test(v)) {
                        v = v.padStart(2, '0') + ':00';
                    }
                }
                const parts = v.split(':').map(p => parseInt(p, 10));
                if (parts.length < 2 || isNaN(parts[0]) || isNaN(parts[1])) return null;
                parts[0] = (parts[0] + 24) % 24;
                parts[1] = Math.max(0, Math.min(59, parts[1]));
                return parts;
            }

            function updatePreview() {

                const jam = jamKe.value || '-';
                const mulai = jamMulai.value || '--:--';
                const selesai = jamSelesai.value || '--:--';

                document.getElementById('preview-jam').textContent = jam;
                document.getElementById('preview-jam-text').textContent = jam;
                document.getElementById('preview-waktu').textContent = mulai + ' - ' + selesai;

                const startParts = parseTimeStr(jamMulai.value || jamMulai.getAttribute('value'));
                const endParts = parseTimeStr(jamSelesai.value || jamSelesai.getAttribute('value'));
                if (startParts && endParts) {
                    const startMin = startParts[0] * 60 + startParts[1];
                    const endMin = endParts[0] * 60 + endParts[1];
                    const duration = endMin - startMin;
                    document.getElementById('preview-durasi').textContent = (isFinite(duration) ? duration : 0) + ' menit';
                } else {
                    document.getElementById('preview-durasi').textContent = '-- menit';
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

                // Show preview if any value filled
                if (jamKe.value || jamMulai.value) {
                    previewBox.classList.remove('hidden');
                }
            }

            // Auto calculate and normalize jam_mulai -> jam_selesai (+45 minutes)
            if (jamMulai && jamSelesai) {
                // normalize initial value attribute if present (e.g., '20.50')
                const initialAttr = jamMulai.getAttribute('value');
                if (!jamMulai.value && initialAttr) {
                    console.debug('initial jam_mulai attribute:', initialAttr);
                    const parsedInit = parseTimeStr(initialAttr);
                    console.debug('parsed initial jam_mulai:', parsedInit);
                    if (parsedInit) {
                        const normalized = String(parsedInit[0]).padStart(2, '0') + ':' + String(parsedInit[1]).padStart(2, '0');
                        jamMulai.value = normalized;
                        try { jamMulai.setAttribute('value', normalized); } catch (e) { }
                    }
                }

                function computeEndFromStart(val) {
                    const inVal = (val || jamMulai.value || jamMulai.getAttribute('value'));
                    console.debug('computeEndFromStart input:', inVal);
                    const parsed = parseTimeStr(inVal);
                    console.debug('computeEndFromStart parsed:', parsed);
                    if (!parsed) return;
                    const hours = parsed[0], minutes = parsed[1];
                    const date = new Date();
                    date.setHours(hours);
                    date.setMinutes(minutes + 45);
                    const endHours = String(date.getHours()).padStart(2, '0');
                    const endMinutes = String(date.getMinutes()).padStart(2, '0');
                    const normalizedEnd = `${endHours}:${endMinutes}`;
                    jamSelesai.value = normalizedEnd;
                    try { jamSelesai.setAttribute('value', normalizedEnd); } catch (e) { }
                    updatePreview();
                }

                // listen to input and change (some browsers update input[type=time] differently)
                jamMulai.addEventListener('input', function (e) { computeEndFromStart(e.target.value); });
                jamMulai.addEventListener('change', function (e) { computeEndFromStart(e.target.value); });

                // compute immediately if possible
                computeEndFromStart();
            }

            // Add listeners
            jamKe.addEventListener('input', updatePreview);
            jamMulai.addEventListener('change', updatePreview);
            jamSelesai.addEventListener('change', updatePreview);
            isActive.addEventListener('change', updatePreview);

            // Initial update
            updatePreview();
        });
    </script>
@endpush