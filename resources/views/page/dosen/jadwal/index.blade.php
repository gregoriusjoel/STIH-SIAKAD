@extends('layouts.app')

@section('title', 'Jadwal Mengajar')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .active-nav {
            background-color: var(--color-primary);
            color: white !important;
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col min-w-0 h-full" x-data="scheduleNavigation()">
        <div class="px-6 md:px-8 py-6 w-full flex flex-col gap-6">

            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-black text-[#111218] dark:text-white">Jadwal Mengajar</h1>
                    <p class="text-[#616889] dark:text-slate-400 text-sm">Jadwal perkuliahan Semester Ganjil 2023/2024.</p>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Availability Check Button -->
                    <a href="{{ route('dosen.availability.index') }}" 
                        class="hidden md:flex items-center gap-2 px-4 py-2 bg-[#8B1538] text-white rounded-lg text-sm font-semibold shadow-sm hover:opacity-90 transition-opacity">
                        <span class="material-symbols-outlined text-[20px]">event_available</span>
                        Ketersediaan Waktu
                    </a>

                    <!-- Week Navigator -->
                    <div
                        class="flex items-center gap-2 bg-white dark:bg-[#1a1d2e] p-1 rounded-lg border border-[#dbdde6] dark:border-slate-800 shadow-sm">
                        @if($weekOffset > 0)
                            <a href="{{ route('dosen.jadwal', ['week' => $weekOffset - 1]) }}"
                                class="p-1.5 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-md text-[#616889]">
                                <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                            </a>
                        @else
                            <span class="p-1.5 rounded-md text-[#616889] opacity-50 cursor-not-allowed">
                                <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                            </span>
                        @endif

                        <span class="text-sm font-semibold px-2 text-[#111218] dark:text-white">
                            @php
                                $weekNum = ceil($weekStart->day / 7);
                                $monthName = $weekStart->translatedFormat('M');
                            @endphp
                            Minggu ke-{{ $weekNum }} ({{ $weekStart->format('d') }} - {{ $weekEnd->format('d') }}
                            {{ $monthName }})
                        </span>

                        <a href="{{ route('dosen.jadwal', ['week' => $weekOffset + 1]) }}"
                            class="p-1.5 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-md text-[#616889]">
                            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Rejected Reschedules Alert -->
            @if($rejectedReschedules->count() > 0)
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-red-500 text-xl">error</span>
                        <div class="flex-1">
                            <h4 class="font-bold text-red-800 text-sm mb-2">Permintaan Reschedule Ditolak</h4>
                            @foreach($rejectedReschedules as $rejected)
                                <div class="text-sm text-red-700 mb-1">
                                    <span class="font-semibold">{{ $rejected->kelasMataKuliah->mataKuliah->nama_mk ?? '-' }}
                                        ({{ $rejected->kelasMataKuliah->kode_kelas ?? '-' }})</span>
                                    - {{ $rejected->new_hari }} {{ substr($rejected->new_jam_mulai, 0, 5) }} -
                                    {{ substr($rejected->new_jam_selesai, 0, 5) }}
                                    @if($rejected->catatan_admin)
                                        <div class="text-xs text-red-600 italic mt-0.5">Alasan: {{ $rejected->catatan_admin }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Schedule Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @php
                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                @endphp

                @foreach($days as $day)
                    <div
                        class="bg-white dark:bg-[#1a1d2e] rounded-xl border border-[#dbdde6] dark:border-slate-800 p-5 flex flex-col gap-4 shadow-sm {{ !isset($schedulesByDay[$day]) ? 'min-h-[200px]' : '' }}">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-slate-800">
                            <h3 class="font-bold text-[#111218] dark:text-white">{{ $day }}</h3>
                            @if(isset($schedulesByDay[$day]))
                                <span class="text-xs font-medium text-[#616889]">{{ $schedulesByDay[$day]->count() }} Kelas</span>
                            @endif
                        </div>
                        @if(isset($schedulesByDay[$day]) && $schedulesByDay[$day]->count() > 0)
                            @foreach($schedulesByDay[$day] as $kelas)
                                    <!-- Class Item -->
                                    <div
                                        class="flex gap-3 relative pl-3 hover:bg-gray-50/50 rounded-r-lg transition-colors p-2 -mx-2 {{ $kelas->is_rescheduled ? 'bg-amber-50/50' : '' }}">
                                        <div
                                            class="absolute left-0 top-1 bottom-1 w-1 {{ $kelas->is_rescheduled ? 'bg-amber-500' : 'bg-red-900' }} rounded-full">
                                        </div>
                                        <div class="flex-1 flex flex-col gap-1">
                                            <div class="flex justify-between items-start">
                                                <h4 class="font-bold text-[#111218] dark:text-white text-sm">
                                                    {{ $kelas->mataKuliah->nama_mk }}</h4>
                                                <div class="flex items-center gap-1">
                                                    @if($kelas->is_rescheduled)
                                                        <span
                                                            class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded text-[10px] font-bold">RESCHEDULE</span>
                                                    @endif
                                                    @php
                                                        $jenisNorm = strtolower($kelas->mataKuliah->jenis ?? '');
                                                        $isPilihan = $jenisNorm === 'pilihan';
                                                        $jenisLabel = ucwords(str_replace('_', ' ', $jenisNorm));
                                                    @endphp
                                <span
                                                        class="bg-{{ $isPilihan ? 'blue' : 'orange' }}-50 text-{{ $isPilihan ? 'blue' : 'orange' }}-600 px-2 py-0.5 rounded text-[10px] font-bold">{{ $jenisLabel }}</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 text-xs text-[#616889]">
                                                <span
                                                    class="bg-red-50 text-red-700 px-1.5 rounded font-bold">{{ $kelas->display_kelas }}</span>
                                                <span>•</span>
                                                <span class="font-semibold">{{ $kelas->mataKuliah->sks }} SKS</span>
                                                @if($kelas->display_jam_mulai && $kelas->display_jam_selesai)
                                                    <span>•</span>
                                                    <div class="flex items-center gap-1">
                                                        <span class="material-symbols-outlined text-[14px]">schedule</span>
                                                        {{ substr($kelas->display_jam_mulai, 0, 5) }} -
                                                        {{ substr($kelas->display_jam_selesai, 0, 5) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-1 text-xs text-[#616889] mt-0.5">
                                                <span class="material-symbols-outlined text-[14px]">location_on</span>
                                                {{ $kelas->display_ruang ?: 'Belum ditentukan' }}
                                            </div>
                                            <!-- Reschedule Button (only show if not already rescheduled this week and no pending request) -->
                                            @if($kelas->is_rescheduled)
                                                {{-- Already rescheduled badge shown above --}}
                                            @elseif($kelas->has_pending_reschedule)
                                                <div class="mt-2">
                                                    <span
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-yellow-100 text-yellow-700 border border-yellow-300 rounded-md text-xs font-semibold">
                                                        <span class="material-symbols-outlined text-[14px]">hourglass_top</span>
                                                        Menunggu Approval
                                                    </span>
                                                </div>
                                            @else
                                                <div class="mt-2">
                                                    <button type="button"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#8B1538] text-white rounded-md text-xs font-semibold shadow hover:opacity-90"
                                                        @click="openRescheduleModal({
                                                                    id: {{ $kelas->id }},
                                                                    mata_kuliah: '{{ $kelas->mataKuliah->nama_mk }}',
                                                                    kode_kelas: '{{ $kelas->kode_kelas }}',
                                                                    hari: '{{ $kelas->hari }}',
                                                                    jam_mulai: '{{ $kelas->jam_mulai ? substr($kelas->jam_mulai, 0, 5) : '' }}',
                                                                    jam_selesai: '{{ $kelas->jam_selesai ? substr($kelas->jam_selesai, 0, 5) : '' }}'
                                                                })">
                                                        <span class="material-symbols-outlined text-[14px]">calendar_month</span>
                                                        Reschedule
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                            @endforeach
                        @else
                            <div class="flex-1 flex flex-col items-center justify-center text-center gap-2 text-[#616889]">
                                <p class="text-sm">Tidak ada jadwal mengajar</p>
                            </div>
                        @endif
                    </div>
                @endforeach

            </div>

            <!-- Buttons to Redirect -->
            <!-- Reschedule Modal -->
            <!-- Reschedule Modal -->
            <div x-show="showRescheduleModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/40 backdrop-blur"
                    style="backdrop-filter: blur(2px); -webkit-backdrop-filter: blur(2px);"
                    @click="showRescheduleModal = false">
                </div>

                <!-- Modal Card -->
                <div x-show="showRescheduleModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-3"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative bg-white dark:bg-[#1a1d2e] rounded-2xl shadow-2xl max-w-2xl w-full mx-4 p-6 z-50 max-h-[90vh] overflow-y-auto">

                    <!-- Header -->
                    <div class="mb-5">
                        <h3 class="text-lg font-black text-[#111218] dark:text-white">
                            Reschedule Jadwal
                        </h3>
                        <p class="text-sm text-[#616889] dark:text-slate-400 mt-1">
                            Ajukan perubahan hari atau jam mengajar
                        </p>
                        <!-- Week Indicator -->
                        <div
                            class="mt-3 px-3 py-2 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <div class="flex items-center gap-2 text-sm">
                                <span
                                    class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-[18px]">calendar_month</span>
                                <span class="text-blue-700 dark:text-blue-300 font-medium">
                                    Berlaku untuk: <strong>{{ $weekStart->format('d M') }} -
                                        {{ $weekEnd->format('d M Y') }}</strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form method="POST" :action="rescheduleFormAction" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <input type="hidden" name="kelas_mata_kuliah_id" :value="rescheduleData.id">
                        <input type="hidden" name="week_offset" value="{{ $weekOffset }}">

                        <!-- Mata Kuliah -->
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-[#111218] dark:text-white">
                                Mata Kuliah
                            </label>
                            <input type="text" class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 
                        rounded-lg bg-gray-100 dark:bg-slate-800 text-sm"
                                :value="rescheduleData.mata_kuliah + ' (' + rescheduleData.kode_kelas + ')'" readonly>
                        </div>

                        <!-- Hari -->
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-[#111218] dark:text-white">
                                Hari <span class="text-red-500">*</span>
                            </label>
                            <select name="new_hari" x-model="rescheduleData.hari" @change="checkRoomAvailability()" class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 
                        rounded-lg bg-white dark:bg-slate-800 text-sm
                        focus:outline-none focus:ring-1 focus:ring-[#8B1538]">
                                <template x-for="h in ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']" :key="h">
                                    <option :value="h" x-text="h"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Jam -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-[#111218] dark:text-white">
                                    Jam Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="new_jam_mulai" x-model="rescheduleData.jam_mulai"
                                    @change="checkRoomAvailability()" required class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 
                            rounded-lg bg-white dark:bg-slate-800 text-sm
                            focus:outline-none focus:ring-1 focus:ring-[#8B1538]">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2 text-[#111218] dark:text-white">
                                    Jam Selesai <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="new_jam_selesai" x-model="rescheduleData.jam_selesai"
                                    @change="checkRoomAvailability()" required class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 
                            rounded-lg bg-white dark:bg-slate-800 text-sm
                            focus:outline-none focus:ring-1 focus:ring-[#8B1538]">
                            </div>
                        </div>

                        <!-- Hidden input for room (selected from availability card) -->
                        <input type="hidden" name="new_ruang" :value="rescheduleData.ruang">

                        <!-- Metode Pengajaran -->
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-[#111218] dark:text-white">
                                Metode Pengajaran <span class="text-red-500">*</span>
                            </label>
                            <select name="metode_pengajaran" x-model="rescheduleData.metode_pengajaran"
                                @change="onMetodeChange()"
                                class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-1 focus:ring-[#8B1538]">
                                <option value="offline">Offline (Tatap Muka)</option>
                                <option value="online">Online (Sesi Live)</option>
                                <option value="asynchronous">Asynchronous (Tugas / Materi Mandiri)</option>
                            </select>
                        </div>

                        <!-- Online link input (visible when metode = online) -->
                        <div x-show="rescheduleData.metode_pengajaran === 'online'" x-cloak>
                            <label class="block text-sm font-semibold mb-2 text-[#111218] dark:text-white">Link Meeting
                                (GMeet/Zoom) <span class="text-red-500">*</span></label>
                            <input type="url" name="online_link" x-model="rescheduleData.online_link"
                                placeholder="https://meet.google.com/... atau https://zoom.us/meet/..."
                                class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-sm">
                        </div>

                        <!-- Asynchronous task input (visible when metode = asynchronous) -->
                        <div x-show="rescheduleData.metode_pengajaran === 'asynchronous'" x-cloak>
                            <label class="block text-sm font-semibold mb-2 text-[#111218] dark:text-white">Tugas / Instruksi
                                Asynchronous <span class="text-red-500">*</span></label>
                            <textarea name="asynchronous_tugas" x-model="rescheduleData.asynchronous_tugas" rows="3"
                                class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-sm"
                                placeholder="Deskripsikan tugas atau materi yang harus dikerjakan mahasiswa..."></textarea>
                        </div>

                        <!-- Optional PDF upload for asynchronous metode -->
                        <div x-show="rescheduleData.metode_pengajaran === 'asynchronous'" x-cloak class="mt-2">
                            <label class="block text-sm font-semibold mb-2 text-[#111218] dark:text-white">Lampirkan PDF
                                (opsional)</label>
                            <div class="flex items-center gap-2">
                                <input type="file" name="asynchronous_file" accept="application/pdf" x-ref="asyncFile"
                                    @change="(e) => { const f = e.target.files[0]; rescheduleData.asynchronous_file_name = f ? f.name : ''; }"
                                    class="text-sm">
                                <div class="text-sm text-gray-600"
                                    x-text="rescheduleData.asynchronous_file_name ? rescheduleData.asynchronous_file_name : 'Belum ada file yang dipilih'">
                                    Belum ada file yang dipilih</div>
                            </div>
                        </div>

                        <!-- Room Availability Card (visible only for Offline metode) -->
                        <div x-show="rescheduleData.metode_pengajaran === 'offline'" x-cloak
                            class="border rounded-lg p-3 bg-gray-50 dark:bg-slate-800 max-h-64 overflow-y-auto">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-xs font-semibold text-gray-600 dark:text-gray-300 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">meeting_room</span>
                                    Ketersediaan Ruangan
                                    <span class="text-gray-400 ml-1"
                                        x-text="rescheduleData.hari ? '(' + rescheduleData.hari + ')' : ''"></span>
                                </div>
                                <!-- Filter Buttons -->
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="roomFilter = 'all'"
                                        :class="roomFilter === 'all' ? 'bg-gray-600 text-white' : 'bg-gray-200 text-gray-600 hover:bg-gray-300'"
                                        class="px-2 py-0.5 rounded text-[10px] font-bold transition-all">
                                        Semua
                                    </button>
                                    <button type="button" @click="roomFilter = 'available'"
                                        :class="roomFilter === 'available' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-700 hover:bg-green-200'"
                                        class="px-2 py-0.5 rounded text-[10px] font-bold transition-all flex items-center gap-1">
                                        <span class="w-2 h-2 bg-green-300 rounded"></span> Tersedia
                                    </button>
                                    <button type="button" @click="roomFilter = 'occupied'"
                                        :class="roomFilter === 'occupied' ? 'bg-red-600 text-white' : 'bg-red-100 text-red-700 hover:bg-red-200'"
                                        class="px-2 py-0.5 rounded text-[10px] font-bold transition-all flex items-center gap-1">
                                        <span class="w-2 h-2 bg-red-300 rounded"></span> Terpakai
                                    </button>
                                </div>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                <div class="grid auto-rows-fr gap-2"
                                    style="grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));">
                                    @foreach($daftarRuangan as $room)
                                        <div x-show="roomFilter === 'all' || (roomFilter === 'available' && isRoomAvailable('{{ $room->kode_ruangan }}')) || (roomFilter === 'occupied' && !isRoomAvailable('{{ $room->kode_ruangan }}'))"
                                            class="p-2 rounded-md text-sm font-semibold cursor-pointer transition-all text-center flex items-center justify-center"
                                            :class="{
                                                  'bg-green-50 text-green-800 border border-green-200 hover:bg-green-100': isRoomAvailable('{{ $room->kode_ruangan }}') && rescheduleData.ruang !== '{{ $room->kode_ruangan }}',
                                                  'bg-red-50 text-red-800 border border-red-200 opacity-90': !isRoomAvailable('{{ $room->kode_ruangan }}') && rescheduleData.ruang !== '{{ $room->kode_ruangan }}',
                                                  'bg-blue-600 text-white ring-2 ring-blue-400': rescheduleData.ruang === '{{ $room->kode_ruangan }}'
                                              }"
                                            @click="if(isRoomAvailable('{{ $room->kode_ruangan }}')) { rescheduleData.ruang = '{{ $room->kode_ruangan }}' }"
                                            :title="isRoomAvailable('{{ $room->kode_ruangan }}') ? 'Tersedia - Klik untuk memilih' : getRoomConflict('{{ $room->kode_ruangan }}')">
                                            <span>{{ $room->kode_ruangan }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Selected Room Display (only for Offline metode) -->
                        <div x-show="rescheduleData.metode_pengajaran === 'offline' && rescheduleData.ruang" x-cloak
                            class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <div class="flex items-center gap-2 text-sm">
                                <span class="material-symbols-outlined text-blue-600 text-lg">check_circle</span>
                                <span class="text-blue-700">Ruangan dipilih: <strong
                                        x-text="rescheduleData.ruang"></strong></span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-3 pt-4">
                            <button type="button" @click="showRescheduleModal = false" class="px-4 py-2 rounded-lg border text-sm font-semibold 
                        hover:bg-gray-100 dark:hover:bg-slate-800">
                                Batal
                            </button>

                            <button type="submit"
                                :disabled="!( (rescheduleData.metode_pengajaran !== 'offline' || rescheduleData.ruang) && rescheduleData.hari && rescheduleData.jam_mulai && rescheduleData.jam_selesai && (rescheduleData.metode_pengajaran !== 'online' || rescheduleData.online_link) && (rescheduleData.metode_pengajaran !== 'asynchronous' || rescheduleData.asynchronous_tugas) )"
                                :class="{'opacity-50 cursor-not-allowed': !( (rescheduleData.metode_pengajaran !== 'offline' || rescheduleData.ruang) && rescheduleData.hari && rescheduleData.jam_mulai && rescheduleData.jam_selesai && (rescheduleData.metode_pengajaran !== 'online' || rescheduleData.online_link) && (rescheduleData.metode_pengajaran !== 'asynchronous' || rescheduleData.asynchronous_tugas) ) }"
                                class="px-5 py-2 rounded-lg bg-[#8B1538] text-white text-sm font-semibold shadow hover:opacity-90">
                                Ubah Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Availability Check Modal -->
            <div x-show="showAvailabilityModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/40 backdrop-blur"
                    style="backdrop-filter: blur(2px); -webkit-backdrop-filter: blur(2px);"
                    @click="showAvailabilityModal = false">
                </div>

                <!-- Modal Card -->
                <div x-show="showAvailabilityModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-3"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative bg-white dark:bg-[#1a1d2e] rounded-2xl shadow-2xl max-w-md w-full mx-4 p-6 z-50">

                    <!-- Header -->
                    <div class="mb-5">
                        <h3 class="text-lg font-black text-[#111218] dark:text-white">
                            Cek Ketersediaan Waktu
                        </h3>
                        <p class="text-sm text-[#616889] dark:text-slate-400 mt-1">
                            Pilih mata kuliah dan waktu untuk mengecek ketersediaan.
                        </p>
                    </div>

                    <!-- Form -->
                    <div class="space-y-4">
                        <!-- Mata Kuliah -->
                        <form action="{{ route('dosen.jadwal.check_availability') }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-[#111218] dark:text-white">
                                        Mata Kuliah
                                    </label>
                                    <select name="mata_kuliah_id" x-model="availabilityData.mata_kuliah_id" class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 
                                rounded-lg bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-1 focus:ring-[#8B1538]" required>
                                        <option value="">Pilih Mata Kuliah</option>
                                        @php
                                            $uniqueMKs = $kelasMataKuliahs->unique('mata_kuliah_id');
                                        @endphp
                                        @foreach($uniqueMKs as $mk)
                                            <option value="{{ $mk->mata_kuliah_id }}">{{ $mk->mataKuliah->nama_mk }}</option>
                                        @endforeach
                                    </select>
                                </div>
        
                                <!-- Hari -->
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-[#111218] dark:text-white">
                                        Hari
                                    </label>
                                    <select name="hari" x-model="availabilityData.hari" class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 
                                rounded-lg bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-1 focus:ring-[#8B1538]" required>
                                        <option value="">Pilih Hari</option>
                                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                            <option value="{{ $hari }}">{{ $hari }}</option>
                                        @endforeach
                                    </select>
                                </div>
        
                                <!-- Jam -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold mb-2 text-[#111218] dark:text-white">
                                            Jam Mulai
                                        </label>
                                        <input type="time" name="jam_mulai" x-model="availabilityData.jam_mulai" class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 
                                    rounded-lg bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-1 focus:ring-[#8B1538]" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold mb-2 text-[#111218] dark:text-white">
                                            Jam Selesai
                                        </label>
                                        <input type="time" name="jam_selesai" x-model="availabilityData.jam_selesai" class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 
                                    rounded-lg bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-1 focus:ring-[#8B1538]" required>
                                    </div>
                                </div>
        
                                <!-- Actions -->
                                <div class="flex justify-end gap-3 pt-4">
                                    <button type="button" @click="showAvailabilityModal = false" class="px-4 py-2 rounded-lg border text-sm font-semibold 
                                hover:bg-gray-100 dark:hover:bg-slate-800">
                                        Batal
                                    </button>
        
                                    <button type="submit" 
                                        class="px-5 py-2 rounded-lg bg-[#8B1538] text-white text-sm font-semibold shadow hover:opacity-90">
                                        Cek Ketersediaan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Creation and pending submission UI removed -->
            <div class="mt-6"></div>
        </div>

    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('scheduleNavigation', () => ({
                    currentDate: new Date(),
                    minDate: new Date(),
                    weekOffset: {{ $weekOffset }},

                    // All schedules for room availability checking
                    allSchedules: @js($allSchedules),

                    // Room filter state: 'all', 'available', 'occupied'
                    roomFilter: 'all',

                    // Modal state
                    showRescheduleModal: false,
                    showAvailabilityModal: false,
                    
                    rescheduleData: {
                        id: '',
                        mata_kuliah: '',
                        kode_kelas: '',
                        hari: '',
                        jam_mulai: '',
                        jam_selesai: '',
                        ruang: '',
                        metode_pengajaran: 'offline',
                        online_link: '',
                        asynchronous_tugas: '',
                        asynchronous_file_name: ''
                    },
                    
                    availabilityData: {
                        mata_kuliah_id: '',
                        hari: '',
                        jam_mulai: '',
                        jam_selesai: ''
                    },
                    get rescheduleFormAction() {
                        return '/dosen/kelas/reschedule';
                    },
                    openRescheduleModal(kelas) {
                        this.rescheduleData = { ...kelas, ruang: '', metode_pengajaran: 'offline', online_link: '', asynchronous_tugas: '' };
                        this.showRescheduleModal = true;
                    },

                    onMetodeChange() {
                        // Clear fields when switching metode to avoid accidental submission
                        if (this.rescheduleData.metode_pengajaran !== 'online') {
                            this.rescheduleData.online_link = '';
                        }
                        if (this.rescheduleData.metode_pengajaran !== 'asynchronous') {
                            this.rescheduleData.asynchronous_tugas = '';
                            this.rescheduleData.asynchronous_file_name = '';
                            if (this.$refs && this.$refs.asyncFile) this.$refs.asyncFile.value = '';
                        }
                        if (this.rescheduleData.metode_pengajaran !== 'offline') {
                            this.rescheduleData.ruang = '';
                        }
                    },

                    // Check if a room is available for the selected day and time
                    isRoomAvailable(room) {
                        if (!this.rescheduleData.hari || !this.rescheduleData.jam_mulai || !this.rescheduleData.jam_selesai) {
                            return true; // Show all as available if time not selected yet
                        }

                        const conflict = this.allSchedules.find(s => {
                            if (s.id === this.rescheduleData.id) return false; // Exclude current class
                            if (s.ruang !== room) return false;
                            if (s.hari !== this.rescheduleData.hari) return false;

                            // Check time overlap: (StartA < EndB) && (EndA > StartB)
                            return s.jam_mulai < this.rescheduleData.jam_selesai &&
                                s.jam_selesai > this.rescheduleData.jam_mulai;
                        });

                        return !conflict;
                    },

                    // Get conflict info for tooltip
                    getRoomConflict(room) {
                        if (!this.rescheduleData.hari || !this.rescheduleData.jam_mulai || !this.rescheduleData.jam_selesai) {
                            return '';
                        }

                        const conflict = this.allSchedules.find(s => {
                            if (s.id === this.rescheduleData.id) return false;
                            if (s.ruang !== room) return false;
                            if (s.hari !== this.rescheduleData.hari) return false;
                            return s.jam_mulai < this.rescheduleData.jam_selesai &&
                                s.jam_selesai > this.rescheduleData.jam_mulai;
                        });

                        if (conflict) {
                            return `Terpakai: ${conflict.dosen} (${conflict.mk}) ${conflict.jam_mulai}-${conflict.jam_selesai}`;
                        }
                        return '';
                    },

                    checkRoomAvailability() {
                        // Reset room selection when time changes to force re-evaluation
                        // This is handled reactively by Alpine.js
                    },

                    init() {
                        // Set to current week's Monday
                        this.normalizeToMonday(this.currentDate);
                        this.normalizeToMonday(this.minDate);
                    },
                    normalizeToMonday(date) {
                        const day = date.getDay();
                        const diff = date.getDate() - day + (day == 0 ? -6 : 1); // adjust when day is sunday
                        date.setDate(diff);
                        date.setHours(0, 0, 0, 0);
                    },
                    nextWeek() {
                        this.currentDate.setDate(this.currentDate.getDate() + 7);
                        this.currentDate = new Date(this.currentDate);
                    },
                    prevWeek() {
                        if (this.canGoBack) {
                            this.currentDate.setDate(this.currentDate.getDate() - 7);
                            this.currentDate = new Date(this.currentDate);
                        }
                    },
                    get canGoBack() {
                        return this.currentDate.getTime() > this.minDate.getTime();
                    },
                    get displayText() {
                        const start = new Date(this.currentDate);
                        const end = new Date(this.currentDate);
                        end.setDate(end.getDate() + 5); // Saturday
                        const weekNum = Math.ceil(start.getDate() / 7);
                        const monthName = new Intl.DateTimeFormat('id-ID', { month: 'short' }).format(start);
                        return `Minggu ke-${weekNum} (${start.getDate()} - ${end.getDate()} ${monthName})`;
                    }
                }))
            });
            // pending check removed
        </script>
    @endpush
@endsection