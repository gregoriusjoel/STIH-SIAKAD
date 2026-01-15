@extends('layouts.admin')

@section('title', 'Hasil Pencarian')

@section('page-title', 'Pencarian')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
            <form action="{{ route('admin.search') }}" method="GET">
                <div class="flex">
                    <input name="q" value="{{ $q }}" class="flex-1 border rounded-l px-3 py-2" placeholder="Ketik kata kunci dan tekan Enter" />
                    <button class="bg-maroon text-white px-4 rounded-r" type="submit">Cari</button>
                </div>
            </form>
        </div>

        @if(empty($q))
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">Masukkan kata kunci untuk mencari data.</div>
        @else
            <div class="grid grid-cols-1 gap-4">
                @if(empty($results) && empty($features))
                    <div class="bg-white p-4 rounded shadow-sm">Tidak ada hasil untuk "<strong>{{ $q }}</strong>".</div>
                @else
                    @if(!empty($features))
                        <div class="bg-white p-4 rounded shadow-sm">
                            <h3 class="font-semibold mb-2">Fitur Cepat</h3>
                            <ul class="divide-y">
                                @foreach($features as $f)
                                    <li class="py-2 flex items-center justify-between">
                                        <div class="text-sm text-gray-700">{{ $f['label'] }}</div>
                                        <div>
                                            @if(isset($f['route']) && Route::has($f['route']))
                                                <a href="{{ route($f['route']) }}" class="text-maroon hover:underline text-sm">Buka</a>
                                            @else
                                                <a href="#" class="text-gray-400 text-sm">Tidak tersedia</a>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @php
                        $routeMap = [
                            'users' => 'users',
                            'dosens' => 'dosen',
                            'mahasiswas' => 'mahasiswa',
                            'mata_kuliahs' => 'mata-kuliah',
                            'kelas_mata_kuliahs' => 'kelas-mata-kuliah',
                            'parents' => 'parents',
                            'jadwals' => 'jadwal',
                            'semesters' => 'semester',
                            'krs' => 'krs',
                        ];
                    @endphp

                    @foreach($results as $table => $items)
                        <div class="bg-white p-4 rounded shadow-sm">
                            <h3 class="font-semibold mb-2">{{ ucfirst(str_replace('_', ' ', $table)) }} ({{ $items->count() }})</h3>
                            <ul class="divide-y">
                                @foreach($items as $item)
                                    <li class="py-2 flex items-center justify-between">
                                        <div class="text-sm text-gray-700">
                                            @php
                                                // Pick a sensible display value
                                                $display = null;
                                                foreach (['name','nama','nama_semester','title','email','nrp','kode','nidn','ruang','hari'] as $c) {
                                                    if (isset($item->$c) && $item->$c) { $display = $item->$c; break; }
                                                }
                                                if (!$display) { $display = 'ID: ' . ($item->id ?? '-'); }
                                            @endphp
                                            {{ $display }}
                                        </div>
                                        <div>
                                            @php $prefix = $routeMap[$table] ?? null; @endphp
                                            @if($prefix)
                                                <a href="{{ url('/admin/' . $prefix . '/' . ($item->id ?? '')) }}" class="text-maroon hover:underline text-sm">Lihat</a>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                @endif
            </div>
        @endif
    </div>
@endsection
