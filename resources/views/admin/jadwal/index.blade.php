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
        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        {{-- Cards Layout: Pending, Waiting Room, Active --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Left Column (30%): Form Tambah Kelas --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
                    <div class="p-6 border-b border-gray-200 bg-maroon text-white">
                        <div class="font-semibold text-lg flex items-center"><i class="fas fa-plus-circle mr-2"></i>Tambah
                            Jadwal Baru</div>
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
                                                              }
                                                          }">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1"><i
                                        class="fas fa-book text-gray-400 mr-1"></i>Mata Kuliah <span
                                        class="text-red-500">*</span></label>
                                <select name="mata_kuliah_id" x-model="mataKuliahId" @change="fetchDosens()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm"
                                    required>
                                    <option value="">Pilih Mata Kuliah</option>
                                    @foreach($mataKuliahs as $mk)
                                        <option value="{{ $mk->id }}">{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1"><i
                                        class="fas fa-users text-gray-400 mr-1"></i>Nama Kelas <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="nama_kelas"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm"
                                    placeholder="A, B, C..." required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1"><i
                                        class="fas fa-user-tie text-gray-400 mr-1"></i>Dosen <span
                                        class="text-red-500">*</span></label>

                                {{-- Loading state --}}
                                <div x-show="loading"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 text-sm">
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
                                    <label class="block text-sm font-medium text-gray-700 mb-1"><i
                                            class="fas fa-user-friends text-gray-400 mr-1"></i>Kuota <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" name="kuota"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm"
                                        placeholder="40" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1"><i
                                            class="fas fa-door-open text-gray-400 mr-1"></i>Ruangan <span
                                            class="text-red-500">*</span></label>
                                    <select name="ruangan_id" x-model="ruanganId" @change="checkRoom()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm"
                                        required>
                                        <option value="">Pilih Ruangan</option>
                                        @foreach($daftarRuangan as $r)
                                            <option value="{{ $r->id }}">{{ $r->kode_ruangan }}</option>
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
                                <label class="block text-sm font-medium text-gray-700 mb-1"><i
                                        class="fas fa-calendar-day text-gray-400 mr-1"></i>Hari <span
                                        class="text-red-500">*</span></label>
                                <select name="hari" x-model="hari" @change="checkRoom()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm"
                                    required>
                                    <option value="">Pilih Hari</option>
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                                        <option value="{{ $h }}">{{ $h }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1"><i
                                        class="fas fa-clock text-gray-400 mr-1"></i>Jam Perkuliahan <span
                                        class="text-red-500">*</span></label>
                                <select name="jam_perkuliahan_id" x-model="jamPerkuliahanId" @change="updateJamFromPerkuliahan(); checkRoom();"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition text-sm"
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

            {{-- Right Column (70%): Cards & Table --}}
            <div class="lg:col-span-9 space-y-6">
                {{-- Card: Active Schedules (Full Width inside Right Column) --}}
                <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
                    <div class="p-6 border-b border-gray-200 bg-maroon text-white flex items-center justify-between">
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
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($kelasMataKuliahs as $k)
                                        <tr class="hover:bg-maroon-50 transition duration-200">
                                            <td class="px-4 py-3">
                                                <div class="font-semibold text-gray-900">{{ $k->hari ?: '-' }}</div>
                                                <div class="text-xs text-gray-500">
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

                {{-- Generator: Auto Generate Jadwal (embedded) --}}
                @include('admin.jadwal._generator_partial')

                {{-- Card: Informasi Penggunaan Ruangan (New Feature) --}}
                <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden" x-data="{
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
                    <div
                        class="p-6 border-b border-gray-200 bg-gradient-to-r from-maroon to-red-900 text-white flex items-center justify-between">
                        <div class="font-bold text-white text-xl flex items-center gap-3">
                            <div class="p-2 bg-white/10 rounded-lg backdrop-blur-sm">
                                <i class="fas fa-door-open"></i>
                            </div>
                            Informasi Penggunaan Ruangan
                        </div>
                        <div class="relative">
                            <select x-model="selectedDay"
                                class="appearance-none pl-4 pr-10 py-2 border border-white/20 rounded-full text-sm font-semibold focus:ring-2 focus:ring-white/40 bg-white/10 text-white cursor-pointer hover:bg-white/20 transition-all backdrop-blur-sm shadow-sm">
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                                    <option value="{{ $h }}" class="text-gray-800 bg-white">{{ $h }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-white">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 bg-gray-50/50">
                        @if($rooms->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                                @foreach($rooms as $room)
                                    <div class="group relative bg-white border rounded-xl overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1"
                                        :class="getRoomSchedules('{{ $room }}').length === 0 ? 'border-green-200 hover:border-green-400' : 'border-red-200 hover:border-red-400'">

                                        <!-- Room Header -->
                                        <div class="p-4 border-b flex justify-between items-center"
                                            :class="getRoomSchedules('{{ $room }}').length === 0 ? 'bg-green-50/50 border-green-100' : 'bg-red-50/50 border-red-100'">
                                            <div class="font-bold text-lg text-gray-800">{{ $room }}</div>
                                            <div class="text-xs font-bold px-2 py-1 rounded-full text-center min-w-[60px]"
                                                :class="getRoomSchedules('{{ $room }}').length === 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                                                <span
                                                    x-text="getRoomSchedules('{{ $room }}').length === 0 ? 'KOSONG' : 'TERISI'"></span>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="p-4 min-h-[140px]">
                                            <ul class="space-y-3" x-show="getRoomSchedules('{{ $room }}').length > 0">
                                                <template x-for="jadwal in getRoomSchedules('{{ $room }}')" :key="jadwal.id">
                                                    <li
                                                        class="relative pl-3 border-l-2 border-red-200 group-hover:border-red-400 transition-colors">
                                                        <div class="text-xs font-bold text-maroon mb-0.5 flex items-center gap-1">
                                                            <i class="far fa-clock"></i> <span x-text="jadwal.jam"></span>
                                                        </div>
                                                        <div class="font-medium text-gray-800 text-xs leading-tight mb-1"
                                                            x-text="jadwal.mk" :title="jadwal.mk"></div>
                                                        <div class="text-[10px] text-gray-500 flex items-center gap-1">
                                                            <i class="fas fa-chalkboard-teacher text-gray-400"></i>
                                                            <span x-text="jadwal.dosen" class="truncate"></span>
                                                        </div>
                                                    </li>
                                                </template>
                                            </ul>

                                            <!-- Empty State Illustration -->
                                            <div x-show="getRoomSchedules('{{ $room }}').length === 0"
                                                class="h-full flex flex-col items-center justify-center text-center py-4 text-green-600/80 group-hover:text-green-600 transition-colors">
                                                <div
                                                    class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                                    <i class="fas fa-check text-xl"></i>
                                                </div>
                                                <p class="text-xs font-medium">Tidak ada jadwal</p>
                                                <p class="text-[10px] opacity-70">Ruangan tersedia</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-door-open text-3xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">Belum ada data ruangan</h3>
                                <p class="text-sm text-gray-500">Silakan tambahkan data ruangan terlebih dahulu.</p>
                            </div>
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