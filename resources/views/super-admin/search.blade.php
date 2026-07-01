@extends('layouts.super-admin')

@section('title', 'Pencarian Global')
@section('page-title', 'Pencarian Global')

@section('content')
<div class="space-y-6">
    <!-- Search Bar card -->
    <div class="glass-card p-6">
        <h2 class="text-lg font-bold text-slate-800 mb-2">Pencarian Sistem</h2>
        <form action="{{ route('super-admin.search') }}" method="GET" class="flex gap-3">
            <input type="text" name="query" value="{{ $query }}" placeholder="Cari Nama, NIM, NIDN, email, invoice..." 
                class="flex-1 px-4 py-3 rounded-xl border border-slate-250 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a1621] focus:border-transparent bg-slate-50 transition" required>
            <button type="submit" class="btn-maroon px-6 py-3 rounded-xl font-semibold text-sm shadow-md flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">search</span>
                <span>Cari</span>
            </button>
        </form>
        @if($query)
            <p class="text-xs text-slate-400 mt-2">Menampilkan hasil pencarian untuk: <strong class="text-slate-650">"{{ $query }}"</strong></p>
        @endif
    </div>

    <div id="search-results">
        @if($query)
            <!-- Search Results Grid -->
            <div class="space-y-6">
                <!-- Mahasiswa Section -->
                @if(isset($results['mahasiswa']) && $results['mahasiswa']->count() > 0)
                    <div class="glass-card p-6">
                        <h3 class="text-sm font-bold text-[#7a1621] uppercase tracking-wider mb-4 border-b border-[#7a1621]/10 pb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#7a1621]">school</span>
                            <span>Mahasiswa ({{ $results['mahasiswa']->count() }})</span>
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($results['mahasiswa'] as $mhs)
                                <div class="border border-[#7a1621]/5 rounded-xl p-4 flex justify-between items-center bg-slate-50/40 hover:bg-[#7a1621]/5 hover:border-[#7a1621]/20 transition duration-200">
                                    <div>
                                        <h4 class="font-bold text-slate-800 text-sm">{{ $mhs->nama }}</h4>
                                        <p class="text-xs text-slate-400 mt-0.5">NIM: {{ $mhs->nim }} | Prodi: {{ $mhs->prodiData ? $mhs->prodiData->nama_prodi : '-' }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('super-admin.student-360', $mhs->id) }}" class="bg-[#7a1621] hover:bg-[#5e1019] text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition flex items-center gap-1">
                                            <span class="material-symbols-outlined text-xs">visibility</span> Profile 360
                                        </a>
                                        @if($mhs->user)
                                            <form action="{{ route('super-admin.impersonate', $mhs->user->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="btn-gold px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-xs">login</span> Impersonate
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
    
                <!-- Dosen Section -->
                @if(isset($results['dosen']) && $results['dosen']->count() > 0)
                    <div class="glass-card p-6">
                        <h3 class="text-sm font-bold text-[#7a1621] uppercase tracking-wider mb-4 border-b border-[#7a1621]/10 pb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#7a1621]">local_library</span>
                            <span>Dosen ({{ $results['dosen']->count() }})</span>
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($results['dosen'] as $dsn)
                                <div class="border border-[#7a1621]/5 rounded-xl p-4 flex justify-between items-center bg-slate-50/40 hover:bg-[#7a1621]/5 hover:border-[#7a1621]/20 transition duration-200">
                                    <div>
                                        <h4 class="font-bold text-slate-800 text-sm">{{ $dsn->nama }}</h4>
                                        <p class="text-xs text-slate-400 mt-0.5">NIDN: {{ $dsn->nidn ?? '-' }} | Kode: {{ $dsn->kode_dosen ?? '-' }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($dsn->user)
                                            <form action="{{ route('super-admin.impersonate', $dsn->user->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="btn-gold px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-xs">login</span> Impersonate
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
    
                <!-- Users Section -->
                @if(isset($results['users']) && $results['users']->count() > 0)
                    <div class="glass-card p-6">
                        <h3 class="text-sm font-bold text-[#7a1621] uppercase tracking-wider mb-4 border-b border-[#7a1621]/10 pb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#7a1621]">group</span>
                            <span>Semua Akun ({{ $results['users']->count() }})</span>
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-slate-500">
                                <thead class="text-xs uppercase bg-[#7a1621]/5 text-[#7a1621] rounded-lg">
                                    <tr>
                                        <th class="px-4 py-3">Nama</th>
                                        <th class="px-4 py-3">Email</th>
                                        <th class="px-4 py-3">Role</th>
                                        <th class="px-4 py-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($results['users'] as $usr)
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $usr->name }}</td>
                                            <td class="px-4 py-3 text-slate-650">{{ $usr->email }}</td>
                                            <td class="px-4 py-3">
                                                @foreach($usr->roles as $role)
                                                    <span class="px-2 py-0.5 rounded bg-[#7a1621]/5 border border-[#7a1621]/10 text-[#7a1621] text-[10px] font-bold uppercase tracking-wider">{{ $role->name }}</span>
                                                @endforeach
                                                @if($usr->roles->isEmpty())
                                                    <span class="px-2 py-0.5 rounded bg-slate-500/5 border border-slate-500/10 text-slate-400 text-[10px] font-bold uppercase tracking-wider">Tanpa Role</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                @if($usr->id !== auth()->id())
                                                    <form action="{{ route('super-admin.impersonate', $usr->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="btn-gold px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition inline-flex items-center gap-1">
                                                            <span class="material-symbols-outlined text-xs">login</span> Impersonate
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-xs text-slate-400 italic">Anda sendiri</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
    
                <!-- Invoices Section -->
                @if(isset($results['invoices']) && $results['invoices']->count() > 0)
                    <div class="glass-card p-6">
                        <h3 class="text-sm font-bold text-[#7a1621] uppercase tracking-wider mb-4 border-b border-[#7a1621]/10 pb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#7a1621]">account_balance_wallet</span>
                            <span>Tagihan & Invoice ({{ $results['invoices']->count() }})</span>
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-slate-500">
                                <thead class="text-xs uppercase bg-[#7a1621]/5 text-[#7a1621] rounded-lg">
                                    <tr>
                                        <th class="px-4 py-3">No Invoice</th>
                                        <th class="px-4 py-3">Jumlah</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Dibuat Pada</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($results['invoices'] as $inv)
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $inv->invoice_number }}</td>
                                            <td class="px-4 py-3 font-bold text-slate-700">Rp {{ number_format($inv->amount, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                                    @if($inv->status === 'PAID') bg-emerald-500/10 text-emerald-700 border border-emerald-500/20
                                                    @else bg-rose-500/10 text-rose-700 border border-rose-500/20 @endif">
                                                    {{ $inv->status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-slate-400 text-xs">{{ $inv->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
    
                <!-- KRS & Academic Details Section -->
                @if(isset($results['krs']) && $results['krs']->count() > 0)
                    <div class="glass-card p-6">
                        <h3 class="text-sm font-bold text-[#7a1621] uppercase tracking-wider mb-4 border-b border-[#7a1621]/10 pb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#7a1621]">fact_check</span>
                            <span>Data KRS ({{ $results['krs']->count() }})</span>
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-slate-500">
                                <thead class="text-xs uppercase bg-[#7a1621]/5 text-[#7a1621] rounded-lg">
                                    <tr>
                                        <th class="px-4 py-3">Mahasiswa</th>
                                        <th class="px-4 py-3">Mata Kuliah</th>
                                        <th class="px-4 py-3">Tahun Ajaran</th>
                                        <th class="px-4 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($results['krs'] as $k)
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="px-4 py-3">
                                                <span class="font-bold text-slate-800 block text-xs">{{ $k->mahasiswa ? $k->mahasiswa->nama : '-' }}</span>
                                                <span class="text-[10px] text-slate-400 block">NIM: {{ $k->mahasiswa ? $k->mahasiswa->nim : '-' }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="font-medium text-slate-700 block text-xs">{{ $k->mataKuliah ? $k->mataKuliah->nama_mata_kuliah : '-' }}</span>
                                                <span class="text-[10px] text-slate-400 block">Kode: {{ $k->mataKuliah ? $k->mataKuliah->kode_mata_kuliah : '-' }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-slate-600 text-xs">{{ $k->tahun_ajaran }}</td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                                    @if($k->status === 'sudah submit') bg-emerald-500/10 text-emerald-700 border border-emerald-500/20
                                                    @elseif($k->status === 'pending') bg-amber-500/10 text-amber-700 border border-amber-500/20
                                                    @else bg-slate-500/10 text-slate-500 border border-slate-500/20 @endif">
                                                    {{ $k->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
    
                @if(collect($results)->flatMap(fn($item) => $item)->isEmpty())
                    <div class="glass-card p-12 text-center text-slate-400">
                        <span class="material-symbols-outlined text-4xl block mb-2 text-slate-350">search_off</span>
                        <p class="text-sm font-semibold">Tidak ditemukan data apapun yang cocok dengan kata kunci "{{ $query }}".</p>
                    </div>
                @endif
            </div>
        @else
            <div class="glass-card p-12 text-center text-slate-400">
                <span class="material-symbols-outlined text-4xl block mb-2 text-slate-350">travel_explore</span>
                <p class="text-sm font-semibold">Masukkan kata kunci di atas untuk mencari seluruh data sistem.</p>
            </div>
        @endif
    </div>
</div>
@endsection
