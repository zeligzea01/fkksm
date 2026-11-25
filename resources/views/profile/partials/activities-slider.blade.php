<!-- Rangkaian Kegiatan FKKSM 2026: Activity Band with 6 Interactive Thumbnails -->
<section class="py-16 bg-[#082236] text-white border-b border-[#176D91]/30" id="activities">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            
            <!-- Left Controls & Title -->
            <div class="lg:col-span-5 space-y-5">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('fkksm/logo-transparent.png') }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.jpeg') }}'" alt="FKKSM Logo" class="h-9 w-auto object-contain">
                    <div>
                        <span class="block text-white font-heading font-bold text-sm tracking-wider uppercase">FKKSM COMMUNITY</span>
                        <span class="block text-[10px] text-[#54D6D0] font-mono tracking-widest uppercase">MM2100 HEADQUARTERS</span>
                    </div>
                </div>

                <div>
                    <span class="text-[11px] font-mono font-medium text-[#54D6D0] uppercase tracking-widest block mb-1">
                        DOKUMENTASI &amp; AGENDA
                    </span>
                    <h2 class="font-heading font-bold text-3xl sm:text-4xl lg:text-5xl uppercase tracking-tight leading-tight">
                        RANGKAIAN KEGIATAN<br>
                        <span class="text-[#54D6D0]">FKKSM 2026</span>
                    </h2>
                </div>

                <p class="text-xs text-slate-300 leading-relaxed">
                    Kilasan aktivitas sosialisasi kepatuhan ketenagakerjaan, uji kompetensi K3, workshop otomotif manufaktur, dan rapat koordinasi tahunan anggota kawasan MM2100.
                </p>

                <!-- 6 Interactive Thumbnails -->
                <div class="grid grid-cols-3 sm:grid-cols-6 lg:grid-cols-3 gap-2.5 pt-2" id="activityThumbnails">
                    @for ($i = 1; $i <= 6; $i++)
                        @php
                            $imgNum = str_pad($i, 2, '0', STR_PAD_LEFT);
                        @endphp
                        <button type="button" onclick="selectActivity({{ $i - 1 }})" class="act-thumb relative rounded-md overflow-hidden aspect-[4/3] border {{ $i === 1 ? 'border-[#54D6D0] ring-1 ring-[#54D6D0]' : 'border-white/20 hover:border-white/60' }} transition-all" aria-label="Tampilkan kegiatan {{ $i }}">
                            <img src="{{ asset('fkksm/assets/images/activity-' . $imgNum . '.jpg') }}" alt="Kegiatan {{ $i }}" class="w-full h-full object-cover">
                        </button>
                    @endfor
                </div>
            </div>

            <!-- Right Main Slideshow Viewport -->
            <div class="lg:col-span-7">
                <div class="relative rounded-xl overflow-hidden aspect-[16/10] bg-slate-950 border border-[#176D91]/40">
                    <div id="activityMainDisplay" class="w-full h-full">
                        <img id="activityMainImg" src="{{ asset('fkksm/assets/images/activity-01.jpg') }}" alt="Dokumentasi Kegiatan FKKSM" class="w-full h-full object-cover transition-opacity duration-200">
                    </div>

                    <!-- Overlay Gradient & Caption -->
                    <div class="absolute inset-x-0 bottom-0 p-5 bg-gradient-to-t from-slate-950/90 via-slate-950/50 to-transparent flex items-end justify-between">
                        <div>
                            <span id="activityCaptionTag" class="px-2 py-0.5 rounded bg-[#10A9BC] text-white text-[10px] font-mono font-medium uppercase tracking-wider inline-block mb-1">
                                AGENDA RESMI 2026
                            </span>
                            <h4 id="activityCaptionTitle" class="font-heading font-bold text-lg text-white">
                                Forum Koordinasi Komite K3 &amp; Hubungan Industrial
                            </h4>
                        </div>
                        <span id="activityCounter" class="px-2.5 py-0.5 rounded bg-slate-900/80 text-white text-xs font-mono tracking-widest border border-white/20">
                            1 / 6
                        </span>
                    </div>

                    <!-- Prev/Next Controls with SVG icons -->
                    <button type="button" onclick="stepActivity(-1)" class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-slate-900/80 hover:bg-slate-900 text-white flex items-center justify-center transition shadow-md z-10" aria-label="Sebelumnya">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button type="button" onclick="stepActivity(1)" class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-slate-900/80 hover:bg-slate-900 text-white flex items-center justify-center transition shadow-md z-10" aria-label="Selanjutnya">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

        </div>
    </div>
</section>

