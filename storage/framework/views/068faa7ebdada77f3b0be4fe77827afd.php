<aside
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-[#1f1616] flex flex-col md:sticky md:top-0 md:h-screen shadow-xl md:shadow-none transition-transform duration-300 transform md:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    
    <div class="h-16 flex items-center gap-3 px-6 border-b border-white/10 bg-[#8B1538] dark:bg-[#3a0a1a]">
        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white shadow-sm border border-white/20 shrink-0">
             <img src="<?php echo e(asset('images/logo_stih_white.png')); ?>" alt="STIH" class="w-6 h-6 object-contain">
        </div>
        <div class="flex flex-col min-w-0">
            <h1 class="text-white text-sm font-bold truncate leading-tight drop-shadow-sm">
                STIH Adhyaksa
            </h1>
            <p class="text-white/70 text-[10px] font-medium tracking-wider uppercase">
                Student Site
            </p>
        </div>
    </div>

    
    <style>
        .sidebar-no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .sidebar-no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    <div class="flex-1 overflow-y-auto overflow-x-hidden sidebar-no-scrollbar py-6 px-4 space-y-1 border-r border-gray-100 dark:border-gray-800">
        
        <?php
            $navItemClass = "group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium text-sm relative overflow-hidden";
            $activeClass = "bg-gradient-to-r from-[#8B1538] to-[#6D1029] text-white shadow-md shadow-red-900/20";
            $inactiveClass = "text-gray-600 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/10 hover:text-[#8B1538] dark:hover:text-red-400";
        ?>

        <div x-data="{
            openAkademik: <?php echo e(Request::routeIs('mahasiswa.nilai*','mahasiswa.kelas*','mahasiswa.jadwal*','mahasiswa.perpustakaan*','mahasiswa.prestasi*') ? 'true' : 'false'); ?>,
            openPengajuan: <?php echo e(Request::routeIs('mahasiswa.pengajuan*') ? 'true' : 'false'); ?>,
            openMagang: <?php echo e(Request::routeIs('mahasiswa.magang*') ? 'true' : 'false'); ?>

        }" class="space-y-1">

            
            <a href="<?php echo e(route('mahasiswa.dashboard')); ?>"
               class="<?php echo e($navItemClass); ?> <?php echo e(Request::routeIs('mahasiswa.dashboard') ? $activeClass : $inactiveClass); ?>">
                <i class="fas fa-home w-5 text-center transition-transform group-hover:scale-110"></i>
                <span class="tracking-wide">Dashboard</span>
                <?php if(Request::routeIs('mahasiswa.dashboard')): ?>
                    <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-white/20 rounded-l-full"></div>
                <?php endif; ?>
            </a>

            
            <a href="<?php echo e(route('mahasiswa.profil.manajemen')); ?>"
               class="<?php echo e($navItemClass); ?> <?php echo e(Request::routeIs('mahasiswa.profil.manajemen') ? $activeClass : $inactiveClass); ?>">
                <i class="fas fa-user-cog w-5 text-center transition-transform group-hover:scale-110"></i>
                <span class="tracking-wide">Manajemen Profil</span>
            </a>

            
            <a href="<?php echo e(route('mahasiswa.krs.index')); ?>"
                class="<?php echo e($navItemClass); ?> <?php echo e(Request::routeIs('mahasiswa.krs*') ? $activeClass : $inactiveClass); ?>">
                 <i class="fas fa-file-alt w-5 text-center transition-transform group-hover:scale-110"></i>
                 <span class="tracking-wide">KRS</span>
             </a>

            
            <div class="pt-4 pb-2">
                <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Akademik</p>
            </div>

            
            <div>
                <button @click="openAkademik=!openAkademik"
                    class="w-full flex items-center justify-between <?php echo e($navItemClass); ?> <?php echo e(Request::routeIs('mahasiswa.nilai*','mahasiswa.kelas*','mahasiswa.jadwal*','mahasiswa.perpustakaan*','mahasiswa.prestasi*') ? $activeClass : $inactiveClass); ?>">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-graduation-cap w-5 text-center"></i>
                        <span class="tracking-wide">Akademik</span>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] transition-transform duration-300"
                       :class="{'rotate-90':openAkademik}"></i>
                </button>

                <div x-show="openAkademik" x-collapse x-cloak
                     class="mt-1 ml-4 pl-4 border-l-2 border-gray-100 dark:border-gray-800 space-y-1">
                    <?php $__currentLoopData = [
                        'mahasiswa.jadwal.index'=>'Jadwal Kelas',
                        'mahasiswa.kelas.index'=>'E-Learning',
                        'mahasiswa.nilai.index'=>'Kartu Hasil Studi',
                        'mahasiswa.perpustakaan.index'=>'Perpustakaan',
                        'mahasiswa.prestasi.index'=>'Prestasi Mahasiswa',
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route($route)); ?>"
                           class="block px-4 py-2 text-xs rounded-lg transition-colors
                           <?php echo e(Request::routeIs($route) ? 'bg-gradient-to-r from-[#8B1538] to-[#6D1029] text-white shadow-md shadow-red-900/20' : 'text-gray-500 dark:text-gray-400 hover:text-[#8B1538] dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10'); ?>">
                            <?php echo e($label); ?>

                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

             
             <div>
                <button @click="openPengajuan=!openPengajuan"
                    class="w-full flex items-center justify-between <?php echo e($navItemClass); ?> <?php echo e(Request::routeIs('mahasiswa.pengajuan*') ? $activeClass : $inactiveClass); ?>">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-signature w-5 text-center"></i>
                        <span class="tracking-wide">Pengajuan</span>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] transition-transform duration-300"
                       :class="{'rotate-90':openPengajuan}"></i>
                </button>

                <div x-show="openPengajuan" x-collapse x-cloak
                     class="mt-1 ml-4 pl-4 border-l-2 border-gray-100 dark:border-gray-800 space-y-1">
                    <?php $__currentLoopData = [
                        'mahasiswa.pengajuan.surat.index'=>'Pengajuan Surat',
                        'mahasiswa.pengajuan.yudisium.index'=>'Pengajuan Yudisium',
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route($route)); ?>"
                           class="block px-4 py-2 text-xs rounded-lg transition-colors
                           <?php echo e(Request::routeIs($route) ? 'bg-gradient-to-r from-[#8B1538] to-[#6D1029] text-white shadow-md shadow-red-900/20' : 'text-gray-500 dark:text-gray-400 hover:text-[#8B1538] dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10'); ?>">
                            <?php echo e($label); ?>

                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <a href="<?php echo e(route('mahasiswa.magang.index')); ?>"
               class="<?php echo e($navItemClass); ?> <?php echo e(Request::routeIs('mahasiswa.magang*') ? $activeClass : $inactiveClass); ?>">
                <i class="fas fa-briefcase w-5 text-center transition-transform group-hover:scale-110"></i>
                <span class="tracking-wide">Magang</span>
            </a>

            
            <?php if(Route::has('mahasiswa.skripsi.index')): ?>
            <a href="<?php echo e(route('mahasiswa.skripsi.index')); ?>"
               class="<?php echo e($navItemClass); ?> <?php echo e(Request::routeIs('mahasiswa.skripsi*') ? $activeClass : $inactiveClass); ?>">
                <i class="fas fa-graduation-cap w-5 text-center transition-transform group-hover:scale-110"></i>
                <span class="tracking-wide">Skripsi</span>
            </a>
            <?php endif; ?>

            
            <div class="pt-4 pb-2">
                <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Keuangan</p>
            </div>

            
            <a href="<?php echo e(route('mahasiswa.pembayaran.index')); ?>"
                class="<?php echo e($navItemClass); ?> <?php echo e(Request::routeIs('mahasiswa.pembayaran*') ? $activeClass : $inactiveClass); ?>">
                 <i class="fas fa-credit-card w-5 text-center transition-transform group-hover:scale-110"></i>
                 <span class="tracking-wide">Pembayaran</span>
             </a>

        </div>
    </div>

    
    <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-black/20 border-r border-gray-100 dark:border-gray-800">
        
        
        <a href="<?php echo e(route('mahasiswa.profil.index')); ?>" class="flex items-center gap-3 mb-3 px-3 py-2 rounded-xl hover:bg-white dark:hover:bg-white/5 transition border border-transparent hover:border-gray-200 dark:hover:border-gray-700 group">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#8B1538] to-[#6D1029] text-white flex items-center justify-center font-bold shadow-sm shrink-0">
                <?php if(Auth::user()->mahasiswa && Auth::user()->mahasiswa->foto): ?>
                    <img src="<?php echo e(Auth::user()->mahasiswa->foto_url ?? ''); ?>" alt="Foto" class="w-full h-full rounded-full object-cover">
                <?php else: ?>
                    <?php echo e(substr(Auth::user()->mahasiswa->nama ?? Auth::user()->name, 0, 1)); ?>

                <?php endif; ?>
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-bold text-gray-700 dark:text-gray-200 truncate group-hover:text-[#8B1538] dark:group-hover:text-red-400 transition">
                    <?php echo e(Auth::user()->mahasiswa->nama ?? Auth::user()->name); ?>

                </p>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Online
                </p>
            </div>
        </a>
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit"
                class="group w-full flex items-center gap-3 px-4 py-3 rounded-xl border border-red-100 dark:border-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-900/80 transition-all shadow-sm hover:shadow-red-600/20">
                <i class="fas fa-sign-out-alt w-5 text-center transition-transform group-hover:-translate-x-1"></i>
                <span class="font-semibold text-sm">Logout</span>
            </button>
        </form>
    </div>

</aside>
<?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/layouts/partials/sidebar-mahasiswa.blade.php ENDPATH**/ ?>