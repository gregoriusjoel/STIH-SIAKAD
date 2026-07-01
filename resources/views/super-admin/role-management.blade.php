@extends('layouts.super-admin')

@section('title', 'Role Management')
@section('page-title', 'Role Management')

@section('content')
<div class="space-y-6" x-data="{
    activeRole: @js($roles->first()?->id),
    roles: @js($roles->keyBy('id')->map(fn($r) => [
        'id' => $r->id,
        'name' => $r->name,
        'is_protected' => $r->name === 'super_admin',
        'permissions' => $r->permissions->pluck('name')->toArray()
    ])),
    searchQuery: ''
}">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621] font-bold">admin_panel_settings</span>
                Role & Permission Center
            </h2>
            <p class="text-sm text-slate-500 mt-0.5">Kelola hak akses dan peran untuk seluruh pengguna sistem</p>
        </div>
    </div>
 
    {{-- Main Layout Split --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
 
        {{-- Left Panel: Roles List --}}
        <div class="lg:col-span-1 glass-card p-4 h-fit space-y-4">
            <h3 class="font-bold text-slate-400 text-xs uppercase tracking-wider">Daftar Peran (Roles)</h3>
            <div class="space-y-1">
                <template x-for="role in Object.values(roles)" :key="role.id">
                    <button @click="activeRole = role.id"
                        :class="activeRole === role.id ? 'bg-[#7a1621]/5 border-[#7a1621] text-[#7a1621] font-bold' : 'hover:bg-slate-50 border-transparent text-slate-700'"
                        class="w-full text-left px-4 py-3 rounded-xl border-l-4 transition-all duration-150 flex items-center justify-between">
                        <div>
                            <span class="text-sm block" x-text="role.name === 'super_admin' ? 'Super Admin' : (role.name === 'akademik' ? 'Akademik' : (role.name === 'keuangan' ? 'Keuangan' : (role.name === 'dosen' ? 'Dosen' : (role.name === 'mahasiswa' ? 'Mahasiswa' : (role.name === 'parents' ? 'Parents' : role.name)))))"></span>
                            <span class="text-[10px] text-slate-450 block mt-0.5" x-text="role.permissions.length + ' Hak Akses' + (role.permissions.length !== 1 ? '' : '')"></span>
                        </div>
                        <span x-show="role.is_protected" class="material-symbols-outlined text-sm text-slate-400" title="Protected Role">lock</span>
                    </button>
                </template>
            </div>
        </div>
 
        {{-- Right Panel: Role Permissions Form --}}
        <div class="lg:col-span-3 glass-card p-6">
            {{-- Selected Role Header --}}
            <div class="border-b border-slate-100 pb-4 mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#7a1621]">lock_open</span>
                        Hak Akses Peran: <span class="text-[#7a1621]" x-text="roles[activeRole] ? (roles[activeRole].name === 'super_admin' ? 'Super Admin' : (roles[activeRole].name === 'akademik' ? 'Akademik' : (roles[activeRole].name === 'keuangan' ? 'Keuangan' : (roles[activeRole].name === 'dosen' ? 'Dosen' : (roles[activeRole].name === 'mahasiswa' ? 'Mahasiswa' : (roles[activeRole].name === 'parents' ? 'Parents' : roles[activeRole].name)))))) : ''"></span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-1">Centang/lepas centang pada daftar di bawah untuk merubah hak akses.</p>
                </div>
 
                {{-- Search Box --}}
                <div class="relative w-full max-w-xs">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-sm">search</span>
                    <input type="text" x-model="searchQuery" placeholder="Cari hak akses..."
                        class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#7a1621]">
                </div>
            </div>
 
            {{-- Protected Notice --}}
            <div x-show="roles[activeRole]?.is_protected" class="mb-6 p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-3">
                <span class="material-symbols-outlined text-slate-500 text-xl">lock</span>
                <div>
                    <h4 class="font-bold text-slate-700 text-sm">Peran Dilindungi</h4>
                    <p class="text-xs text-slate-500">Role <strong>Super Admin</strong> memiliki akses penuh ke seluruh fitur sistem dan tidak dapat diubah hak aksesnya demi alasan keamanan.</p>
                </div>
            </div>
 
            {{-- Form --}}
            <form :action="'{{ url('super-admin/roles') }}/' + activeRole + '/permissions'" method="POST">
                @csrf
 
                <div class="space-y-6">
                    @foreach($permissionGroups as $groupName => $permissions)
                    {{-- Only display group if there are visible items under search --}}
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm mb-3 border-b border-slate-100 pb-1.5 flex items-center gap-2">
                            <span class="w-1.5 h-3 bg-[#7a1621] rounded-full"></span>
                            {{ $groupName }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($permissions as $perm)
                                <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-150 hover:bg-slate-50 transition cursor-pointer select-none"
                                    x-show="searchQuery === '' || '{{ strtolower($perm->name) }}'.includes(searchQuery.toLowerCase())"
                                    :class="roles[activeRole]?.is_protected ? 'opacity-70 cursor-not-allowed bg-slate-50' : ''">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                        :disabled="roles[activeRole]?.is_protected"
                                        :checked="roles[activeRole]?.permissions.includes('{{ $perm->name }}')"
                                        class="mt-0.5 w-4 h-4 rounded text-[#7a1621] focus:ring-[#7a1621] border-slate-300">
                                    <div class="text-xs">
                                        <p class="font-bold text-slate-700">{{ $perm->name }}</p>
                                        <p class="text-slate-450 mt-0.5">{{ $perm->guard_name }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
 
                {{-- Action Bar --}}
                <div class="flex justify-end gap-3 mt-8 pt-5 border-t border-slate-100" x-show="!roles[activeRole]?.is_protected">
                    <button type="submit"
                        class="btn-maroon px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">save</span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
