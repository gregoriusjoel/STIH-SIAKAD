@extends('layouts.admin')
@section('title', 'Jadwal Perkuliahan')
@section('page-title', 'Jadwal Perkuliahan')

@push('styles')
    <style>
        .tab-btn.active {
            border-color: #8B1538;
            color: #8B1538;
            background-color: #FEF2F2;
        }
        .dark .tab-btn.active {
            background-color: rgba(139, 21, 56, 0.2);
            color: #f87171;
            border-color: #f87171;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        @if(!empty($roomsMissing))
            <div class="mb-4 p-4 rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-800">
                <strong>Perhatian:</strong> Kode ruangan belum tersedia atau belum ada data ruangan. Silakan tambahkan data ruangan terlebih dahulu.
                @if(Route::has('admin.ruangan.index'))
                    <a href="{{ route('admin.ruangan.index') }}" class="underline font-semibold ml-2">Buka Master Ruangan</a>
                @endif
            </div>
        @endif
        {{-- Cards Layout: Pending, Waiting Room, Active --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Left Column (50%): Form Tambah Kelas --}}
            <div class="flex flex-col">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden flex-1 flex flex-col">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-maroon text-white">
                        <div class="flex items-center justify-between">
                            <div class="font-semibold text-lg flex items-center text-white"><i class="fas fa-plus-circle mr-2"></i>Tambah
                                Jadwal Baru</div>
                            <button type="button" onclick="openRoomUsageModal()"
                                class="px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white text-sm rounded-lg transition flex items-center gap-2 border border-white/20">
                                <i class="fas fa-door-open"></i>
                                <span>Lihat Penggunaan Ruangan</span>
                            </button>
                        </div>
                    </div>
                    <form action="{{ route('admin.kelas-mata-kuliah.store') }}" method="POST" class="p-4" x-data="{
                                                              mataKuliahId: '',
                                                              dosens: [],
                                                              selectedDosenId: '',
                                                              selectedDosenSks: 0,
                                                              loading: false,

                                                              // Room Validation
                                                              hari: '',
                                                              jamMulai: '',
                                                              jamSelesai: '',
                                                              ruanganId: '',
                                                              jamPerkuliahanId: '',
                                                              kuota: '',
                                                              roomStatus: { available: true, message: '' },
                                                              checkingRoom: false,

                                                              updateSks() {
                                                                  const dosen = this.dosens.find(d => d.id == this.selectedDosenId);
                                                                  this.selectedDosenSks = dosen ? dosen.total_sks : 0;
                                                              },

                                                              updateJamFromPerkuliahan() {
                                                                  if (!this.jamPerkuliahanId) {
                                                                      this.jamMulai = '';
                                                                      this.jamSelesai = '';
                                                                      return;
                                                                  }
                                                                  const select = document.querySelector('select[name=jam_perkuliahan_id]');
                                                                  const option = select.options[select.selectedIndex];
                                                                  this.jamMulai = option.dataset.mulai || '';
                                                                  this.jamSelesai = option.dataset.selesai || '';
                                                              },

                                                              async fetchDosens() {
                                                                  if (!this.mataKuliahId) {
                                                                      this.dosens = [];
                                                                      this.selectedDosenId = '';
                                                                      return;
                                                                  }
                                                                  this.loading = true;
                                                                  try {
                                                                      const response = await fetch(`/api/dosens-by-mata-kuliah/${this.mataKuliahId}`);
                                                                      this.dosens = await response.json();
                                                                      if (this.dosens.length === 1) {
                                                                          this.selectedDosenId = this.dosens[0].id;
                                                                          this.selectedDosenSks = this.dosens[0].total_sks;
                                                                      } else {
                                                                          this.selectedDosenId = '';
                                                                          this.selectedDosenSks = 0;
                                                                      }
                                                                  } catch (e) {
                                                                      console.error('Error fetching dosens:', e);
                                                                      this.dosens = [];
                                                                  }
                                                                  this.loading = false;
                                                              },

                                                              async checkRoom() {
                                                                  if (!this.hari || !this.jamMulai || !this.jamSelesai || !this.ruanganId) {
                                                                      this.roomStatus = { available: true, message: '' };
                                                                      return;
                                                                  }

                                                                  this.checkingRoom = true;
                                                                  try {
                                                                      const params = new URLSearchParams({
                                                                          hari: this.hari,
                                                                          jam_mulai: this.jamMulai,
                                                                          jam_selesai: this.jamSelesai,
                                                                          ruangan_id: this.ruanganId
                                                                      });
                                                                      const response = await fetch(`/api/check-room-availability?${params.toString()}`);
                                                                      const data = await response.json();
                                                                      this.roomStatus = data;
                                                                  } catch (e) {
                                                                      console.error('Error checking room:', e);
                                                                  }
                                                                  this.checkingRoom = false;
                                                              },

                                                              updateKuota() {
                                                                  if (!this.ruanganId) {
                                                                      this.kuota = '';
                                                                      return;
                                                                  }
                                                                  const select = document.querySelector('select[name=ruangan_id]');
                                                                  const option = select.options[select.selectedIndex];
                                                                  this.kuota = option.dataset.kapasitas || '';
                                                              }
                                                          }">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><i
                                        class="fas fa-book text-gray-400 dark:text-gray-500 mr-1"></i>Mata Kuliah <span
                                        class="text-red-500">*</span></label>
                                <select name="mata_kuliah_id" x-model="mataKuliahId" @change="fetchDosens()"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm"
                                    required>
                                    <option value="">Pilih Mata Kuliah</option>
                                    @foreach($mataKuliahs as $mk)
                                        <option value="{{ $mk->id }}">{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><i
                                        class="fas fa-users text-gray-400 dark:text-gray-500 mr-1"></i>Nama Kelas <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="nama_kelas"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm"
                                    placeholder="A, B, C..." required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><i
                                        class="fas fa-user-tie text-gray-400 dark:text-gray-500 mr-1"></i>Dosen <span
                                        class="text-red-500">*</span></label>

                                {{-- Loading state --}}
                                <div x-show="loading"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-sm">
                                    <i class="fas fa-spinner fa-spin mr-1"></i> Memuat dosen...
                                </div>

                                {{-- No mata kuliah selected --}}
                                <div x-show="!loading && !mataKuliahId"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-400 text-sm">
                                    <i class="fas fa-info-circle mr-1"></i> Pilih mata kuliah terlebih dahulu
                                </div>

                                {{-- No dosen available --}}
                                <div x-show="!loading && mataKuliahId && dosens.length === 0"
                                    class="w-full px-3 py-2 border border-red-300 rounded-lg bg-red-50 text-red-500 text-sm">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Tidak ada dosen yang mengajar mata
                                    kuliah ini
                                </div>

                                {{-- Single dosen (auto-select, readonly) --}}
                                <template x-if="!loading && dosens.length === 1">
                                    <div>
                                        <input type="hidden" name="dosen_id" :value="selectedDosenId">
                                        <div
                                            class="w-full px-3 py-2 border border-green-300 rounded-lg bg-green-50 text-green-700 text-sm flex items-center justify-between">
                                            <span><i class="fas fa-check-circle mr-1"></i> <span
                                                    x-text="dosens[0].name"></span></span>
                                            <i class="fas fa-lock text-green-400 text-xs"></i>
                                        </div>
                                    </div>
                                </template>

                                {{-- Multiple dosens (dropdown) --}}
                                <select x-show="!loading && dosens.length > 1" name="dosen_id" x-model="selectedDosenId" @change="updateSks()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm"
                                    :required="dosens.length > 1">
                                    <option value="">Pilih Dosen</option>
                                    <template x-for="dosen in dosens" :key="dosen.id">
                                        <option :value="dosen.id" x-text="dosen.name"></option>
                                    </template>
                                </select>

                                {{-- Alert Low SKS --}}
                                <div x-show="selectedDosenId && selectedDosenSks < 20" 
                                     class="mt-2 bg-yellow-50 border border-yellow-200 text-yellow-800 px-3 py-2 rounded-lg text-sm flex items-start gap-2 animate-pulse">
                                    <i class="fas fa-exclamation-triangle mt-0.5 text-yellow-600"></i>
                                    <div>
                                        <strong>Perhatian:</strong>
                                        <span class="block text-xs">Total SKS dosen ini baru <span class="font-bold" x-text="selectedDosenSks"></span> SKS (Belum mencapai 20 SKS).</span>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><i
                                            class="fas fa-user-friends text-gray-400 dark:text-gray-500 mr-1"></i>Kuota <span
                                            class="text-red-500">*</span></label>

                                    {{-- No room selected --}}
                                    <div x-show="!ruanganId"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-400 dark:text-gray-500 text-sm">
                                        <i class="fas fa-info-circle mr-1"></i> Pilih ruangan terlebih dahulu
                                    </div>

                                    {{-- Room selected (auto-fill, readonly) --}}
                                    <template x-if="ruanganId">
                                        <div>
                                            <input type="hidden" name="kuota" :value="kuota">
                                            <div
                                                class="w-full px-3 py-2 border border-green-300 dark:border-green-600 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-sm flex items-center justify-between">
                                                <span><i class="fas fa-check-circle mr-1"></i> <span
                                                        x-text="kuota + ' Mahasiswa'"></span></span>
                                                <i class="fas fa-lock text-green-400 dark:text-green-500 text-xs"></i>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><i
                                            class="fas fa-door-open text-gray-400 dark:text-gray-500 mr-1"></i>Ruangan <span
                                            class="text-red-500">*</span></label>
                                    <select name="ruangan_id" x-model="ruanganId" @change="updateKuota(); checkRoom()"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm"
                                        required>
                                        <option value="">Pilih Ruangan</option>
                                        @foreach($daftarRuangan as $r)
                                            <option value="{{ $r->id }}" data-kapasitas="{{ $r->kapasitas }}">{{ $r->kode_ruangan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div x-show="!roomStatus.available"
                                class="bg-red-50 border border-red-200 text-red-600 px-3 py-2 rounded-lg text-xs flex items-start gap-2">
                                <i class="fas fa-exclamation-circle mt-0.5"></i>
                                <span x-text="roomStatus.message"></span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><i
                                        class="fas fa-calendar-day text-gray-400 dark:text-gray-500 mr-1"></i>Hari <span
                                        class="text-red-500">*</span></label>
                                <select name="hari" x-model="hari" @change="checkRoom()"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm"
                                    required>
                                    <option value="">Pilih Hari</option>
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                                        <option value="{{ $h }}">{{ $h }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><i
                                        class="fas fa-clock text-gray-400 dark:text-gray-500 mr-1"></i>Jam Perkuliahan <span
                                        class="text-red-500">*</span></label>
                                <select name="jam_perkuliahan_id" x-model="jamPerkuliahanId" @change="updateJamFromPerkuliahan(); checkRoom();"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm"
                                    required>
                                    <option value="">Pilih Jam Perkuliahan</option>
                                    @foreach($jamPerkuliahan as $jp)
                                        <option value="{{ $jp->id }}" data-mulai="{{ date('H:i', strtotime($jp->jam_mulai)) }}" data-selesai="{{ date('H:i', strtotime($jp->jam_selesai)) }}">
                                            Jam ke-{{ $jp->jam_ke }} ({{ date('H.i', strtotime($jp->jam_mulai)) }} - {{ date('H.i', strtotime($jp->jam_selesai)) }})
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="jam_mulai" x-model="jamMulai">
                                <input type="hidden" name="jam_selesai" x-model="jamSelesai">
                            </div>
                            <button type="submit" :disabled="!roomStatus.available || checkingRoom"
                                :class="{'opacity-50 cursor-not-allowed': !roomStatus.available || checkingRoom, 'hover:bg-maroon-700': roomStatus.available && !checkingRoom}"
                                class="w-full bg-maroon text-white px-4 py-2 rounded-lg transition flex items-center justify-center gap-2 text-sm font-semibold shadow-md mt-4">
                                <i class="fas fa-save"></i> Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Right Column (50%): Cards & Table --}}
            <div class="space-y-6">
                {{-- Generator: Auto Generate Jadwal (embedded) --}}
                @include('admin.jadwal._generator_partial')

                {{-- Card: Active Schedules (Full Width inside Right Column) --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-maroon text-white flex items-center justify-between">
                        <div class="font-semibold text-white text-lg"><i class="fas fa-check-circle mr-2"></i>Jadwal Aktif
                        </div>
                        <div class="text-lg">
                            @if($kelasMataKuliahs->count() > 0)
                                <span
                                    class="ml-2 px-2 py-0.5 bg-white text-maroon text-xs rounded-full">{{ $kelasMataKuliahs->total() }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-base">
                                <thead class="bg-maroon text-white">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold">Hari/Jam</th>
                                        <th class="px-4 py-3 text-left font-semibold">Mata Kuliah</th>
                                        <th class="px-4 py-3 text-left font-semibold">Dosen</th>
                                        <th class="px-4 py-3 text-left font-semibold">Ruang</th>
                                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($kelasMataKuliahs as $k)
                                        <tr class="hover:bg-maroon-50 dark:hover:bg-maroon-900/10 transition duration-200">
                                            <td class="px-4 py-3">
                                                <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $k->hari ?: '-' }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $k->jam_mulai ? substr($k->jam_mulai, 0, 5) : '-' }} -
                                                    {{ $k->jam_selesai ? substr($k->jam_selesai, 0, 5) : '-' }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="font-bold text-lg">{{ $k->nama_mk }}</div>
                                                <div class="text-sm font-bold text-gray-500">{{ $k->kode_kelas }} •
                                                    {{ $k->sks }} SKS
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $k->dosen_name }}
                                            </td>
                                            <td class="px-4 py-3 font-medium">{{ $k->ruang ?: '-' }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="flex justify-center space-x-1">
                                                    @if($k->type === 'jadwal')
                                                        <a href="{{ route('admin.jadwal.edit', $k->db_id) }}"
                                                            class="bg-yellow-500 text-white p-1.5 rounded hover:bg-yellow-600 transition"
                                                            title="Edit"><i class="fas fa-edit text-xs"></i></a>
                                                        <form action="{{ route('admin.jadwal.destroy', $k->db_id) }}"
                                                            method="POST" class="delete-form">@csrf
                                                            @method('DELETE')<button type="submit"
                                                                class="bg-maroon text-white p-1.5 rounded hover:bg-maroon-700 transition"
                                                                title="Hapus"><i class="fas fa-trash text-xs"></i></button></form>
                                                    @else
                                                        <a href="{{ route('admin.kelas-mata-kuliah.edit', $k->db_id) }}"
                                                            class="bg-yellow-500 text-white p-1.5 rounded hover:bg-yellow-600 transition"
                                                            title="Edit"><i class="fas fa-edit text-xs"></i></a>
                                                        <form action="{{ route('admin.kelas-mata-kuliah.destroy', $k->db_id) }}"
                                                            method="POST" class="delete-form">@csrf
                                                            @method('DELETE')<button type="submit"
                                                                class="bg-maroon text-white p-1.5 rounded hover:bg-maroon-700 transition"
                                                                title="Hapus"><i class="fas fa-trash text-xs"></i></button></form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada jadwal aktif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($kelasMataKuliahs->hasPages())
                            <div class="mt-4">{{ $kelasMataKuliahs->links() }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-times-circle text-red-500 mr-2"></i>Tolak
                Jadwal</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span
                            class="text-red-500">*</span></label>
                    <textarea name="catatan" rows="3" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeRejectModal()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-maroon text-white rounded-lg hover:bg-maroon-700 transition">Tolak
                        Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Reject Modal for Kelas Reschedule (Weekly) --}}
    <div id="rejectModalKelas" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center"
        style="display: none;">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-times-circle text-red-500 mr-2"></i>Tolak
                Reschedule Mingguan</h3>
            <form id="rejectFormKelas" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span
                            class="text-red-500">*</span></label>
                    <textarea name="catatan_admin" rows="3" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeRejectModalWeekly()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-maroon text-white rounded-lg hover:bg-maroon-700 transition"
                        onclick="return confirm('Yakin ingin menolak permintaan reschedule ini?')">Tolak</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Room Usage Sidebar --}}
    <div id="roomUsageModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 pointer-events-none"></div>
        <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white dark:bg-gray-800 shadow-2xl transform transition-transform duration-300 ease-out translate-x-full z-10" 
             id="roomUsageSidebar"
             x-data="{
                selectedDay: '{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd') }}',
                schedules: @js($allSchedules->map(function ($s) {
                    return [
                        'id' => $s->id,
                        'hari' => $s->hari,
                        'ruang' => $s->ruang,
                        'jam' => substr($s->jam_mulai, 0, 5) . '-' . substr($s->jam_selesai, 0, 5),
                        'mk' => $s->mataKuliah->nama_mk ?? '',
                        'dosen' => $s->dosen->user->name ?? ''
                    ];
                })),
                getRoomSchedules(room) {
                    return this.schedules.filter(s => s.ruang === room && s.hari === this.selectedDay);
                }
             }">
            {{-- Sidebar Header --}}
            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-maroon to-red-900 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div class="font-bold text-white text-lg flex items-center gap-2">
                        <i class="fas fa-door-open"></i>
                        Penggunaan Ruangan
                    </div>
                    <button type="button" onclick="closeRoomUsageModal()"
                        class="p-1.5 hover:bg-white/10 rounded-lg transition">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                <div class="relative">
                    <select x-model="selectedDay"
                        class="w-full appearance-none pl-3 pr-10 py-2 border border-white/20 rounded-lg text-sm font-semibold focus:ring-2 focus:ring-white/40 bg-white/10 text-white cursor-pointer hover:bg-white/20 transition-all backdrop-blur-sm">
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                            <option value="{{ $h }}" class="text-gray-800 bg-white">{{ $h }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-white">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>

            {{-- Sidebar Body --}}
            <div class="p-4 bg-gray-50/50 overflow-y-auto" style="height: calc(100vh - 140px);">
                @if($rooms->count() > 0)
                    <div class="space-y-3">
                        @foreach($rooms as $room)
                            <div class="group relative bg-white border rounded-lg overflow-hidden transition-all duration-200 hover:shadow-md"
                                :class="getRoomSchedules('{{ $room }}').length === 0 ? 'border-green-200 hover:border-green-400' : 'border-red-200 hover:border-red-400'">

                                <!-- Room Header -->
                                <div class="p-3 border-b flex justify-between items-center"
                                    :class="getRoomSchedules('{{ $room }}').length === 0 ? 'bg-green-50/50 border-green-100' : 'bg-red-50/50 border-red-100'">
                                    <div class="font-bold text-base text-gray-800">{{ $room }}</div>
                                    <div class="text-xs font-bold px-2 py-0.5 rounded-full"
                                        :class="getRoomSchedules('{{ $room }}').length === 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                                        <span x-text="getRoomSchedules('{{ $room }}').length === 0 ? 'KOSONG' : 'TERISI'"></span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-3">
                                    <ul class="space-y-2" x-show="getRoomSchedules('{{ $room }}').length > 0">
                                        <template x-for="jadwal in getRoomSchedules('{{ $room }}')" :key="jadwal.id">
                                            <li class="relative pl-2.5 border-l-2 border-red-200">
                                                <div class="text-xs font-bold text-maroon mb-0.5 flex items-center gap-1">
                                                    <i class="far fa-clock text-[10px]"></i> <span x-text="jadwal.jam"></span>
                                                </div>
                                                <div class="font-medium text-gray-800 text-xs leading-tight mb-0.5"
                                                    x-text="jadwal.mk" :title="jadwal.mk"></div>
                                                <div class="text-[10px] text-gray-500 flex items-center gap-1">
                                                    <i class="fas fa-chalkboard-teacher text-gray-400"></i>
                                                    <span x-text="jadwal.dosen" class="truncate"></span>
                                                </div>
                                            </li>
                                        </template>
                                    </ul>

                                    <!-- Empty State -->
                                    <div x-show="getRoomSchedules('{{ $room }}').length === 0"
                                        class="flex items-center gap-2 text-green-600/80 py-2">
                                        <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-check text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium">Tidak ada jadwal</p>
                                            <p class="text-[10px] opacity-70">Ruangan tersedia</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-door-open text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-base font-medium text-gray-900">Belum ada data ruangan</h3>
                        <p class="text-xs text-gray-500">Silakan tambahkan data ruangan terlebih dahulu.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Tab switching
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                    btn.classList.add('active');
                    document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
                });
            });

            // Reject modal for jadwal
            function openRejectModal(jadwalId) {
                document.getElementById('rejectForm').action = '/admin/jadwal/' + jadwalId + '/reject';
                document.getElementById('rejectModal').classList.remove('hidden');
            }
            function closeRejectModal() {
                document.getElementById('rejectModal').classList.add('hidden');
            }

            // Reject modal for jadwal-reschedules (permintaan reschedule biasa)
            window.openRejectModalReschedule = function (rescheduleId) {
                document.getElementById('rejectForm').action = '/admin/jadwal-reschedules/' + rescheduleId + '/reject';
                document.getElementById('rejectModal').classList.remove('hidden');
            }

            // Reject modal for kelas reschedule (weekly)
            window.openRejectModalWeekly = function (rescheduleId) {
                console.log('Opening reject modal for id:', rescheduleId);
                document.getElementById('rejectFormKelas').action = '/admin/kelas-reschedules/' + rescheduleId + '/reject';
                const modal = document.getElementById('rejectModalKelas');
                if (modal) {
                    modal.style.display = 'flex';
                } else {
                    console.error('Reject modal not found!');
                }
            }

            window.closeRejectModalWeekly = function () {
                const modal = document.getElementById('rejectModalKelas');
                if (modal) {
                    modal.style.display = 'none';
                }
            }

            // Room Usage Sidebar
            window.openRoomUsageModal = function () {
                const modal = document.getElementById('roomUsageModal');
                const sidebar = document.getElementById('roomUsageSidebar');
                modal.classList.remove('hidden');
                // Trigger reflow to enable transition
                setTimeout(() => {
                    sidebar.classList.remove('translate-x-full');
                }, 10);
            }

            window.closeRoomUsageModal = function () {
                const modal = document.getElementById('roomUsageModal');
                const sidebar = document.getElementById('roomUsageSidebar');
                sidebar.classList.add('translate-x-full');
                // Wait for animation to complete before hiding
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }

            // SweetAlert Delete Confirmation
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data jadwal ini akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7a1621',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: '#ffffff',
                        customClass: {
                            confirmButton: 'btn btn-danger',
                            cancelButton: 'btn btn-secondary'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection