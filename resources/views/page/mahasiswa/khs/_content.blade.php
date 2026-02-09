<div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-start justify-between gap-6">
        <div>
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">NILAI AKADEMIK</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan nilai akademik Anda</p>
        </div>
        <div class="ml-auto">
            <a href="{{ route('mahasiswa.nilai.print') }}" target="_blank" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-maroon dark:bg-red-600 text-white rounded-lg hover:bg-maroon-hover dark:hover:bg-red-700 transition-colors font-medium shadow-sm">
                <i class="fas fa-print text-sm"></i>
                <span>Cetak Rangkuman Nilai</span>
            </a>
        </div>
    </div>

    <div class="p-6 space-y-6">
        @php
            $all = collect($nilaiPerSemester)->flatten(1)->values();
            $totalSksCalc = $all->sum(function($krs) { return $krs->kelasMataKuliah->mataKuliah->sks ?? 0; });
            $totalMk = $all->count();
            $ipkVal = $ipk ?? 0;
        @endphp

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/50">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">KODE</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">MATAKULIAH</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">JENIS</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">SKS</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">NILAI</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">SEM</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($all as $i => $krs)
                        @php
                            $mataKuliah = $krs->kelasMataKuliah->mataKuliah ?? null;
                            $nilai = $krs->nilai;
                            $nilaiAngka = $nilai->nilai_akhir ?? 0;
                            $sks = $mataKuliah->sks ?? 0;
                            $bobot = (new \App\Http\Controllers\Mahasiswa\NilaiController)->getBobot($nilaiAngka ?: 0);

                            $jenis = $mataKuliah->jenis ?? ($krs->kelasMataKuliah->jenis ?? '-');
                            $sem = $mataKuliah->semester ?? ($krs->kelasMataKuliah->semester->nama_semester ?? '-');
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100 font-medium">{{ $mataKuliah->kode_mk ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $mataKuliah->nama_mk ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ strtoupper(str_replace('_', ' ', $jenis)) }}</td>
                            <td class="px-4 py-3 text-center text-gray-900 dark:text-gray-100 font-semibold">{{ $sks }}</td>
                            <td class="px-4 py-3 text-center text-gray-900 dark:text-gray-100 font-bold">{{ $nilaiAngka }}</td>
                            <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">{{ $sem }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-sm">
                    <div class="text-gray-700 dark:text-gray-300">
                        <span class="font-semibold">{{ $totalMk }}</span> Matakuliah
                    </div>
                    <div class="text-gray-700 dark:text-gray-300">
                        Total SKS: <span class="font-semibold">{{ $totalSksCalc }}</span>
                    </div>
                    <div class="text-gray-700 dark:text-gray-300">
                        IPK: <span class="font-bold text-maroon dark:text-red-400">{{ number_format($ipkVal, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
