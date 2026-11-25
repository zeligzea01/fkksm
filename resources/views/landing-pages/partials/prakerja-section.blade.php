<!-- Section: Pelatihan Prakerja with Co-Branded Header & Skill Badges -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 border-t border-slate-200" id="prakerja">
    <!-- Co-Branded Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <img src="{{ asset('cakap_files/cakap-logo.svg') }}" alt="Cakap" class="h-6 w-auto">
                <span class="text-slate-300 font-bold text-lg">&times;</span>
                <img src="{{ asset('cakap_files/new-icon-prakerja.svg') }}" alt="Prakerja" class="h-6 w-auto">
            </div>
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-slate-900 tracking-tight">
                Ikut pelatihan Prakerja terbaik di Cakap
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Gunakan saldo Kartu Prakerja Anda untuk mengambil kelas keterampilan kerja bersertifikat resmi.
            </p>
        </div>
    </div>

    <!-- Prakerja Redeem Callout Banner -->
    <div class="bg-gradient-to-r from-[#0069b1] to-[#008be3] rounded-xl p-5 sm:p-6 mb-8 text-white shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4 text-center sm:text-left">
            <div class="w-12 h-12 rounded-lg bg-white/10 flex items-center justify-center p-2 shrink-0">
                <img src="{{ asset('cakap_files/prakerja-white.svg') }}" alt="Prakerja White" class="w-8 h-8 object-contain">
            </div>
            <div>
                <h3 class="font-heading font-bold text-base sm:text-lg">
                    Tukar kode belajar Prakerja di sini
                </h3>
                <p class="text-xs text-sky-100">
                    Masukkan kode kupon dari Tokopedia, Bukalapak, atau Pijar Mahir Anda.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 w-full sm:w-auto">
            <button type="button" onclick="openVoucherModal()" class="w-full sm:w-auto px-5 py-2.5 rounded-lg bg-white hover:bg-slate-100 text-[#0069b1] font-heading font-bold text-xs uppercase tracking-wider transition shadow-xs">
                Tukar Kode
            </button>
            <a href="{{ route('hubungi') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-lg bg-white/15 hover:bg-white/25 text-white font-heading font-bold text-xs uppercase tracking-wider border border-white/30 transition text-center">
                Cara Tukar
            </a>
        </div>
    </div>

    @php
        $prakerjaCourses = [
            [
                'title' => 'Belajar Merancang Produk IT untuk Calon Manajer Produk',
                'category' => 'Teknologi & Produk',
                'price' => 'Rp 2.500.000',
                'thumbnail' => 'cakap_files/_1795405504399_1756706515.png',
                'badges' => [
                    ['icon' => '1765793972955_1772123099.png', 'name' => 'Product Discovery & Definition - Dasar'],
                    ['icon' => '1765793978027_1027588230.png', 'name' => 'Product Reliability & Support - Dasar'],
                    ['icon' => '1765794082625_1289476765.png', 'name' => 'Stakeholder Alignment & Execution - Dasar'],
                ]
            ],
            [
                'title' => 'Bahasa Inggris untuk Staf Administrasi Kantor',
                'category' => 'Bahasa & Administrasi',
                'price' => 'Rp 1.200.000',
                'rating' => '4.9 (339)',
                'thumbnail' => 'cakap_files/_1795405761782_736403967.png',
                'badges' => [
                    ['icon' => '1766373528567_723602149.png', 'name' => 'Basic Workplace Communication (English) - Dasar'],
                    ['icon' => '1766375618222_589836390.png', 'name' => 'Office Administrations Skills - Pemula'],
                ]
            ],
            [
                'title' => 'Memasarkan Produk dengan Copywriting bagi Copywriter',
                'category' => 'Pemasaran & Media',
                'price' => 'Rp 2.000.000',
                'thumbnail' => 'cakap_files/_1795406710457_1730707105.png',
                'badges' => [
                    ['icon' => '1765780573638_1844925480.png', 'name' => 'Creative Advertising Strategy - Dasar'],
                    ['icon' => '1765780617687_734637452.png', 'name' => 'Media & Client Management - Dasar'],
                ]
            ],
            [
                'title' => 'Melayani Pelanggan dengan Berbahasa Inggris bagi Customer Service',
                'category' => 'Layanan Pelanggan',
                'price' => 'Rp 1.000.000',
                'thumbnail' => 'cakap_files/1785919977474_177374032.png',
                'badges' => [
                    ['icon' => '1765780691003_1299763240.png', 'name' => 'Operational English & Ethics - Dasar'],
                    ['icon' => '1765769136090_613011060.png', 'name' => 'Customer Service & Complaint Handling - Pemula'],
                ]
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @foreach($prakerjaCourses as $pk)
            <article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md hover:border-[#0069b1] transition-all flex flex-col justify-between group">
                <div>
                    <!-- Thumbnail with Prakerja Partner Tag -->
                    <div class="relative aspect-[16/9] overflow-hidden bg-slate-900">
                        <img src="{{ asset($pk['thumbnail']) }}" alt="{{ $pk['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute top-2 left-2">
                            <span class="px-2 py-0.5 rounded bg-sky-600 text-white text-[9px] font-mono font-bold uppercase tracking-wider">
                                Prakerja
                            </span>
                        </div>
                    </div>

                    <!-- Authentic Cakap Skill Badges Section Icon -->
                    <div class="relative px-3.5 -mt-5 z-10 flex items-center gap-1.5 mb-1">
                        @foreach($pk['badges'] as $b)
                            <div class="relative group/badge" title="{{ $b['name'] }}">
                                <button type="button" class="w-9 h-9 rounded-full bg-white p-0.5 border border-slate-200 shadow-sm hover:scale-110 transition-transform flex items-center justify-center">
                                    <img src="{{ asset('cakap_files/' . $b['icon']) }}" alt="{{ $b['name'] }}" class="w-full h-full object-contain rounded-full">
                                </button>
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 hidden group-hover/badge:block whitespace-nowrap bg-slate-900 text-white text-[10px] font-mono px-2 py-0.5 rounded shadow-lg pointer-events-none z-30">
                                    {{ $b['name'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="p-3.5 pt-2 space-y-2">
                        <h3 class="font-heading font-bold text-xs sm:text-sm text-slate-900 group-hover:text-[#0069b1] transition-colors line-clamp-2 leading-snug">
                            {{ $pk['title'] }}
                        </h3>

                        @if(isset($pk['rating']))
                            <div class="flex items-center gap-1 text-[11px] font-mono text-amber-500">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="font-bold text-slate-800">{{ $pk['rating'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="p-3.5 pt-2 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between">
                    <span class="font-mono font-extrabold text-[#0069b1] text-sm">
                        {{ $pk['price'] }}
                    </span>
                    <a href="{{ route('lms.courses.index') }}" class="px-2.5 py-1 rounded bg-slate-100 group-hover:bg-[#0069b1] text-slate-700 group-hover:text-white font-heading font-bold text-[10px] uppercase tracking-wider transition">
                        Beli
                    </a>
                </div>
            </article>
        @endforeach
    </div>
</section>

