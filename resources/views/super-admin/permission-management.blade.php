@extends('layouts.super-admin')

@section('title', 'Permission Matrix')
@section('page-title', 'Permission Matrix')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621] font-bold">key</span>
                Permission Matrix
            </h2>
            <p class="text-sm text-slate-500 mt-0.5">Kelola hubungan hak akses (permission) dengan peran (role) secara real-time</p>
        </div>
 
        {{-- Search Input --}}
        <div class="relative w-full max-w-xs">
            <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-sm">search</span>
            <input type="text" id="perm-search" placeholder="Cari hak akses..."
                class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#7a1621]">
        </div>
    </div>
 
    {{-- Alert info --}}
    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-start gap-3">
        <span class="material-symbols-outlined text-[#7a1621] text-xl mt-0.5">info</span>
        <div class="text-xs text-slate-650">
            <p class="font-bold text-slate-700">Petunjuk Penggunaan</p>
            <p class="mt-0.5">Centang kotak untuk memberikan hak akses, dan kosongkan untuk mencabut. Perubahan disimpan secara otomatis via AJAX. Kolom <strong>super_admin</strong> dikunci untuk mencegah kegagalan sistem.</p>
        </div>
    </div>
 
    {{-- Matrix Table --}}
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs font-bold uppercase text-[#7a1621] bg-[#7a1621]/5 border-b border-[#7a1621]/10">
                    <tr>
                        <th class="px-5 py-4 w-[35%]">Hak Akses (Permission)</th>
                        @foreach($roles as $role)
                            <th class="px-3 py-4 text-center">
                                <span class="block font-bold text-slate-800 text-xs">{{ $role->name === 'super_admin' ? 'Super Admin' : ($role->name === 'akademik' ? 'Akademik' : ($role->name === 'keuangan' ? 'Keuangan' : ($role->name === 'dosen' ? 'Dosen' : ($role->name === 'mahasiswa' ? 'Mahasiswa' : ($role->name === 'parents' ? 'Parents' : $role->name))))) }}</span>
                                <span class="text-[9px] text-slate-400 font-normal">({{ $role->name }})</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($permissionGroups as $groupName => $permissions)
                        {{-- Group Header Row --}}
                        <tr class="bg-slate-50/50">
                            <td colspan="{{ count($roles) + 1 }}" class="px-5 py-2.5 text-xs font-bold text-slate-500 border-y border-slate-100/80">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-3 bg-[#7a1621] rounded-full"></span>
                                    {{ $groupName }} Group
                                </span>
                            </td>
                        </tr>
                        
                        @foreach($permissions as $perm)
                            <tr class="permission-row hover:bg-[#7a1621]/5 transition-colors" data-name="{{ $perm->name }}">
                                <td class="px-5 py-3">
                                    <p class="font-bold text-slate-700 text-xs">{{ $perm->name }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Guard: {{ $perm->guard_name }}</p>
                                </td>
                                @foreach($roles as $role)
                                    @php
                                        $isChecked = $perm->roles->contains('id', $role->id) || $role->name === 'super_admin';
                                        $isDisabled = $role->name === 'super_admin';
                                    @endphp
                                    <td class="px-3 py-3 text-center">
                                        <div class="inline-flex items-center justify-center">
                                            <input type="checkbox" 
                                                {{ $isChecked ? 'checked' : '' }} 
                                                {{ $isDisabled ? 'disabled' : '' }}
                                                onchange="togglePermission(this, {{ $role->id }}, {{ $perm->id }})"
                                                class="w-4 h-4 rounded text-[#7a1621] focus:ring-[#7a1621] border-slate-350 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Toast container --}}
<div id="toast-container" class="fixed bottom-5 right-5 z-[9999] space-y-2"></div>

<script>
// Row search filter
document.getElementById('perm-search').addEventListener('input', function(e) {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('.permission-row').forEach(row => {
        const name = row.getAttribute('data-name').toLowerCase();
        if (name.includes(q)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Toggle permission via AJAX
function togglePermission(checkbox, roleId, permissionId) {
    const isChecked = checkbox.checked;
    const url = isChecked ? '{{ route("super-admin.permission-management.assign") }}' : '{{ route("super-admin.permission-management.revoke") }}';

    checkbox.disabled = true;

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            role_id: roleId,
            permission_id: permissionId
        })
    })
    .then(response => response.json())
    .then(data => {
        checkbox.disabled = false;
        if (data.success) {
            showToast(data.message, 'success');
        } else {
            checkbox.checked = !isChecked;
            showToast(data.message || 'Gagal menyimpan perubahan.', 'error');
        }
    })
    .catch(error => {
        checkbox.disabled = false;
        checkbox.checked = !isChecked;
        showToast('Terjadi kesalahan koneksi.', 'error');
    });
}

// Toast notification helper
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `flex items-center gap-2 px-4 py-3 rounded-xl shadow-lg border transition-all duration-300 transform translate-y-2 opacity-0 ${
        type === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-red-50 border-red-200 text-red-900'
    }`;
    
    const icon = type === 'success' ? 'check_circle' : 'error';
    toast.innerHTML = `
        <span class="material-symbols-outlined text-sm font-bold">${icon}</span>
        <span class="text-xs font-semibold">${message}</span>
    `;
    
    container.appendChild(toast);
    
    // Trigger fade in
    setTimeout(() => {
        toast.classList.remove('translate-y-2', 'opacity-0');
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-y-2', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
@endsection
