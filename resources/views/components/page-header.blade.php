@props([
    'badge' => 'FKKSM MM2100',
    'title' => '',
    'description' => null,
    'breadcrumb' => null,
])

<!-- Clean Sub-Page Header Component -->
<section class="bg-[#0C3553] text-white border-b border-[#176D91]/30 relative overflow-hidden py-12 lg:py-16">
    <div class="absolute inset-0 bg-industrial-grid-dark opacity-10 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs text-slate-400 mb-4 font-medium" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-[#54D6D0] transition flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Beranda</span>
            </a>
            @if ($breadcrumb)
                <span class="text-slate-600">/</span>
                <span class="text-[#54D6D0] font-semibold">{{ $breadcrumb }}</span>
            @endif
        </nav>

        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
            <div class="max-w-3xl space-y-3">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded bg-[#132B45] border border-[#176D91]/40 text-[#54D6D0] text-[11px] font-mono font-semibold tracking-wider uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#10A9BC]"></span>
                    <span>{!! $badge !!}</span>
                </div>

                <h1 class="font-heading font-extrabold text-3xl sm:text-4xl lg:text-5xl uppercase tracking-tight text-white leading-tight">
                    {!! $title !!}
                </h1>

                @if ($description)
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-2xl font-normal">
                        {{ $description }}
                    </p>
                @endif
            </div>

            @if (isset($actions))
                <div class="shrink-0 pt-2 lg:pt-0">
                    {{ $actions }}
                </div>
            @endif
        </div>
    </div>
</section>
