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
                <p class="text-gray-600">Semester: <span class="font-semibold">{{ $semesterAktif->nama_semester ?? 'Tidak ada semester aktif' }}</span></p>
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
                    <li>• Secara default, semua mata kuliah akan terpilih <strong>Ya</strong></li>
                    <li>• Maksimal beban SKS per semester: <strong>24 SKS</strong></li>
                    <li>• Klik <strong>Simpan Draft</strong> untuk menyimpan tanpa mengajukan</li>
                    <li>• Klik <strong>Ajukan KRS</strong> untuk mengirimkan ke dosen wali</li>
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
                <p class="text-sm text-green-800">KRS Anda telah disetujui oleh dosen wali. Anda tidak dapat mengubah KRS yang sudah disetujui.</p>
            </div>
        </div>
    </div>
    @endif

    {{-- KRS Form --}}
    <form action="{{ route('mahasiswa.krs.store') }}" method="POST" id="krsForm">
        @csrf
        
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            {{-- Table Header --}}
            <div class="bg-gradient-to-r from-maroon to-maroon-hover text-white px-6 py-4">
                <h3 class="text-xl font-bold">Daftar Mata Kuliah Tersedia</h3>
            </div>

            {{-- Table Content --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">No</th>
                            <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Kode MK</th>
                            <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Mata Kuliah</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-700">SKS</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-700">Semester</th>
                            <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Dosen</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-700">Kelas</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-700">Jadwal</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-700">Ambil?</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($availableKelas as $index => $kelas)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                            <td class="px-4 py-4 text-sm font-medium text-gray-800">{{ $kelas->mataKuliah->kode_mk ?? '-' }}</td>
                            <td class="px-4 py-4 text-sm text-gray-800">{{ $kelas->mataKuliah->nama_mk ?? '-' }}</td>
                            <td class="px-4 py-4 text-sm text-center font-semibold text-gray-800">{{ $kelas->mataKuliah->sks ?? 0 }}</td>
                            <td class="px-4 py-4 text-sm text-center text-gray-700">{{ $kelas->mataKuliah->semester ?? '-' }}</td>
                            <td class="px-4 py-4 text-sm text-gray-700">{{ $kelas->dosen->user->name ?? '-' }}</td>
                            <td class="px-4 py-4 text-sm text-center font-medium text-gray-800">{{ $kelas->nama_kelas }}</td>
                            <td class="px-4 py-4 text-sm text-gray-600">
                                @if($kelas->jadwal)
                                    {{ $kelas->jadwal->hari }}, {{ substr($kelas->jadwal->jam_mulai, 0, 5) }} - {{ substr($kelas->jadwal->jam_selesai, 0, 5) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    // Cek apakah kelas ini sudah ada di KRS
                                    $existingKrs = $existingKrsData->firstWhere('kelas_mata_kuliah_id', $kelas->id);
                                    $selectedValue = $existingKrs ? $existingKrs->ambil_mk : 'ya';
                                    $isDisabled = $statusKrs === 'diajukan' || $statusKrs === 'approved';
                                @endphp
                                
                                <div class="flex justify-center">
                                    <select 
                                        name="kelas[{{ $kelas->id }}]" 
                                        class="krs-select px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent text-sm font-medium
                                        @if($selectedValue === 'ya') bg-green-50 text-green-700 border-green-300
                                        @else bg-red-50 text-red-700 border-red-300
                                        @endif"
                                        data-sks="{{ $kelas->mataKuliah->sks ?? 0 }}"
                                        @if($isDisabled) disabled @endif
                                    >
                                        <option value="ya" @if($selectedValue === 'ya') selected @endif>Ya</option>
                                        <option value="tidak" @if($selectedValue === 'tidak') selected @endif>Tidak</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                <p>Tidak ada mata kuliah yang tersedia untuk semester ini</p>
                            </td>
                        </tr>
                        @endforelse
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

                    @if($statusKrs !== 'diajukan' && $statusKrs !== 'approved')
                    <div class="flex gap-3">
                        <button type="submit" name="action" value="draft" class="px-6 py-3 bg-gray-600 text-white font-bold rounded-lg hover:bg-gray-700 transition shadow-md hover:shadow-lg flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            Simpan Draft
                        </button>
                        <button type="submit" name="action" value="submit" class="px-6 py-3 bg-maroon text-white font-bold rounded-lg hover:bg-maroon-hover transition shadow-md hover:shadow-lg flex items-center gap-2" id="submitBtn">
                            <i class="fas fa-paper-plane"></i>
                            Ajukan KRS
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    function calculateTotal() {
        let totalSks = 0;
        let totalMk = 0;

        document.querySelectorAll('.krs-select').forEach(select => {
            if (select.value === 'ya') {
                const sks = parseInt(select.getAttribute('data-sks')) || 0;
                totalSks += sks;
                totalMk += 1;
                
                // Change styling based on selection
                select.classList.remove('bg-red-50', 'text-red-700', 'border-red-300');
                select.classList.add('bg-green-50', 'text-green-700', 'border-green-300');
            } else {
                select.classList.remove('bg-green-50', 'text-green-700', 'border-green-300');
                select.classList.add('bg-red-50', 'text-red-700', 'border-red-300');
            }
        });

        document.getElementById('totalSks').textContent = totalSks;
        document.getElementById('totalMk').textContent = totalMk;

        // Update color based on SKS limit
        const totalSksEl = document.getElementById('totalSks');
        if (totalSks > 24) {
            totalSksEl.classList.remove('text-gray-800', 'text-green-600');
            totalSksEl.classList.add('text-red-600');
        } else if (totalSks > 0) {
            totalSksEl.classList.remove('text-gray-800', 'text-red-600');
            totalSksEl.classList.add('text-green-600');
        } else {
            totalSksEl.classList.remove('text-red-600', 'text-green-600');
            totalSksEl.classList.add('text-gray-800');
        }
    }

    // Add event listeners to all selects
    document.querySelectorAll('.krs-select').forEach(select => {
        select.addEventListener('change', calculateTotal);
    });

    // Validate form submission
    document.getElementById('krsForm')?.addEventListener('submit', function(e) {
        const action = e.submitter?.value;
        
        if (action === 'submit') {
            const totalSks = parseInt(document.getElementById('totalSks').textContent);
            
            if (totalSks > 24) {
                e.preventDefault();
                alert('Total SKS melebihi batas maksimal (24 SKS)! Silakan kurangi mata kuliah yang diambil.');
                return false;
            }
            
            if (totalSks === 0) {
                e.preventDefault();
                alert('Anda belum memilih mata kuliah apapun!');
                return false;
            }

            if (!confirm(`Apakah Anda yakin ingin mengajukan KRS dengan total ${totalSks} SKS?`)) {
                e.preventDefault();
                return false;
            }
        }
    });

    // Calculate initial totals on page load
    calculateTotal();
</script>
@endpush
