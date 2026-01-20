<div class="space-y-6 font-sans">
    <!-- Header -->
    <div
        class="bg-[#8B1538] text-white p-5 rounded-xl shadow-lg flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">Absensi Kegiatan</h1>
            <p class="text-white/80 text-sm mt-1">{{ $class_info['topic'] }} untuk Kelas {{ $class_info['section'] }}
            </p>
        </div>
        <div class="flex gap-3">
            <button
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition-colors shadow-sm">
                <i class="fas fa-file-export"></i> Laporan
            </button>
            <a href="{{ route('dosen.kelas') }}"
                class="bg-red-700/50 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition-colors shadow-sm border border-red-500/30">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>


                </div>
            </div>

        </div>

