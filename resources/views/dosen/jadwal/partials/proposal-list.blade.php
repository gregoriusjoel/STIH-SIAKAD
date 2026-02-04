<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-800">
        <thead class="bg-gray-50 dark:bg-slate-800/50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Mata Kuliah</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Kelas</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Jadwal</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Ruangan</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                @if($status == 'rejected')
                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Alasan/Usulan</th>
                @endif
                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Tanggal</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-[#1a1d2e] divide-y divide-gray-100 dark:divide-slate-800">
            @forelse($proposals as $proposal)
            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $proposal->mataKuliah->nama }}</div>
                    <div class="text-xs text-gray-500 dark:text-slate-400">{{ $proposal->mataKuliah->kode }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-slate-300">
                    {{ $proposal->kelas->nama }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-bold text-primary">{{ $proposal->hari }}</div>
                    <div class="text-xs text-gray-500 dark:text-slate-400">
                        {{ \Carbon\Carbon::parse($proposal->jam_mulai)->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($proposal->jam_selesai)->format('H:i') }}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-slate-300">
                    <span class="px-2 py-0.5 bg-gray-100 dark:bg-slate-800 rounded text-xs font-medium">
                        {{ $proposal->ruangan }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @switch($proposal->status)
                        @case('approved_dosen')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                Disetujui - Menunggu Admin
                            </span>
                            @break
                        @case('pending_admin')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                                Direview Admin
                            </span>
                            @break
                        @case('approved_admin')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                Jadwal Aktif
                            </span>
                            @break
                        @case('rejected_dosen')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-800 dark:bg-slate-800 dark:text-slate-400">
                                Ditolak Dosen
                            </span>
                            @break
                        @case('rejected_admin')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                Ditolak Admin
                            </span>
                            @break
                    @endswitch
                </td>
                @if($status == 'rejected')
                <td class="px-6 py-4">
                    @php
                        $lastApproval = $proposal->getLatestApproval();
                    @endphp
                    @if($lastApproval)
                        <div class="max-w-xs">
                            <div class="text-xs font-bold text-red-600 dark:text-red-400 mb-1 italic line-clamp-2" title="{{ $lastApproval->alasan_penolakan }}">
                                "{{ $lastApproval->alasan_penolakan }}"
                            </div>
                            @if($lastApproval->hasAlternative())
                                <div class="mt-1 p-2 bg-gray-50 dark:bg-slate-800/50 rounded-lg text-[10px] border border-gray-100 dark:border-slate-700">
                                    <span class="font-bold text-gray-700 dark:text-slate-300">Usulan:</span>
                                    {{ $lastApproval->hari_pengganti }}, 
                                    {{ \Carbon\Carbon::parse($lastApproval->jam_mulai_pengganti)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($lastApproval->jam_selesai_pengganti)->format('H:i') }}
                                    @if($lastApproval->ruangan_pengganti)
                                        <div class="text-primary font-medium">{{ $lastApproval->ruangan_pengganti }}</div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @else
                        <span class="text-xs text-gray-400">-</span>
                    @endif
                </td>
                @endif
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400">
                    {{ $proposal->created_at->format('d M Y') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ $status == 'rejected' ? 7 : 6 }}" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-slate-400">
                    Tidak ada data ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>