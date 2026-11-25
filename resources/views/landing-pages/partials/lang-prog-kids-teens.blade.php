<!-- Section: Program Bahasa untuk Anak & Remaja (Cakap Next & Cakap Kids Academy) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 border-t border-slate-200">
    <div class="mb-6">
        <h2 class="font-heading font-bold text-2xl sm:text-3xl text-slate-900 tracking-tight">
            Program Bahasa untuk Anak &amp; Remaja
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Program pembelajaran bahasa asing komprehensif yang dirancang interaktif untuk rentang usia 4–18 tahun.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Card 1: Cakap Next (Online) -->
        <article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
            <div>
                <div class="relative aspect-[16/9] overflow-hidden bg-slate-900">
                    <img src="{{ asset('cakap_files/lang-program-thumb-cnext.webp') }}" alt="Cakap Next" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 rounded-md bg-[#0069b1] text-white text-[11px] font-mono font-bold tracking-wider uppercase shadow-xs">
                            Online
                        </span>
                    </div>
                </div>

                <div class="p-5 space-y-2">
                    <h3 class="font-heading font-bold text-xl text-slate-900 group-hover:text-[#0069b1] transition-colors">
                        Cakap Next
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Kelas interaktif live tutoring online dengan kurikulum internasional standar CEFR untuk anak dan remaja. Fleksibel dari mana saja dengan native dan bilingual tutor berpengalaman.
                    </p>
                </div>
            </div>

            <div class="p-5 pt-3 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500 font-mono">Usia 4–18 Tahun</span>
                <a href="{{ route('lms.courses.index', ['category' => 'Bahasa']) }}" class="px-5 py-2 rounded-lg bg-[#0069b1] hover:bg-[#005a99] text-white font-heading font-bold text-xs uppercase tracking-wider transition shadow-xs flex items-center gap-1.5">
                    <span>Lihat Detail</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </article>

        <!-- Card 2: Cakap Kids Academy (Offline) -->
        <article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
            <div>
                <div class="relative aspect-[16/9] overflow-hidden bg-slate-900">
                    <img src="{{ asset('cakap_files/lang-program-thumb-cka.webp') }}" alt="Cakap Kids Academy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 rounded-md bg-amber-600 text-white text-[11px] font-mono font-bold tracking-wider uppercase shadow-xs">
                            Offline
                        </span>
                    </div>
                </div>

                <div class="p-5 space-y-2">
                    <h3 class="font-heading font-bold text-xl text-slate-900 group-hover:text-amber-600 transition-colors">
                        Cakap Kids Academy
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Pusat belajar tatap muka langsung (offline learning center) dengan metode multisensori terpadu, ruang kelas modern ramah anak, dan aktivitas bermain peran edukatif.
                    </p>
                </div>
            </div>

            <div class="p-5 pt-3 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500 font-mono">Learning Center</span>
                <a href="{{ route('lms.courses.index', ['category' => 'Bahasa']) }}" class="px-5 py-2 rounded-lg bg-slate-800 hover:bg-slate-900 text-white font-heading font-bold text-xs uppercase tracking-wider transition shadow-xs flex items-center gap-1.5">
                    <span>Lihat Detail</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </article>
    </div>
</section>

