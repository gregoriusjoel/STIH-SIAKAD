<div class="space-y-6">

    @php 
        // Helper function untuk mendapatkan warna badge berdasarkan grade
        function getGradeColor($grade) {
            if (in_array($grade, ['A', 'A-'])) {
                return 'bg-gradient-to-r from-green-500 to-green-600 text-white';
            } elseif (in_array($grade, ['B+', 'B', 'B-'])) {
                return 'bg-gradient-to-r from-blue-500 to-blue-600 text-white';
            } elseif (in_array($grade, ['C+', 'C', 'C-'])) {
                return 'bg-gradient-to-r from-yellow-500 to-orange-500 text-white';
            } else {
                return 'bg-gradient-to-r from-red-500 to-red-600 text-white';
            }
        }
    @endphp

    @if($nilaiPerSemester->isEmpty())
        <div class="bg-bg-card rounded-2xl shadow-lg overflow-hidden border border-border-color">
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-bg-hover rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-3xl text-text-muted"></i>
                </div>
                <p class="text-text-muted font-medium">Belum ada nilai</p>
                <p class="text-sm text-text-muted/80 mt-1">Nilai akan muncul setelah dosen menginput nilai</p>
            </div>
        </div>
    @else
        @foreach($nilaiPerSemester as $semesterNama => $nilaiList)
            @php
                // Calculate semester statistics
                $totalSKS = 0;
                $semesterNumber = null;
                
                if ($nilaiList && $nilaiList->count()) {
                    foreach ($nilaiList as $krs) {
                        $mataKuliah = $krs->kelasMataKuliah->mataKuliah ?? null;
                        $totalSKS += $mataKuliah->sks ?? 0;
                        
                        // Get semester number from first mata kuliah
                        if ($semesterNumber === null && $mataKuliah) {
                            $semesterNumber = $mataKuliah->semester ?? null;
                        }
                    }
                }
                
                // Display semester label
                $semesterLabel = $semesterNumber ? "Semester {$semesterNumber}" : $semesterNama;
            @endphp

            <div class="bg-bg-card rounded-2xl shadow-lg overflow-hidden border border-border-color">
                {{-- Semester Header --}}
                <div class="bg-maroon dark:bg-red-900 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book-open text-white text-lg"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-white">{{ $semesterLabel }}</h4>
                                <p class="text-sm text-white/80">{{ $nilaiList->count() }} Mata Kuliah • {{ $totalSKS }} SKS</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-white">{{ $nilaiList->count() }}</div>
                            <div class="text-xs text-white/80">Courses</div>
                        </div>
                    </div>
                </div>



                {{-- Course Cards Grid --}}
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($nilaiList as $index => $krs)
                            @php
                                $mataKuliah = $krs->kelasMataKuliah->mataKuliah ?? null;
                                $nilai = $krs->nilai;
                                $nilaiAngka = $nilai->nilai_akhir ?? 0;
                                $grade = $nilai->grade ?? '-';
                                $gradeColor = getGradeColor($grade);
                                
                                // Get bobot penilaian from kelas
                                $kelas = $krs->kelas ?? $krs->kelasMataKuliah->kelas ?? null;
                                $bobotPenilaian = $kelas?->bobotPenilaian;
                            @endphp
                            
                            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:scale-[1.02] transition-all duration-200"
                                 x-data="{ expanded: false }">
                                {{-- Compact Card (Collapsed) --}}
                                <div class="p-4 cursor-pointer" @click="expanded = !expanded">
                                    {{-- Header: Number + Code --}}
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded bg-maroon/10 dark:bg-red-900/20 text-maroon dark:text-red-400 font-bold text-xs">
                                                {{ $index + 1 }}
                                            </span>
                                            <span class="text-base font-mono font-semibold text-text-secondary">
                                                {{ $mataKuliah->kode_mk ?? '-' }}
                                            </span>
                                        </div>
                                        <i class="fas fa-chevron-down text-text-muted text-xs transition-transform duration-200"
                                           :class="expanded ? 'rotate-180' : ''"></i>
                                    </div>
                                    
                                    {{-- Course Name --}}
                                    <h5 class="text-lg font-semibold text-text-primary mb-1 line-clamp-2 min-h-[2.5rem]">
                                        {{ $mataKuliah->nama_mk ?? '-' }}
                                    </h5>
                                    
                                    {{-- SKS --}}
                                    <p class="text-xs text-text-muted mb-3">
                                        <i class="fas fa-book text-[10px]"></i> {{ $mataKuliah->sks ?? 0 }} SKS
                                    </p>
                                    
                                    {{-- Score & Grade Badge (Bottom Right Corner) --}}
                                    <div class="flex justify-end items-end mt-auto pt-4">
                                        <div class="text-right mr-3">
                                            <div class="text-3xl font-bold text-text-primary leading-none">
                                                {{ number_format($nilaiAngka, 0) }}
                                            </div>
                                            <div class="text-[10px] text-text-muted uppercase tracking-wide">Nilai</div>
                                        </div>
                                        <div class="relative">
                                            <div class="absolute inset-0 {{ $gradeColor }} rounded-lg blur-sm opacity-40"></div>
                                            <div class="relative {{ $gradeColor }} rounded-lg px-3 py-2 shadow-md">
                                                <span class="text-xl font-black">{{ $grade }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Expandable Detail Section --}}
                                @if($bobotPenilaian)
                                    <div x-show="expanded" 
                                         x-collapse
                                         class="border-t border-border-color bg-bg-hover/50">
                                        <div class="p-4 space-y-3">
                                            <h6 class="text-xs font-bold text-text-secondary uppercase tracking-wide mb-3">
                                                <i class="fas fa-clipboard-list mr-1 text-maroon"></i>
                                                Komponen Penilaian
                                            </h6>
                                            
                                            {{-- Component Grid --}}
                                            <div class="grid grid-cols-2 gap-2">
                                                @if($bobotPenilaian->bobot_partisipatif > 0)
                                                    <div class="bg-bg-card rounded p-2 border border-border-color">
                                                        <div class="text-[10px] text-text-muted mb-1">Partisipatif</div>
                                                        <div class="flex items-baseline justify-between">
                                                            <span class="text-sm font-bold text-text-primary">{{ number_format($nilai->nilai_partisipatif ?? 0, 0) }}</span>
                                                            <span class="text-[10px] text-text-muted">{{ $bobotPenilaian->bobot_partisipatif }}%</span>
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                @if($bobotPenilaian->bobot_proyek > 0)
                                                    <div class="bg-bg-card rounded p-2 border border-border-color">
                                                        <div class="text-[10px] text-text-muted mb-1">Proyek</div>
                                                        <div class="flex items-baseline justify-between">
                                                            <span class="text-sm font-bold text-text-primary">{{ number_format($nilai->nilai_proyek ?? 0, 0) }}</span>
                                                            <span class="text-[10px] text-text-muted">{{ $bobotPenilaian->bobot_proyek }}%</span>
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                @if($bobotPenilaian->bobot_quiz > 0)
                                                    <div class="bg-bg-card rounded p-2 border border-border-color">
                                                        <div class="text-[10px] text-text-muted mb-1">Quiz</div>
                                                        <div class="flex items-baseline justify-between">
                                                            <span class="text-sm font-bold text-text-primary">{{ number_format($nilai->nilai_quiz ?? 0, 0) }}</span>
                                                            <span class="text-[10px] text-text-muted">{{ $bobotPenilaian->bobot_quiz }}%</span>
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                @if($bobotPenilaian->bobot_tugas > 0)
                                                    <div class="bg-bg-card rounded p-2 border border-border-color">
                                                        <div class="text-[10px] text-text-muted mb-1">Tugas</div>
                                                        <div class="flex items-baseline justify-between">
                                                            <span class="text-sm font-bold text-text-primary">{{ number_format($nilai->nilai_tugas ?? 0, 0) }}</span>
                                                            <span class="text-[10px] text-text-muted">{{ $bobotPenilaian->bobot_tugas }}%</span>
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                @if($bobotPenilaian->bobot_uts > 0)
                                                    <div class="bg-bg-card rounded p-2 border border-border-color">
                                                        <div class="text-[10px] text-text-muted mb-1">UTS</div>
                                                        <div class="flex items-baseline justify-between">
                                                            <span class="text-sm font-bold text-text-primary">{{ number_format($nilai->nilai_uts ?? 0, 0) }}</span>
                                                            <span class="text-[10px] text-text-muted">{{ $bobotPenilaian->bobot_uts }}%</span>
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                @if($bobotPenilaian->bobot_uas > 0)
                                                    <div class="bg-bg-card rounded p-2 border border-border-color">
                                                        <div class="text-[10px] text-text-muted mb-1">UAS</div>
                                                        <div class="flex items-baseline justify-between">
                                                            <span class="text-sm font-bold text-text-primary">{{ number_format($nilai->nilai_uas ?? 0, 0) }}</span>
                                                            <span class="text-[10px] text-text-muted">{{ $bobotPenilaian->bobot_uas }}%</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            
                                            {{-- Final Summary --}}
                                            <div class="mt-3 pt-3 border-t border-border-color">
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-text-secondary">Nilai Akhir</span>
                                                    <span class="font-bold text-maroon">{{ number_format($nilaiAngka, 0) }}</span>
                                                </div>
                                                <div class="flex items-center justify-between text-xs mt-1">
                                                    <span class="text-text-secondary">Grade Point</span>
                                                    <span class="font-bold text-maroon">{{ number_format($nilai->bobot ?? 0, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>