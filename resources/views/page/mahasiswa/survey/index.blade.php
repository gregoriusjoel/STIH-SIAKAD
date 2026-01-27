@extends('layouts.mahasiswa')

@section('title', 'Kuesioner Mahasiswa Baru')
@section('page-title', 'Kuesioner Mahasiswa Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-maroon to-maroon-hover text-white p-6">
            <h2 class="text-2xl font-bold mb-2">Kuesioner Mahasiswa Baru</h2>
            <p class="text-sm opacity-90">Harap isi kuesioner singkat berikut sebelum melanjutkan ke dashboard.</p>
        </div>

        <form action="{{ route('mahasiswa.survey_new.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="space-y-4">
                @if(!empty($questions) && is_array($questions))
                    @foreach($questions as $index => $q)
                        <div class="bg-white rounded-lg shadow-md p-4">
                            <div class="text-sm text-gray-700 mb-3">{{ $loop->iteration }}. {{ $q['text'] }} <span class="text-red-600">*</span></div>
                                <div class="grid grid-cols-2 gap-x-6 gap-y-3 mt-2">
                                    <label class="flex items-center space-x-3 text-sm">
                                        <input type="radio" name="{{ $q['key'] }}" value="4" required class="h-4 w-4 text-maroon"> 
                                        <span>Sangat Baik</span>
                                    </label>
                                    <label class="flex items-center space-x-3 text-sm">
                                        <input type="radio" name="{{ $q['key'] }}" value="3" class="h-4 w-4 text-maroon"> 
                                        <span>Baik</span>
                                    </label>
                                    <label class="flex items-center space-x-3 text-sm">
                                        <input type="radio" name="{{ $q['key'] }}" value="2" class="h-4 w-4 text-maroon"> 
                                        <span>Cukup Baik</span>
                                    </label>
                                    <label class="flex items-center space-x-3 text-sm">
                                        <input type="radio" name="{{ $q['key'] }}" value="1" class="h-4 w-4 text-maroon"> 
                                        <span>Kurang Baik</span>
                                    </label>
                                </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div>
                <label class="block text-gray-800 font-semibold mb-2">Saran dan Masukan (Opsional)</label>
                <textarea name="saran" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent" placeholder="Tuliskan saran atau masukan Anda..."></textarea>
            </div>

            <div class="flex justify-center">
                <button type="submit" class="px-8 py-3 bg-maroon text-white font-bold rounded-lg">Kirim dan Lanjutkan</button>
            </div>
        </form>
    </div>
</div>
@endsection
