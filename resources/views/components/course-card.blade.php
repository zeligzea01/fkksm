@props(['course'])

<article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md hover:border-[#10A9BC] transition-all flex flex-col justify-between group">
    <div>
        <!-- Course Thumbnail -->
        <div class="relative aspect-[16/9] overflow-hidden bg-slate-900">
            <img src="{{ $course->thumbnail_url }}" onerror="this.onerror=null;this.src='{{ asset('fkksm/assets/images/activity-02.jpg') }}'" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            <div class="absolute top-2.5 left-2.5 flex items-center gap-1.5">
                <span class="px-2 py-0.5 rounded bg-[#0C3553]/90 backdrop-blur-xs text-[#54D6D0] text-[10px] font-mono font-bold uppercase tracking-wider border border-[#176D91]/50">
                    {{ $course->category }}
                </span>
            </div>
            @if ($course->is_free)
                <div class="absolute top-2.5 right-2.5">
                    <span class="px-2 py-0.5 rounded bg-emerald-600 text-white text-[10px] font-mono font-bold uppercase tracking-wider shadow-xs">
                        SUBSIDI 100%
                    </span>
                </div>
            @endif
        </div>

        <!-- Skill Badges Overlap (if configured on Course) -->
        @if (is_array($course->skill_badges) && count($course->skill_badges) > 0)
            <div class="relative px-4 -mt-5 z-10 flex items-center gap-1.5 mb-1">
                @foreach ($course->skill_badges as $badge)
                    <div class="relative group/badge" title="{{ $badge['name'] ?? '' }}">
                        <button type="button" class="w-9 h-9 rounded-full bg-white border border-slate-200 shadow-sm hover:scale-110 transition-transform flex items-center justify-center text-[#10A9BC]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </button>
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 hidden group-hover/badge:block whitespace-nowrap bg-slate-900 text-white text-[10px] font-mono px-2 py-0.5 rounded shadow-lg pointer-events-none z-30">
                            {{ $badge['name'] ?? '' }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Course Meta Info -->
        <div class="p-4 sm:p-5 {{ is_array($course->skill_badges) && count($course->skill_badges) > 0 ? 'pt-2' : '' }} space-y-3">
            <div class="flex items-center justify-between text-[11px] text-slate-500 font-mono">
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-[#10A9BC]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ $course->duration_hours }} Jam Pelatihan</span>
                </span>
                <span class="flex items-center gap-1 text-slate-600 tabular-nums">
                    <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <strong>{{ number_format((float)($course->rating ?? 4.9), 1) }}</strong> ({{ $course->materials_count ?? $course->materials->count() }} Tahap)
                </span>
            </div>

            <h3 class="font-heading font-bold text-lg sm:text-xl text-[#132B45] group-hover:text-[#10A9BC] transition-colors line-clamp-2 leading-snug">
                {{ $course->title }}
            </h3>

            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                {{ $course->summary ?? $course->description }}
            </p>

            <div class="pt-2 flex items-center gap-2 border-t border-slate-100">
                <div class="w-6 h-6 rounded-full bg-[#10A9BC]/15 text-[#10A9BC] flex items-center justify-center font-heading font-bold text-[10px]">
                    {{ strtoupper(substr($course->instructor->name ?? 'F', 0, 1)) }}
                </div>
                <span class="text-xs text-slate-700 font-medium truncate">
                    {{ $course->instructor->name ?? 'Tim Instruktur FKKSM' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Course Footer Card -->
    <div class="p-4 sm:p-5 pt-3 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between">
        <div>
            <span class="block text-[10px] text-slate-400 font-mono uppercase">Biaya Pelatihan</span>
            <div class="font-heading font-extrabold text-lg text-[#132B45] leading-none">
                @if ($course->is_free)
                    <span class="text-emerald-700">GRATIS</span>
                    @if ($course->original_price && $course->original_price > 0)
                        <span class="text-[10px] text-slate-400 font-normal line-through ml-1">Rp {{ number_format((float)$course->original_price, 0, ',', '.') }}</span>
                    @endif
                @else
                    {{ $course->formatted_price }}
                @endif
            </div>
        </div>

        <a href="{{ route('lms.courses.show', $course) }}" class="px-4 py-2 rounded-lg bg-[#0C3553] group-hover:bg-[#10A9BC] text-white font-heading font-bold text-xs uppercase tracking-wider transition shadow-xs flex items-center gap-1.5">
            <span>Daftar Kelas</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>
</article>
