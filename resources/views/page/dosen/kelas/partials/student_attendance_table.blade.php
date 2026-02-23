@forelse($students as $index => $student)
    <tr class="hover:bg-gray-50/50 transition-colors">
        <td class="px-4 py-3 text-gray-500 font-medium text-[13px]">{{ $index + 1 }}</td>
        <td class="px-4 py-3">
            <div class="font-bold text-gray-800 text-[13px]">{{ $student['name'] }}</div>
            <div class="text-[11px] text-gray-500">{{ $student['prodi'] }}</div>
        </td>
        <td class="px-4 py-3 font-mono text-[13px] text-gray-600">{{ $student['nim'] }}</td>
        <td class="px-4 py-3 text-center">
            @if(isset($student['attendance_status']) && $student['attendance_status'] === 'hadir')
                @php
                    $presenceMode = $student['presence_mode'] ?? null;
                    $distanceMeters = $student['distance_meters'] ?? null;
                    $reasonCategory = $student['reason_category'] ?? null;
                    $reasonDetail = $student['reason_detail'] ?? null;
                @endphp
                
                @if($presenceMode === 'offline')
                    <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-100 text-green-700">
                        <span class="material-symbols-outlined text-[14px] leading-none">check_circle</span>
                        <span class="leading-none mt-[1.5px]">Hadir Offline</span>
                    </span>
                    @if($distanceMeters !== null)
                        <div class="text-[9px] text-gray-500 mt-0.5">({{ round($distanceMeters) }}m dari kampus)</div>
                    @endif
                @elseif($presenceMode === 'online')
                    <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-700">
                        <span class="material-symbols-outlined text-[14px] leading-none">check_circle</span>
                        <span class="leading-none mt-[1.5px]">Hadir Online</span>
                    </span>
                    @if($distanceMeters !== null)
                        <div class="text-[9px] text-gray-500 mt-0.5">({{ round($distanceMeters) }}m dari kampus)</div>
                    @endif
                    @if($reasonCategory)
                        <div class="text-[9px] text-blue-600 mt-0.5 font-medium flex flex-col items-center">
                            {{ $reasonCategory }}
                            @if($reasonDetail && $reasonCategory === 'Lainnya')
                                <span class="text-gray-500">: {{ Str::limit($reasonDetail, 30) }}</span>
                            @endif
                        </div>
                    @endif
                @else
                    <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-100 text-green-700">
                        <span class="material-symbols-outlined text-[14px] leading-none">check_circle</span>
                        <span class="leading-none mt-[1.5px]">Hadir</span>
                    </span>
                @endif
            @else
                <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-gray-100 text-gray-600">
                    <span class="material-symbols-outlined text-[14px] leading-none">cancel</span>
                    <span class="leading-none mt-[1.5px]">Belum Absen</span>
                </span>
            @endif
        </td>
        <td class="px-4 py-3 text-center text-gray-600 text-[12px] font-mono">
            {{ $student['attendance_time'] ?? '-' }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-3 py-6 text-center text-[13px] text-gray-500">
            Tidak ada mahasiswa terdaftar di kelas ini.
        </td>
    </tr>
@endforelse
