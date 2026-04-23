<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', 'SIAKAD STIH - Mahasiswa'); ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/logo_stih_white.png')); ?>">

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1" rel="stylesheet" crossorigin="anonymous" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Vite Assets -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .active-nav {
            background: linear-gradient(90deg, rgba(139, 21, 56, 0.1) 0%, rgba(139, 21, 56, 0.05) 100%);
            color: #8B1538;
            font-weight: 600;
            border-left: 3px solid #8B1538;
        }

        .sidebar-link {
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background: rgba(139, 21, 56, 0.05);
            transform: translateX(2px);
        }

        /* Thin scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #8B1538;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #6D1029;
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>



</head>

<body class="bg-bg-body font-inter text-text-primary transition-colors duration-200 overflow-x-hidden"
      x-data="{ sidebarOpen: false }"
      @open-sidebar.window="sidebarOpen = true">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let touchStartX = 0;
            let touchStartY = 0;
            
            // console.log('Swipe gesture initialized (Global)');
            
            document.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].clientX;
                touchStartY = e.changedTouches[0].clientY;
            }, false);
            
            document.addEventListener('touchend', function(e) {
                const touchEndX = e.changedTouches[0].clientX;
                const touchEndY = e.changedTouches[0].clientY;
                
                // Only on mobile devices (md breakpoint is usually 768px)
                if (window.innerWidth >= 768) {
                    return;
                }
                
                const deltaX = touchEndX - touchStartX;
                const deltaY = touchEndY - touchStartY;
                
                // Check if movement is primarily horizontal
                if (Math.abs(deltaX) <= Math.abs(deltaY)) {
                    return; // Vertical movement
                }
                
                // Check if swipe is Left -> Right
                if (deltaX <= 70) { // Increased threshold slightly for global swipe
                    return;
                }

                // Check for conflict with scrollable elements
                // If the user swipes on an element that can scroll left, ignore the sidebar toggle
                let target = e.target;
                let isScrollable = false;

                while (target && target !== document.body) {
                    // Check if element has horizontal scroll
                    if (target.scrollWidth > target.clientWidth) {
                        const style = window.getComputedStyle(target);
                        if (style.overflowX === 'auto' || style.overflowX === 'scroll') {
                            // If it's scrollable and NOT at the start, user is scrolling the element
                            if (target.scrollLeft > 0) {
                                isScrollable = true;
                                break;
                            }
                        }
                    }
                    target = target.parentElement;
                }

                if (isScrollable) {
                    console.log('Swipe ignored due to scrollable container');
                    return;
                }
                
                // Valid swipe detected
                // console.log('Global swipe detected! Opening sidebar...');
                window.dispatchEvent(new CustomEvent('open-sidebar'));
                
            }, false);
        });
    </script>

    <!-- Page Wrapper -->
    <div class="flex min-h-screen bg-bg-body">

        <!-- Mobile Backdrop -->
        <div x-show="sidebarOpen"
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/80 z-40 md:hidden">
        </div>

        <!-- Sidebar -->
        <?php echo $__env->make('layouts.partials.sidebar-mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Content Wrapper -->
        <div class="relative flex flex-col flex-1 min-w-0">

            <!-- Topbar -->
            <?php echo $__env->make('layouts.partials.navbar-mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>



            <!-- Main Content -->
            <main class="flex-1 w-full min-w-0 p-4 sm:p-6 lg:p-8 bg-bg-body transition-colors duration-200">


                <?php echo $__env->yieldContent('content'); ?>

            </main>

            <!-- Footer -->
            <footer class="bg-bg-card border-t border-border-color px-6 py-4 transition-colors duration-200">
                <div class="text-center text-sm text-text-secondary">
                    <p>&copy; <?php echo e(date('Y')); ?> Universitas Adhyaksa. All rights reserved.</p>
                </div>
            </footer>

        </div>
    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>

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
<?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/layouts/mahasiswa.blade.php ENDPATH**/ ?>