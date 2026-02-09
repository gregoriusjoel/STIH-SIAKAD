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

                <div id="qrCard" class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-200 dark:border-slate-800 p-6 text-center shadow-sm relative overflow-hidden" 
                    x-data="{ 
                        isLoaded: {{ (isset($token) && $token && $qrEnabled) ? 'true' : 'false' }},
                        activating: false,
                        showQr(formId) {
                            this.isLoaded = false;
                            this.activating = true;
                            // Small delay to show the skeleton effect as requested
                            setTimeout(() => {
                                document.getElementById(formId).submit();
                            }, 1000);
                        }
                    }">
                    
                    <h4 class="text-sm font-bold text-primary mb-6 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-lg">qr_code_2</span>
                        QR Absensi
                    </h4>

                    <div class="relative flex flex-col items-center gap-5">
                        <!-- Skeleton Wrapper (HeroUI Style) -->
                        <div class="w-full max-w-[200px] flex flex-col gap-4 mx-auto">
                            <!-- Main QR Area Skeleton -->
                            <div class="relative w-full aspect-square rounded-2xl overflow-hidden bg-gray-100 dark:bg-slate-800" 
                                :class="{ 'animate-pulse': !isLoaded || activating }">
                                
                                @if(isset($token) && $token && $qrEnabled)
                                    <div x-show="isLoaded && !activating" x-transition.opacity.duration.500>
                                        <img id="generatedQr" src="{{ route('qrcode.kelas.image', $token) }}" 
                                            alt="QR Kelas" class="w-full h-full p-4 bg-white" 
                                            @load="isLoaded = true" />
                                    </div>
                                @endif
                                
                                <!-- Skeleton Overlay -->
                                <div x-show="!isLoaded || activating" 
                                    class="absolute inset-0 bg-gray-200 dark:bg-slate-700 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl text-gray-300 dark:text-slate-600">qr_code_scanner</span>
                                </div>
                            </div>

                            <!-- Text Skeletons -->
                            <div class="space-y-3 px-2">
                                <div class="h-3 w-3/5 rounded-lg bg-gray-200 dark:bg-slate-700 mx-auto" :class="{ 'animate-pulse': !isLoaded || activating }">
                                    <div x-show="isLoaded && !activating" class="text-[10px] text-gray-500 font-medium">SCAN UNTUK HADIR</div>
                                </div>
                                <div class="h-3 w-4/5 rounded-lg bg-gray-200 dark:bg-slate-700 mx-auto" :class="{ 'animate-pulse': !isLoaded || activating }">
                                    <div x-show="isLoaded && !activating" class="text-[10px] text-gray-400">Pastikan jarak cukup</div>
                                </div>
                            </div>
                        </div>

                        <!-- Info & Expiry -->
                        <div class="w-full pt-2">
                            @if(isset($token) && $token && $qrEnabled)
                                @if($qrExpires)
                                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-red-50 dark:bg-red-900/20 text-red-600 rounded-full text-[11px] font-bold">
                                        <span class="material-symbols-outlined text-xs">timer</span>
                                        Berakhir: {{ \Illuminate\Support\Carbon::parse($qrExpires)->locale('id')->isoFormat('H:mm') }}
                                        <span id="qrCountdown"></span>
                                    </div>
                                @endif
                            @else
                                <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">QR BELUM AKTIF</div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="w-full flex flex-col gap-2 mt-2">
                            @if(isset($token) && $token)
                                @if($qrEnabled)
                                    <div class="grid grid-cols-1 gap-2">
                                        <a href="{{ route('qrcode.kelas.image', $token) }}" 
                                            class="flex items-center justify-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-green-600/20" 
                                            download>
                                            <span class="material-symbols-outlined text-lg">download</span>
                                            Simpan QR
                                        </a>
                                        <form action="{{ route('dosen.kelas.deactivate_qr', ['id' => $id]) }}" method="POST" class="deactivate-qr-form">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 dark:bg-slate-800 hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-600 dark:text-gray-300 hover:text-red-600 transition-all rounded-xl text-sm font-bold">
                                                <span class="material-symbols-outlined text-lg">block</span>
                                                Nonaktifkan
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <form id="activateQrForm" action="{{ route('dosen.kelas.activate_qr', ['id' => $id]) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="pertemuan" value="{{ request('pertemuan', $class_info['pertemuan'] ?? 1) }}">
                                        <button type="button" @click="showQr('activateQrForm')" 
                                            class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-primary hover:bg-primary-hover text-white rounded-xl font-bold transition-all shadow-xl shadow-primary/20 group">
                                            <span class="material-symbols-outlined transition-transform group-hover:rotate-12">bolt</span>
                                            Tampilkan QR Sekarang
                                        </button>
                                    </form>
                                @endif
                            @else
                                <form id="generateQrForm" action="{{ route('dosen.kelas.generate_qr', ['id' => $id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="pertemuan" value="{{ request('pertemuan', $class_info['pertemuan'] ?? 1) }}">
                                    <button type="button" @click="showQr('generateQrForm')" 
                                        class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-primary hover:bg-primary-hover text-white rounded-xl font-bold transition-all shadow-xl shadow-primary/20">
                                        <span class="material-symbols-outlined">add_box</span>
                                        Buat QR Absensi
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <label class="text-xs text-gray-500">Link Absensi</label>
                    <div class="mt-2 flex items-center gap-2">
                        <input id="absensiLink" type="text" readonly value="{{ (isset($token) && $token) ? route('absensi.form', ['token' => $token]) : 'N/A' }}" class="flex-1 text-sm px-3 py-2 border border-gray-200 rounded-lg bg-gray-50" />
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
                function tryCopyText(text) {
                    if (!text) return Promise.reject(new Error('no-text'));
                    // Prefer modern clipboard API
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        return navigator.clipboard.writeText(text);
                    }
                    // Fallback using execCommand (works on http in most browsers)
                    return new Promise(function(resolve, reject){
                        try {
                            const textarea = document.createElement('textarea');
                            textarea.value = text;
                            // avoid showing the element
                            textarea.style.position = 'fixed';
                            textarea.style.left = '-9999px';
                            document.body.appendChild(textarea);
                            textarea.select();
                            const ok = document.execCommand('copy');
                            document.body.removeChild(textarea);
                            if (ok) resolve(); else reject(new Error('exec-failed'));
                        } catch (e) { reject(e); }
                    });
                }

                copyBtn.addEventListener('click', function(){
                    try {
                        const text = absensiLinkEl.value || '';
                        tryCopyText(text).then(()=>{
                            if (toggleBtn) {
                                const prev = toggleBtn.innerText;
                                toggleBtn.innerText = 'Disalin';
                                setTimeout(()=> toggleBtn.innerText = prev, 900);
                            }
                            const prevBtn = copyBtn.innerText;
                            copyBtn.innerText = 'Disalin';
                            setTimeout(()=> copyBtn.innerText = prevBtn, 900);
                        }).catch(()=>{
                            // final fallback: select input and instruct user
                            if (absensiLinkEl.select) absensiLinkEl.select();
                        });
                    } catch (ex) {
                    }
                });

                // Auto-copy when clicking the input itself
                absensiLinkEl.addEventListener('click', function(){
                    const text = absensiLinkEl.value || '';
                    tryCopyText(text).then(()=>{
                        const prevBtn = copyBtn.innerText;
                        copyBtn.innerText = 'Disalin';
                        setTimeout(()=> copyBtn.innerText = prevBtn, 900);
                    }).catch(()=>{
                        if (absensiLinkEl.select) absensiLinkEl.select();
                    });
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

    {{-- Real-time attendance polling --}}
    <script>
        (function() {
            const kelasId = {{ $id }};
            const currentPertemuan = {{ request('pertemuan', $class_info['pertemuan'] ?? 1) }};
            const apiUrl = "{{ route('dosen.kelas.attendance_data', ['id' => $id]) }}";
            let lastAttendanceIds = new Set();
            let isFirstLoad = true;

            // Initialize with current attendance IDs
            @if(!empty($presensis) && count($presensis) > 0)
                @foreach($presensis as $p)
                    lastAttendanceIds.add({{ $p->id }});
                @endforeach
            @endif

            function fetchAttendanceData() {
                fetch(apiUrl + '?pertemuan=' + currentPertemuan, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.presensis) {
                        updateAttendanceTable(data.presensis);
                    }
                })
                .catch(error => {
                    console.error('Error fetching attendance data:', error);
                });
            }

            function updateAttendanceTable(presensis) {
                const tbody = document.querySelector('table tbody');
                if (!tbody) return;

                // Check if there are new entries
                const newIds = new Set(presensis.map(p => p.id));
                const hasNewEntries = !isFirstLoad && presensis.some(p => !lastAttendanceIds.has(p.id));

                if (presensis.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada peserta yang mengisi absensi.</td>
                        </tr>
                    `;
                } else {
                    tbody.innerHTML = presensis.map((p, index) => {
                        const isNew = !isFirstLoad && !lastAttendanceIds.has(p.id);
                        const rowClass = isNew ? 'bg-green-50 animate-pulse' : '';
                        
                        return `
                            <tr class="${rowClass}" data-attendance-id="${p.id}">
                                <td class="px-4 py-4">${index + 1}</td>
                                <td class="px-4 py-4">${p.nama}</td>
                                <td class="px-4 py-4">${p.kelas}</td>
                                <td class="px-4 py-4">${p.kontak}</td>
                                <td class="px-4 py-4">${p.waktu}</td>
                                <td class="px-4 py-4">-</td>
                            </tr>
                        `;
                    }).join('');

                    // Remove highlight animation after 3 seconds
                    if (hasNewEntries) {
                        setTimeout(() => {
                            document.querySelectorAll('tr.animate-pulse').forEach(row => {
                                row.classList.remove('bg-green-50', 'animate-pulse');
                            });
                        }, 3000);
                    }
                }

                // Update the set of known IDs
                lastAttendanceIds = newIds;
                isFirstLoad = false;
            }

            // Poll every 5 seconds
            const pollingInterval = setInterval(fetchAttendanceData, 5000);

            // Cleanup on page unload
            window.addEventListener('beforeunload', () => {
                clearInterval(pollingInterval);
            });
        })();
    </script>
    <script>
        // SweetAlert2 confirmation for Deactivate QR
        document.querySelectorAll('.deactivate-qr-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Nonaktifkan QR?',
                    text: 'QR absensi akan dinonaktifkan dan mahasiswa tidak bisa scan untuk absen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#8B1538',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Nonaktifkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Use native submit to bypass the event listener
                        HTMLFormElement.prototype.submit.call(form);
                    }
                });
            });
        });
    </script>
@endpush

@endsection