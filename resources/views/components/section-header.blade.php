@props([
    'badge' => null,
    'title',
    'subtitle' => null,
    'linkUrl' => null,
    'linkText' => null,
    'align' => 'left'
])

<div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8 {{ $align === 'center' ? 'text-center md:text-center max-w-2xl mx-auto' : '' }}">
    <div>
        @if ($badge)
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-[#EDF5FA] border border-[#DCE6EE] text-[#10A9BC] text-xs font-mono font-bold tracking-wider uppercase mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#10A9BC]"></span>
                {{ $badge }}
            </div>
        @endif
        <h2 class="font-heading font-extrabold text-2xl sm:text-3xl lg:text-4xl text-[#132B45] uppercase tracking-tight leading-tight">
            {{ $title }}
        </h2>
        @if ($subtitle)
            <p class="text-xs sm:text-sm text-slate-600 mt-2 max-w-2xl leading-relaxed">
                {{ $subtitle }}
            </p>
        @endif
    </div>

    @if ($linkUrl && $linkText)
        <a href="{{ $linkUrl }}" class="text-[#10A9BC] hover:text-[#0C3553] font-heading font-bold text-xs uppercase tracking-wider transition flex items-center gap-1.5 shrink-0 self-start md:self-end">
            <span>{{ $linkText }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    @endif
</div>

