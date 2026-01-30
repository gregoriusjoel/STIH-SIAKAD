@foreach($availableMahasiswas as $mahasiswa)
    <label class="flex items-center p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0">
        <input type="checkbox" 
               name="mahasiswa_ids[]" 
               value="{{ $mahasiswa->id }}"
               class="w-4 h-4 text-maroon border-gray-300 rounded focus:ring-maroon">
        <div class="ml-3 flex-1">
            <div class="text-sm font-medium text-gray-900">{{ $mahasiswa->user->name }}</div>
            <div class="text-xs text-gray-500">NIM: {{ $mahasiswa->nim }}</div>
        </div>
    </label>
@endforeach

@if($availableMahasiswas->total() > $availableMahasiswas->perPage())
    <div class="mt-2">{{ $availableMahasiswas->appends(request()->only('search','tab'))->links() }}</div>
@endif
