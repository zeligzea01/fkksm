@php
    $wyl = $sections['what_you_learn'] ?? null;
    $wc = $wyl['content'] ?? [];
    $webinars = $wc['webinars'] ?? [];
@endphp

@if($wyl['is_active'] ?? true)
<!-- Section: Kursus Apa yang Ingin Kamu Pelajari? (Dynamic via Admin CMS) -->
<section class="fkksm-container py-8 lg:py-12" id="what-you-want-to-learn">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
        <div>
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-slate-900 tracking-tight">
                {{ $wc['heading'] ?? 'Kursus Apa yang Ingin Kamu Pelajari?' }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                {{ $wc['subtitle'] ?? 'Pilih format belajar yang paling sesuai: webinar langsung bersama mentor atau belajar mandiri online.' }}
            </p>
        </div>

        <div class="inline-flex rounded-lg border border-slate-200 bg-slate-100/80 p-1 text-xs font-heading font-bold uppercase tracking-wider" role="tablist">
            <button type="button" id="tabWebinarBtn" onclick="switchLearnTab('webinar')" class="px-4 py-2 rounded-md transition shadow-xs bg-white text-[#10A9BC] border border-slate-200">
                Live Webinar
            </button>
            <button type="button" id="tabMandiriBtn" onclick="switchLearnTab('mandiri')" class="px-4 py-2 rounded-md transition text-slate-600 hover:text-slate-900">
                Kursus Mandiri
            </button>
        </div>
    </div>

    <!-- Tab Content 1: Live Webinars -->
    <div id="tabWebinarContent" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($webinars as $w)
            <article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
                <div>
                    <div class="relative aspect-[16/9] overflow-hidden bg-slate-900">
                        <img src="{{ \App\Models\LandingSection::resolveImageUrl($w['image'] ?? null, 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=800&q=80') }}" onerror="this.onerror=null;this.src='{{ asset('fkksm/assets/images/activity-02.jpg') }}'" alt="{{ $w['title'] ?? '' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @if(!empty($w['schedule']))
                            <div class="absolute top-2.5 left-2.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md {{ !empty($w['is_live_soon']) ? 'bg-rose-600' : 'bg-[#0C3553]' }} text-white text-[11px] font-mono font-bold tracking-wider uppercase shadow-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white {{ !empty($w['is_live_soon']) ? 'animate-pulse' : '' }}"></span>
                                    {{ $w['schedule'] }}
                                </span>
                            </div>
                        @endif
                        @if(!empty($w['discount_badge']))
                            <div class="absolute top-2.5 right-2.5">
                                <span class="px-2 py-0.5 rounded bg-emerald-600 text-white text-[10px] font-mono font-bold uppercase tracking-wider shadow-xs">
                                    {{ $w['discount_badge'] }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="p-4 sm:p-5 space-y-3">
                        <span class="inline-block px-2 py-0.5 rounded bg-cyan-50 text-[#10A9BC] text-[10px] font-mono font-bold uppercase tracking-wider border border-cyan-100">
                            {{ $w['category'] ?? 'Live Webinar' }}
                        </span>

                        <h3 class="font-heading font-bold text-base sm:text-lg text-[#132B45] group-hover:text-[#10A9BC] transition-colors line-clamp-2 leading-snug">
                            {{ $w['title'] ?? '' }}
                        </h3>

                        <div class="flex items-center gap-2 pt-2 border-t border-slate-100 text-xs text-slate-600">
                            <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-[10px]">
                                {{ $w['tutor_initials'] ?? 'FK' }}
                            </div>
                            <span class="font-medium">{{ $w['tutor'] ?? 'Instruktur Ahli FKKSM' }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-5 pt-3 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        @if(!empty($w['original_price']))
                            <span class="text-[10px] text-slate-400 font-mono line-through block">{{ $w['original_price'] }}</span>
                        @endif
                        <span class="font-heading font-extrabold text-lg text-emerald-600 leading-none">{{ $w['price_label'] ?? 'GRATIS' }}</span>
                    </div>
                    <a href="{{ route('lms.courses.index') }}" class="px-4 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold text-xs uppercase tracking-wider transition shadow-xs">
                        {{ $w['cta_label'] ?? 'Ikut Gratis' }}
                    </a>
                </div>
            </article>
        @endforeach
    </div>

    <!-- Tab Content 2: Kursus Mandiri -->
    <div id="tabMandiriContent" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse ($courses->take(4) as $course)
            <x-course-card :course="$course" />
        @empty
            <div class="col-span-full text-center py-8 text-slate-500 text-sm">
                Belum ada kursus mandiri yang tersedia saat ini.
            </div>
        @endforelse
    </div>
</section>

<script>
    function switchLearnTab(tab) {
        const wBtn = document.getElementById('tabWebinarBtn');
        const mBtn = document.getElementById('tabMandiriBtn');
        const wContent = document.getElementById('tabWebinarContent');
        const mContent = document.getElementById('tabMandiriContent');

        if (tab === 'webinar') {
            wBtn.className = 'px-4 py-2 rounded-md transition shadow-xs bg-white text-[#10A9BC] border border-slate-200';
            mBtn.className = 'px-4 py-2 rounded-md transition text-slate-600 hover:text-slate-900';
            wContent.classList.remove('hidden');
            mContent.classList.add('hidden');
        } else {
            mBtn.className = 'px-4 py-2 rounded-md transition shadow-xs bg-white text-[#10A9BC] border border-slate-200';
            wBtn.className = 'px-4 py-2 rounded-md transition text-slate-600 hover:text-slate-900';
            mContent.classList.remove('hidden');
            wContent.classList.add('hidden');
        }
    }
</script>
@endif
