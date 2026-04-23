                                                                        <?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'size' => 'md', // sm, md, lg
    'color' => 'text-[#8B1538]',
    'label' => null,
    'labelClass' => '',
    'variant' => 'wave',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'size' => 'md', // sm, md, lg
    'color' => 'text-[#8B1538]',
    'label' => null,
    'labelClass' => '',
    'variant' => 'wave',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $heights = [
        'sm' => 'h-3',
        'md' => 'h-5',
        'lg' => 'h-8',
    ];
    
    $widths = [
        'sm' => 'w-0.5',
        'md' => 'w-1',
        'lg' => 'w-1.5',
    ];
    
    $barHeight = $heights[$size] ?? $heights['md'];
    $barWidth = $widths[$size] ?? $widths['md'];
?>

<div role="status" <?php echo e($attributes->merge(['class' => 'flex flex-col items-center'])); ?>>
    <span class="sr-only">Loading...</span>
    <div class="flex items-center justify-center gap-[6px] <?php echo e($color); ?>">
        <div class="<?php echo e($barHeight); ?> <?php echo e($barWidth); ?> bg-current rounded-full animate-wave" style="animation-delay: 0s;"></div>
        <div class="<?php echo e($barHeight); ?> <?php echo e($barWidth); ?> bg-current rounded-full animate-wave" style="animation-delay: -0.3s;"></div>
        <div class="<?php echo e($barHeight); ?> <?php echo e($barWidth); ?> bg-current rounded-full animate-wave" style="animation-delay: -0.15s;"></div>
        <div class="<?php echo e($barHeight); ?> <?php echo e($barWidth); ?> bg-current rounded-full animate-wave" style="animation-delay: 0s;"></div>
        <div class="<?php echo e($barHeight); ?> <?php echo e($barWidth); ?> bg-current rounded-full animate-wave" style="animation-delay: -0.3s;"></div>
    </div>

    <?php if($label): ?>
        <div class="<?php echo e($labelClass); ?> mt-2"><?php echo e($label); ?></div>
    <?php endif; ?>

    <style>
        @keyframes wave {
            0%, 100% {
                transform: scaleY(0.4);
                opacity: 0.6;
            }
            50% {
                transform: scaleY(1);
                opacity: 1;
            }
        }
        .animate-wave {
            animation: wave 1.2s ease-in-out infinite;
            will-change: transform, opacity;
        }
    </style>
</div>
<?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/components/ui/spinner.blade.php ENDPATH**/ ?>