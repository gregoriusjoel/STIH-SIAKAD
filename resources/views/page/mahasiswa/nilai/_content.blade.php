<div class="space-y-8">

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-4">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:justify-between">
            <div>
                <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">Cetak Nilai</h4>
                <p class="text-xs text-slate-400 font-semibold">Pilih semester untuk cetak rangkuman nilai</p>
            </div>
            <form action="{{ route('mahasiswa.nilai.print.rangkuman') }}" method="GET" target="_blank" data-no-loader class="flex items-center gap-2">
                <select name="semester"
                    class="h-10 px-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm font-semibold text-slate-600 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-maroon/20">
                    <option value="">Semua Semester</option>
                    @foreach(($semesterOptions ?? []) as $opt)
                        <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                    @endforeach
                </select>
                <button type="submit"
                    class="inline-flex items-center gap-2 h-10 px-4 bg-maroon text-white rounded-lg hover:bg-maroon-hover transition-colors font-semibold shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">print</span>
                    <span>Cetak</span>
                </button>
            </form>
        </div>
    </div>

    @php 
        // Helper function untuk mendapatkan warna badge berdasarkan grade (Versi Soft)
        function getGradeStyle($grade) {
            if (in_array($grade, ['A', 'A-'])) {
                return 'bg-green-50 text-green-600 border-green-100';
            } elseif (in_array($grade, ['B+', 'B', 'B-'])) {
                return 'bg-blue-50 text-blue-600 border-blue-100';
            } elseif (in_array($grade, ['C+', 'C', 'C-'])) {
                return 'bg-amber-50 text-amber-600 border-amber-100';
            } else {
                return 'bg-red-50 text-red-600 border-red-100';
            }
        }
    @endphp

    @if($nilaiPerSemester->isEmpty())
        <div class="bg-white dark:bg-slate-800 rounded-[32px] shadow-sm border border-slate-100 dark:border-slate-700/50 p-16 text-center">
            <div class="size-24 bg-slate-50 dark:bg-slate-900/50 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-4xl text-slate-300">inventory_2</span>
            </div>
            <h4 class="text-xl font-black text-slate-900 dark:text-white mb-2">Belum Ada Nilai</h4>
            <p class="text-slate-400 font-bold text-sm uppercase tracking-wider">Nilai akan muncul setelah dilakukannya penginputan nilai</p>
        </div>
    @else
        @foreach($nilaiPerSemester as $semesterNama => $nilaiList)
            @php
                $totalSKS = 0;
                $semesterNumber = null;
                
                if ($nilaiList && $nilaiList->count()) {
                    foreach ($nilaiList as $krs) {
                        $mataKuliah = $krs->kelasMataKuliah->mataKuliah ?? null;
                        $totalSKS += $mataKuliah->sks ?? 0;
                        if ($semesterNumber === null && $mataKuliah) {
                            $semesterNumber = $mataKuliah->semester ?? null;
                        }
                    }
                }
                $semesterLabel = $semesterNumber ? "Semester {$semesterNumber}" : $semesterNama;
            @endphp

            <div class="bg-white dark:bg-slate-800 rounded-[32px] shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                {{-- Semester Header --}}
                <div class="bg-primary px-8 py-5 border-b border-primary/5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-5">
                            <div class="size-14 bg-white/10 rounded-2xl border border-white/20 flex items-center justify-center text-white">
                                <span class="material-symbols-outlined text-2xl fill-current">auto_stories</span>
                            </div>
                            <div>
                                <h4 class="text-xl font-black text-white">{{ $semesterLabel }}</h4>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-[10px] font-black bg-white/20 text-white px-2 py-1 rounded-md uppercase tracking-wider">{{ $nilaiList->count() }} Mata Kuliah</span>
                                    <span class="size-1 bg-white/30 rounded-full"></span>
                                    <span class="text-[10px] font-black bg-white/10 text-white/80 px-2 py-1 rounded-md uppercase tracking-wider">{{ $totalSKS }} SKS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Course Table --}}
                <div class="p-4 md:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-start">
                        @foreach($nilaiList as $index => $krs)
                            @php
                                $mataKuliah = $krs->kelasMataKuliah->mataKuliah ?? null;
                                $nilai = $krs->nilai;
                                $nilaiAngka = $nilai->nilai_akhir ?? 0;
                                $grade = $nilai->grade ?? '-';
                                $gradeStyle = getGradeStyle($grade);
                                $kelas = $krs->kelas ?? $krs->kelasMataKuliah->kelas ?? null;
                                $bobotPenilaian = $kelas?->bobotPenilaian;
                            @endphp
                            
                            <div class="bg-white dark:bg-slate-900/50 rounded-[24px] border border-slate-100 dark:border-slate-700/50 hover:shadow-xl hover:shadow-primary/5 transition-all duration-300 group flex flex-col cursor-pointer select-none"
                                 x-data="{ expanded: false }"
                                 @click="expanded = !expanded">
                                
                                {{-- Card Content --}}
                                <div class="p-5 flex flex-col flex-1">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="size-7 bg-primary/5 text-primary rounded-lg flex items-center justify-center text-[10px] font-black">
                                                {{ $index + 1 }}
                                            </span>
                                            <span class="font-mono text-[9px] font-black tracking-widest text-slate-400 uppercase">
                                                {{ $mataKuliah->kode_mk ?? '-' }}
                                            </span>
                                        </div>
                                        <div class="size-7 rounded-lg group-hover:bg-slate-50 dark:group-hover:bg-slate-700 flex items-center justify-center transition-colors">
                                            <span class="material-symbols-outlined text-[18px] text-slate-400 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''">expand_more</span>
                                        </div>
                                    </div>

                                    <h5 class="text-lg font-black text-slate-900 dark:text-white leading-tight mb-4 min-h-[52px] line-clamp-2">
                                        {{ $mataKuliah->nama_mk ?? '-' }}
                                    </h5>

                                    <div class="mt-auto pt-4 border-t border-slate-100 dark:border-slate-800">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div>
                                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Nilai</p>
                                                    <p class="text-2xl font-black text-slate-900 dark:text-white tracking-tighter leading-none">{{ number_format($nilaiAngka, 0) }}</p>
                                                </div>
                                                <div class="h-8 w-px bg-slate-100 dark:bg-slate-700"></div>
                                                <div class="{{ $gradeStyle }} border px-3 py-1.5 rounded-lg flex items-center gap-2">
                                                    <span class="text-[10px] font-black uppercase tracking-widest opacity-60">Grade</span>
                                                    <span class="text-sm font-black leading-none">{{ $grade }}</span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">SKS</p>
                                                <p class="text-sm font-black text-slate-900 dark:text-white leading-none">{{ $mataKuliah->sks ?? 0 }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Details --}}
                                @if($bobotPenilaian || $krs->is_internship_conversion)
                                    <div x-show="expanded" x-collapse>
                                        <div class="px-5 pb-5 pt-1 border-t border-dashed border-slate-100 dark:border-slate-800">
                                            <p class="text-[9px] font-black text-primary uppercase tracking-[0.15em] mb-3 flex items-center gap-2">
                                                <span class="size-1 bg-primary rounded-full animate-pulse"></span>
                                                Komponen
                                                @if($krs->is_internship_conversion && !$bobotPenilaian)
                                                    <span class="ml-1 text-[8px] font-bold text-slate-400 normal-case tracking-normal">(Konversi Magang)</span>
                                                @endif
                                            </p>
                                            
                                            <div class="grid grid-cols-2 gap-2">
                                                @php
                                                    if ($bobotPenilaian) {
                                                        $components = [
                                                            ['label' => 'Part.', 'nilai' => $nilai->nilai_partisipatif ?? 0, 'bobot' => $bobotPenilaian->bobot_partisipatif],
                                                            ['label' => 'Proyek', 'nilai' => $nilai->nilai_proyek ?? 0, 'bobot' => $bobotPenilaian->bobot_proyek],
                                                            ['label' => 'Quiz', 'nilai' => $nilai->nilai_quiz ?? 0, 'bobot' => $bobotPenilaian->bobot_quiz],
                                                            ['label' => 'Tugas', 'nilai' => $nilai->nilai_tugas ?? 0, 'bobot' => $bobotPenilaian->bobot_tugas],
                                                            ['label' => 'UTS', 'nilai' => $nilai->nilai_uts ?? 0, 'bobot' => $bobotPenilaian->bobot_uts],
                                                            ['label' => 'UAS', 'nilai' => $nilai->nilai_uas ?? 0, 'bobot' => $bobotPenilaian->bobot_uas],
                                                        ];
                                                    } else {
                                                        // Konversi magang: semua komponen sama rata dengan nilai yang diinputkan
                                                        $nilaiInput = $nilaiAngka;
                                                        $components = [
                                                            ['label' => 'Part.', 'nilai' => $nilaiInput, 'bobot' => 16.67],
                                                            ['label' => 'Proyek', 'nilai' => $nilaiInput, 'bobot' => 16.67],
                                                            ['label' => 'Quiz', 'nilai' => $nilaiInput, 'bobot' => 16.67],
                                                            ['label' => 'Tugas', 'nilai' => $nilaiInput, 'bobot' => 16.67],
                                                            ['label' => 'UTS', 'nilai' => $nilaiInput, 'bobot' => 16.67],
                                                            ['label' => 'UAS', 'nilai' => $nilaiInput, 'bobot' => 16.65],
                                                        ];
                                                    }
                                                @endphp

                                                @foreach($components as $comp)
                                                    @if($comp['bobot'] > 0)
                                                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-2.5 border border-slate-100 dark:border-slate-800 flex flex-col justify-between">
                                                            <div class="flex items-center justify-between mb-1">
                                                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none">{{ $comp['label'] }}</p>
                                                                <span class="text-[9px] font-bold text-primary bg-primary/5 px-1 py-0.5 rounded leading-none">{{ number_format($comp['bobot'], 2) }}%</span>
                                                            </div>
                                                            <div class="flex items-baseline leading-none mt-1">
                                                                <span class="text-lg font-black text-slate-900 dark:text-white">{{ number_format($comp['nilai'], 0) }}</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>

                                            <div class="mt-3 flex items-center justify-between p-2.5 bg-slate-900 dark:bg-primary rounded-xl text-white">
                                                <div class="flex items-center gap-2">
                                                    <span class="material-symbols-outlined text-[16px]">workspace_premium</span>
                                                    <span class="text-[8px] font-black uppercase tracking-widest">Bobot</span>
                                                </div>
                                                <span class="text-sm font-black">{{ number_format($nilai->bobot ?? 0, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Footer Info --}}
                <div class="bg-slate-50 dark:bg-slate-900/50 px-8 py-4 border-t border-slate-100 dark:border-slate-800 flex flex-wrap gap-6 items-center">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Indeks Semester (IPS)</span>
                        <span class="text-sm font-black text-primary">{{ number_format($ipsPerSemester[$semesterNama]['ips'] ?? 0, 2) }}</span>
                    </div>
                    <div class="h-3 w-px bg-slate-300 hidden sm:block"></div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Total SKS Semester</span>
                        <span class="text-sm font-black text-slate-700 dark:text-slate-300">{{ $ipsPerSemester[$semesterNama]['sks'] ?? 0 }} SKS</span>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
</style>