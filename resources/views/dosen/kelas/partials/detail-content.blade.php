<div class="space-y-6">

    <!-- Modal Header (Visible only in Modal) -->
    @if($is_modal)
        <div class="flex justify-between items-center mb-0">
            <h2 class="text-2xl font-bold text-gray-900">Detail Kelas</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="border-b border-gray-100 -mx-6 mb-6"></div>
    @endif

    <!-- Breadcrumb & Header (Visible only in Full Page) -->
    @if(!$is_modal)
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                    <a href="{{ route('dosen.kelas') }}" class="hover:text-[#8B1538] transition-colors">Kelas Saya</a>
                    <span>/</span>
                    <span>Detail Kelas</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Detail Kelas</h1>
            </div>
        </div>
    @endif

    <!-- Info Card -->
    <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-xl font-bold text-gray-900">{{ $class_info['name'] }}</h3>
                <p class="text-gray-500 text-sm mt-1">{{ $class_info['code'] }} • {{ $class_info['sks'] }} SKS •
                    Semester {{ $class_info['semester'] }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-pink-100 text-[#8B1538]">
                {{ $class_info['section'] }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div class="flex items-center gap-3 text-gray-600">
                <i class="far fa-calendar text-[#8B1538] text-lg w-6 text-center"></i>
                <span class="text-sm font-medium">{{ $class_info['day'] }}</span>
            </div>
            <div class="flex items-center gap-3 text-gray-600">
                <i class="far fa-clock text-[#8B1538] text-lg w-6 text-center"></i>
                <span class="text-sm font-medium">{{ $class_info['time'] }}</span>
            </div>
            <div class="flex items-center gap-3 text-gray-600">
                <i class="fas fa-map-marker-alt text-[#8B1538] text-lg w-6 text-center"></i>
                <span class="text-sm font-medium">{{ $class_info['room'] }}</span>
            </div>
            <div class="flex items-center gap-3 text-gray-600">
                <i class="fas fa-user-friends text-[#8B1538] text-lg w-6 text-center"></i>
                <span class="text-sm font-medium">{{ $class_info['students_count'] }} Mahasiswa</span>
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div>
        <div class="flex justify-between items-center mb-2">
            <h4 class="text-base font-bold text-gray-700">Progress Pertemuan</h4>
            <span class="text-[#8B1538] font-bold text-sm">{{ $class_info['progress'] }} /
                {{ $class_info['total_pertemuan'] }}</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-2.5">
            <div class="bg-[#8B1538] h-2.5 rounded-full"
                style="width: {{ ($class_info['progress'] / $class_info['total_pertemuan']) * 100 }}%"></div>
        </div>
    </div>

    <!-- Student List Section -->
    <div>
        <div class="flex items-center gap-3 mb-4">
            <i class="fas fa-users text-gray-900 text-lg"></i>
            <h4 class="text-lg font-bold text-gray-900">Daftar Mahasiswa</h4>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">NIM
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Prodi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @foreach($students as $student)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student['nim'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $student['name'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student['prodi'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>