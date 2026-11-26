@php
    $hero = $sections['hero_slides'] ?? null;
    $slides = $hero['content']['slides'] ?? [];
@endphp

@if(($hero['is_active'] ?? true) && count($slides) > 0)
<!-- Main Hero Carousel Slider (Dynamic via Admin CMS) -->
<section class="relative bg-[#F5F8FC] py-6 sm:py-8 border-b border-slate-200">
    <div class="fkksm-container">
        <div class="relative w-full rounded-xl overflow-hidden shadow-xs border border-slate-200 bg-white aspect-[2/1] sm:aspect-[21/9] max-h-[460px] 2xl:max-h-[560px] min-[2200px]:max-h-[640px]" id="fkksmHeroCarousel">
            @foreach($slides as $idx => $slide)
                <div class="fkksm-slide absolute inset-0 transition-opacity duration-500 {{ $idx === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
                    <img src="{{ \App\Models\LandingSection::resolveImageUrl($slide['image'] ?? null, 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1400&q=80') }}" onerror="this.onerror=null;this.src='{{ asset('fkksm/assets/images/activity-01.jpg') }}'" alt="{{ $slide['headline'] ?? 'Hero Banner' }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-950/85 via-slate-950/45 to-transparent flex items-center p-6 sm:p-12 lg:p-16">
                        <div class="max-w-xl text-white space-y-4">
                            @if(!empty($slide['badge']))
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded bg-[#10A9BC]/30 border border-[#10A9BC]/50 text-[#54D6D0] text-xs font-mono font-medium uppercase tracking-wider">
                                    <span class="w-2 h-2 rounded-full bg-[#54D6D0]"></span>
                                    {{ $slide['badge'] }}
                                </span>
                            @endif

                            @if($idx === 0)
                                <h1 class="font-heading font-extrabold text-3xl sm:text-5xl lg:text-6xl uppercase tracking-tight leading-tight">
                                    {{ $slide['headline'] ?? '' }}
                                </h1>
                            @else
                                <h2 class="font-heading font-extrabold text-3xl sm:text-5xl lg:text-6xl uppercase tracking-tight leading-tight">
                                    {{ $slide['headline'] ?? '' }}
                                </h2>
                            @endif

                            @if(!empty($slide['subtitle']))
                                <p class="text-xs sm:text-base text-slate-200 line-clamp-3 leading-relaxed">
                                    {{ $slide['subtitle'] }}
                                </p>
                            @endif

                            <div class="flex flex-wrap items-center gap-3 pt-2">
                                @if(!empty($slide['cta_label']))
                                    <a href="{{ \App\Models\LandingSection::resolveUrl($slide['cta_url'] ?? null, '#katalog-kursus') }}" class="px-6 py-3 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold text-xs uppercase tracking-wider transition shadow-sm">
                                        {{ $slide['cta_label'] }} →
                                    </a>
                                @endif
                                @if(!empty($slide['secondary_label']))
                                    <a href="{{ \App\Models\LandingSection::resolveUrl($slide['secondary_url'] ?? null, route('profile.fkksm')) }}" class="px-6 py-3 rounded-lg bg-white/10 hover:bg-white/20 text-white font-heading font-bold text-xs uppercase tracking-wider border border-white/20 transition">
                                        {{ $slide['secondary_label'] }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if(count($slides) > 1)
                <button type="button" onclick="changeFkksmSlide(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-slate-900/60 hover:bg-slate-900 text-white flex items-center justify-center transition shadow-md z-20" aria-label="Slide sebelumnya">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" onclick="changeFkksmSlide(1)" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-slate-900/60 hover:bg-slate-900 text-white flex items-center justify-center transition shadow-md z-20" aria-label="Slide berikutnya">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>

                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 z-20" id="fkksmCarouselDots">
                    @foreach($slides as $idx => $slide)
                        <button type="button" onclick="goToFkksmSlide({{ $idx }})" class="w-3 h-3 rounded-full {{ $idx === 0 ? 'bg-[#10A9BC]' : 'bg-white/60 hover:bg-white' }} transition-all" aria-label="Slide {{ $idx + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
@endif
