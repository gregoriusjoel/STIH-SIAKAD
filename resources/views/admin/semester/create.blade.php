@extends('layouts.admin')
@section('title', 'Tambah Semester')
@section('page-title', 'Tambah Semester')
@section('content')
    <div class="w-full">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-maroon-800">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-maroon text-white rounded-t-xl">
                <h3 class="text-xl font-bold flex items-center"><i class="fas fa-calendar-alt mr-3 text-2xl"></i>Tambah
                    Semester & Tahun Ajaran</h3>
            </div>
            <form action="{{ route('admin.semester.store') }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><i
                                class="fas fa-bookmark text-gray-400 dark:text-gray-500 mr-1"></i>Nama Semester *</label>
                        <select name="nama_semester" id="nama_semester"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                            required>
                            <option value="">-- Pilih Nama Semester --</option>
                            <option value="Ganjil" {{ old('nama_semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ old('nama_semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><i
                                class="fas fa-graduation-cap text-gray-400 dark:text-gray-500 mr-1"></i>Tahun Ajaran *</label>
                        @php
                            $currentYear = (int) date('Y');
                            $startYear = $currentYear - 2;
                            $endYear = $currentYear + 3;
                            $selected = old('tahun_ajaran', '');
                        @endphp
                        <select name="tahun_ajaran" id="tahun_ajaran"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                            required>
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            @for ($y = $startYear; $y <= $endYear; $y++)
                                @php $label = $y . '/' . ($y + 1); @endphp
                                <option value="{{ $label }}" {{ $selected == $label ? 'selected' : '' }}>{{ $label }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="md:col-span-2 flex flex-col md:flex-row items-center gap-4">
                        <div class="flex-1 w-full">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><i
                                    class="fas fa-calendar-alt text-gray-400 dark:text-gray-500 mr-1"></i>Tanggal Mulai *</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                required>
                        </div>



                        <div class="flex-1 w-full">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><i
                                    class="fas fa-calendar-alt text-gray-400 dark:text-gray-500 mr-1"></i>Tanggal Selesai *</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                required>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><i
                                class="fas fa-check-circle text-maroon dark:text-red-500 mr-1"></i>Status *</label>
                        <select name="status"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                            required>
                            <option value="non-aktif" {{ old('status', 'non-aktif') == 'non-aktif' ? 'selected' : '' }}>
                                Non-Aktif</option>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        </select>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Jika dipilih <strong>Aktif</strong>, semester lain akan
                            dinonaktifkan.</p>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t dark:border-gray-700"><a href="{{ route('admin.semester.index') }}"
                        class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition flex items-center"><i
                            class="fas fa-times mr-2"></i>Batal</a><button type="submit"
                        class="bg-maroon text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition flex items-center shadow-md transform hover:scale-105"><i
                            class="fas fa-save mr-2"></i>Simpan</button></div>
            </form>
            <script>
                (function () {
                    const start = document.getElementById('tanggal_mulai');
                    const end = document.getElementById('tanggal_selesai');
                    const nama = document.getElementById('nama_semester');
                    const tahun = document.getElementById('tahun_ajaran');
                    if (!start || !end) return;

                    function toYMD(d) {
                        const mm = String(d.getMonth() + 1).padStart(2, '0');
                        const dd = String(d.getDate()).padStart(2, '0');
                        return `${d.getFullYear()}-${mm}-${dd}`;
                    }

                    start.addEventListener('change', function () {
                        if (!this.value) return;
                        const s = new Date(this.value);
                        // add 6 months
                        const target = new Date(s.getTime());
                        target.setMonth(target.getMonth() + 6);
                        end.value = toYMD(target);
                    });

                    // When selecting Genap, autofill tanggal_mulai from previous Ganjil.tanggal_selesai + 1 day
                    async function tryFillFromPrevious() {
                        if (!nama || !tahun) return;
                        if (nama.value !== 'Genap') return;
                        if (!tahun.value) return;

                        try {
                            const url = `{{ url('admin/semester/previous-end') }}?tahun_ajaran=${encodeURIComponent(tahun.value)}`;
                            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                            if (!res.ok) return;
                            const data = await res.json();
                            if (data && data.tanggal_selesai) {
                                // set tanggal_mulai to the day after previous semester's tanggal_selesai
                                const prev = new Date(data.tanggal_selesai);
                                prev.setDate(prev.getDate() + 1);
                                start.value = toYMD(prev);
                                // compute default end (+6 months)
                                const target = new Date(prev.getTime());
                                target.setMonth(target.getMonth() + 6);
                                end.value = toYMD(target);
                            }
                        } catch (e) {
                            console.error('Failed to fetch previous semester end', e);
                        }
                    }

                    if (nama && tahun) {
                        nama.addEventListener('change', tryFillFromPrevious);
                        tahun.addEventListener('change', tryFillFromPrevious);
                        // call now in case of preselected values
                        tryFillFromPrevious();
                    }
                })();
            </script>
        </div>
    </div>
@endsection