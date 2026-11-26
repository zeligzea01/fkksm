@php
    $catSec = $sections['course_categories'] ?? null;
    $cc = $catSec['content'] ?? [];
    $items = $cc['items'] ?? [];
    $themeClasses = [
        'sky' => 'bg-cyan-50 text-[#10A9BC] border-cyan-100',
        'indigo' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
        'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        'amber' => 'bg-amber-50 text-amber-700 border-amber-100',
        'teal' => 'bg-teal-50 text-teal-700 border-teal-100',
        'purple' => 'bg-purple-50 text-purple-700 border-purple-100',
        'rose' => 'bg-rose-50 text-rose-700 border-rose-100',
    ];
    $iconPaths = [
        'language' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />',
        'tech' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />',
        'education' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5" />',
        'business' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />',
        'agriculture' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
        'career' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />',
        'media' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />',
    ];
@endphp

@if($catSec['is_active'] ?? true)
<!-- Section: Kategori Kursus (Dynamic via Admin CMS) -->
<section class="fkksm-container py-8 border-t border-slate-200">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-slate-900 tracking-tight">
                {{ $cc['heading'] ?? 'Kategori Kursus' }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                {{ $cc['subtitle'] ?? 'Jelajahi beragam program spesialisasi keahlian sesuai bidang minatmu.' }}
            </p>
        </div>
        <a href="{{ route('lms.courses.index') }}" class="hidden sm:inline-flex items-center gap-1 text-xs font-heading font-bold text-[#10A9BC] hover:underline uppercase tracking-wider">
            <span>Lihat Semua Kategori</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-4 sm:gap-5">
        @foreach($items as $cat)
            @php
                $bgClass = $themeClasses[$cat['theme'] ?? 'sky'] ?? $themeClasses['sky'];
                $svgPath = $iconPaths[$cat['icon_key'] ?? 'language'] ?? $iconPaths['language'];
            @endphp
            <a href="{{ route('lms.courses.index', ['category' => $cat['category_param'] ?? $cat['name']]) }}" class="group bg-white rounded-xl border border-slate-200 p-4 text-center hover:border-[#10A9BC] hover:shadow-md transition-all flex flex-col items-center justify-center">
                <div class="w-14 h-14 rounded-full {{ $bgClass }} border flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $svgPath !!}
                    </svg>
                </div>
                <span class="font-heading font-bold text-xs sm:text-sm text-slate-800 group-hover:text-[#10A9BC] transition-colors leading-snug">
                    {{ $cat['name'] }}
                </span>
            </a>
        @endforeach
    </div>
</section>
@endif
