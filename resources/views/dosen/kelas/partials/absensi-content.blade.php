<div class="space-y-6">

    <!-- Breadcrumb & Header (Hidden in Modal) -->
    @if(!$is_modal)
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                    <a href="{{ route('dosen.kelas') }}" class="hover:text-[#8B1538] transition-colors">Kelas Saya</a>
                    <span>/</span>
                    <span>Detail Absensi</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $class_info['name'] }}</h1>
                <p class="text-gray-500 mt-1">{{ $class_info['code'] }} • {{ $class_info['section'] }}</p>
            </div>
            <div class="flex gap-3">
                <button
                    class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium text-sm hover:bg-gray-50 flex items-center shadow-sm">
                    <i class="fas fa-print mr-2 text-gray-400"></i> Cetak Rekap
                </button>
                <button
                    class="bg-[#8B1538] text-white px-4 py-2 rounded-lg font-medium text-sm hover:bg-[#722036] flex items-center shadow-md">
                    <i class="fas fa-save mr-2"></i> Simpan Absensi
                </button>
            </div>
        </div>
    @endif

    <!-- Modal Header (Visible only in Modal) -->
    @if($is_modal)
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $class_info['name'] }}</h2>
                <p class="text-gray-500 text-sm">{{ $class_info['code'] }} • {{ $class_info['section'] }}</p>
            </div>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
    @endif

    <!-- Info Card -->
    <div
        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-[#8B1538]">
                <i class="fas fa-chalkboard-teacher text-xl"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Pertemuan Ke-</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $class_info['pertemuan'] }}</p>
            </div>
        </div>
        <div class="flex-1 w-full md:w-auto border-l border-gray-100 pl-0 md:pl-6">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Topik Pembahasan</h3>
            <p class="text-lg font-medium text-gray-900">{{ $class_info['topic'] }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal</p>
            <p class="text-base font-medium text-gray-900"><i
                    class="far fa-calendar-alt mr-2 text-gray-400"></i>{{ $class_info['date'] }}</p>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-900">Daftar Kehadiran Mahasiswa</h2>
            <div class="flex gap-4 text-sm font-medium">
                <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-green-500"></span>
                    Hadir</span>
                <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500"></span>
                    Sakit</span>
                <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                    Izin</span>
                <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-red-500"></span> Alpha</span>
            </div>
        </div>
        <div class="overflow-x-auto max-h-[400px]"> <!-- Added max-height for modal scrolling -->
            <table class="min-w-full divide-y divide-gray-100 relative">
                <thead class="bg-gray-50 sticky top-0 z-10"> <!-- Sticky header -->
                    <tr>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">NIM
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama
                            Mahasiswa</th>
                        <th scope="col"
                            class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Status Kehadiran</th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @foreach($students as $index => $student)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student['nim'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $student['name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center gap-4">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="status_{{ $student['nim'] }}" value="Hadir"
                                            class="form-radio text-green-600 focus:ring-green-500 h-4 w-4" {{ $student['status'] == 'Hadir' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">H</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="status_{{ $student['nim'] }}" value="Sakit"
                                            class="form-radio text-blue-600 focus:ring-blue-500 h-4 w-4" {{ $student['status'] == 'Sakit' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">S</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="status_{{ $student['nim'] }}" value="Izin"
                                            class="form-radio text-yellow-600 focus:ring-yellow-500 h-4 w-4" {{ $student['status'] == 'Izin' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">I</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="status_{{ $student['nim'] }}" value="Alpha"
                                            class="form-radio text-red-600 focus:ring-red-500 h-4 w-4" {{ $student['status'] == 'Alpha' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">A</span>
                                    </label>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="text"
                                    class="border-gray-300 rounded-md text-sm w-full focus:ring-[#8B1538] focus:border-[#8B1538]"
                                    placeholder="Catatan...">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <span class="text-sm text-gray-500">Total: {{ count($students) }} Mahasiswa</span>
            <div class="flex gap-4 text-sm font-bold text-gray-700">
                <span>Hadir: 7</span>
                <span>Sakit: 1</span>
                <span>Izin: 1</span>
                <span>Alpha: 1</span>
            </div>
        </div>
    </div>

    <!-- Modal Footer Actions -->
    @if($is_modal)
    <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
        <button onclick="closeModal()"
            class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium text-sm hover:bg-gray-50">
            Tutup
        </button>
        <button
            class="bg-[#8B1538] text-white px-4 py-2 rounded-lg font-medium text-sm hover:bg-[#722036] flex items-center shadow-md">
            <i class="fas fa-save mr-2"></i> Simpan Absensi
        </button>
    </div>
    @endif
</div>