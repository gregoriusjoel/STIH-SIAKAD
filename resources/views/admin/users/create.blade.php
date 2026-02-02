@extends('layouts.admin')
@section('title', 'Tambah User')
@section('page-title', 'Tambah User')
@section('content')
<div class="max-w-4xl mx-auto" x-data="{ role: '' }">
    <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon">
        <div class="p-6 border-b border-gray-200 bg-maroon text-white rounded-t-xl">
            <h3 class="text-xl font-bold flex items-center"><i class="fas fa-user-plus mr-3 text-2xl"></i>Tambah User Baru</h3>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-user text-gray-400 mr-1"></i>Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-envelope text-gray-400 mr-1"></i>Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-shield-alt text-gray-400 mr-1"></i>Role *</label>
                    <select name="role" x-model="role" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition" required>
                        <option value="">Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="dosen">Dosen</option>
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="parent">Parent</option>
                        <option value="keuangan">Keuangan</option>
                        <option value="perpustakaan">Perpustakaan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-lock text-gray-400 mr-1"></i>Password *</label>
                    <div class="relative">
                        <input id="pw" type="password" name="password" value="{{ old('password') }}" class="w-full pr-10 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon-500 focus:border-transparent transition" required>
                        <button type="button" id="togglePwBtn" aria-pressed="false" class="absolute right-3 top-1/2 transform -translate-y-1/2 inline-flex items-center px-2 text-sm text-gray-500 hover:text-gray-700 bg-transparent border-0"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-lock text-gray-400 mr-1"></i>Konfirmasi Password *</label>
                    <div class="relative">
                        <input id="pw_confirm" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" class="w-full pr-10 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" required>
                        <button type="button" id="togglePwConfirmBtn" aria-pressed="false" class="absolute right-3 top-1/2 transform -translate-y-1/2 inline-flex items-center px-2 text-sm text-gray-500 hover:text-gray-700 bg-transparent border-0"><i class="fas fa-eye"></i></button>
                    </div>
                </div>

                {{-- Mahasiswa Fields --}}
                <template x-if="role === 'mahasiswa'">
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6 p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="md:col-span-3">
                            <p class="text-sm text-green-700 font-medium mb-4"><i class="fas fa-user-graduate mr-2"></i>Data Mahasiswa</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-id-card text-gray-400 mr-1"></i>NIM *</label>
                            <input type="text" name="nim" value="{{ old('nim') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition" placeholder="Contoh: 2024010001">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-graduation-cap text-gray-400 mr-1"></i>Program Studi *</label>
                            <select name="prodi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                                <option value="">Pilih Prodi</option>
                                <option value="Hukum Tata Kabupaten" {{ old('prodi') == 'Hukum Tata Kabupaten' ? 'selected' : '' }}>Hukum Tata Kabupaten</option>
                                <option value="Hukum Bisnis" {{ old('prodi') == 'Hukum Bisnis' ? 'selected' : '' }}>Hukum Bisnis</option>
                                <option value="Hukum Pidana" {{ old('prodi') == 'Hukum Pidana' ? 'selected' : '' }}>Hukum Pidana</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-calendar text-gray-400 mr-1"></i>Angkatan *</label>
                            <select name="angkatan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                                <option value="">Pilih Angkatan</option>
                                @for($year = date('Y'); $year >= 2015; $year--)
                                    <option value="{{ $year }}" {{ old('angkatan') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </template>
            </div>
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center"><i class="fas fa-times mr-2"></i>Batal</a>
                <button type="submit" class="bg-maroon text-white px-6 py-3 rounded-lg hover:bg-red-800 transition flex items-center shadow-md transform hover:scale-105"><i class="fas fa-save mr-2"></i>Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const roleSelect = document.querySelector('select[name="role"]');
    const pw = document.getElementById('pw');
    const pwc = document.getElementById('pw_confirm');

    function applyDefault(){
        if(!pw || !pwc || !roleSelect) return;
        // only set defaults when fields are empty
        if(pw.value.trim() !== '') return;
        const role = roleSelect.value;
        if(role === 'mahasiswa'){
            pw.value = 'mahasiswa123'; pwc.value = 'mahasiswa123';
        } else if(role === 'dosen'){
            pw.value = 'dosen123'; pwc.value = 'dosen123';
        } else if(role === 'parent'){
            pw.value = 'parent123'; pwc.value = 'parent123';
        }
    }

    roleSelect?.addEventListener('change', applyDefault);
    // apply on first load if role already selected
    applyDefault();
    
    // Toggle visibility for password fields
    const togglePwBtn = document.getElementById('togglePwBtn');
    const togglePwConfirmBtn = document.getElementById('togglePwConfirmBtn');

    function toggleField(btn, field){
        if(!btn || !field) return;
        btn.addEventListener('click', function(){
            if(field.type === 'password'){
                field.type = 'text';
                btn.innerHTML = '<i class="fas fa-eye-slash"></i>';
                btn.setAttribute('aria-pressed', 'true');
            } else {
                field.type = 'password';
                btn.innerHTML = '<i class="fas fa-eye"></i>';
                btn.setAttribute('aria-pressed', 'false');
            }
        });
    }

    toggleField(togglePwBtn, pw);
    toggleField(togglePwConfirmBtn, pwc);
});
</script>
@endsection
