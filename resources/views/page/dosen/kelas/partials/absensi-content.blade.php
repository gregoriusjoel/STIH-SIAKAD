<div class="space-y-6">

    <!-- Breadcrumb & Header -->
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

    <!-- Page layout: left info + QR, right attendance -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="space-y-6">
            <!-- Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col md:flex-row justify-between items-center gap-6">
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

            <!-- QR Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">QR Code Absensi</h3>
                <div id="qrCard" class="text-center">
                    <div id="qrWrap" class="inline-block p-6 bg-white border rounded-lg shadow-sm">
                        @php $token = $class['qr_token'] ?? ($class->qr_token ?? null); @endphp
                        @if($token)
                            <img id="generatedQr" src="{{ route('qrcode.kelas.image', $token) }}" alt="QR Kelas" width="180" height="180" />
                        @else
                            <div class="w-44 h-44 flex items-center justify-center border rounded bg-gray-50">
                                <div class="text-sm text-gray-500">QR belum dibuat</div>
                            </div>
                        @endif
                    </div>

                    <p class="text-xs text-gray-400 mt-3">Scan QR untuk mengisi absensi</p>

                    <div class="mt-4 flex items-center justify-center gap-3">
                        @if($token)
                            <a id="downloadBtn" href="{{ route('qrcode.kelas.image', $token) }}" class="px-3 py-2 rounded-md bg-green-600 text-white text-sm" download>Download QR</a>
                        @else
                            <form action="{{ route('dosen.kelas.generate_qr', ['id' => $id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 rounded-md text-white text-sm bg-green-600">Buat QR</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <label class="text-xs text-gray-500">Link Absensi</label>
                <div class="mt-2 flex items-center gap-2">
                    <input id="absensiLink" type="text" readonly value="{{ $token ? route('absensi.form', ['token' => $token]) : 'N/A' }}" class="flex-1 text-sm px-3 py-2 border border-gray-200 rounded-lg bg-gray-50" />
                    <button id="copyBtn" class="px-3 py-2 bg-[#8B1538] text-white rounded-lg text-sm">Copy</button>
                </div>
            </div>

        </div>

        <!-- Right column: Daftar Hadir -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-bold text-[#8B1538]">Daftar Hadir</h4>
                    <div class="flex items-center gap-3">
                        <label class="text-sm text-gray-500">Tampilkan</label>
                        <select class="border border-gray-200 rounded px-2 py-1 text-sm">
                            <option>25</option>
                            <option>50</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Kelas</th>
                                <th class="px-4 py-3">Kontak</th>
                                <th class="px-4 py-3">Waktu</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <!-- Dummy empty state -->
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada peserta yang mengisi absensi.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>