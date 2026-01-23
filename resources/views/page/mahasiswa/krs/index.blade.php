@extends('layouts.mahasiswa')

@section('title', 'Kartu Rencana Studi (KRS)')
@section('page-title', 'Kartu Rencana Studi')

@section('content')
<div class="max-w-7xl mx-auto">
    
    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Kartu Rencana Studi (KRS)</h2>
                <p class="text-gray-600">Semester: <span class="font-semibold">{{ $semesterAktif->nama_semester ?? 'Tidak ada semester aktif' }}</span> • <span class="font-semibold">Semester {{ $mahasiswaSemester ?? 1 }}</span></p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600 mb-1">Status KRS:</p>
                <span class="px-4 py-2 rounded-lg font-bold text-sm
                    @if($statusKrs === 'approved') bg-green-100 text-green-700
                    @elseif($statusKrs === 'diajukan') bg-yellow-100 text-yellow-700
                    @else bg-gray-100 text-gray-700
                    @endif">
                    {{ strtoupper($statusKrs) }}
                </span>
            </div>
        </div>
    </div>
    

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 mb-6">
        <div class="flex items-center gap-3">
            <i class="fas fa-check-circle text-green-600 text-xl"></i>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-6">
        <div class="flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @php
        $isEditable = ($statusKrs === 'draft' || $statusKrs === null);
    @endphp

    {{-- Info Box --}}
    @if($statusKrs === 'draft' || $statusKrs === null)
    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-5 mb-6">
        <div class="flex items-start gap-3">
            <i class="fas fa-info-circle text-blue-600 text-2xl"></i>
            <div>
                <h4 class="font-bold text-blue-900 mb-2">Petunjuk Pengisian KRS</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Pilih <strong>Ya</strong> untuk mata kuliah yang akan Anda ambil semester ini</li>
                    <li>• Pilih <strong>Tidak</strong> untuk mata kuliah yang tidak diambil</li>
                    <li>• Maksimal beban SKS per semester: <strong>24 SKS</strong></li>
                    <li>• Klik <strong>Simpan Draft</strong> untuk menyimpan tanpa mengajukan</li>
                    <li>• Konsultasi Terlebih dahulu Dengan <strong>Dosen PA</strong> Sebelum Mengajukan KRS</li>
                </ul>
            </div>
        </div>
    </div>
    @elseif($statusKrs === 'diajukan')
    <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-5 mb-6">
        <div class="flex items-start gap-3">
            <i class="fas fa-clock text-yellow-600 text-2xl"></i>
            <div>
                <h4 class="font-bold text-yellow-900 mb-2">KRS Menunggu Persetujuan</h4>
                <p class="text-sm text-yellow-800">KRS Anda telah diajukan dan sedang menunggu persetujuan dari dosen wali. Anda tidak dapat mengubah KRS selama dalam proses persetujuan.</p>
            </div>
        </div>
    </div>
    @elseif($statusKrs === 'approved')
    <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-5 mb-6">
        <div class="flex items-start gap-3">
            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
            <div>
                <h4 class="font-bold text-green-900 mb-2">KRS Telah Disetujui</h4>
                <p class="text-sm text-green-800">KRS Anda telah disetujui oleh . Anda tidak dapat mengubah KRS yang sudah disetujui.</p>
            </div>
        </div>
    </div>
    @endif

    {{-- KRS Form --}}
    <form action="{{ route('mahasiswa.krs.store') }}" method="POST" id="krsForm">
        @csrf
        
        {{-- Add Mata Kuliah Section (for cross-semester courses) --}}
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="text-lg font-bold text-gray-800">Tambah Mata Kuliah Lintas Semester</h4>
                    <p class="text-sm text-gray-600 mt-1">
                        Pilih mata kuliah tambahan dari semester lain (sesuai aturan ganjil/genap)
                    </p>
                </div>
            </div>
            
            <div class="flex gap-3">
                <select id="mataKuliahSelect" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent text-sm" @if(!$isEditable) disabled aria-disabled="true" @endif>
                    <option value="">-- Pilih Mata Kuliah Tambahan --</option>
                    @foreach($additionalMataKuliah as $mk)
                        @php
                            $alreadyTaken = $existingKrs->contains('mata_kuliah_id', $mk->id);
                        @endphp
                        @if(!$alreadyTaken)
                        <option value="{{ $mk->id }}" 
                                data-kode="{{ $mk->kode_mk }}"
                                data-kode-id="{{ $mk->kode_id }}"
                                data-nama="{{ $mk->nama_mk }}"
                                data-sks="{{ $mk->sks }}"
                                data-semester="{{ $mk->semester }}"
                                data-jenis="{{ $mk->jenis }}"
                                data-praktikum="{{ $mk->praktikum ?? 0 }}">
                            [{{ $mk->kode_id }}] {{ $mk->kode_mk }} - {{ $mk->nama_mk }} ({{ $mk->sks }} SKS) 
                            @if($mk->praktikum)
                                <span class="text-blue-600">+ Praktikum</span>
                            @endif
                        </option>
                        @endif
                    @endforeach
                </select>
                <button type="button" id="addMataKuliahBtn" class="px-6 py-3 bg-maroon text-white font-bold rounded-lg hover:bg-maroon-hover transition shadow-md hover:shadow-lg flex items-center gap-2" @if(!$isEditable) disabled aria-disabled="true" class="opacity-50 cursor-not-allowed" @endif>
                    <i class="fas fa-plus"></i>
                    Tambah
                </button>
            </div>
        </div>
        

        <div class="mt-4 mb-6">
            <div class="bg-white rounded-lg border border-gray-200 p-4 flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-semibold text-gray-700">Ambil Semua Mata Kuliah (Semester Ini)</h4>
                    <p class="text-xs text-gray-500">Klik untuk memilih semua mata kuliah saat ini sekaligus.</p>
                </div>
                <div>
                    <button id="ambilSemuaBtn" type="button" class="px-4 py-2 bg-maroon text-white rounded-lg hover:bg-maroon-hover transition shadow-sm" @if(!$isEditable) disabled aria-disabled="true" class="opacity-50 cursor-not-allowed" @endif>Ambil Semua</button>
                </div>
            </div>
        </div>
        
        
        @php
            // kelas yang punya jadwal - ensure mataKuliah is loaded
            $calendarKelas = $availableKelas->filter(function($k){
                return isset($k->jadwals) && $k->jadwals->isNotEmpty();
            })->load('mataKuliah');
        @endphp

        @if($calendarKelas->isNotEmpty())
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-bold text-gray-800">Kalender Jadwal Mata Kuliah</h4>
                <div class="flex items-center gap-4 text-xs">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded" style="background: #3b82f6;"></div>
                        <span class="font-medium">Wajib Nasional</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded" style="background: #ef4444;"></div>
                        <span class="font-medium">Wajib Prodi</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded" style="background: #8b5cf6;"></div>
                        <span class="font-medium">Pilihan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded" style="background: #eab308;"></div>
                        <span class="font-medium">Peminatan</span>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <style>
                    .krs-calendar { display:flex; gap:8px; }
                    .krs-column { flex:1; min-width:120px; background:#fff; border:1px solid #eef2f7; position:relative; height:780px; }
                    .krs-column .day-label { position:absolute; top:6px; left:8px; font-weight:600; color:#374151 }
                    .krs-slot { position:absolute; left:8px; right:8px; background:#06b6d4; color:#fff; border-radius:8px; padding:6px 8px; font-size:12px; box-shadow:0 2px 6px rgba(0,0,0,0.08); }
                </style>

                <div class="krs-calendar">
                    @php
                        $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                        $hourStart = 6; $hourEnd = 19; $hourHeight = 60; // px per hour
                    @endphp

                    @foreach($days as $day)
                        <div class="krs-column">
                            <div class="day-label">{{ $day }}</div>
                            {{-- slots --}}
                            @foreach($calendarKelas as $kelasItem)
                                @foreach($kelasItem->jadwals as $jadwal)
                                    @if(trim($jadwal->hari) === $day)
                                        @php
                                            try {
                                                $start = \Carbon\Carbon::parse($jadwal->jam_mulai);
                                                $end = \Carbon\Carbon::parse($jadwal->jam_selesai);
                                                $startMinutes = $start->hour * 60 + $start->minute;
                                                $endMinutes = $end->hour * 60 + $end->minute;
                                                $top = max(0, $startMinutes - ($hourStart * 60));
                                                $height = max(20, $endMinutes - $startMinutes);
                                            } catch (\Exception $e) {
                                                $top = 0; $height = 40;
                                            }
                                            
                                            // Color based on jenis mata kuliah
                                            $jenis = $kelasItem->mataKuliah->jenis ?? 'wajib_prodi';
                                            switch($jenis) {
                                                case 'wajib_nasional':
                                                    $bg = '#3b82f6'; // blue
                                                    break;
                                                case 'wajib_prodi':
                                                    $bg = '#ef4444'; // red
                                                    break;
                                                case 'pilihan':
                                                    $bg = '#8b5cf6'; // purple
                                                    break;
                                                case 'peminatan':
                                                    $bg = '#eab308'; // yellow
                                                    break;
                                                default:
                                                    $bg = '#06b6d4';
                                            }
                                        @endphp
                                        <div class="krs-slot" style="top: {{ $top }}px; height: {{ $height }}px; background: {{ $bg }};">
                                            <div class="font-semibold">{{ $kelasItem->mataKuliah->nama_mk ?? '-' }}</div>
                                            <div class="text-xs">{{ $start->format('H:i') }} - {{ $end->format('H:i') }} • {{ $kelasItem->section ?? $kelasItem->kode_kelas ?? '-' }}</div>
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            {{-- Table Header --}}
            <div class="bg-gradient-to-r from-maroon to-maroon-hover text-white px-6 py-4">
                <h3 class="text-xl font-bold">Daftar Mata Kuliah yang Diambil</h3>
            </div>

            {{-- Table Content --}}
            <div class="overflow-x-auto">
                <table class="w-full" id="krsTable">
                    <thead class="bg-gray-100 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">No</th>
                            <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Kode MK</th>
                            <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Mata Kuliah</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-700">SKS</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-700">Semester</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-700">Jenis</th>
                            <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Dosen</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-700">Jadwal</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="krsTableBody">
                        {{-- Display current semester mata kuliah first --}}
                        @foreach($currentSemesterMataKuliah as $mk)
                            @php
                                $krs = $existingKrs->get($mk->id);
                                $isChecked = $krs && $krs->ambil_mk === 'ya';
                            @endphp
                            <tr class="hover:bg-gray-50 transition {{ $isChecked ? 'bg-blue-50' : '' }}" data-mk-id="{{ $mk->id }}">
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4 text-sm font-medium text-gray-800">
                                    {{ $mk->kode_mk }}
                                    <span class="block text-xs text-gray-500 mt-1">{{ strtoupper($mk->kode_id) }}</span>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-800">
                                    {{ $mk->nama_mk }}
                                    @if($mk->praktikum)
                                    <span class="inline-block ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-full">Praktikum</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-center font-semibold text-gray-800">{{ $mk->sks }}</td>
                                <td class="px-4 py-4 text-sm text-center text-gray-700">{{ $mk->semester }}</td>
                                <td class="px-4 py-4 text-center">
                                    @php
                                        $jenisLabel = ucwords(str_replace('_', ' ', $mk->jenis));
                                        $jenisColors = [
                                            'wajib_nasional' => 'bg-blue-100 text-blue-800',
                                            'wajib_prodi' => 'bg-red-100 text-red-800',
                                            'pilihan' => 'bg-purple-100 text-purple-800',
                                            'peminatan' => 'bg-yellow-100 text-yellow-800',
                                        ];
                                        $colorClass = $jenisColors[$mk->jenis] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $colorClass }}">
                                        {{ $jenisLabel }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700 text-center">-</td>
                                <td class="px-4 py-4 text-sm text-center text-gray-600">-</td>
                                <td class="px-4 py-4 text-center">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               name="mata_kuliah[{{ $mk->id }}]" 
                                               value="ya"
                                               class="mk-checkbox w-5 h-5 text-maroon border-gray-300 rounded focus:ring-maroon"
                                               data-sks="{{ $mk->sks }}"
                                               {{ $isChecked ? 'checked' : '' }}
                                               @if(!$isEditable) disabled aria-disabled="true" @endif>
                                        <span class="ml-2 text-sm text-gray-600">Ambil</span>
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                        
                        {{-- Display additional taken courses (cross-semester) --}}
                        @foreach($existingKrs->where('ambil_mk', 'ya') as $krs)
                            @if($krs->mataKuliah && $krs->mataKuliah->kode_id !== $currentKodeId)
                            @php $mk = $krs->mataKuliah; @endphp
                            <tr class="hover:bg-gray-50 transition bg-green-50" data-mk-id="{{ $mk->id }}" data-additional="true">
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $currentSemesterMataKuliah->count() + $loop->iteration }}</td>
                                <td class="px-4 py-4 text-sm font-medium text-gray-800">
                                    {{ $mk->kode_mk }}
                                    <span class="block text-xs text-green-600 mt-1">{{ strtoupper($mk->kode_id) }} • Lintas Semester</span>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-800">
                                    {{ $mk->nama_mk }}
                                    @if($mk->praktikum)
                                    <span class="inline-block ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-full">Praktikum</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-center font-semibold text-gray-800">{{ $mk->sks }}</td>
                                <td class="px-4 py-4 text-sm text-center text-gray-700">{{ $mk->semester }}</td>
                                <td class="px-4 py-4 text-center">
                                    @php
                                        $jenisLabel = ucwords(str_replace('_', ' ', $mk->jenis));
                                        $jenisColors = [
                                            'wajib_nasional' => 'bg-blue-100 text-blue-800',
                                            'wajib_prodi' => 'bg-red-100 text-red-800',
                                            'pilihan' => 'bg-purple-100 text-purple-800',
                                            'peminatan' => 'bg-yellow-100 text-yellow-800',
                                        ];
                                        $colorClass = $jenisColors[$mk->jenis] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $colorClass }}">
                                        {{ $jenisLabel }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700 text-center">-</td>
                                <td class="px-4 py-4 text-sm text-center text-gray-600">-</td>
                                <td class="px-4 py-4 text-center">
                                    <button type="button" class="remove-additional-btn px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition" data-mk-id="{{ $mk->id }}" @if(!$isEditable) disabled aria-disabled="true" @endif>
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                    <input type="hidden" name="mata_kuliah[{{ $mk->id }}]" value="ya">
                                </td>
                            </tr>
                            @endif
                        @endforeach
                        
                        @if($currentSemesterMataKuliah->isEmpty() && $existingKrs->where('ambil_mk', 'ya')->isEmpty())
                        <tr id="emptyRow">
                            <td colspan="{{ $isEditable ? '9' : '8' }}" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada mata kuliah tersedia untuk semester ini</p>
                                <p class="text-sm text-gray-400 mt-1">Hubungi admin untuk menambahkan mata kuliah</p>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Summary Footer --}}
            <div class="bg-gray-50 px-6 py-5 border-t-2 border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-8">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total Mata Kuliah Diambil:</p>
                            <p class="text-2xl font-bold text-gray-800" id="totalMk">0</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total SKS:</p>
                            <p class="text-2xl font-bold" id="totalSks">0</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Maksimal SKS:</p>
                            <p class="text-2xl font-bold text-gray-500">24</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" name="action" value="draft" class="px-6 py-3 font-bold rounded-lg transition shadow-md hover:shadow-lg flex items-center gap-2 text-white border {{ !$isEditable ? 'bg-maroon border-maroon cursor-not-allowed opacity-50' : 'bg-maroon border-maroon hover:bg-red-800' }}" @if(!$isEditable) disabled aria-disabled="true" @endif>
                            <i class="fas {{ !$isEditable ? 'fa-lock' : 'fa-save' }}"></i>
                            Simpan Draft
                        </button>
                        <button type="submit" name="action" value="submit" class="px-6 py-3 font-bold rounded-lg transition shadow-md hover:shadow-lg flex items-center gap-2 text-white {{ !$isEditable ? 'bg-maroon cursor-not-allowed opacity-50' : 'bg-maroon hover:bg-red-800' }}" id="submitBtn" @if(!$isEditable) disabled aria-disabled="true" @endif>
                            <i class="fas {{ !$isEditable ? 'fa-lock' : 'fa-paper-plane' }}"></i>
                            Submit 
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
        // Ambil Semua button: toggle all current-semester checkboxes
        const ambilSemuaBtn = document.getElementById('ambilSemuaBtn');
        if (ambilSemuaBtn) {
            ambilSemuaBtn.addEventListener('click', function () {
                const checkboxes = Array.from(document.querySelectorAll('.mk-checkbox'));
                if (checkboxes.length === 0) return;
                const anyUnchecked = checkboxes.some(cb => !cb.checked);
                checkboxes.forEach(cb => {
                    cb.checked = anyUnchecked;
                    const row = cb.closest('tr');
                    if (row) {
                        if (anyUnchecked) row.classList.add('bg-blue-50'); else row.classList.remove('bg-blue-50');
                    }
                });
                calculateTotal();
                ambilSemuaBtn.textContent = anyUnchecked ? 'Batal Ambil Semua' : 'Ambil Semua';
            });
        }
    function calculateTotal() {
        let totalSks = 0;
        let totalMk = 0;

        document.querySelectorAll('.mk-checkbox:checked').forEach(cb => {
            totalSks += parseInt(cb.dataset.sks) || 0;
            totalMk += 1;
        });

        document.querySelectorAll('#krsTableBody tr[data-additional="true"]').forEach(row => {
            totalSks += parseInt(row.querySelector('td:nth-child(4)')?.textContent.trim()) || 0;
            totalMk += 1;
        });

        document.getElementById('totalSks').textContent = totalSks;
        document.getElementById('totalMk').textContent = totalMk;

        const totalSksEl = document.getElementById('totalSks');
        totalSksEl.classList.remove('text-gray-800', 'text-red-600', 'text-green-600');
        if (totalSks > 24) totalSksEl.classList.add('text-red-600');
        else if (totalSks > 0) totalSksEl.classList.add('text-green-600');
        else totalSksEl.classList.add('text-gray-800');
    }

    // Checkbox handlers
    document.querySelectorAll('.mk-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            const row = this.closest('tr');
            if (this.checked) row.classList.add('bg-blue-50'); else row.classList.remove('bg-blue-50');
            calculateTotal();
        });
    });

    // Add additional mata kuliah
    document.getElementById('addMataKuliahBtn')?.addEventListener('click', function () {
        const select = document.getElementById('mataKuliahSelect');
        const opt = select.options[select.selectedIndex];
        if (!opt || !opt.value) { alert('Silakan pilih mata kuliah terlebih dahulu!'); return; }

        const mkId = opt.value;
        const kodeMk = opt.dataset.kode;
        const kodeId = opt.dataset.kodeId;
        const namaMk = opt.dataset.nama;
        const sks = parseInt(opt.dataset.sks) || 0;
        const semester = opt.dataset.semester || '-';
        const jenis = opt.dataset.jenis || '';
        const praktikum = opt.dataset.praktikum || 0;

        // SKS limit
        const currentSks = parseInt(document.getElementById('totalSks').textContent) || 0;
        if (currentSks + sks > 24) { alert('Total SKS akan melebihi batas maksimal (24 SKS)!'); return; }

        // Add row
        const tbody = document.getElementById('krsTableBody');
        const count = document.querySelectorAll('#krsTableBody tr:not(#emptyRow)').length;
        const praktikumBadge = praktikum && praktikum != '0' ? '<span class="inline-block ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-full">Praktikum</span>' : '';
        const jenisColors = {
            'wajib_nasional': 'bg-blue-100 text-blue-800',
            'wajib_prodi': 'bg-red-100 text-red-800',
            'pilihan': 'bg-purple-100 text-purple-800',
            'peminatan': 'bg-yellow-100 text-yellow-800'
        };
        const colorClass = jenisColors[jenis] || 'bg-gray-100 text-gray-800';

        const tr = document.createElement('tr');
        tr.setAttribute('data-additional', 'true');
        tr.setAttribute('data-mk-id', mkId);
        tr.className = 'hover:bg-gray-50 transition bg-green-50';
        tr.innerHTML = `
            <td class="px-4 py-4 text-sm text-gray-700">${count+1}</td>
            <td class="px-4 py-4 text-sm font-medium text-gray-800">${kodeMk}<span class="block text-xs text-green-600 mt-1">${kodeId.toUpperCase()} • Lintas Semester</span></td>
            <td class="px-4 py-4 text-sm text-gray-800">${namaMk}${praktikumBadge}</td>
            <td class="px-4 py-4 text-sm text-center font-semibold text-gray-800">${sks}</td>
            <td class="px-4 py-4 text-sm text-center text-gray-700">${semester}</td>
            <td class="px-4 py-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold ${colorClass}">${jenis.replace(/_/g,' ').replace(/\b\w/g,l=>l.toUpperCase())}</span></td>
            <td class="px-4 py-4 text-sm text-gray-700 text-center">-</td>
            <td class="px-4 py-4 text-sm text-center text-gray-600">-</td>
            <td class="px-4 py-4 text-center"><button type="button" class="remove-additional-btn px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition" data-mk-id="${mkId}"><i class="fas fa-trash"></i> Hapus</button><input type="hidden" name="mata_kuliah[${mkId}]" value="ya"></td>
        `;
        // remove emptyRow if present
        const empty = document.getElementById('emptyRow'); if (empty) empty.remove();
        tbody.appendChild(tr);
        opt.remove(); select.selectedIndex = 0;
        calculateTotal();

        tr.querySelector('.remove-additional-btn').addEventListener('click', function(){
            if (!confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?')) return;
            tr.remove();
            // add back simple option
            const sel = document.getElementById('mataKuliahSelect');
            const option = document.createElement('option'); option.value = mkId; option.textContent = `${kodeMk} - ${namaMk} (${sks} SKS)`; sel.appendChild(option);
            calculateTotal();
        });
    });

    // Remove buttons for kelas-based rows
    document.querySelectorAll('.remove-btn').forEach(btn=> btn.addEventListener('click', function(e){
        const kelasId = this.dataset.kelasId; if (!confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?')) return;
        const row = document.querySelector(`tr[data-kelas-id="${kelasId}"]`); if (row) row.remove(); calculateTotal();
    }));

    // Initial calculation
    calculateTotal();

    // Form submit validation
    document.getElementById('krsForm')?.addEventListener('submit', function(e){
        const totalSks = parseInt(document.getElementById('totalSks').textContent)||0;
        if (totalSks > 24) { e.preventDefault(); alert('Total SKS melebihi batas maksimal (24 SKS)!'); return false; }
        if (totalSks === 0) { e.preventDefault(); alert('Anda belum memilih mata kuliah!'); return false; }
        return true;
    });
});
</script>
@endpush
