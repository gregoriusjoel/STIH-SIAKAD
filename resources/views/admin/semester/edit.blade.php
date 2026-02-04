@extends('layouts.admin')
@section('title', 'Edit Semester')
@section('page-title', 'Edit Semester')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon ">
        <div class="p-6 border-b border-gray-200 bg-maroon text-white rounded-t-xl"><h3 class="text-xl font-bold flex items-center"><i class="fas fa-calendar-alt mr-3 text-2xl"></i>Edit Semester & Tahun Ajaran</h3></div>
        <form action="{{ route('admin.semester.update', $semester) }}" method="POST" class="p-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-bookmark text-gray-400 mr-1"></i>Nama Semester *</label>
                    <select name="nama_semester" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" required>
                        <option value="Ganjil" {{ old('nama_semester', $semester->nama_semester) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="Genap" {{ old('nama_semester', $semester->nama_semester) == 'Genap' ? 'selected' : '' }}>Genap</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-graduation-cap text-gray-400 mr-1"></i>Tahun Ajaran *</label>
                    @php
                        $currentYear = (int) date('Y');
                        $startYear = $currentYear - 2;
                        $endYear = $currentYear + 3;
                        $selected = old('tahun_ajaran', $semester->tahun_ajaran);
                    @endphp
                    <select name="tahun_ajaran" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" required>
                        @for ($y = $startYear; $y <= $endYear; $y++)
                            @php $label = $y . '/' . ($y + 1); @endphp
                            <option value="{{ $label }}" {{ $selected == $label ? 'selected' : '' }}>{{ $label }}</option>
                        @endfor
                    </select>
                </div>
                <div class="md:col-span-2 flex flex-col md:flex-row items-center gap-4">
                    <div class="flex-1 w-full">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-calendar-alt text-gray-400 mr-1"></i>Tanggal Mulai *</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', optional($semester->tanggal_mulai)->format('Y-m-d')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" required>
                    </div>

                    <div class="text-center text-sm text-gray-500 px-2 py-2 md:py-0 w-full md:w-auto flex justify-center items-center">
                        <span class="md:hidden transform rotate-90 mr-2"></span>
                        <span>— <strong>6 bulan</strong> —</span>
                    </div>

                    <div class="flex-1 w-full">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-calendar-alt text-gray-400 mr-1"></i>Tanggal Selesai *</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', optional($semester->tanggal_selesai)->format('Y-m-d')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" required>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-check-circle text-maroon mr-1"></i>Status *</label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" required>
                        <option value="non-aktif" {{ old('status', $semester->status) == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        <option value="aktif" {{ old('status', $semester->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    </select>
                    <p class="text-sm text-gray-500 mt-2">Jika dipilih <strong>Aktif</strong>, semester lain akan dinonaktifkan.</p>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t"><a href="{{ route('admin.semester.index') }}" class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center"><i class="fas fa-times mr-2"></i>Batal</a><button type="submit" class="bg-maroon text-white px-6 py-3 rounded-lg hover:bg-white-700 transition flex items-center shadow-md transform hover:scale-105"><i class="fas fa-save mr-2"></i>Update</button></div>
        </form>
        <script>
            (function(){
                const start = document.getElementById('tanggal_mulai');
                const end = document.getElementById('tanggal_selesai');
                if (!start || !end) return;

                function toYMD(d){
                    const mm = String(d.getMonth()+1).padStart(2,'0');
                    const dd = String(d.getDate()).padStart(2,'0');
                    return `${d.getFullYear()}-${mm}-${dd}`;
                }

                start.addEventListener('change', function(){
                    if(!this.value) return;
                    const s = new Date(this.value);
                    const target = new Date(s.getTime());
                    target.setMonth(target.getMonth() + 6);
                    end.value = toYMD(target);
                });
            })();
        </script>
    </div>
</div>
@endsection
