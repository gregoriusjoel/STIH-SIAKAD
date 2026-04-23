<header class="bg-gradient-to-r from-[#8B1538] to-[#6D1029] dark:from-[#3a0a1a] dark:to-[#1a050d] border-b border-white/10 px-4 sm:px-6 md:px-8 shadow-md sticky top-0 z-40 transition-colors duration-200 h-16 relative overflow-hidden">
    <!-- Decorative Background Images -->
    <div class="absolute inset-0 pointer-events-none z-0 flex justify-between px-10 md:px-32 items-center opacity-5">
        <img src="<?php echo e(asset('images/university_bg.png')); ?>" class="h-28 w-auto transform -rotate-12 translate-y-2" alt="">
        <img src="<?php echo e(asset('images/university_bg.png')); ?>" class="h-32 w-auto transform rotate-6 -translate-y-4 hidden md:block" alt="">
        <img src="<?php echo e(asset('images/university_bg.png')); ?>" class="h-24 w-auto transform rotate-12 translate-y-3" alt="">
    </div>
    <div class="flex items-center justify-between h-full relative z-10">
        <!-- Mobile Menu Button -->
        <button class="md:hidden w-10 h-10 flex items-center justify-center text-white/80 hover:bg-white/10 rounded-lg transition-colors" @click="sidebarOpen = !sidebarOpen">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <!-- Page Title / Branding -->
        <div class="flex items-center gap-4">
            <h2 class="text-lg font-extrabold text-white tracking-tight drop-shadow-sm">
                <?php echo $__env->yieldContent('page-title', 'Dashboard'); ?>
            </h2>
        </div>

        <!-- Right Side -->
        <div class="flex items-center gap-4">


            <!-- User Dropdown (minimal) -->
            <div class="flex items-center gap-3 pl-4 border-l border-white/20">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-white leading-tight">
                        <?php echo e(Auth::user()->mahasiswa->nama ?? Auth::user()->name); ?>

                    </p>
                    <p class="text-[10px] text-white/70 font-bold uppercase tracking-wider">
                        NIM: <?php echo e(Auth::user()->mahasiswa->nim ?? '-'); ?>

                    </p>
                </div>
                <div class="relative group cursor-pointer">
                    <?php if(Auth::user()->mahasiswa && Auth::user()->mahasiswa->foto): ?>
                        <img src="<?php echo e(Auth::user()->mahasiswa->foto_url ?? ''); ?>" 
                             alt="Profile Photo" 
                             class="w-10 h-10 rounded-full object-cover border-2 border-white/20 shadow-md">
                    <?php else: ?>
                        <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold shadow-sm border border-white/30 transition-transform group-hover:scale-105">
                            <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                        </div>
                    <?php endif; ?>
                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-[#8B1538] rounded-full"></div>
                </div>
            </div>
        </div>
    </div>
</header>
<?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/layouts/partials/navbar-mahasiswa.blade.php ENDPATH**/ ?>