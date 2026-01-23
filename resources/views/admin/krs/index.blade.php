@extends('layouts.admin')

@section('title', 'Data KRS')
@section('page-title', 'Data KRS')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-file-alt mr-3 text-maroon"></i>
                Manajemen KRS
            </h2>
            <p class="text-gray-600 text-sm mt-1">Kelola dan verifikasi Kartu Rencana Studi mahasiswa</p>
        </div>
    </div>

    <!-- Semester Aktif & Pengaturan KRS (two-column) -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left: Semester Aktif -->
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden relative flex">
                        <div class="flex w-full">
                            <div class="flex-1 p-6 relative flex flex-col justify-between">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Semester Aktif</p>
                                @if(isset($semesterAktif))
                                    <h3 class="text-3xl font-extrabold text-gray-900 mt-1">{{ $semesterAktif->nama_semester ?? '-' }} {{ $semesterAktif->tahun_ajaran ?? '' }}</h3>

                                    <div class="flex items-center gap-2 mt-2">
                                        @php
                                            $now = \Carbon\Carbon::now();
                                            $mulai = $semesterAktif->tanggal_mulai ? \Carbon\Carbon::parse($semesterAktif->tanggal_mulai) : null;
                                            $selesai = $semesterAktif->tanggal_selesai ? \Carbon\Carbon::parse($semesterAktif->tanggal_selesai) : null;
                                            $isRunning = $mulai && $selesai && $now->between($mulai, $selesai);
                                        @endphp

                                        @if($isRunning)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Sedang Berjalan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-clock mr-2"></i>
                                                Tidak Aktif
                                            </span>
                                        @endif

                                        @if($selesai)
                                            <div class="text-sm text-gray-500 flex items-center"><i class="fas fa-clock text-gray-400 mr-2"></i>Berakhir {{ $selesai->format('d M Y') }}</div>
                                        @endif
                                    </div>

                                    <hr class="my-4 border-t border-gray-100">
                                        <div class="mt-3 flex items-center space-x-3">
                                        <a href="{{ route('admin.semester.manage') }}" class="px-6 py-3 bg-maroon text-white rounded-full hover:bg-red-900 transition flex items-center shadow-sm"><i class="fas fa-plus mr-2"></i>Set Semester Baru</a>
                                        <a href="{{ route('admin.jadwal.index') }}" class="px-5 py-3 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 transition flex items-center">Lihat Kalender Akademik</a>
                                    </div>
                                @else
                                    <h3 class="text-lg font-semibold text-gray-800 mt-2">Belum ada semester aktif</h3>
                                    <p class="text-sm text-gray-500 mt-2">Silakan atur semester aktif pada halaman Semester & Tahun Ajaran.</p>
                                    <div class="mt-4">
                                        <a href="{{ route('admin.semester.manage') }}" class="w-full md:w-auto px-4 py-3 bg-maroon text-white rounded-lg hover:bg-red-900 transition flex items-center justify-center"><i class="fas fa-cog mr-2"></i>Atur Semester</a>
                                    </div>
                                @endif
                            </div>

                            <div class="w-44 pr-6 flex items-center justify-end relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-4 right-4 opacity-10" width="140" height="140" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: rgba(0,0,0,0.04);">
                                    <rect x="3" y="2" width="18" height="18" rx="2" />
                                    <path d="M16 2v4" />
                                    <path d="M8 2v4" />
                                    <path d="M3 10h18" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Pengaturan KRS -->
                    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Pengaturan KRS</h3>

                        @if(isset($semesterAktif))
                        <form action="{{ route('admin.semester.update-krs-settings', $semesterAktif->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Status Pengisian KRS</span>
                                   <div class="flex items-center gap-3">
                                        <input type="checkbox"
                                            name="krs_dapat_diisi"
                                            value="1"
                                            {{ $semesterAktif->krs_dapat_diisi ? 'checked' : '' }}
                                            class="sr-only peer"
                                            id="krsToggleAdmin">
                                        <label for="krsToggleAdmin"
                                            class="relative w-11 h-6 flex-shrink-0 bg-gray-200 
                                                rounded-full peer 
                                                peer-checked:bg-green-600 
                                                cursor-pointer
                                                after:content-[''] 
                                                after:absolute 
                                                after:top-[2px] 
                                                after:left-[2px] 
                                                after:bg-white 
                                                after:border-gray-300 
                                                after:border 
                                                after:rounded-full 
                                                after:h-5 
                                                after:w-5 
                                                after:transition-all
                                                peer-checked:after:translate-x-full
                                                peer-checked:after:border-white">
                                        </label>
                                    </div>
                                </label>
                                <p class="text-xs text-gray-500 mt-1">{{ $semesterAktif->krs_dapat_diisi ? 'Mahasiswa dapat mengisi KRS' : 'Mahasiswa tidak dapat mengisi KRS' }}</p>
                            </div>


                            <div class="mb-4">
                                <label class="block text-xs text-gray-600 mb-1">Mulai Periode</label>
                                <input type="date" name="krs_mulai" value="{{ $semesterAktif->krs_mulai?->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs text-gray-600 mb-1">Akhir Periode</label>
                                <input type="date" name="krs_selesai" value="{{ $semesterAktif->krs_selesai?->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>

                            <button type="submit" class="w-full px-4 py-2 bg-maroon text-white rounded-lg hover:bg-red-900 transition flex items-center justify-center mt-4 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-maroon/30 ring-1 ring-maroon/10">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Pengaturan
                            </button>
                        </form>
                        @else
                        <div class="text-sm text-gray-500">Belum ada semester aktif. Silakan atur semester aktif pada halaman Semester & Tahun Ajaran.</div>
                        @endif
                    </div>
                </div>
            </div>
<div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-maroon text-white">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-hashtag mr-2"></i>No
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-user-graduate mr-2"></i>Mahasiswa
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-book mr-2"></i>Mata Kuliah
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-calendar-alt mr-2"></i>Semester
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-calculator mr-2"></i>SKS
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-info-circle mr-2"></i>Status
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-cog mr-2"></i>Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($krsData as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            {{ ($krsData->currentPage() - 1) * $krsData->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-maroon to-red-700 flex items-center justify-center text-white font-bold mr-3">
                                    {{ strtoupper(substr($item->mahasiswa->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $item->mahasiswa->user->name }}</div>
                                    <div class="text-xs text-gray-500">
                                        <i class="fas fa-id-card text-gray-400 mr-1"></i>
                                        NPM: {{ $item->mahasiswa->npm }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold mr-2 text-xs">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ optional(optional($item->kelas)->mataKuliah)->nama_mk ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ optional(optional($item->kelas)->mataKuliah)->kode_mk ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-700">
                                <i class="fas fa-calendar text-gray-400 mr-1"></i>
                                {{ $item->semester?->nama_semester ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 inline-flex items-center justify-center mx-auto text-sm font-bold rounded-full bg-purple-100 text-purple-800">
                                <i class="fas fa-calculator mr-1"></i>
                                {{ optional(optional($item->kelas)->mataKuliah)->sks ? optional(optional($item->kelas)->mataKuliah)->sks . ' SKS' : '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($item->status == 'pending')
                                <span class="px-3 py-1 inline-flex items-center justify-center mx-auto text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Pending
                                </span>
                            @elseif($item->status == 'disetujui')
                                <span class="px-3 py-1 inline-flex items-center justify-center mx-auto text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Disetujui
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex items-center justify-center mx-auto text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <button type="button" onclick="document.getElementById('modal-krs-{{ $item->id }}').classList.remove('hidden')" class="text-blue-600 hover:text-blue-900 transition p-2 hover:bg-blue-50 rounded" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if($item->status == 'pending')
                                    <form action="{{ route('admin.krs.updateStatus', $item) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="disetujui">
                                        <button type="submit" 
                                            class="text-green-600 hover:text-green-900 transition p-2 hover:bg-green-50 rounded" 
                                            title="Setujui">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.krs.updateStatus', $item) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="ditolak">
                                        <button type="submit" 
                                            class="text-red-600 hover:text-red-900 transition p-2 hover:bg-red-50 rounded" 
                                            onclick="return confirm('Yakin ingin menolak KRS ini?')"
                                            title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.krs.destroy', $item) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="text-gray-600 hover:text-gray-900 transition p-2 hover:bg-gray-50 rounded" 
                                        onclick="return confirm('Yakin ingin menghapus KRS ini?')"
                                        title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal KRS -->
                    <div id="modal-krs-{{ $item->id }}" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden">
                        <div class="bg-white rounded-lg shadow-2xl w-11/12 md:w-3/4 lg:w-2/3 max-h-[90vh] overflow-y-auto overflow-x-hidden">
                            <div class="flex items-center justify-between px-6 py-4 bg-maroon text-white">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 rounded-full bg-white bg-opacity-10 flex items-center justify-center text-white font-bold">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold">Detail KRS</h3>
                                        <p class="text-sm text-white text-opacity-90">Informasi KRS mahasiswa</p>
                                    </div>
                                </div>
                                <button onclick="document.getElementById('modal-krs-{{ $item->id }}').classList.add('hidden')" class="text-white text-xl leading-none">&times;</button>
                            </div>
                            <div class="p-6 text-sm text-gray-700">
                                <div class="grid grid-cols-1 gap-2">
                                    <div><strong>Mahasiswa:</strong> {{ $item->mahasiswa->user->name }} (NPM: {{ $item->mahasiswa->npm }})</div>
                                    <div><strong>Mata Kuliah:</strong> {{ optional(optional($item->kelas)->mataKuliah)->nama_mk ?? '-' }}</div>
                                    <div><strong>Semester:</strong> {{ $item->semester?->nama_semester ?? '-' }}</div>
                                    <div><strong>SKS:</strong> {{ optional(optional($item->kelas)->mataKuliah)->sks ?? '-' }}</div>
                                    <div><strong>Status:</strong> {{ ucfirst($item->status) }}</div>
                                </div>
                            </div>
                            <div class="px-6 py-4 bg-gray-50 flex justify-end">
                                <button onclick="document.getElementById('modal-krs-{{ $item->id }}').classList.add('hidden')" class="px-4 py-2 border rounded">Tutup</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium">Belum ada data KRS</p>
                                <p class="text-sm text-gray-400 mt-1">Data KRS akan tampil ketika mahasiswa melakukan pengisian KRS</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($krsData->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $krsData->links() }}
        </div>
    @endif
</div>
@endsection
