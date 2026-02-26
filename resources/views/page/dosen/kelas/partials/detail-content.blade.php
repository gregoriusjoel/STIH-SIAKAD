{{-- Detail Kelas Modal Content --}}
<div class="p-2">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-xl font-bold text-[#111218]">Detail Kelas</h2>
        </div>
        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    {{-- Class Info Header --}}
    <div class="mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-lg font-bold text-[#111218]">{{ $class_info['name'] }}</h3>
                <p class="text-sm text-[#616889]">{{ $class_info['code'] }} • {{ $class_info['sks'] }} SKS • Semester {{ $class_info['semester'] }}</p>
            </div>
            <span class="px-3 py-1 rounded text-xs font-bold bg-pink-50 text-[#8B1538] border border-pink-100">
                {{ $class_info['section'] }}
            </span>
        </div>

        <div class="flex flex-wrap gap-4 mt-4 text-sm text-[#616889]">
            <div class="flex items-center gap-1.5">
                <i class="far fa-calendar text-gray-400"></i>
                <span>{{ $class_info['day'] }}</span>
            </div>
            <div class="flex items-center gap-1.5">
                <i class="far fa-clock text-gray-400"></i>
                <span>{{ $class_info['time'] }}</span>
            </div>
            <div class="flex items-center gap-1.5">
                <i class="fas fa-map-marker-alt text-gray-400"></i>
                <span>{{ $class_info['room'] }}</span>
            </div>
            <div class="flex items-center gap-1.5">
                <i class="fas fa-users text-gray-400"></i>
                <span>{{ $class_info['students_count'] }} Mahasiswa</span>
            </div>
        </div>
    </div>

    {{-- Progress Pertemuan --}}
    <div class="mb-6">
        <div class="flex justify-between text-sm mb-2">
            <span class="text-[#111218] font-medium">Progress Pertemuan</span>
            <span class="text-[#8B1538] font-bold">{{ $class_info['progress'] }} / {{ $class_info['total_pertemuan'] }}</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-2">
            <div class="bg-[#8B1538] h-2 rounded-full" style="width: {{ ($class_info['progress'] / $class_info['total_pertemuan']) * 100 }}%"></div>
        </div>
    </div>

    {{-- Daftar Mahasiswa Table --}}
    <div class="mb-6">
        <h4 class="text-sm font-bold text-[#111218] mb-3 flex items-center gap-2">
            <i class="fas fa-users text-[#8B1538]"></i>
            Daftar Mahasiswa
        </h4>
        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="w-full text-sm text-left" style="min-width: 700px;">
                <thead class="bg-gray-50 text-[#616889]">
                    <tr>
                        <th class="px-4 py-3 font-semibold">NIM</th>
                        <th class="px-4 py-3 font-semibold">Nama</th>
                        <th class="px-4 py-3 font-semibold">No. Telp</th>
                        <th class="px-4 py-3 font-semibold">Prodi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($students as $student)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-[#616889]">{{ $student['nim'] }}</td>
                        <td class="px-4 py-3 text-[#111218] font-medium">{{ $student['name'] }}</td>
                        <td class="px-4 py-3 text-[#616889]">{{ $student['phone'] ?? '-' }}</td>
                        <td class="px-4 py-3 text-[#616889]">{{ $student['prodi'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex gap-3 pt-2">
        <a href="{{ route('dosen.kelas.input-nilai', $kelas->id) }}" class="flex items-center gap-2 px-4 py-2 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary-hover transition-colors shadow-sm whitespace-nowrap">
            <span class="material-symbols-outlined text-[20px]">edit_note</span>
            Input Nilai Akhir
        </a>
        <button class="flex-1 py-2.5 rounded-lg border border-gray-200 text-[#616889] text-sm font-medium hover:bg-gray-50 transition-colors">
            Lihat Absensi
        </button>
        <button class="flex-1 py-2.5 rounded-lg border border-gray-200 text-[#616889] text-sm font-medium hover:bg-gray-50 transition-colors">
            Download Materi
        </button>
    </div>
</div>