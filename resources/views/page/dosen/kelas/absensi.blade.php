@extends('layouts.app')

@section('title', 'Detail Absensi | Portal Dosen')
@section('header_title', 'Detail Absensi')

@section('content')
    <div class="flex flex-col gap-6 max-w-[1200px] mx-auto w-full flex-1 py-6">
        @if(session('success'))
            <div class="max-w-[1200px] mx-auto px-4">
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="max-w-[1200px] mx-auto px-4">
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
            </div>
        @endif
        @if(session('debug_info'))
            <div class="max-w-[1200px] mx-auto px-4">
                <pre class="text-xs bg-gray-50 border p-2 rounded text-gray-700">Debug: {{ json_encode(session('debug_info')) }}</pre>
            </div>
        @endif
        {{-- debug_absensi removed --}}
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
                        @php
                            $token = $class['qr_token'] ?? ($class->qr_token ?? null);
                            $qrEnabled = $class['qr_enabled'] ?? false;
                            $qrExpires = $class['qr_expires_at'] ?? null;
                        @endphp

                        @if($token)
                            @if($qrEnabled)
                                <img id="generatedQr" src="{{ route('qrcode.kelas.image', $token) }}" alt="QR Kelas" width="180" height="180" />
                                @if($qrExpires)
                                        <div id="qrExpiryText" class="text-xs text-gray-500 mt-2">Berakhir: {{ \Illuminate\Support\Carbon::parse($qrExpires)->locale('id')->isoFormat('H:mm, D MMM') }}<span id="qrCountdown"></span></div>
                                @endif
                                        <div id="qrExpiryText" class="text-xs text-gray-500 mt-2"></div>
                                <div class="w-44 h-44 flex items-center justify-center border rounded bg-gray-50">
                                    <div class="text-sm text-gray-500">QR aktif</div>
                                </div>
                            @endif
                        @else
                            <div class="w-44 h-44 flex items-center justify-center border rounded bg-gray-50">
                                <div class="text-sm text-gray-500">QR belum dibuat</div>
                            </div>
                        @endif
                    </div>

                    <p class="text-xs text-gray-400 mt-3">Scan QR untuk mengisi absensi</p>

                    <div class="mt-4 flex items-center justify-center gap-3">
                        @if($token)
                            @if($qrEnabled)
                                <div class="flex items-center gap-2">
                                    <a id="downloadBtn" href="{{ route('qrcode.kelas.image', $token) }}" class="px-3 py-2 rounded-md bg-green-600 text-white text-sm" download>Download QR</a>
                                        <form id="deactivateForm" action="{{ route('dosen.kelas.deactivate_qr', ['id' => $id]) }}" method="POST" onsubmit="return confirm('Nonaktifkan QR sekarang?');">
                                        @csrf
                                        <button type="submit" class="px-3 py-2 rounded-md bg-red-600 text-white text-sm">Nonaktifkan QR</button>
                                    </form>
                                </div>
                            @else
                                    <form id="activateForm" action="{{ route('dosen.kelas.activate_qr', ['id' => $id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="pertemuan" value="{{ request('pertemuan', $class_info['pertemuan'] ?? 1) }}">
                                    <button type="submit" class="px-4 py-2 rounded-md text-white text-sm bg-green-600">Tampilkan QR (5 menit)</button>
                                </form>
                            @endif
                        @else
                            <form action="{{ route('dosen.kelas.generate_qr', ['id' => $id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="pertemuan" value="{{ request('pertemuan', $class_info['pertemuan'] ?? 1) }}">
                                <button type="submit" class="px-4 py-2 rounded-md text-white text-sm bg-green-600">Buat QR</button>
                            </form>
                        @endif
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
                                @if(!empty($presensis) && count($presensis) > 0)
                                    @foreach($presensis as $index => $p)
                                        <tr>
                                            <td class="px-4 py-4">{{ $index + 1 }}</td>
                                            <td class="px-4 py-4">
                                                {{ $p->nama ?? ($p->krs->mahasiswa->user->name ?? ($p->krs->mahasiswa->nama ?? '-')) }}
                                            </td>
                                            <td class="px-4 py-4">{{ $p->krs?->kelas?->section ?? ($class_info['section'] ?? '-') }}</td>
                                            <td class="px-4 py-4">{{ $p->kontak ?? '-' }}</td>
                                            <td class="px-4 py-4">{{ optional($p->waktu ?? $p->tanggal)->format('d M Y H:i') ?? (optional($p->tanggal)->format('d M Y') ?? '-') }}</td>
                                            <td class="px-4 py-4">-</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada peserta yang mengisi absensi.</td>
                                    </tr>
                                @endif
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
            const generatedQr = document.getElementById('generatedQr');
            const copyBtn = document.getElementById('copyBtn');
            const absensiLinkEl = document.getElementById('absensiLink');

            let active = localStorage.getItem(key) === '1';

            function updateUI(){
                if (toggleBtn) {
                    if(active){
                        toggleBtn.textContent = 'Nonaktifkan QR';
                        toggleBtn.classList.remove('bg-green-600');
                        toggleBtn.classList.add('bg-red-600');
                        if (downloadBtn) downloadBtn.classList.remove('hidden');
                        if (qrWrap) qrWrap.style.opacity = '1';
                    } else {
                        toggleBtn.textContent = 'Aktifkan QR';
                        toggleBtn.classList.remove('bg-red-600');
                        toggleBtn.classList.add('bg-green-600');
                        if (downloadBtn) downloadBtn.classList.add('hidden');
                        if (qrWrap) qrWrap.style.opacity = '0.45';
                    }
                } else {
                    if (qrWrap) qrWrap.style.opacity = (generatedQr ? '1' : '0.45');
                }
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e){
                    e.preventDefault();
                    active = !active;
                    localStorage.setItem(key, active ? '1' : '0');
                    updateUI();
                });
            }

            if (downloadBtn) {
                downloadBtn.addEventListener('click', function(e){
                    if (generatedQr && generatedQr.src) {
                        return;
                    }
                    e.preventDefault();
                    if (!dummyQr) return;
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
            }

            if (copyBtn && absensiLinkEl) {
                copyBtn.addEventListener('click', function(){
                    try {
                        if (absensiLinkEl.select) absensiLinkEl.select();
                        navigator.clipboard.writeText(absensiLinkEl.value || '').then(()=>{
                            if (toggleBtn) {
                                const prev = toggleBtn.innerText;
                                toggleBtn.innerText = 'Disalin';
                                setTimeout(()=> toggleBtn.innerText = prev, 900);
                            }
                        }).catch(()=>{});
                    } catch (ex) {
                    }
                });
            }

            updateUI();
        })();
    </script>
    <script>
        (function(){
            const qrEnabled = {!! json_encode($class['qr_enabled'] ?? false) !!};
            const qrExpiresRaw = {!! json_encode($class['qr_expires_at'] ?? null) !!};
            const deactivateUrl = "{{ route('dosen.kelas.deactivate_qr', ['id' => $id]) }}";

            const generatedQr = document.getElementById('generatedQr');
            const qrExpiryText = document.getElementById('qrExpiryText');
            const qrCountdown = document.getElementById('qrCountdown');
            const downloadBtn = document.getElementById('downloadBtn');
            const activateForm = document.getElementById('activateForm');
            const deactivateForm = document.getElementById('deactivateForm');

            function setDisabledUI() {
                const wrap = document.getElementById('qrWrap');
                if (wrap) {
                    wrap.innerHTML = '<div class="w-44 h-44 flex items-center justify-center border rounded bg-gray-50"><div class="text-sm text-gray-500">QR tidak aktif</div></div>';
                }
                if (downloadBtn) downloadBtn.classList.add('hidden');
                if (deactivateForm) deactivateForm.remove();
                if (activateForm) activateForm.classList.remove('hidden');
                if (qrExpiryText) qrExpiryText.textContent = '';
            }

            if (qrEnabled && qrExpiresRaw) {
                const expiresAt = new Date(qrExpiresRaw);
                function tick() {
                    const now = new Date();
                    const diff = expiresAt - now;
                    if (diff <= 0) {
                        clearInterval(timer);
                        // persist server-side deactivation, then update UI
                        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                        fetch(deactivateUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({})
                        }).catch(()=>{}).finally(()=>{
                            setDisabledUI();
                        });
                        return;
                    }
                    const mm = Math.floor(diff/60000);
                    const ss = Math.floor((diff%60000)/1000);
                    if (qrCountdown) qrCountdown.textContent = ' ('+String(mm).padStart(2,'0')+':'+String(ss).padStart(2,'0')+')';
                }
                tick();
                const timer = setInterval(tick, 1000);
            }
        })();
    </script>
@endpush

@endsection