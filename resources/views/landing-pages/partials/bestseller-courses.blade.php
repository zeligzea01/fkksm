@php
    $bs = $sections['bestseller_courses'] ?? null;
    $bsc = $bs['content'] ?? [];
    $bestsellerCourses = $bsc['items'] ?? [];
@endphp

@if($bs['is_active'] ?? true)
<!-- Section: Kursus Terlaris with Skill Badges (Dynamic via Admin CMS) -->
<section class="fkksm-container py-8 border-t border-slate-200" id="kursus-terlaris">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-slate-900 tracking-tight">
                {{ $bsc['heading'] ?? 'Kursus Terlaris' }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                {{ $bsc['subtitle'] ?? 'Program pelatihan vokasi dan keterampilan kerja dengan peminat terbanyak di FKKSM Upskill.' }}
            </p>
        </div>
        <a href="{{ route('lms.courses.index') }}" class="hidden sm:inline-flex items-center gap-1 text-xs font-heading font-bold text-[#10A9BC] hover:underline uppercase tracking-wider">
            <span>Lihat Semua Kursus</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
        @foreach($bestsellerCourses as $c)
            <article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md hover:border-[#10A9BC] transition-all flex flex-col justify-between group">
                <div>
                    <div class="relative aspect-[16/9] overflow-hidden bg-slate-900">
                        <img src="{{ \App\Models\LandingSection::resolveImageUrl($c['thumbnail'] ?? null, 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=700&q=80') }}" onerror="this.onerror=null;this.src='{{ asset('fkksm/assets/images/activity-02.jpg') }}'" alt="{{ $c['title'] ?? '' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute top-2 left-2">
                            <span class="px-2 py-0.5 rounded bg-slate-900/80 backdrop-blur-xs text-white text-[9px] font-mono font-bold uppercase tracking-wider border border-white/20">
                                {{ $c['category'] ?? 'Keterampilan' }}
                            </span>
                        </div>
                    </div>

                    @if(!empty($c['badges']))
                        <div class="relative px-3.5 -mt-5 z-10 flex items-center gap-1.5 mb-1">
                            @foreach($c['badges'] as $b)
                                <div class="relative group/badge" title="{{ $b['name'] ?? '' }}">
                                    <button type="button" class="w-9 h-9 rounded-full bg-white border border-slate-200 shadow-sm hover:scale-110 transition-transform flex items-center justify-center text-[#10A9BC]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                        </svg>
                                    </button>
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 hidden group-hover/badge:block whitespace-nowrap bg-slate-900 text-white text-[10px] font-mono px-2 py-0.5 rounded shadow-lg pointer-events-none z-30">
                                        {{ $b['name'] ?? '' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="p-3.5 pt-2 space-y-2">
                        <h3 class="font-heading font-bold text-xs sm:text-sm text-slate-900 group-hover:text-[#10A9BC] transition-colors line-clamp-2 leading-snug">
                            {{ $c['title'] ?? '' }}
                        </h3>

                        <div class="flex items-center gap-1 text-[11px] font-mono tabular-nums">
                            <div class="flex items-center text-amber-500">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="font-bold text-slate-800">{{ $c['rating'] ?? '4.9' }}</span>
                            <span class="text-slate-400 text-[10px]">{{ $c['reviews'] ?? '' }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-3.5 pt-2 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between">
                    <span class="font-mono font-extrabold text-[#0C3553] text-sm tabular-nums">
                        {{ $c['price'] ?? 'Rp 500.000' }}
                    </span>
                    <a href="{{ route('lms.courses.index') }}" class="px-2.5 py-1 rounded bg-slate-100 group-hover:bg-[#10A9BC] text-slate-700 group-hover:text-white font-heading font-bold text-[10px] uppercase tracking-wider transition">
                        Daftar
                    </a>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif
