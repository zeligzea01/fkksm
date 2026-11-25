<!-- Hero Section: Quiet, Grounded, Industrial Precision with Upcoming Summit 2026 -->
<section class="relative bg-[#0C3553] text-white py-16 lg:py-24 border-b border-[#176D91]/30 overflow-hidden" id="home">
    <div class="absolute inset-0 bg-industrial-grid-dark opacity-10 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Hero Copy -->
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-[#132B45] border border-[#176D91]/50 text-[#54D6D0] text-xs font-mono font-medium tracking-wider uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#10A9BC]"></span>
                    TRANSFORMASI &amp; KOLABORASI INDUSTRI INDONESIA
                </div>

                <h1 class="font-heading font-bold text-4xl sm:text-5xl lg:text-6xl tracking-tight leading-[1.08] uppercase text-white">
                    Semua Informasi &amp; Sinergi,<br>
                    dalam <span class="text-[#54D6D0]">Satu Wadah Terpadu.</span>
                </h1>

                <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-2xl font-normal">
                    Wadah representasi industri, praktisi SDM, pakar K3, institusi vokasi, dan pembuat kebijakan untuk memacu kemajuan teknologi serta daya saing ketenagakerjaan kawasan industri MM2100 bersama FKKSM.
                </p>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <a href="#program" class="px-6 py-3 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold text-xs uppercase tracking-wider transition flex items-center gap-2">
                        <span>Jelajahi Program FKKSM</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="{{ route('members') }}" class="px-6 py-3 rounded-lg bg-[#132B45] hover:bg-[#1A3859] text-white font-heading font-bold text-xs uppercase tracking-wider border border-[#176D91]/40 transition">
                        Direktori Anggota Industri
                    </a>
                    <a href="{{ route('home') }}" class="px-5 py-3 rounded-lg bg-[#082236] hover:bg-[#0F304B] text-[#54D6D0] font-heading font-bold text-xs uppercase tracking-wider border border-[#176D91]/50 transition flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#54D6D0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span>Portal Upskill LMS</span>
                    </a>
                </div>

                <div class="pt-6 flex flex-wrap items-center gap-6 text-xs text-slate-400 font-medium border-t border-[#176D91]/30">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#54D6D0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Diakui Kementerian Perindustrian RI
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#54D6D0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Data Kelola Berstandar BNSP &amp; K3
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#54D6D0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        1.250+ Anggota Korporasi MM2100
                    </span>
                </div>
            </div>

            <!-- Right Hero Card: Upcoming Summit 2026 -->
            <div class="lg:col-span-5">
                <div class="bg-white text-slate-900 rounded-xl p-6 shadow-xl border border-slate-200">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-rose-600"></span>
                            <span class="font-heading font-bold text-xs tracking-wider uppercase text-slate-900">
                                UPCOMING SUMMIT 2026
                            </span>
                        </div>
                        <span class="px-2.5 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-mono font-medium uppercase tracking-wider border border-slate-200">
                            Kuota Terbatas
                        </span>
                    </div>

                    <!-- Photo Slider Viewport -->
                    <div class="relative rounded-lg overflow-hidden aspect-[16/10] bg-slate-950 mb-4" id="heroSummitSlider">
                        <div class="hero-summit-slide transition-opacity duration-500 absolute inset-0 opacity-100">
                            <img src="{{ asset('fkksm/assets/images/activity-01.jpg') }}" alt="FKKSM Summit Event" class="w-full h-full object-cover">
                        </div>
                        <div class="hero-summit-slide transition-opacity duration-500 absolute inset-0 opacity-0 pointer-events-none">
                            <img src="{{ asset('fkksm/assets/images/activity-02.jpg') }}" alt="Seminar Industri" class="w-full h-full object-cover">
                        </div>
                        <div class="hero-summit-slide transition-opacity duration-500 absolute inset-0 opacity-0 pointer-events-none">
                            <img src="{{ asset('fkksm/assets/images/activity-03.jpg') }}" alt="Konferensi Nasional" class="w-full h-full object-cover">
                        </div>

                        <!-- Prev/Next buttons with SVG icons -->
                        <button type="button" onclick="changeHeroSlide(-1)" class="absolute left-2.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-slate-900/80 hover:bg-slate-900 text-white flex items-center justify-center transition shadow-md z-10" aria-label="Slide sebelumnya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button type="button" onclick="changeHeroSlide(1)" class="absolute right-2.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-slate-900/80 hover:bg-slate-900 text-white flex items-center justify-center transition shadow-md z-10" aria-label="Slide berikutnya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>

                        <!-- Indicator Dots -->
                        <div class="absolute bottom-2.5 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-10">
                            <button type="button" onclick="setHeroSlide(0)" class="hero-dot w-6 h-1 rounded bg-[#10A9BC] transition-all"></button>
                            <button type="button" onclick="setHeroSlide(1)" class="hero-dot w-2 h-1 rounded bg-white/60 hover:bg-white transition-all"></button>
                            <button type="button" onclick="setHeroSlide(2)" class="hero-dot w-2 h-1 rounded bg-white/60 hover:bg-white transition-all"></button>
                        </div>
                    </div>

                    <!-- Event Info -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-1.5 text-xs font-semibold text-[#10A9BC]">
                            <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Jakarta International Expo (JIExpo) Kemayoran</span>
                        </div>

                        <h3 class="font-heading font-bold text-xl text-slate-900 leading-snug">
                            FKKSM National Conference &amp; Industrial Summit 2026
                        </h3>

                        <p class="text-xs text-slate-600 leading-relaxed">
                            Pertemuan akbar tahunan lebih dari 1.200 delegasi lintas manufaktur, teknologi otomotif, K3, dan kebijakan ketenagakerjaan nasional.
                        </p>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-4">
                            <div>
                                <span class="block text-[10px] text-slate-400 font-mono uppercase font-semibold">Jadwal Acara</span>
                                <strong class="block text-xs font-bold text-slate-900">14 – 16 Oktober 2026</strong>
                            </div>
                            <a href="{{ route('hubungi') }}" class="px-4 py-2 rounded-lg bg-[#0C3553] hover:bg-[#10A9BC] text-white font-heading font-bold text-xs uppercase tracking-wider transition">
                                Daftar Delegasi →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

