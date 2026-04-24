<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', 'STIH'); ?> - Keuangan</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/logo_stih_white.png')); ?>">

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts/dist/apexcharts.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Vite Assets -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <style>
        [x-cloak] { display: none !important; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="bg-gray-100 font-nunito overflow-hidden">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        
        <!-- Specialized Finance Sidebar -->
        <?php echo $__env->make('layouts.partials.sidebar-finance', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Content Wrapper -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar">

            <!-- Specialized Finance Topbar -->
            <?php echo $__env->make('layouts.partials.navbar-finance', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Main Content -->
            <main class="w-full flex-grow p-4 md:p-8">
                <?php echo $__env->yieldContent('content'); ?>
            </main>

            <!-- Footer -->
            <?php echo $__env->make('layouts.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    <!-- Scroll to Top (Optional for modern layouts but kept for utility) -->
    <div id="scrollToTop" class="fixed bottom-8 right-8 z-50 hidden">
        <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" 
                class="size-12 rounded-2xl bg-[#8B1538] text-white shadow-lg shadow-red-900/20 flex items-center justify-center hover:scale-110 transition-transform">
            <i class="fas fa-chevron-up"></i>
        </button>
    </div>

    <!-- JS Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    <script>
        // Auto-scroll-to-top visibility
        window.addEventListener('scroll', () => {
            const btn = document.getElementById('scrollToTop');
            if (window.scrollY > 300) btn?.classList.remove('hidden');
            else btn?.classList.add('hidden');
        });
    </script>

    <?php if (isset($component)) { $__componentOriginalb2a2353587888c282e9549a3f6940609 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2a2353587888c282e9549a3f6940609 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.preloader','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.preloader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb2a2353587888c282e9549a3f6940609)): ?>
<?php $attributes = $__attributesOriginalb2a2353587888c282e9549a3f6940609; ?>
<?php unset($__attributesOriginalb2a2353587888c282e9549a3f6940609); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb2a2353587888c282e9549a3f6940609)): ?>
<?php $component = $__componentOriginalb2a2353587888c282e9549a3f6940609; ?>
<?php unset($__componentOriginalb2a2353587888c282e9549a3f6940609); ?>
<?php endif; ?>
</body>
</html>
<?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/layouts/finance.blade.php ENDPATH**/ ?>