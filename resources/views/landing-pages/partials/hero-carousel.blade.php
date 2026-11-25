<!-- Main Hero Carousel Slider (Authentic Cakap Upskill Banners) -->
<section class="relative bg-[#F5F8FC] py-6 sm:py-8 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Carousel Container -->
        <div class="relative rounded-xl overflow-hidden shadow-xs border border-slate-200 bg-white aspect-[2/1] sm:aspect-[21/9] max-h-[460px]" id="cakapHeroCarousel">
            
            <!-- Slide 1 -->
            <div class="cakap-slide absolute inset-0 transition-opacity duration-500 opacity-100">
                <img src="{{ asset('cakap_files/1789383338230_894012253.png') }}" onerror="this.onerror=null;this.src='{{ asset('cakap_files/_1795405504399_1756706515.png') }}'" alt="Program Pelatihan Keterampilan Kerja Industri" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/85 via-slate-950/45 to-transparent flex items-center p-6 sm:p-12 lg:p-16">
                    <div class="max-w-xl text-white space-y-4">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded bg-[#10A9BC]/30 border border-[#10A9BC]/50 text-[#54D6D0] text-xs font-mono font-medium uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-[#54D6D0]"></span>
                            SERTIFIKASI KERJA RESMI 2026
                        </span>
                        <h1 class="font-heading font-extrabold text-3xl sm:text-5xl lg:text-6xl uppercase tracking-tight leading-tight">
                            KURSUS ONLINE &amp; KELAS KETERAMPILAN KERJA
                        </h1>
                        <p class="text-xs sm:text-base text-slate-200 line-clamp-3 leading-relaxed">
                            Akselerasi keahlian praktisi manufaktur, manajemen HR, otomasi industri, dan keselamatan kerja (K3) bersama komite instruktur ahli kawasan MM2100.
                        </p>
                        <div class="flex items-center gap-3 pt-2">
                            <a href="#katalog-kursus" class="px-6 py-3 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold text-xs uppercase tracking-wider transition shadow-sm">
                                Jelajahi Kelas →
                            </a>
                            <a href="{{ route('profile.fkksm') }}" class="px-6 py-3 rounded-lg bg-white/10 hover:bg-white/20 text-white font-heading font-bold text-xs uppercase tracking-wider border border-white/20 transition">
                                Profile FKKSM
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="cakap-slide absolute inset-0 transition-opacity duration-500 opacity-0 pointer-events-none">
                <img src="{{ asset('cakap_files/1785919977474_177374032.png') }}" onerror="this.onerror=null;this.src='{{ asset('cakap_files/_1795405504399_1756706515.png') }}'" alt="In-Company Corporate Training" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-[#0C3553]/90 via-[#0C3553]/50 to-transparent flex items-center p-6 sm:p-12 lg:p-16">
                    <div class="max-w-xl text-white space-y-4">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded bg-[#10A9BC]/30 border border-[#10A9BC]/50 text-[#54D6D0] text-xs font-mono font-medium uppercase tracking-wider">
                            CORPORATE INDUSTRIAL UPSKILL
                        </span>
                        <h2 class="font-heading font-extrabold text-3xl sm:text-5xl lg:text-6xl uppercase tracking-tight leading-tight">
                            PELATIHAN KORPORASI SESUAI KEBUTUHAN PABRIK
                        </h2>
                        <p class="text-xs sm:text-base text-slate-200 line-clamp-3 leading-relaxed">
                            Kurikulum disesuaikan dengan kebutuhan in-house training perusahaan di kawasan industri MM2100 untuk meningkatkan efisiensi dan kepatuhan K3.
                        </p>
                        <div class="pt-2">
                            <a href="{{ route('hubungi') }}" class="px-6 py-3 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold text-xs uppercase tracking-wider transition shadow-sm inline-block">
                                Konsultasi Program Perusahaan →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="cakap-slide absolute inset-0 transition-opacity duration-500 opacity-0 pointer-events-none">
                <img src="{{ asset('cakap_files/1794890154449_1375558849.png') }}" onerror="this.onerror=null;this.src='{{ asset('cakap_files/_1795405504399_1756706515.png') }}'" alt="Program Vokasi & Magang Industri" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-950/50 to-transparent flex items-center p-6 sm:p-12 lg:p-16">
                    <div class="max-w-xl text-white space-y-4">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded bg-emerald-600/30 border border-emerald-500/50 text-emerald-300 text-xs font-mono font-medium uppercase tracking-wider">
                            PROGRAM VOKASI &amp; MAGANG KERJA
                        </span>
                        <h2 class="font-heading font-extrabold text-3xl sm:text-5xl lg:text-6xl uppercase tracking-tight leading-tight">
                            KONSORSIUM SMK &amp; LINK AND MATCH INDUSTRI
                        </h2>
                        <p class="text-xs sm:text-base text-slate-200 line-clamp-3 leading-relaxed">
                            Menghubungkan lulusan vokasi siap pakai dengan kebutuhan rekrutmen ratusan tenant manufaktur di Kawasan MM2100.
                        </p>
                        <div class="pt-2">
                            <a href="{{ route('members') }}" class="px-6 py-3 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold text-xs uppercase tracking-wider transition shadow-sm inline-block">
                                Lihat Direktori Tenant MM2100 →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carousel Controls -->
            <button type="button" onclick="changeCakapSlide(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-slate-900/60 hover:bg-slate-900 text-white flex items-center justify-center transition shadow-md z-20" aria-label="Slide sebelumnya">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button type="button" onclick="changeCakapSlide(1)" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-slate-900/60 hover:bg-slate-900 text-white flex items-center justify-center transition shadow-md z-20" aria-label="Slide berikutnya">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>

            <!-- Indicators -->
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 z-20" id="cakapCarouselDots">
                <button type="button" onclick="goToCakapSlide(0)" class="w-3 h-3 rounded-full bg-[#10A9BC] transition-all" aria-label="Slide 1"></button>
                <button type="button" onclick="goToCakapSlide(1)" class="w-3 h-3 rounded-full bg-white/60 hover:bg-white transition-all" aria-label="Slide 2"></button>
                <button type="button" onclick="goToCakapSlide(2)" class="w-3 h-3 rounded-full bg-white/60 hover:bg-white transition-all" aria-label="Slide 3"></button>
            </div>
        </div>
    </div>
</section>

