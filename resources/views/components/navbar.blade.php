<!-- FKKSM MM2100 Refined Modern Navigation Header -->
<header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/90 shadow-[0_1px_2px_rgba(15,23,42,0.03)]">
    <div class="fkksm-container h-20 flex items-center justify-between gap-4">

        <!-- Left: Brand Identity -->
        <a href="{{ route('home') }}" class="flex items-center gap-3 group shrink-0">
            <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-center p-1.5 shadow-2xs group-hover:border-[#10A9BC]/40 transition-colors">
                <img src="{{ asset('fkksm/logo-transparent.png') }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.jpeg') }}'" alt="FKKSM Logo" class="w-full h-full object-contain">
            </div>
            <div class="flex flex-col">
                <div class="flex items-center gap-1.5">
                    <span class="font-heading font-extrabold text-lg sm:text-xl tracking-tight text-[#0A2540] leading-none">
                        FKKSM
                    </span>
                    <span class="px-1.5 py-0.5 rounded bg-[#E7F6F8] text-[#0E97A8] font-heading font-extrabold text-xs tracking-wide leading-none">
                        MM2100
                    </span>
                </div>
                <span class="hidden sm:block text-[10px] font-medium text-slate-500 tracking-wide mt-1">
                    Industrial HR &amp; Skill Hub
                </span>
            </div>
        </a>

        <!-- Center: Clean Pill Navigation Links -->
        <nav aria-label="Navigasi Utama" class="hidden lg:flex items-center gap-1 bg-slate-100/75 p-1 rounded-xl border border-slate-200/70">
            <a href="{{ route('home') }}"
               class="px-3.5 py-1.5 rounded-lg text-[13px] font-semibold transition-all whitespace-nowrap {{ request()->routeIs('home') ? 'bg-white text-[#0C3553] shadow-2xs font-bold' : 'text-slate-600 hover:text-[#0C3553] hover:bg-white/60' }}">
                Beranda
            </a>

            <a href="{{ route('lms.courses.index') }}"
               class="px-3.5 py-1.5 rounded-lg text-[13px] font-semibold transition-all whitespace-nowrap {{ request()->routeIs('lms.courses.*') ? 'bg-white text-[#0C3553] shadow-2xs font-bold' : 'text-slate-600 hover:text-[#0C3553] hover:bg-white/60' }}">
                Program Pelatihan
            </a>

            <a href="{{ route('profile.fkksm') }}"
               class="px-3.5 py-1.5 rounded-lg text-[13px] font-semibold transition-all whitespace-nowrap {{ request()->routeIs('profile.fkksm') ? 'bg-white text-[#0C3553] shadow-2xs font-bold' : 'text-slate-600 hover:text-[#0C3553] hover:bg-white/60' }}">
                Profile
            </a>

            <a href="{{ route('members') }}"
               class="px-3.5 py-1.5 rounded-lg text-[13px] font-semibold transition-all whitespace-nowrap {{ request()->routeIs('members') ? 'bg-white text-[#0C3553] shadow-2xs font-bold' : 'text-slate-600 hover:text-[#0C3553] hover:bg-white/60' }}">
                Direktori Industri
            </a>

            <a href="{{ route('berita') }}"
               class="px-3.5 py-1.5 rounded-lg text-[13px] font-semibold transition-all whitespace-nowrap {{ request()->routeIs('berita') ? 'bg-white text-[#0C3553] shadow-2xs font-bold' : 'text-slate-600 hover:text-[#0C3553] hover:bg-white/60' }}">
                Berita &amp; Forum
            </a>

            <a href="{{ route('hubungi') }}"
               class="px-3.5 py-1.5 rounded-lg text-[13px] font-semibold transition-all whitespace-nowrap {{ request()->routeIs('hubungi') ? 'bg-white text-[#0C3553] shadow-2xs font-bold' : 'text-slate-600 hover:text-[#0C3553] hover:bg-white/60' }}">
                Hubungi Kami
            </a>
        </nav>

        <!-- Right: Quick Certificate Check & Auth Controls -->
        <div class="flex items-center gap-2.5 shrink-0 whitespace-nowrap">
            <a href="{{ route('certificates.verify') }}"
               class="hidden xl:inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-600 hover:text-[#10A9BC] hover:bg-slate-50 transition-colors"
               title="Verifikasi Keabsahan Sertifikat">
                <svg class="w-4 h-4 text-[#10A9BC]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Cek Sertifikat</span>
            </a>

            @auth
                <a href="{{ route('lms.dashboard') }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-[#0C3553] hover:bg-[#132B45] text-white text-xs font-bold transition shadow-2xs">
                    <span class="w-5 h-5 rounded-full bg-[#10A9BC] text-white text-[10px] font-extrabold flex items-center justify-center shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </span>
                    <span>Ruang Belajar</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block" data-confirm="Anda yakin ingin keluar dari sesi Portal FKKSM?" data-confirm-title="Konfirmasi Keluar" data-confirm-btn="Ya, Keluar">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center px-3 py-2 rounded-xl border border-slate-200 hover:border-rose-200 hover:bg-rose-50 text-slate-600 hover:text-rose-600 text-xs font-bold transition cursor-pointer"
                            title="Keluar dari akun">
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="hidden sm:inline-flex px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 hover:text-[#0C3553] hover:bg-slate-100 transition">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center px-3 sm:px-4 py-2 rounded-xl bg-[#10A9BC] hover:bg-[#0E97A8] text-white text-xs font-bold shadow-2xs transition">
                    <span class="sm:hidden">Daftar</span>
                    <span class="hidden sm:inline">Daftar Member</span>
                </a>
            @endauth

            <!-- Mobile Menu Toggle Button -->
            <button type="button" onclick="toggleMobileNav()" class="lg:hidden p-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100 transition" aria-label="Buka menu navigasi" id="mobileNavToggle">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="hamburgerIcon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="closeIcon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div id="mobileNavDrawer" class="hidden lg:hidden border-t border-slate-200 bg-white shadow-lg px-4 py-4 space-y-1 text-xs font-semibold text-slate-700">
        <a href="{{ route('home') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request()->routeIs('home') ? 'bg-[#EDF5FA] text-[#0C3553] font-bold' : 'hover:bg-slate-50' }}">
            <span>Beranda</span>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        <a href="{{ route('lms.courses.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request()->routeIs('lms.courses.*') ? 'bg-[#EDF5FA] text-[#0C3553] font-bold' : 'hover:bg-slate-50' }}">
            <span>Program Pelatihan</span>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        <a href="{{ route('profile.fkksm') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request()->routeIs('profile.fkksm') ? 'bg-[#EDF5FA] text-[#0C3553] font-bold' : 'hover:bg-slate-50' }}">
            <span>Profile</span>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        <a href="{{ route('members') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request()->routeIs('members') ? 'bg-[#EDF5FA] text-[#0C3553] font-bold' : 'hover:bg-slate-50' }}">
            <span>Direktori Industri</span>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        <a href="{{ route('berita') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request()->routeIs('berita') ? 'bg-[#EDF5FA] text-[#0C3553] font-bold' : 'hover:bg-slate-50' }}">
            <span>Berita &amp; Forum</span>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        <a href="{{ route('hubungi') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request()->routeIs('hubungi') ? 'bg-[#EDF5FA] text-[#0C3553] font-bold' : 'hover:bg-slate-50' }}">
            <span>Hubungi Kami</span>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        <div class="pt-3 mt-2 border-t border-slate-100 flex flex-col gap-2">
            <a href="{{ route('certificates.verify') }}" class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-[#0E97A8] font-bold bg-[#E7F6F8]/70 hover:bg-[#E7F6F8]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>Verifikasi Sertifikat Resmi</span>
            </a>

            @auth
                <div class="grid grid-cols-2 gap-2 pt-1">
                    <a href="{{ route('lms.dashboard') }}" class="w-full text-center py-2.5 rounded-xl bg-[#0C3553] text-white font-bold">
                        Ruang Belajar
                    </a>
                    <form method="POST" action="{{ route('logout') }}" data-confirm="Anda yakin ingin keluar dari sesi Portal FKKSM?" data-confirm-title="Konfirmasi Keluar" data-confirm-btn="Ya, Keluar">
                        @csrf
                        <button type="submit" class="w-full text-center py-2.5 rounded-xl border border-rose-200 text-rose-600 font-bold hover:bg-rose-50">
                            Keluar
                        </button>
                    </form>
                </div>
            @else
                <div class="grid grid-cols-2 gap-2 pt-1">
                    <a href="{{ route('login') }}" class="w-full text-center py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold hover:bg-slate-50">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="w-full text-center py-2.5 rounded-xl bg-[#10A9BC] text-white font-bold hover:bg-[#0E97A8]">
                        Daftar Member
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>

<script>
    function toggleMobileNav() {
        const drawer = document.getElementById('mobileNavDrawer');
        const hamburger = document.getElementById('hamburgerIcon');
        const close = document.getElementById('closeIcon');

        if (drawer.classList.contains('hidden')) {
            drawer.classList.remove('hidden');
            hamburger.classList.add('hidden');
            close.classList.remove('hidden');
        } else {
            drawer.classList.add('hidden');
            hamburger.classList.remove('hidden');
            close.classList.add('hidden');
        }
    }
</script>
