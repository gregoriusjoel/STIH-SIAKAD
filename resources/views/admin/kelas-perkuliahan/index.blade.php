@extends('layouts.admin')
@section('title', 'Kelas Perkuliahan')
@section('page-title', 'Kelas Perkuliahan')
@section('content')

    <div x-data="{ showGenerateModal: false }">
        {{-- Header --}}
        <div class="mb-6 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-layer-group text-maroon mr-2"></i>Master Data Kelas Perkuliahan
                </h3>
                <p class="text-sm text-gray-600 mt-1">Kelola data kelas berdasarkan tingkat dan program studi. Format: <code class="px-1.5 py-0.5 bg-gray-100 rounded text-maroon font-semibold">[Tingkat][Kode Prodi][Kode Kelas]</code></p>
            </div>
            <div class="flex flex-wrap gap-2">

                <button @click="showGenerateModal = true" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition flex items-center gap-2">
                    <i class="fas fa-magic"></i> Generate Massal
                </button>
                <a href="{{ route('admin.kelas-perkuliahan.create') }}" class="px-4 py-2 bg-maroon text-white rounded-lg text-sm font-medium hover:bg-red-800 transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Tambah Manual
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('admin.kelas-perkuliahan.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kelas, kode prodi..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                </div>
                <div class="min-w-[160px]">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Prodi</label>
                    <select name="prodi_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                        <option value="">Semua Prodi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-[120px]">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tingkat</label>
                    <select name="tingkat" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                        <option value="">Semua</option>
                        @for($i = 1; $i <= 4; $i++)
                            <option value="{{ $i }}" {{ request('tingkat') == $i ? 'selected' : '' }}>Tingkat {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="min-w-[200px]">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tahun Akademik</label>
                    <select name="tahun_akademik_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                        <option value="">Semua</option>
                        @foreach($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ request('tahun_akademik_id') == $sem->id ? 'selected' : '' }}>{{ $sem->nama_semester }} {{ $sem->tahun_ajaran }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-maroon text-white rounded-lg text-sm font-medium hover:bg-red-800 transition">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.kelas-perkuliahan.index') }}" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-maroon text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nama Kelas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Tingkat</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Prodi</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Kode Kelas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Tahun Akademik</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($kelasPerkuliahans as $kp)
                            <tr class="hover:bg-blue-50 transition duration-200">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-blue-100 text-blue-800">
                                        <i class="fas fa-layer-group mr-1.5"></i>{{ $kp->nama_kelas }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
                                        Tingkat {{ $kp->tingkat }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $kp->prodi?->nama_prodi ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $kp->kode_prodi }}</div>
                                </td>
                                <td class="px-6 py-4 font-mono font-semibold text-gray-700">{{ $kp->kode_kelas }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if($kp->tahunAkademik)
                                        {{ $kp->tahunAkademik->nama_semester }} {{ $kp->tahunAkademik->tahun_ajaran }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.kelas-perkuliahan.show', $kp) }}" class="text-blue-600 hover:text-blue-800 transition" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.kelas-perkuliahan.edit', $kp) }}" class="text-yellow-600 hover:text-yellow-800 transition" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.kelas-perkuliahan.destroy', $kp) }}" class="inline" onsubmit="return confirm('Yakin hapus kelas {{ $kp->nama_kelas }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-3"></i>
                                    <p class="text-lg font-semibold">Belum ada data kelas perkuliahan</p>
                                    <p class="text-sm mt-2">Gunakan tombol "Tambah Manual" atau "Generate Massal" untuk menambahkan data.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($kelasPerkuliahans->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">{{ $kelasPerkuliahans->links() }}</div>
            @endif
        </div>

        {{-- Generate Modal --}}
        <div x-show="showGenerateModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-2" style="display: none;">
            <div @click.away="showGenerateModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-7xl max-h-[95vh] overflow-y-auto flex flex-col">
                <div class="bg-maroon p-4 text-white sticky top-0 z-10">
                    <h3 class="text-lg font-bold flex items-center"><i class="fas fa-magic mr-2"></i>Generate Kelas Massal</h3>
                    <p class="text-sm opacity-80 mt-1">Generate kelas perkuliahan secara otomatis berdasarkan program studi</p>
                </div>
                <form method="POST" action="{{ route('admin.kelas-perkuliahan.generate-bulk') }}" class="p-5 space-y-3 flex-1 overflow-y-auto">
                    @csrf
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Program Studi <span class="text-red-500">*</span></label>
                            <select name="prodi_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                                <option value="">Pilih Prodi</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tahun Akademik</label>
                            <select name="tahun_akademik_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                                <option value="">Tidak terikat</option>
                                @foreach($semesters as $sem)
                                    <option value="{{ $sem->id }}">{{ $sem->nama_semester }} {{ $sem->tahun_ajaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jumlah Tingkat <span class="text-red-500">*</span></label>
                            <input type="number" name="max_tingkat" value="4" min="1" max="8" required @change="updateTingkatCount" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                        </div>
                    </div>
                    <div x-data="{ mode: 'manual', maxPerKelas: 40 }">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Mode Generate <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 cursor-pointer text-sm" :class="mode === 'manual' ? 'bg-maroon/5 border-maroon' : 'hover:bg-gray-50'">
                                <input type="radio" name="mode" value="manual" x-model="mode" class="w-4 h-4 text-maroon">
                                <span class="font-medium text-gray-700">Manual</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 cursor-pointer text-sm" :class="mode === 'auto' ? 'bg-maroon/5 border-maroon' : 'hover:bg-gray-50'">
                                <input type="radio" name="mode" value="auto" x-model="mode" class="w-4 h-4 text-maroon">
                                <span class="font-medium text-gray-700">Otomatis (Per Tingkat)</span>
                            </label>
                        </div>
                        <div x-show="mode === 'manual'" class="space-y-2">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kelas per Tingkat <span class="text-red-500">*</span></label>
                                <input type="number" name="kelas_per_tingkat" value="1" min="1" max="10" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">Jumlah kelas akan sama untuk semua tingkat.</p>
                            </div>
                        </div>
                        <div x-show="mode === 'auto'" class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Maks Siswa/Kelas <span class="text-red-500">*</span></label>
                                <input type="number" name="max_students_per_class" x-model="maxPerKelas" min="1" max="100" value="40" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                            </div>
                            <div class="bg-green-50 rounded-lg p-3 text-sm text-green-800 mb-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Edit jumlah mahasiswa per tingkat di bawah
                            </div>
                            <div class="grid grid-cols-4 gap-3" id="tingkatContainer">
                                <!-- Dinamis form per tingkat akan ditambahkan di sini -->
                            </div>
                        </div>
                        <div x-data="{ overwrite: false }">
                            <label class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 border border-gray-200 cursor-pointer hover:bg-gray-100 transition text-sm">
                                <input type="checkbox" name="overwrite" x-model="overwrite" class="w-4 h-4 text-maroon rounded focus:ring-2 focus:ring-maroon">
                                <span class="font-medium text-gray-700">Timpa data yang sudah ada</span>
                            </label>
                            <div x-show="!overwrite" class="bg-blue-50 rounded-lg p-3 text-sm text-blue-800 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Hanya tambah</strong> data baru, yang ada dilewati.
                            </div>
                            <div x-show="overwrite" class="bg-red-50 rounded-lg p-3 text-sm text-red-800 mt-2">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Data lama akan <strong>dihapus</strong> & diganti!
                            </div>
                        </div>
                        <div id="summaryBox" class="bg-blue-50 rounded-lg p-3 text-sm text-blue-800" style="display: none;">
                            <i class="fas fa-info-circle mr-1"></i>
                            <span id="calculationSummary">-</span>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-3 border-t sticky bottom-0 bg-white">
                        <button type="button" @click="showGenerateModal = false" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-maroon text-white rounded-lg text-sm font-medium hover:bg-red-800 transition flex items-center gap-2">
                            <i class="fas fa-magic"></i> Generate
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form[action*="generate-bulk"]');
                if (!form) return;

                const maxTingkatInput = form.querySelector('input[name="max_tingkat"]');
                const modeInputs = form.querySelectorAll('input[name="mode"]');
                const container = document.getElementById('tingkatContainer');
                const summary = document.getElementById('calculationSummary');
                const summaryBox = document.getElementById('summaryBox');
                const maxStudentsInput = form.querySelector('input[name="max_students_per_class"]');

                function getSemesterLabel(tingkat) {
                    const semesterAwal = (tingkat * 2) - 1;
                    const semesterAkhir = tingkat * 2;
                    return `Semester ${semesterAwal} & ${semesterAkhir}`;
                }

                function updateTingkatForm() {
                    const maxTingkat = parseInt(maxTingkatInput.value) || 4;
                    container.innerHTML = '';
                    const maxPerKelas = parseInt(maxStudentsInput.value) || 40;

                    for (let i = 1; i <= maxTingkat; i++) {
                        const div = document.createElement('div');
                        div.className = 'p-3 rounded-lg border border-gray-200 bg-gray-50';

                        div.innerHTML = `
                            <div class="flex items-center gap-2 mb-2">
                                <label class="text-sm font-semibold text-gray-700 flex-1">
                                    Tingkat ${i}
                                    <span class="block text-xs font-normal text-gray-500">(${getSemesterLabel(i)})</span>
                                </label>
                                <span class="text-sm font-medium text-maroon bg-white px-2 py-1 rounded" data-kelas-count="0">0 Kelas</span>
                            </div>
                            <input type="number" name="siswa_tingkat_${i}" placeholder="Siswa Tingkat ${i}" value="0" min="0" max="10000" onfocus="this.select()" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-maroon focus:border-transparent siswa-input" data-tingkat="${i}">
                        `;
                        container.appendChild(div);
                    }

                    // Attach event listeners
                    container.querySelectorAll('.siswa-input').forEach(input => {
                        input.addEventListener('input', updateCalculation);
                    });
                    maxStudentsInput.addEventListener('input', updateCalculation);

                    // Trigger calculation to show initial values
                    updateCalculation();
                }

                function updateCalculation() {
                    const maxPerKelas = parseInt(maxStudentsInput.value) || 40;
                    let totalKelas = 0;
                    const maxTingkat = parseInt(maxTingkatInput.value) || 4;
                    let breakdown = [];

                    for (let i = 1; i <= maxTingkat; i++) {
                        const siswaInput = form.querySelector(`input[name="siswa_tingkat_${i}"]`);
                        const siswa = parseInt(siswaInput.value) || 0;
                        const kelasNeeded = siswa > 0 ? Math.ceil(siswa / maxPerKelas) : 0;
                        totalKelas += kelasNeeded;

                        const span = siswaInput.parentElement.querySelector('[data-kelas-count]');
                        if (span) {
                            span.textContent = `${kelasNeeded} Kelas`;
                        }

                        if (siswa > 0) {
                            breakdown.push(`Tingkat ${i}: ${kelasNeeded} Kelas`);
                        }
                    }

                    const breakdownText = breakdown.length > 0 ? breakdown.join(', ') : '-';
                    summary.innerHTML = `<strong>Total ${totalKelas} kelas:</strong> ${breakdownText}`;
                    summaryBox.style.display = 'block';
                }

                // Event listener untuk perubahan max_tingkat
                maxTingkatInput.addEventListener('change', updateTingkatForm);

                // Inisialisasi form
                updateTingkatForm();
            });
        </script>


    </div>
@endsection
