<!-- Informasi & Edaran Terkini -->
<section class="py-16 bg-[#F8FAFC] border-b border-slate-200" id="news">
    <div class="fkksm-container">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
            <div>
                <span class="text-[10px] font-mono font-medium text-[#10A9BC] uppercase tracking-widest block mb-1">
                    BERITA &amp; EDARAN
                </span>
                <h2 class="font-heading font-bold text-3xl text-slate-900 uppercase tracking-tight">
                    Informasi &amp; Edaran Terkini
                </h2>
                <p class="text-xs text-slate-500 mt-1">Kabar resmi seputar kebijakan industri, penyesuaian regulasi ketenagakerjaan, dan program mitra.</p>
            </div>
            <a href="{{ route('berita') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#10A9BC] hover:underline uppercase tracking-wider font-heading">
                <span>Semua Informasi</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs hover:border-slate-300 transition flex flex-col justify-between group">
                <div>
                    <div class="aspect-[16/10] overflow-hidden bg-slate-900">
                        <img src="{{ asset('fkksm/assets/images/story-01.jpg') }}" alt="MoU Ekosistem" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                    </div>
                    <div class="p-5 space-y-2.5">
                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-medium bg-blue-50 text-blue-800 uppercase tracking-wider">
                            KOLABORASI INDUSTRI
                        </span>
                        <h3 class="font-heading font-bold text-xl text-slate-900 group-hover:text-[#10A9BC] transition-colors leading-snug">
                            FKKSM Resmi Tandatangani MoU Penguatan Ekosistem Vokasi
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Kolaborasi baru memperluas sinergi magang tersertifikasi dan kurikulum industri bagi SMK binaan di koridor industri MM2100.
                        </p>
                    </div>
                </div>
                <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-[#10A9BC]">
                    <span class="text-slate-500 font-mono text-[11px]">15 Nov 2026</span>
                    <a href="{{ route('berita') }}" class="hover:underline">Baca Selengkapnya →</a>
                </div>
            </article>

            <article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs hover:border-slate-300 transition flex flex-col justify-between group">
                <div>
                    <div class="aspect-[16/10] overflow-hidden bg-slate-900">
                        <img src="{{ asset('fkksm/assets/images/activity-04.jpg') }}" alt="Forum Dialog" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                    </div>
                    <div class="p-5 space-y-2.5">
                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-medium bg-purple-50 text-purple-800 uppercase tracking-wider">
                            KEPEMIMPINAN
                        </span>
                        <h3 class="font-heading font-bold text-xl text-slate-900 group-hover:text-[#10A9BC] transition-colors leading-snug">
                            Forum Dialog Pemimpin Industri Manufaktur MM2100
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Praktisi dan factory manager bertukar strategi membangun lingkungan kerja yang adaptif, produktif, dan tangguh terhadap disrupsi global.
                        </p>
                    </div>
                </div>
                <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-[#10A9BC]">
                    <span class="text-slate-500 font-mono text-[11px]">10 Nov 2026</span>
                    <a href="{{ route('berita') }}" class="hover:underline">Baca Selengkapnya →</a>
                </div>
            </article>

            <article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs hover:border-slate-300 transition flex flex-col justify-between group">
                <div>
                    <div class="aspect-[16/10] overflow-hidden bg-slate-900">
                        <img src="{{ asset('fkksm/assets/images/story-03.jpg') }}" alt="Kesiapan Industri" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                    </div>
                    <div class="p-5 space-y-2.5">
                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-medium bg-emerald-50 text-emerald-800 uppercase tracking-wider">
                            TRANSFORMASI DIGITAL
                        </span>
                        <h3 class="font-heading font-bold text-xl text-slate-900 group-hover:text-[#10A9BC] transition-colors leading-snug">
                            Laporan Kesiapan Industri Nasional Menuju Smart Factory
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Temuan komprehensif mengenai tingkat kesiapan otomatisasi PLC, sensor IoT, serta proyeksi kebutuhan kompetensi operator masa depan.
                        </p>
                    </div>
                </div>
                <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-[#10A9BC]">
                    <span class="text-slate-500 font-mono text-[11px]">05 Nov 2026</span>
                    <a href="{{ route('berita') }}" class="hover:underline">Baca Selengkapnya →</a>
                </div>
            </article>
        </div>
    </div>
</section>

