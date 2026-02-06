{{-- Generator Partial: used in generator page and jadwal index --}}

@php
    $activeSemester = \App\Models\Semester::where('status', 'aktif')->first()
        ?? \App\Models\Semester::where('is_active', true)->first();
@endphp

<div x-data="{}" x-cloak class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-maroon text-white flex items-center justify-between">
        <div class="font-bold text-white text-xl flex items-center gap-3">
            <div class="p-2 bg-white/10 rounded-lg backdrop-blur-sm">
                <i class="fas fa-magic"></i>
            </div>
            Auto Generate Jadwal
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.jadwal_admin_approval.index') }}" class="px-4 py-2 bg-white hover:bg-gray-100 text-maroon rounded-lg font-semibold transition-all duration-200 shadow-sm inline-flex items-center">
                <i class="fas fa-eye mr-2"></i>Lihat Data Dosen
            </a>
            <button @click="$refs.genModal.classList.remove('hidden'); $refs.genModal.style.display = 'flex'" 
                class="px-4 py-2 bg-white hover:bg-gray-100 text-maroon rounded-lg font-semibold transition-all duration-200 shadow-sm">
                <i class="fas fa-plus mr-2"></i>Generate Jadwal
            </button>
        </div>
    </div>

    {{-- Statistics + Recent Proposals (condensed) --}}
    <div class="p-6 bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-700/50 dark:to-blue-900/20 border-b border-gray-200 dark:border-gray-700">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-blue-100 dark:border-blue-900/30">
                <div class="text-sm text-gray-600 dark:text-gray-400">Total Proposal</div>
                <div class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $statistics['total_proposals'] ?? 0 }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-yellow-100 dark:border-yellow-900/30">
                <div class="text-sm text-gray-600 dark:text-gray-400">Pending Dosen</div>
                <div class="text-lg font-bold text-yellow-600 dark:text-yellow-400">{{ $statistics['pending_dosen'] ?? 0 }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-green-100 dark:border-green-900/30">
                <div class="text-sm text-gray-600 dark:text-gray-400">Approved</div>
                <div class="text-lg font-bold text-green-600 dark:text-green-400">{{ $statistics['approved_admin'] ?? 0 }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-red-100 dark:border-red-900/30">
                <div class="text-sm text-gray-600 dark:text-gray-400">Rejected</div>
                <div class="text-lg font-bold text-red-600 dark:text-red-400">{{ $statistics['rejected'] ?? 0 }}</div>
            </div>
        </div>

        @if($jadwalProposals->count())
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h4 class="font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2"><i class="fas fa-history text-blue-600 dark:text-blue-400"></i>Proposals Terbaru</h4>
                </div>
                <div class="max-h-48 overflow-y-auto px-4 py-2">
                    @foreach($jadwalProposals as $proposal)
                        <div class="py-2 border-b border-gray-100 dark:border-gray-700 last:border-b-0 flex items-center justify-between">
                            <div>
                                <div class="font-medium text-sm text-gray-900 dark:text-gray-100">{{ $proposal->mataKuliah->nama_mk ?? ($proposal->mataKuliah->nama ?? '') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $proposal->hari }} {{ substr($proposal->jam_mulai,0,5) }} - {{ substr($proposal->jam_selesai,0,5) }} • {{ $proposal->ruangan }}</div>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                @if(in_array($proposal->status, ['pending_dosen']))
                                    <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 rounded-full">Menunggu Dosen</span>
                                @elseif(in_array($proposal->status, ['approved_dosen','pending_admin']))
                                    <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full">Menunggu Admin</span>
                                @elseif($proposal->status === 'approved_admin')
                                    <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full">Disetujui</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 rounded-full">Ditolak</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Generate Modal (simple) --}}
    {{-- Generate Modal (Improved) --}}
    <div x-ref="genModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="display: none;" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="$refs.genModal.classList.add('hidden'); $refs.genModal.style.display = 'none';"></div>

        {{-- Modal Content --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-lg w-full z-10 overflow-hidden transform transition-all scale-100 mx-4">
            {{-- Header --}}
            <div class="bg-maroon px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-magic text-white/80"></i> Generate Jadwal Kuliah
                </h3>
                <button type="button" @click="$refs.genModal.classList.add('hidden'); $refs.genModal.style.display = 'none';" class="text-white/70 hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.jadwal_generator.auto_generate') }}" method="POST">
                @csrf
                <div class="p-6 space-y-5">
                    {{-- Alert Info --}}
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-900/30 text-blue-700 dark:text-blue-300 px-4 py-3 rounded-lg text-sm flex gap-3 items-start">
                        <i class="fas fa-info-circle mt-0.5 text-blue-500 dark:text-blue-400"></i>
                        <div>
                            <span class="font-semibold block mb-0.5">Automated Generation</span>
                            Sistem akan membuat jadwal otomatis berdasarkan data semester dan tahun ajaran yang dipilih.
                        </div>
                    </div>

                    {{-- Semester --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Semester</label>
                        <div class="relative">
                            <i class="fas fa-adjust absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"></i>
                            <select name="semester" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-maroon-500 outline-none transition-all text-sm" required>
                                <option value="">Pilih Semester</option>
                                <option value="Ganjil" {{ (old('semester', $activeSemester?->nama_semester ?? '') == 'Ganjil') ? 'selected' : '' }}>Ganjil</option>
                                <option value="Genap" {{ (old('semester', $activeSemester?->nama_semester ?? '') == 'Genap') ? 'selected' : '' }}>Genap</option>
                            </select>
                        </div>
                    </div>

                    {{-- Tahun Ajaran --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Tahun Ajaran</label>
                        <div class="relative">
                            <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"></i>
                            <select name="tahun_ajaran" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-maroon-500 outline-none transition-all text-sm" required>
                                <option value="">Pilih Tahun Ajaran</option>
                                @php
                                    $currentYear = date('Y');
                                    $activeTA = $activeSemester?->tahun_ajaran ?? ($currentYear . '/' . ($currentYear+1));
                                    for ($year = $currentYear - 2; $year <= $currentYear + 3; $year++) {
                                        $ta = $year . '/' . ($year + 1);
                                        $selected = ($ta === $activeTA) ? 'selected' : '';
                                        echo "<option value=\"$ta\" $selected>$ta</option>";
                                    }
                                @endphp
                            </select>
                        </div>
                    </div>

                    {{-- Checkbox --}}
                    <div class="flex items-start gap-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-100 dark:border-yellow-900/30">
                        <div class="flex h-5 items-center">
                            <input type="checkbox" name="overwrite_existing" value="1" id="overwrite_existing" 
                                class="h-4 w-4 text-maroon border-gray-300 dark:border-gray-600 rounded focus:ring-maroon-500">
                        </div>
                        <label for="overwrite_existing" class="text-sm text-yellow-800 dark:text-yellow-400 cursor-pointer select-none">
                            <span class="font-semibold block text-yellow-900 dark:text-yellow-200">Timpa Jadwal Lama?</span>
                            Centang ini jika ingin menghapus semua jadwal yang sudah ada untuk periode ini sebelum generate ulang.
                        </label>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 rounded-b-xl">
                    <button type="button" @click="$refs.genModal.classList.add('hidden'); $refs.genModal.style.display = 'none';" 
                        class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-colors text-sm">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-6 py-2.5 bg-maroon text-white font-semibold rounded-lg hover:bg-maroon-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-maroon-500 shadow-md hover:shadow-lg transition-all text-sm flex items-center gap-2">
                        <i class="fas fa-cogs"></i> Generate Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Quick handlers for generator partial
document.addEventListener('click', function(e) {
    // placeholder for dynamic handlers if needed
});
</script>
@endpush
