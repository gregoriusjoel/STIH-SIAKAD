<div x-data="{ open: false }"
     @open-settings-modal.window="open = true"
     @keydown.escape.window="open = false"
     x-show="open" 
     class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/50 backdrop-blur-sm p-4"
     style="display: none;"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/50 rounded-t-2xl">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-cog text-gray-400"></i> Pengaturan Surat Prestasi
            </h3>
            <button @click="open = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        {{-- Body --}}
        <form method="POST" action="{{ route('admin.prestasi.settings.update') }}">
            @csrf
            <div class="p-6 space-y-6 max-h-[60vh] overflow-y-auto custom-scrollbar">
                
                @foreach(['tugas', 'rekomendasi', 'keterangan', 'penghargaan'] as $jenis)
                    @php
                        $setting = $suratSettings[$jenis] ?? ['format' => '{counter}/STIH/{tipe}/{month}/{year}', 'counter' => 0];
                        $label = [
                            'tugas' => 'Surat Tugas',
                            'rekomendasi' => 'Surat Rekomendasi',
                            'keterangan' => 'Surat Keterangan',
                            'penghargaan' => 'Surat Penghargaan'
                        ][$jenis];
                    @endphp
                    <div class="space-y-3 p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="font-bold text-sm text-[#8B1538] uppercase tracking-wider">{{ $label }}</div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Format Nomor</label>
                            <input type="text" name="settings[{{ $jenis }}][format]" value="{{ $setting['format'] }}" 
                                   class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-primary focus:border-primary text-sm" required>
                        </div>
                        
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Counter Saat Ini</label>
                                <input type="number" name="settings[{{ $jenis }}][counter]" value="{{ $setting['counter'] }}" 
                                       class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-primary focus:border-primary text-sm" required>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                    <p class="text-xs text-blue-700 font-medium leading-relaxed">
                        <i class="fas fa-info-circle mr-1"></i> <strong>Variabel Tersedia:</strong><br>
                        <code>{counter}</code>: Nomor urut (auto reset tiap tahun)<br>
                        <code>{tipe}</code>: Kode surat (ST, SR, SK, PP)<br>
                        <code>{month}</code>: Bulan (angka)<br>
                        <code>{roman_month}</code>: Bulan (romawi)<br>
                        <code>{year}</code>: Tahun 4 digit
                    </p>
                </div>

            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 rounded-b-2xl">
                <button type="button" @click="open = false" class="px-5 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold bg-[#7a1621] hover:bg-[#63101a] text-white rounded-xl shadow-sm transition-colors">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
