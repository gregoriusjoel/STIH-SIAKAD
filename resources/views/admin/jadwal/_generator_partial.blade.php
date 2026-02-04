{{-- Generator Partial: used in generator page and jadwal index --}}

@php
    $activeSemester = \App\Models\Semester::where('is_active', true)->first();
@endphp

<div x-data="{}" x-cloak class="bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
    <div class="p-6 border-b border-gray-200 bg-maroon text-white flex items-center justify-between">
        <div class="font-bold text-white text-xl flex items-center gap-3">
            <div class="p-2 bg-white/10 rounded-lg backdrop-blur-sm">
                <i class="fas fa-magic"></i>
            </div>
            Auto Generate Jadwal
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.jadwal_admin_approval.index') }}" class="px-4 py-2 bg-white hover:bg-gray-100 text-maroon rounded-lg font-semibold transition-all duration-200 shadow-sm inline-flex items-center">
                <i class="fas fa-eye mr-2"></i>Lihat Auto Generate
            </a>
            <button @click="$refs.genModal.classList.remove('hidden')" 
                class="px-4 py-2 bg-white hover:bg-gray-100 text-maroon rounded-lg font-semibold transition-all duration-200 shadow-sm">
                <i class="fas fa-plus mr-2"></i>Generate Jadwal
            </button>
        </div>
    </div>

    {{-- Statistics + Recent Proposals (condensed) --}}
    <div class="p-6 bg-gradient-to-br from-gray-50 to-blue-50 border-b border-gray-200">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
            <div class="bg-white rounded-lg p-3 shadow-sm border border-blue-100">
                <div class="text-sm text-gray-600">Total Proposal</div>
                <div class="text-lg font-bold text-blue-600">{{ $statistics['total_proposals'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg p-3 shadow-sm border border-yellow-100">
                <div class="text-sm text-gray-600">Pending Dosen</div>
                <div class="text-lg font-bold text-yellow-600">{{ $statistics['pending_dosen'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg p-3 shadow-sm border border-green-100">
                <div class="text-sm text-gray-600">Approved</div>
                <div class="text-lg font-bold text-green-600">{{ $statistics['approved_admin'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg p-3 shadow-sm border border-red-100">
                <div class="text-sm text-gray-600">Rejected</div>
                <div class="text-lg font-bold text-red-600">{{ $statistics['rejected'] ?? 0 }}</div>
            </div>
        </div>

        @if($jadwalProposals->count())
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                    <h4 class="font-semibold text-gray-800 flex items-center gap-2"><i class="fas fa-history text-blue-600"></i>Proposals Terbaru</h4>
                </div>
                <div class="max-h-48 overflow-y-auto px-4 py-2">
                    @foreach($jadwalProposals as $proposal)
                        <div class="py-2 border-b border-gray-100 last:border-b-0 flex items-center justify-between">
                            <div>
                                <div class="font-medium text-sm text-gray-900">{{ $proposal->mataKuliah->nama_mk ?? ($proposal->mataKuliah->nama ?? '') }}</div>
                                <div class="text-xs text-gray-500">{{ $proposal->hari }} {{ substr($proposal->jam_mulai,0,5) }} - {{ substr($proposal->jam_selesai,0,5) }} • {{ $proposal->ruangan }}</div>
                            </div>
                            <div class="text-xs text-gray-500">
                                @if(in_array($proposal->status, ['pending_dosen']))
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">Menunggu Dosen</span>
                                @elseif(in_array($proposal->status, ['approved_dosen','pending_admin']))
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full">Menunggu Admin</span>
                                @elseif($proposal->status === 'approved_admin')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full">Disetujui</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full">Ditolak</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Generate Modal (simple) --}}
    <div x-ref="genModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="fixed inset-0 bg-black/50" @click="$refs.genModal.classList.add('hidden')"></div>
        <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 z-10">
            <h3 class="text-lg font-semibold mb-3">Generate Jadwal Kuliah</h3>
            <form action="{{ route('admin.jadwal_generator.auto_generate') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="block text-sm text-gray-600">Semester</label>
                        <select name="semester" class="w-full px-3 py-2 border rounded-lg" required>
                            <option value="">Pilih Semester</option>
                            <option value="Ganjil" {{ (old('semester', $activeSemester?->nama_semester ?? '') == 'Ganjil') ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ (old('semester', $activeSemester?->nama_semester ?? '') == 'Genap') ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', $activeSemester?->tahun_ajaran ?? (date('Y') . '/' . (date('Y')+1))) }}" class="w-full px-3 py-2 border rounded-lg" required>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="overwrite_existing" value="1" id="overwrite_existing">
                        <label for="overwrite_existing" class="text-sm text-gray-600">Timpa proposal yang ada</label>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="px-4 py-2 border rounded" @click="$refs.genModal.classList.add('hidden')">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Generate Jadwal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Quick handlers for generator partial
document.addEventListener('click', function(e) {
    // placeholder for dynamic handlers if needed
});
</script>
@endpush
