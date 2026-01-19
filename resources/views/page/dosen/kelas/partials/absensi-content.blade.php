<div class="space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('dosen.kelas') }}" class="hover:text-[#8B1538] transition-colors">Kelas Saya</a>
                <span>/</span>
                <span>Detail Absensi</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $class_info['name'] }}</h1>
            <p class="text-gray-500 mt-1">{{ $class_info['code'] }} • {{ $class_info['section'] }}</p>
        </div>
        <div class="flex gap-3">
            <button
                class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium text-sm hover:bg-gray-50 flex items-center shadow-sm">
                <i class="fas fa-print mr-2 text-gray-400"></i> Cetak Rekap
            </button>
            <button
                class="bg-[#8B1538] text-white px-4 py-2 rounded-lg font-medium text-sm hover:bg-[#722036] flex items-center shadow-md">
                <i class="fas fa-save mr-2"></i> Simpan Absensi
            </button>
        </div>
    </div>

    <!-- Page layout: left info + QR, right attendance -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="space-y-6">
            <!-- Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-[#8B1538]">
                <i class="fas fa-chalkboard-teacher text-xl"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Pertemuan Ke-</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $class_info['pertemuan'] }}</p>
            </div>
        </div>
        <div class="flex-1 w-full md:w-auto border-l border-gray-100 pl-0 md:pl-6">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Topik Pembahasan</h3>
            <p class="text-lg font-medium text-gray-900">{{ $class_info['topic'] }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal</p>
            <p class="text-base font-medium text-gray-900"><i
                    class="far fa-calendar-alt mr-2 text-gray-400"></i>{{ $class_info['date'] }}</p>
        </div>
            </div>

            <!-- QR Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">QR Code Absensi</h3>
                *** End Patch