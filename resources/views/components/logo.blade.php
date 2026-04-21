@props(['iconColor' => 'text-[#1A8F5A]', 'textColor' => 'text-[#144d42] dark:text-[#a0d6cb]', 'iconOnly' => false, 'size' => 'normal', 'subtitle' => 'Clinical Admin'])

@php
    $dimensions = $size === 'large' ? '56px' : '40px';
    $textSize = $size === 'large' ? '1.875rem' : '1.25rem';
    $subtextSize = $size === 'large' ? '12px' : '10px';
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
    <!-- Logo Icon -->
    <div class="relative flex items-center justify-center shrink-0" style="width: {{ $dimensions }}; height: {{ $dimensions }};">
        <svg viewBox="0 0 160 160" xmlns="http://www.w3.org/2000/svg" class="w-full h-full {{ $iconColor }} drop-shadow-sm transition-all duration-300">
            <!-- Define Gradient for rich aesthetics -->
            <defs>
                <linearGradient id="logo-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="currentColor"/>
                    <stop offset="100%" stop-color="currentColor" stop-opacity="0.8"/>
                </linearGradient>
            </defs>
            
            <!-- Continuous Outer 'C' and Inner 'e' -->
            <path d="
                     M 120 30 
                     A 20 20 0 0 0 100 10 
                     L 50 10 
                     A 40 40 0 0 0 10 50 
                     L 10 100 
                     A 40 40 0 0 0 50 140 
                     L 100 140 
                     A 20 20 0 0 0 120 120
                     L 120 80
                     A 15 15 0 0 0 105 65
                     L 60 65
                     A 20 20 0 0 1 60 25
                     L 110 25" 
                  fill="none" stroke="url(#logo-gradient)" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>

            <!-- Medical Cross properly aligned inside -->
            <path d="M 20 85 L 60 85 M 40 65 L 40 105" fill="none" stroke="url(#logo-gradient)" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
            
            <!-- eCabinet Feet base -->
            <path d="M 35 140 L 30 155 M 105 140 L 110 155" fill="none" stroke="url(#logo-gradient)" stroke-width="14" stroke-linecap="round"/>
        </svg>
    </div>

    <!-- Text part -->
    @if(!$iconOnly)
    <div class="flex flex-col justify-center">
        <h1 class="font-bold tracking-tight {{ $textColor }} leading-none" style="font-family: 'Manrope', sans-serif; font-size: {{ $textSize }};">eCabinet</h1>
        @if($size !== 'large')
            <p class="font-bold tracking-[0.2em] {{ str_contains($textColor, 'white') ? 'text-white/80' : 'text-slate-400' }} mt-1 uppercase" style="font-family: 'Inter', sans-serif; font-size: {{ $subtextSize }}; line-height: 1;">{{ $subtitle }}</p>
        @endif
    </div>
    @endif
</div>
