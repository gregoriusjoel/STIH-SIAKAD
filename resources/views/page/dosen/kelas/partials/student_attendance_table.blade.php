@forelse($students as $index => $student)
    <tr class="hover:bg-gray-50/50 transition-colors">
        <td class="px-6 py-4 text-gray-500 font-medium">{{ $index + 1 }}</td>
        <td class="px-6 py-4">
            <div class="font-bold text-gray-800">{{ $student['name'] }}</div>
            <div class="text-xs text-gray-500">{{ $student['prodi'] }}</div>
        </td>
        <td class="px-6 py-4 font-mono text-sm text-gray-600">{{ $student['nim'] }}</td>
        <td class="px-6 py-4 text-center">
            @if(isset($student['attendance_status']) && $student['attendance_status'] === 'hadir')
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                    <span class="material-symbols-outlined text-[16px] mr-1">check_circle</span>
                    Hadir
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">
                    <span class="material-symbols-outlined text-[16px] mr-1">cancel</span>
                    Belum Absen
                </span>
            @endif
        </td>
        <td class="px-6 py-4 text-center text-gray-600 text-sm font-mono">
            {{ $student['attendance_time'] ?? '-' }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
            Tidak ada mahasiswa terdaftar di kelas ini.
        </td>
    </tr>
@endforelse
