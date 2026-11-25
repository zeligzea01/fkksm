<!-- Section: Kursus Apa yang Ingin Kamu Pelajari? (Tabs for Live Webinar & Kursus Mandiri) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12" id="what-you-want-to-learn">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
        <div>
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-slate-900 tracking-tight">
                Kursus Apa yang Ingin Kamu Pelajari?
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Pilih format belajar yang paling sesuai: webinar langsung bersama mentor atau belajar mandiri online.
            </p>
        </div>

        <!-- Clean Tabs Navigation -->
        <div class="inline-flex rounded-lg border border-slate-200 bg-slate-100/80 p-1 text-xs font-heading font-bold uppercase tracking-wider" role="tablist">
            <button type="button" id="tabWebinarBtn" onclick="switchLearnTab('webinar')" class="px-4 py-2 rounded-md transition shadow-xs bg-white text-[#0069b1] border border-slate-200">
                Live Webinar
            </button>
            <button type="button" id="tabMandiriBtn" onclick="switchLearnTab('mandiri')" class="px-4 py-2 rounded-md transition text-slate-600 hover:text-slate-900">
                Kursus Mandiri
            </button>
        </div>
    </div>

    <!-- Tab Content 1: Live Webinars -->
    <div id="tabWebinarContent" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Webinar 1 -->
        <article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
            <div>
                <div class="relative aspect-[16/9] overflow-hidden bg-slate-900">
                    <img src="{{ asset('cakap_files/_1795405504399_1756706515.png') }}" alt="TOEFL Structure Trap: Tricky Quantifiers" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <!-- Countdown Badge -->
                    <div class="absolute top-2.5 left-2.5">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-rose-600 text-white text-[11px] font-mono font-bold tracking-wider uppercase shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                            Besok | 15.30
                        </span>
                    </div>
                    <!-- 100% Discount -->
                    <div class="absolute top-2.5 right-2.5">
                        <span class="px-2 py-0.5 rounded bg-emerald-600 text-white text-[10px] font-mono font-bold uppercase tracking-wider shadow-xs">
                            DISKON 100%
                        </span>
                    </div>
                </div>

                <div class="p-4 sm:p-5 space-y-3">
                    <span class="inline-block px-2 py-0.5 rounded bg-sky-50 text-[#0069b1] text-[10px] font-mono font-bold uppercase tracking-wider border border-sky-100">
                        Bahasa &bull; Live Webinar
                    </span>

                    <h3 class="font-heading font-bold text-base sm:text-lg text-slate-900 group-hover:text-[#0069b1] transition-colors line-clamp-2 leading-snug">
                        TOEFL Structure Trap: Tricky Quantifiers
                    </h3>

                    <div class="flex items-center gap-2 pt-2 border-t border-slate-100 text-xs text-slate-600">
                        <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-[10px]">
                            TC
                        </div>
                        <span class="font-medium">Cakap Expert Academic Tutor</span>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-5 pt-3 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-slate-400 font-mono line-through block">Rp 259.000</span>
                    <span class="font-heading font-extrabold text-lg text-emerald-600 leading-none">GRATIS</span>
                </div>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-[#0069b1] hover:bg-[#005a99] text-white font-heading font-bold text-xs uppercase tracking-wider transition shadow-xs">
                    Ikut Gratis
                </a>
            </div>
        </article>

        <!-- Webinar 2 -->
        <article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
            <div>
                <div class="relative aspect-[16/9] overflow-hidden bg-slate-900">
                    <img src="{{ asset('cakap_files/_1795405761782_736403967.png') }}" alt="IELTS Writing Trap: Band 8 Connectors" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute top-2.5 left-2.5">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-[#0069b1] text-white text-[11px] font-mono font-bold tracking-wider uppercase shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                            26 November 2026 | 15.30
                        </span>
                    </div>
                    <div class="absolute top-2.5 right-2.5">
                        <span class="px-2 py-0.5 rounded bg-emerald-600 text-white text-[10px] font-mono font-bold uppercase tracking-wider shadow-xs">
                            DISKON 100%
                        </span>
                    </div>
                </div>

                <div class="p-4 sm:p-5 space-y-3">
                    <span class="inline-block px-2 py-0.5 rounded bg-sky-50 text-[#0069b1] text-[10px] font-mono font-bold uppercase tracking-wider border border-sky-100">
                        Bahasa &bull; Live Webinar
                    </span>

                    <h3 class="font-heading font-bold text-base sm:text-lg text-slate-900 group-hover:text-[#0069b1] transition-colors line-clamp-2 leading-snug">
                        IELTS Writing Trap: Band 8 Connectors
                    </h3>

                    <div class="flex items-center gap-2 pt-2 border-t border-slate-100 text-xs text-slate-600">
                        <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-[10px]">
                            IC
                        </div>
                        <span class="font-medium">International IELTS Certified Master</span>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-5 pt-3 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-slate-400 font-mono line-through block">Rp 259.000</span>
                    <span class="font-heading font-extrabold text-lg text-emerald-600 leading-none">GRATIS</span>
                </div>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-[#0069b1] hover:bg-[#005a99] text-white font-heading font-bold text-xs uppercase tracking-wider transition shadow-xs">
                    Ikut Gratis
                </a>
            </div>
        </article>

        <!-- Webinar 3 -->
        <article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
            <div>
                <div class="relative aspect-[16/9] overflow-hidden bg-slate-900">
                    <img src="{{ asset('cakap_files/_1795406710457_1730707105.png') }}" alt="Black Friday Econ-Speak: Relative Pronouns" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute top-2.5 left-2.5">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-[#0069b1] text-white text-[11px] font-mono font-bold tracking-wider uppercase shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                            27 November 2026 | 15.30
                        </span>
                    </div>
                    <div class="absolute top-2.5 right-2.5">
                        <span class="px-2 py-0.5 rounded bg-emerald-600 text-white text-[10px] font-mono font-bold uppercase tracking-wider shadow-xs">
                            DISKON 100%
                        </span>
                    </div>
                </div>

                <div class="p-4 sm:p-5 space-y-3">
                    <span class="inline-block px-2 py-0.5 rounded bg-sky-50 text-[#0069b1] text-[10px] font-mono font-bold uppercase tracking-wider border border-sky-100">
                        Bahasa &bull; Live Webinar
                    </span>

                    <h3 class="font-heading font-bold text-base sm:text-lg text-slate-900 group-hover:text-[#0069b1] transition-colors line-clamp-2 leading-snug">
                        Black Friday Econ-Speak: Relative Pronouns
                    </h3>

                    <div class="flex items-center gap-2 pt-2 border-t border-slate-100 text-xs text-slate-600">
                        <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-[10px]">
                            BF
                        </div>
                        <span class="font-medium">Business English Specialist</span>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-5 pt-3 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-slate-400 font-mono line-through block">Rp 259.000</span>
                    <span class="font-heading font-extrabold text-lg text-emerald-600 leading-none">GRATIS</span>
                </div>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-[#0069b1] hover:bg-[#005a99] text-white font-heading font-bold text-xs uppercase tracking-wider transition shadow-xs">
                    Ikut Gratis
                </a>
            </div>
        </article>
    </div>

    <!-- Tab Content 2: Kursus Mandiri (Hidden by default) -->
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
            wBtn.className = 'px-4 py-2 rounded-md transition shadow-xs bg-white text-[#0069b1] border border-slate-200';
            mBtn.className = 'px-4 py-2 rounded-md transition text-slate-600 hover:text-slate-900';
            wContent.classList.remove('hidden');
            mContent.classList.add('hidden');
        } else {
            mBtn.className = 'px-4 py-2 rounded-md transition shadow-xs bg-white text-[#0069b1] border border-slate-200';
            wBtn.className = 'px-4 py-2 rounded-md transition text-slate-600 hover:text-slate-900';
            mContent.classList.remove('hidden');
            wContent.classList.add('hidden');
        }
    }
</script>

