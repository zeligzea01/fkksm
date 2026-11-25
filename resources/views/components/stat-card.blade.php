@props([
    'number',
    'label',
    'caption' => null,
    'theme' => 'light'
])

@if ($theme === 'dark')
    <article class="p-6 rounded-xl bg-[#0C3553] border border-[#176D91]/40 text-white shadow-xs">
        <h3 class="font-heading font-extrabold text-4xl sm:text-5xl text-[#54D6D0] leading-none">{{ $number }}</h3>
        <p class="font-heading font-bold text-sm uppercase tracking-wider text-white mt-2 leading-tight">{{ $label }}</p>
        @if ($caption)
            <span class="text-[11px] text-[#A5B4C2] font-mono mt-1 block">{{ $caption }}</span>
        @endif
    </article>
@else
    <article class="p-6 rounded-xl bg-white border border-[#DCE6EE] shadow-xs">
        <h3 class="font-heading font-extrabold text-4xl sm:text-5xl text-[#10A9BC] leading-none">{{ $number }}</h3>
        <p class="font-heading font-bold text-sm uppercase tracking-wider text-[#132B45] mt-2 leading-tight">{{ $label }}</p>
        @if ($caption)
            <span class="text-[11px] text-[#61758A] font-mono mt-1 block">{{ $caption }}</span>
        @endif
    </article>
@endif

