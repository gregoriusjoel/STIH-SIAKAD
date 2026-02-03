                                                                        @props([
    'size' => 'md', // sm, md, lg
    'color' => 'text-[#8B1538]',
    'label' => null,
    'labelClass' => '',
    'variant' => 'wave',
])

@php
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
@endphp

<div role="status" {{ $attributes->merge(['class' => 'flex flex-col items-center']) }}>
    <span class="sr-only">Loading...</span>
    <div class="flex items-center justify-center gap-[6px] {{ $color }}">
        <div class="{{ $barHeight }} {{ $barWidth }} bg-current rounded-full animate-wave" style="animation-delay: 0s;"></div>
        <div class="{{ $barHeight }} {{ $barWidth }} bg-current rounded-full animate-wave" style="animation-delay: -0.3s;"></div>
        <div class="{{ $barHeight }} {{ $barWidth }} bg-current rounded-full animate-wave" style="animation-delay: -0.15s;"></div>
        <div class="{{ $barHeight }} {{ $barWidth }} bg-current rounded-full animate-wave" style="animation-delay: 0s;"></div>
        <div class="{{ $barHeight }} {{ $barWidth }} bg-current rounded-full animate-wave" style="animation-delay: -0.3s;"></div>
    </div>

    @if($label)
        <div class="{{ $labelClass }} mt-2">{{ $label }}</div>
    @endif

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
