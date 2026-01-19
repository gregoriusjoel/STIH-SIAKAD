@extends('layouts.app')

@section('title', 'Detail Absensi | Portal Dosen')
@section('header_title', 'Detail Absensi')

@section('content')
    <div class="flex flex-col gap-6 max-w-[1200px] mx-auto w-full flex-1 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left column: Info + QR -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h4 class="text-sm font-bold text-[#8B1538] mb-2">Info Kegiatan</h4>
                    <p class="text-sm text-[#616889]">Tipe Kegiatan: <span class="font-medium">Kunjungan Sekolah</span></p>
                    <p class="text-sm text-[#616889]">Tempat: <span class="font-medium">MA STIH</span></p>
                    <p class="text-sm text-[#616889]">Tanggal: <span class="font-medium">12 Jan 2026</span></p>
                    <p class="text-sm text-[#616889]">Status: <span class="inline-block px-2 py-0.5 bg-green-50 text-green-700 rounded text-xs">Terlaksana</span></p>
                </div>

                @php
                    $teacherName = $class['teacher']['name'] ?? $class['teacher_name'] ?? (auth()->user()->name ?? 'Nama Dosen');
                    $teacherRole = $class['teacher']['role'] ?? 'Dosen';
                    $teacherPhone = $class['teacher']['phone'] ?? auth()->user()->phone ?? '-';
                @endphp

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h4 class="text-sm font-bold text-[#8B1538] mb-2">Pengajar</h4>
                    <p class="text-sm text-[#616889]">Nama: <span class="font-medium">{{ $teacherName }}</span></p>
                    <p class="text-sm text-[#616889]">Jabatan: <span class="font-medium">{{ $teacherRole }}</span></p>
                    <p class="text-sm text-[#616889]">No. HP: <span class="font-medium">{{ $teacherPhone }}</span></p>
                </div>

                <div id="qrCard" class="bg-white rounded-xl border border-gray-200 p-6 text-center">
                    <h4 class="text-sm font-bold text-[#8B1538] mb-4">QR Code Absensi</h4>
                    <div id="qrWrap" class="inline-block p-6 bg-white border rounded-lg shadow-sm">
                        <!-- Dummy QR: simple SVG pattern -->
                        <svg id="dummyQr" xmlns="http://www.w3.org/2000/svg" width="180" height="180" viewBox="0 0 180 180">
                            <rect width="180" height="180" fill="#fff" />
                            <g fill="#111">
                                <rect x="20" y="20" width="40" height="40" />
                                <rect x="120" y="20" width="40" height="40" />
                                <rect x="20" y="120" width="40" height="40" />
                                <!-- small squares -->
                                <rect x="75" y="75" width="10" height="10" />
                                <rect x="90" y="75" width="10" height="10" />
                                <rect x="105" y="75" width="10" height="10" />
                                <rect x="75" y="90" width="10" height="10" />
                                <rect x="90" y="90" width="10" height="10" />
                                <rect x="105" y="90" width="10" height="10" />
                            </g>
                        </svg>
                    </div>

                    <p class="text-xs text-gray-400 mt-3">Scan QR untuk mengisi absensi</p>

                    <div class="mt-4 flex items-center justify-center gap-3">
                        <button id="toggleBtn" class="px-4 py-2 rounded-md text-white text-sm bg-green-600">Aktifkan QR</button>
                        <button id="downloadBtn" class="px-3 py-2 rounded-md bg-green-600 text-white text-sm hidden">Download QR</button>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <label class="text-xs text-gray-500">Link Absensi</label>
                    <div class="mt-2 flex items-center gap-2">
                        <input id="absensiLink" type="text" readonly value="https://example.com/absen/ABC123" class="flex-1 text-sm px-3 py-2 border border-gray-200 rounded-lg bg-gray-50" />
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
    </div>

@push('scripts')
    <script>
        (function(){
            const key = 'absensi_qr_active';
            const toggleBtn = document.getElementById('toggleBtn');
            const downloadBtn = document.getElementById('downloadBtn');
            const qrWrap = document.getElementById('qrWrap');
            const dummyQr = document.getElementById('dummyQr');

            let active = localStorage.getItem(key) === '1';

            function updateUI(){
                if(active){
                    toggleBtn.textContent = 'Nonaktifkan QR';
                    toggleBtn.classList.remove('bg-green-600');
                    toggleBtn.classList.add('bg-red-600');
                    downloadBtn.classList.remove('hidden');
                    qrWrap.style.opacity = '1';
                } else {
                    toggleBtn.textContent = 'Aktifkan QR';
                    toggleBtn.classList.remove('bg-red-600');
                    toggleBtn.classList.add('bg-green-600');
                    downloadBtn.classList.add('hidden');
                    qrWrap.style.opacity = '0.45';
                }
            }

            toggleBtn.addEventListener('click', function(){
                active = !active;
                localStorage.setItem(key, active ? '1' : '0');
                updateUI();
            });

            downloadBtn.addEventListener('click', function(){
                const svg = dummyQr.outerHTML;
                const blob = new Blob([svg], {type: 'image/svg+xml'});
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'qr-absensi.svg';
                document.body.appendChild(a);
                a.click();
                a.remove();
                URL.revokeObjectURL(url);
            });

            document.getElementById('copyBtn').addEventListener('click', function(){
                const link = document.getElementById('absensiLink');
                link.select();
                navigator.clipboard.writeText(link.value).then(()=>{
                    const prev = toggleBtn.innerText;
                    toggleBtn.innerText = 'Disalin';
                    setTimeout(()=> toggleBtn.innerText = prev, 900);
                });
            });

            updateUI();
        })();
    </script>
@endpush

@endsection