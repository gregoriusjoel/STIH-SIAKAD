@extends('layouts.admin')
@section('title', 'Edit Kelas Mata Kuliah')
@section('page-title', 'Edit Kelas Mata Kuliah')
@section('content')
    <div class="w-full">
        <div class="bg-white rounded-xl shadow-lg  overflow-hidden" x-data="kelasMataKuliahEditor()" x-init="initialize()">
            <div class="p-6 border-b border-gray-200 bg-maroon text-white rounded-t-xl">
                <h3 class="text-xl font-bold flex items-center"><i class="fas fa-chalkboard-teacher mr-3 text-2xl"></i>Edit
                    Kelas Mata Kuliah</h3>
            </div>
            <form action="{{ route('admin.kelas-mata-kuliah.update', $kelasMataKuliah) }}" method="POST" class="p-6">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-2"><i
                                class="fas fa-book text-gray-400 mr-1"></i>Mata Kuliah *</label><select
                            name="mata_kuliah_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            required>
                            <option value="">Pilih Mata Kuliah</option>@foreach($mataKuliahs as $mk)<option
                            value="{{ $mk->id }}" {{ old('mata_kuliah_id', $kelasMataKuliah->mata_kuliah_id) == $mk->id ? 'selected' : '' }}>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>@endforeach
                        </select></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-2"><i
                                class="fas fa-users text-gray-400 mr-1"></i>Nama Kelas *</label><input type="text"
                            name="nama_kelas" value="{{ old('nama_kelas', $kelasMataKuliah->kode_kelas) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="A, B, C..." required></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-2"><i
                                class="fas fa-user-tie text-gray-400 mr-1"></i>Dosen *</label><select name="dosen_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            required>
                            <option value="">Pilih Dosen</option>@foreach($dosens as $d)<option value="{{ $d->id }}" {{ old('dosen_id', $kelasMataKuliah->dosen_id) == $d->id ? 'selected' : '' }}>
                                {{ $d->user->name }}
                            </option>@endforeach
                        </select></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-2"><i
                                class="fas fa-user-friends text-gray-400 mr-1"></i>Kapasitas *</label><input type="number"
                            name="kapasitas" x-model="kapasitas" :readonly="!!ruanganId"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            :class="{ 'bg-green-50 border-green-300 text-green-700 cursor-not-allowed': ruanganId, 'bg-white': !ruanganId }"
                            placeholder="Pilih ruangan terlebih dahulu" required>
                        <small class="text-xs text-gray-500 block mt-1">Nilai Alpine: <span
                                x-text="kapasitas || 'kosong'"></span></small>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-2"><i
                                class="fas fa-door-open text-gray-400 mr-1"></i>Ruangan</label><select name="ruangan_id"
                            x-model="ruanganId" @change="updateKapasitas()"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <option value="">Pilih Ruangan</option>
                            @forelse($daftarRuangan as $r)
                                <option value="{{ $r->id }}" data-kapasitas="{{ $r->kapasitas }}" {{ old('ruangan_id', $kelasMataKuliah->ruangan_id) == $r->id ? 'selected' : '' }}>{{ $r->kode_ruangan }}
                                    ({{ $r->kapasitas }})</option>
                            @empty
                                <option disabled>Tidak ada ruangan</option>
                            @endforelse
                        </select>
                        <small class="text-xs text-gray-500 block mt-1">ID: <span
                                x-text="ruanganId || 'kosong'"></span></small>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-2"><i
                                class="fas fa-calendar-day text-gray-400 mr-1"></i>Hari</label><select name="hari"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <option value="">Pilih Hari</option>
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)<option value="{{ $h }}"
                            {{ old('hari', $kelasMataKuliah->hari) == $h ? 'selected' : '' }}>{{ $h }}</option>@endforeach
                        </select></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-2"><i
                                class="fas fa-clock text-gray-400 mr-1"></i>Jam Mulai</label><input type="time"
                            name="jam_mulai"
                            value="{{ old('jam_mulai', $kelasMataKuliah->jam_mulai ? substr($kelasMataKuliah->jam_mulai, 0, 5) : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-2"><i
                                class="fas fa-clock text-gray-400 mr-1"></i>Jam Selesai</label><input type="time"
                            name="jam_selesai"
                            value="{{ old('jam_selesai', $kelasMataKuliah->jam_selesai ? substr($kelasMataKuliah->jam_selesai, 0, 5) : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t"><a
                        href="{{ route('admin.kelas-mata-kuliah.index') }}"
                        class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center"><i
                            class="fas fa-times mr-2"></i>Batal</a><button type="submit"
                        class="btn-maroon text-white px-6 py-3 rounded-lg hover:opacity-95 transition flex items-center shadow-md transform hover:scale-105"><i
                            class="fas fa-save mr-2"></i>Update</button></div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function kelasMataKuliahEditor() {
            return {
                ruanganId: '{{ $kelasMataKuliah->ruangan_id ?? "" }}',
                kapasitas: '{{ $kelasMataKuliah->kapasitas ?? "" }}',

                initialize() {
                    // Set kapasitas saat initialize
                    if (this.ruanganId) {
                        this.$nextTick(() => {
                            this.updateKapasitas();
                        });
                    }
                },

                updateKapasitas() {
                    if (!this.ruanganId) {
                        this.kapasitas = '';
                        return;
                    }
                    // Ambil value dari select element directly
                    const select = document.querySelector('select[name="ruangan_id"]');
                    if (!select) return;

                    const selectedOption = select.options[select.selectedIndex];
                    const kapasitas = selectedOption?.getAttribute('data-kapasitas') || '';

                    if (kapasitas) {
                        this.kapasitas = kapasitas;
                    }
                }
            };
        }
    </script>
@endpush