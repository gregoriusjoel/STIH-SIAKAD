<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex items-start justify-between gap-6">
        <div>
            <h3 class="text-lg font-bold text-gray-800">NILAI AKADEMIK</h3>
            <p class="text-sm text-gray-500">Ringkasan nilai akademik Anda</p>
        </div>
        <div class="ml-auto">
            <a href="{{ route('mahasiswa.nilai.print') }}" target="_blank" class="px-4 py-2 bg-maroon text-white rounded-lg hover:bg-maroon-hover">Cetak Rangkuman Nilai</a>
        </div>
    </div>

    <div class="p-6 space-y-6">
        @php
            $all = collect($nilaiPerSemester)->flatten(1)->values();
            $totalSksCalc = $all->sum(function($krs) { return $krs->kelasMataKuliah->mataKuliah->sks ?? 0; });
            $totalMk = $all->count();
            $ipkVal = $ipk ?? 0;
        @endphp

        <div class="overflow-x-auto mt-2">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-3 py-2 text-left">KODE</th>
                        <th class="px-3 py-2 text-left">MATAKULIAH</th>
                        <th class="px-3 py-2 text-left">JENIS</th>
                        <th class="px-3 py-2 text-center">SKS</th>
                        <th class="px-3 py-2 text-center">NILAI</th>
                        <th class="px-3 py-2 text-center">SEM</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($all as $i => $krs)
                        @php
                            $mataKuliah = $krs->kelasMataKuliah->mataKuliah ?? null;
                            $nilai = $krs->nilai;
                            $nilaiAngka = $nilai->nilai ?? '-';
                            $sks = $mataKuliah->sks ?? 0;
                            $bobot = (new \App\Http\Controllers\Mahasiswa\NilaiController)->getBobot($nilaiAngka ?: 0);

                            $jenis = $mataKuliah->jenis ?? ($krs->kelasMataKuliah->jenis ?? '-');
                            $sem = $mataKuliah->semester ?? ($krs->kelasMataKuliah->semester->nama_semester ?? '-');
                        @endphp
                        <tr>
                            <td class="border px-3 py-2">{{ $mataKuliah->kode_mk ?? '-' }}</td>
                            <td class="border px-3 py-2">{{ $mataKuliah->nama_mk ?? '-' }}</td>
                            <td class="border px-3 py-2">{{ $jenis }}</td>
                            <td class="border px-3 py-2 text-center">{{ $sks }}</td>
                            <td class="border px-3 py-2 text-center">{{ $nilaiAngka }}</td>
                            <td class="border px-3 py-2 text-center">{{ $sem }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                <div class="text-sm text-gray-700 flex items-center justify-between border-t pt-3">
                    <div class="w-1/3 text-left">{{ $totalMk }} Matakuliah.</div>
                    <div class="w-1/3 text-center">Total SKS: {{ $totalSksCalc }}</div>
                    <div class="w-1/3 text-right">IPK: {{ number_format($ipkVal, 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
