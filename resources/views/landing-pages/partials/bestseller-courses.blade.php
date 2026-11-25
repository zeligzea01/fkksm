<!-- Section: Kursus Terlaris with Skill Badges (Identical to Cakap Upskill) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 border-t border-slate-200" id="kursus-terlaris">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-slate-900 tracking-tight">
                Kursus Terlaris
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Program pelatihan vokasi dan keterampilan kerja dengan peminat terbanyak di FKKSM Upskill.
            </p>
        </div>
        <a href="{{ route('lms.courses.index') }}" class="hidden sm:inline-flex items-center gap-1 text-xs font-heading font-bold text-[#0069b1] hover:underline uppercase tracking-wider">
            <span>Lihat Semua Kursus</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    @php
        $bestsellerCourses = [
            [
                'title' => 'Belajar Menanam Sayur Dengan Sistem Hidroponik Untuk Petani Sayur',
                'category' => 'Pertanian',
                'rating' => '4.9',
                'reviews' => '(16.766)',
                'price' => 'Rp 500.000',
                'thumbnail' => 'cakap_files/_1795405504399_1756706515.png',
                'badges' => [
                    ['icon' => '1765179223375_166389665.png', 'name' => 'Organic Input Production - Basic'],
                    ['icon' => '1765180051349_1286725957.png', 'name' => 'Organic Pest & Weed Management - Basic'],
                ]
            ],
            [
                'title' => 'Menjadi Pengusaha UMKM di Bidang Kuliner',
                'category' => 'Bisnis & Kuliner',
                'rating' => '5.0',
                'reviews' => '(14.815)',
                'price' => 'Rp 500.000',
                'thumbnail' => 'cakap_files/_1795405761782_736403967.png',
                'badges' => [
                    ['icon' => '1765180942518_2059284853.png', 'name' => 'Culinary Business Strategy & Ideation - Dasar'],
                    ['icon' => '1765180983228_806302746.png', 'name' => 'Culinary Product & Financial Planning - Dasar'],
                    ['icon' => '1765181023900_1548929450.png', 'name' => 'Culinary Marketing & Business Development - Dasar'],
                ]
            ],
            [
                'title' => 'Belajar Menyambut Tamu Hotel untuk Resepsionis Hotel',
                'category' => 'Perhotelan',
                'rating' => '4.9',
                'reviews' => '(8.215)',
                'price' => 'Rp 500.000',
                'thumbnail' => 'cakap_files/_1795406710457_1730707105.png',
                'badges' => [
                    ['icon' => '1748333962862_2131417080.png', 'name' => 'Front Office - Basic'],
                ]
            ],
            [
                'title' => 'Cepat Cakap Bahasa Jepang khusus Pemandu Wisata',
                'category' => 'Bahasa & Wisata',
                'rating' => '5.0',
                'reviews' => '(3)',
                'price' => 'Rp 700.000',
                'thumbnail' => 'cakap_files/1785919977474_177374032.png',
                'badges' => [
                    ['icon' => '1765270249133_1527721171.png', 'name' => 'Japanese Tour Facilitation & Coordination - Dasar'],
                    ['icon' => '1765270277280_1142546686.png', 'name' => 'Japanese Tour Leadership & Management - Dasar'],
                    ['icon' => '1765270300739_414434307.png', 'name' => 'Japanese Service Recovery & Complaint Handling - Dasar'],
                ]
            ],
            [
                'title' => 'Cepat Cakap Bahasa Jepang khusus Wawancara Kerja',
                'category' => 'Karier & Bahasa',
                'rating' => '5.0',
                'reviews' => '(4)',
                'price' => 'Rp 700.000',
                'thumbnail' => 'cakap_files/1789383338230_894012253.png',
                'badges' => [
                    ['icon' => '1763708650958_2074870672.png', 'name' => 'Interview Prep & Practice - Dasar'],
                    ['icon' => '1763708702867_1074007178.png', 'name' => 'Japanese Honorifics - Dasar'],
                ]
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
        @foreach($bestsellerCourses as $c)
            <article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md hover:border-[#0069b1] transition-all flex flex-col justify-between group">
                <div>
                    <!-- Thumbnail Container -->
                    <div class="relative aspect-[16/9] overflow-hidden bg-slate-900">
                        <img src="{{ asset($c['thumbnail']) }}" alt="{{ $c['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute top-2 left-2">
                            <span class="px-2 py-0.5 rounded bg-slate-900/80 backdrop-blur-xs text-white text-[9px] font-mono font-bold uppercase tracking-wider border border-white/20">
                                {{ $c['category'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Authentic Cakap Skill Badges Section Icon (Positioned overlapping the card thumbnail) -->
                    <div class="relative px-3.5 -mt-5 z-10 flex items-center gap-1.5 mb-1">
                        @foreach($c['badges'] as $b)
                            <div class="relative group/badge" title="{{ $b['name'] }}">
                                <button type="button" class="w-9 h-9 rounded-full bg-white p-0.5 border border-slate-200 shadow-sm hover:scale-110 transition-transform flex items-center justify-center">
                                    <img src="{{ asset('cakap_files/' . $b['icon']) }}" alt="{{ $b['name'] }}" class="w-full h-full object-contain rounded-full">
                                </button>
                                <!-- Tooltip on Hover -->
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 hidden group-hover/badge:block whitespace-nowrap bg-slate-900 text-white text-[10px] font-mono px-2 py-0.5 rounded shadow-lg pointer-events-none z-30">
                                    {{ $b['name'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Card Body -->
                    <div class="p-3.5 pt-2 space-y-2">
                        <h3 class="font-heading font-bold text-xs sm:text-sm text-slate-900 group-hover:text-[#0069b1] transition-colors line-clamp-2 leading-snug">
                            {{ $c['title'] }}
                        </h3>

                        <!-- Rating & Reviews -->
                        <div class="flex items-center gap-1 text-[11px] font-mono">
                            <div class="flex items-center text-amber-500">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="font-bold text-slate-800">{{ $c['rating'] }}</span>
                            <span class="text-slate-400 text-[10px]">{{ $c['reviews'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Price & Action -->
                <div class="p-3.5 pt-2 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between">
                    <span class="font-mono font-extrabold text-[#0069b1] text-sm">
                        {{ $c['price'] }}
                    </span>
                    <a href="{{ route('lms.courses.index') }}" class="px-2.5 py-1 rounded bg-slate-100 group-hover:bg-[#0069b1] text-slate-700 group-hover:text-white font-heading font-bold text-[10px] uppercase tracking-wider transition">
                        Beli
                    </a>
                </div>
            </article>
        @endforeach
    </div>
</section>

