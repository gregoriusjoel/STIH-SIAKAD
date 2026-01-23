<div class="space-y-6">

    @php $maxSemester = isset($mahasiswa) ? $mahasiswa->getCurrentSemester() : 8; @endphp
    @for($s = 1; $s <= $maxSemester; $s++)
        @php
            $label = 'Semester ' . $s;
            $found = collect($nilaiPerSemester)->first(function($value, $key) use ($s) {
                return preg_match('/\\b' . $s . '\\b/', $key);
            });
        @endphp

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h4 class="text-md font-bold">{{ $label }}</h4>
            </div>

            @if($found && $found->count())
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b-2 border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Kode MK</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Mata Kuliah</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase">SKS</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase">Nilai</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase">Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($found as $index => $krs)
                                @php
                                    $mataKuliah = $krs->kelasMataKuliah->mataKuliah ?? null;
                                    $nilai = $krs->nilai;
                                    $nilaiAngka = $nilai->nilai ?? 0;
                                    $grade = (new \App\Http\Controllers\Mahasiswa\NilaiController)->getGrade($nilaiAngka);
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $mataKuliah->kode_mk ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $mataKuliah->nama_mk ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-center font-semibold text-gray-800">{{ $mataKuliah->sks ?? 0 }}</td>
                                    <td class="px-6 py-4 text-sm text-center font-bold text-gray-900">{{ $nilaiAngka }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-lg font-bold text-sm bg-gray-100 text-gray-700">{{ $grade }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center text-gray-500">
                    Tidak ada nilai untuk semester ini.
                </div>
            @endif
        </div>
    @endfor
</div>
