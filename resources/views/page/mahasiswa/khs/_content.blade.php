<div class="bg-bg-card rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-border-color flex items-start justify-between gap-6">
        <div>
            <h3 class="text-lg font-bold text-text-primary">NILAI AKADEMIK</h3>
            <p class="text-sm text-text-secondary">Ringkasan nilai akademik Anda</p>
        </div>
        <div class="ml-auto">
            <a href="{{ route('mahasiswa.nilai.print') }}" target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2 h-10 bg-maroon text-white rounded-lg hover:bg-maroon-hover transition-colors font-medium shadow-sm">
                <span class="material-symbols-outlined text-[18px]">print</span>
                <span>Cetak</span>
            </a>
        </div>
    </div>

    <div class="p-6 space-y-6">
        @php
            $all = collect($nilaiPerSemester)->flatten(1)->values();
            $totalSksCalc = $all->sum(function ($krs) {
                return $krs->kelasMataKuliah->mataKuliah->sks ?? 0; });
            $totalMk = $all->count();
            $ipkVal = $ipk ?? 0;
        @endphp

        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px] text-sm">
                <thead>
                    <tr class="bg-bg-hover">
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-text-secondary uppercase tracking-wider">
                            KODE</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-text-secondary uppercase tracking-wider">
                            MATAKULIAH</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-text-secondary uppercase tracking-wider">
                            JENIS</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-text-secondary uppercase tracking-wider">
                            SKS</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-text-secondary uppercase tracking-wider">
                            NILAI</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-text-secondary uppercase tracking-wider">
                            SEM</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-color">
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
                        <tr class="hover:bg-bg-hover transition-colors">
                            <td class="px-4 py-3 text-text-primary font-medium">{{ $mataKuliah->kode_mk ?? '-' }}</td>
                            <td class="px-4 py-3 text-text-primary">{{ $mataKuliah->nama_mk ?? '-' }}</td>
                            <td class="px-4 py-3 text-text-secondary">{{ strtoupper(str_replace('_', ' ', $jenis)) }}</td>
                            <td class="px-4 py-3 text-center text-text-primary font-semibold">{{ $sks }}</td>
                            <td class="px-4 py-3 text-center text-text-primary font-bold">{{ $nilaiAngka }}</td>
                            <td class="px-4 py-3 text-center text-text-secondary">{{ $sem }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 pt-4 border-t border-border-color">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-sm">
                    <div class="text-text-secondary">
                        <span class="font-semibold">{{ $totalMk }}</span> Matakuliah
                    </div>
                    <div class="text-text-secondary">
                        Total SKS: <span class="font-semibold">{{ $totalSksCalc }}</span>
                    </div>
                    <div class="text-text-secondary">
                        IPK: <span class="font-bold text-maroon">{{ number_format($ipkVal, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>