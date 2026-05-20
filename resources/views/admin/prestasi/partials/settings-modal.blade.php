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
                        
                        // Extract custom code from format
                        // Expected format: {counter}/[custom_code]/{month}/{year}
                        $customCode = 'STIH/ST'; // default
                        if (preg_match('/\{counter\}\/(.+?)\/\{month\}/', $setting['format'], $matches)) {
                            $customCode = $matches[1];
                        }
                    @endphp
                    <div class="space-y-3 p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="font-bold text-sm text-[#8B1538] uppercase tracking-wider">{{ $label }}</div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Format Nomor Surat</label>
                            <div class="bg-white px-3 py-2.5 rounded-lg border border-gray-200 text-sm space-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-600 font-mono">001</span>
                                    <span class="text-gray-400">/</span>
                                    <input type="text" name="settings[{{ $jenis }}][custom_code]" 
                                           value="{{ $customCode }}"
                                           placeholder="STIH/ST"
                                           maxlength="20"
                                           class="flex-1 px-2 py-1 rounded border border-gray-300 text-sm focus:ring-primary focus:border-primary"
                                           required>
                                    <span class="text-gray-400">/5/2026</span>
                                </div>
                                <p class="text-xs text-gray-500">Kode institusi dan surat (misal: STIH/ST)</p>
                            </div>
                        </div>

                        <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="text-xs text-blue-600 font-semibold mb-1">📋 Contoh Nomor Surat:</div>
                            <div class="font-mono text-sm text-blue-700 font-bold" data-jenis-surat="{{ $jenis }}" data-preview-target="{{ $jenis }}">
                                Memuat preview...
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Counter Saat Ini</label>
                            <input type="number" name="settings[{{ $jenis }}][counter]" value="{{ $setting['counter'] }}" 
                                   class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-primary focus:border-primary text-sm" required>
                            <p class="text-xs text-gray-500 mt-1">⚠️ Counter akan otomatis +1 setiap kali surat dibuat (bukan saat refresh)</p>
                        </div>
                    </div>
                @endforeach

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadPreviewNomor = async () => {
        const previewElements = document.querySelectorAll('[data-jenis-surat]');
        
        for (const elem of previewElements) {
            const jenisSurat = elem.getAttribute('data-jenis-surat');
            const target = elem.getAttribute('data-preview-target');
            
            try {
                const response = await fetch(`{{ route('admin.prestasi.preview-nomor') }}?jenis_surat=${jenisSurat}`);
                const data = await response.json();
                
                if (data.nomor_surat) {
                    elem.textContent = data.nomor_surat;
                } else {
                    elem.textContent = 'Gagal memuat preview';
                }
            } catch (error) {
                console.error('Error loading preview:', error);
                elem.textContent = 'Gagal memuat preview';
            }
        }
    };

    // Load preview saat modal dibuka
    document.addEventListener('open-settings-modal', loadPreviewNomor);
    
    // Atau load langsung
    loadPreviewNomor();
});
</script>
