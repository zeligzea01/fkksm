<!-- FKKSM Modern Clean Navigation Header -->
<header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-[0_1px_3px_rgba(0,0,0,0.04)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 sm:h-20 flex items-center justify-between gap-6">
        
        <!-- Brand Identity -->
        <a href="{{ route('home') }}" class="flex items-center gap-3.5 group shrink-0">
            <img src="{{ asset('fkksm/logo-transparent.png') }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.jpeg') }}'" alt="FKKSM Logo" class="h-10 sm:h-11 w-auto object-contain transition-transform group-hover:scale-102">
            <div class="flex flex-col">
                <span class="font-heading font-extrabold text-xl sm:text-2xl tracking-tight text-[#0A2540] leading-none transition-colors group-hover:text-[#10A9BC]">
                    FKKSM <span class="text-[#10A9BC]">MM2100</span>
                </span>
                <span class="text-[9px] font-mono font-bold text-slate-500 uppercase tracking-[0.18em] mt-1">
                    INDUSTRIAL HR &amp; SKILL HUB
                </span>
            </div>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="hidden lg:flex items-center gap-6 xl:gap-8 text-xs font-bold uppercase tracking-wider text-slate-700">
            <a href="{{ route('home') }}" class="py-2 border-b-2 transition-colors {{ request()->routeIs('home') ? 'border-[#10A9BC] text-[#10A9BC]' : 'border-transparent hover:text-[#10A9BC]' }}">
                Beranda
            </a>
            
            <a href="{{ route('lms.courses.index') }}" class="py-2 border-b-2 transition-colors {{ request()->routeIs('lms.courses.*') ? 'border-[#10A9BC] text-[#10A9BC]' : 'border-transparent hover:text-[#10A9BC]' }}">
                Program Pelatihan
            </a>
            
            <a href="{{ route('profile.fkksm') }}" class="py-2 border-b-2 transition-colors {{ request()->routeIs('profile.fkksm') ? 'border-[#10A9BC] text-[#10A9BC]' : 'border-transparent hover:text-[#10A9BC]' }}">
                Profile
            </a>
            
            <a href="{{ route('members') }}" class="py-2 border-b-2 transition-colors {{ request()->routeIs('members') ? 'border-[#10A9BC] text-[#10A9BC]' : 'border-transparent hover:text-[#10A9BC]' }}">
                Direktori Industri
            </a>
            
            <a href="{{ route('berita') }}" class="py-2 border-b-2 transition-colors {{ request()->routeIs('berita') ? 'border-[#10A9BC] text-[#10A9BC]' : 'border-transparent hover:text-[#10A9BC]' }}">
                Berita &amp; Forum
            </a>
            
            <a href="{{ route('hubungi') }}" class="py-2 border-b-2 transition-colors {{ request()->routeIs('hubungi') ? 'border-[#10A9BC] text-[#10A9BC]' : 'border-transparent hover:text-[#10A9BC]' }}">
                Hubungi Kami
            </a>
        </nav>

        <!-- Right User Actions -->
        <div class="flex items-center gap-3 shrink-0">
            @auth
                <!-- Authenticated User Chip -->
                <div class="hidden sm:flex items-center gap-3 pr-2 border-r border-slate-200">
                    <div class="w-8 h-8 rounded-full bg-[#10A9BC]/15 text-[#10A9BC] font-heading font-bold text-xs flex items-center justify-center border border-[#10A9BC]/30">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="text-left hidden md:block">
                        <span class="block text-xs font-bold text-[#0A2540] leading-none truncate max-w-[120px]">
                            {{ auth()->user()->name }}
                        </span>
                        <span class="block text-[9px] font-mono text-slate-500 uppercase tracking-wider mt-0.5">
                            {{ auth()->user()->role_name }}
                        </span>
                    </div>
                </div>

                <a href="{{ route('lms.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#0C3553] hover:bg-[#132B45] text-white text-xs font-heading font-bold uppercase tracking-wider shadow-xs transition">
                    <svg class="w-3.5 h-3.5 text-[#54D6D0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard LMS</span>
                </a>
            @else
                <!-- Guest Actions -->
                <a href="{{ route('login') }}" class="px-3.5 py-2 text-xs font-bold uppercase tracking-wider text-slate-700 hover:text-[#10A9BC] transition">
                    Masuk
                </a>
                
                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white text-xs font-heading font-bold uppercase tracking-wider shadow-xs transition">
                    Daftar Member
                </a>
            @endauth

            <!-- Mobile Menu Toggle Button -->
            <button type="button" onclick="toggleMobileNav()" class="lg:hidden p-2 rounded-lg text-slate-700 hover:bg-slate-100 transition" aria-label="Buka menu navigasi" id="mobileNavToggle">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="hamburgerIcon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="closeIcon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div id="mobileNavDrawer" class="hidden lg:hidden border-t border-slate-200 bg-white shadow-lg px-4 py-5 space-y-1 text-xs font-bold uppercase tracking-wider text-slate-700 transition-all">
        <a href="{{ route('home') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-lg {{ request()->routeIs('home') ? 'bg-[#EDF5FA] text-[#10A9BC]' : 'hover:bg-slate-50' }}">
            <span>Beranda</span>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        
        <a href="{{ route('lms.courses.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-lg {{ request()->routeIs('lms.courses.*') ? 'bg-[#EDF5FA] text-[#10A9BC]' : 'hover:bg-slate-50' }}">
            <span>Program Pelatihan</span>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        
        <a href="{{ route('profile.fkksm') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-lg {{ request()->routeIs('profile.fkksm') ? 'bg-[#EDF5FA] text-[#10A9BC]' : 'hover:bg-slate-50' }}">
            <span>Profile</span>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        
        <a href="{{ route('members') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-lg {{ request()->routeIs('members') ? 'bg-[#EDF5FA] text-[#10A9BC]' : 'hover:bg-slate-50' }}">
            <span>Direktori Industri</span>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        
        <a href="{{ route('berita') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-lg {{ request()->routeIs('berita') ? 'bg-[#EDF5FA] text-[#10A9BC]' : 'hover:bg-slate-50' }}">
            <span>Berita &amp; Forum</span>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        
        <a href="{{ route('hubungi') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-lg {{ request()->routeIs('hubungi') ? 'bg-[#EDF5FA] text-[#10A9BC]' : 'hover:bg-slate-50' }}">
            <span>Hubungi Kami</span>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        <div class="pt-3 mt-3 border-t border-slate-100 flex flex-col gap-2">
            <a href="{{ route('certificates.verify') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg text-[#10A9BC] font-semibold bg-cyan-50/60 hover:bg-cyan-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>Verifikasi Sertifikat Resmi</span>
            </a>

            @guest
                <div class="grid grid-cols-2 gap-2 pt-2">
                    <a href="{{ route('login') }}" class="w-full text-center py-2.5 rounded-lg border border-slate-200 text-slate-700 font-bold hover:bg-slate-50">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="w-full text-center py-2.5 rounded-lg bg-[#10A9BC] text-white font-bold hover:bg-[#0E97A8]">
                        Daftar
                    </a>
                </div>
            @endguest
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
