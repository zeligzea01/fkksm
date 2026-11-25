<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F5F8FC]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('title', 'LMS FKKSM MM2100 | Learning Management & Industrial Audit Portal')</title>
    <meta name="description" content="Portal pembelajaran daring dan manajemen sertifikasi resmi industri kawasan MM2100 Cikarang Barat.">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0C3553">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="LMS FKKSM">

    <!-- Favicon & Meta Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Google Fonts Matching fkksm.blade.php -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700;800&family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex overflow-hidden font-sans text-[#132B45] bg-[#F5F8FC] antialiased selection:bg-[#10A9BC] selection:text-white">

    <!-- Sidebar Navigation -->
    <aside id="lmsSidebar" class="fixed inset-y-0 left-0 z-50 md:static w-64 bg-[#0C3553] border-r border-[#176D91]/40 flex flex-col shrink-0 select-none shadow-xl transition-transform duration-300 -translate-x-full md:translate-x-0">
        <!-- Brand Header -->
        <div class="h-16 flex items-center justify-between px-5 border-b border-[#176D91]/30 bg-[#082236]/80">
            <a href="{{ route('lms.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('fkksm/logo-transparent.png') }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.jpeg') }}'" alt="Logo" class="h-9 w-auto object-contain">
                <div>
                    <span class="block text-white font-heading font-extrabold text-lg tracking-wide leading-none">
                        FKKSM <span class="text-[#54D6D0]">LMS</span>
                    </span>
                    <span class="block text-[9px] text-[#A5B4C2] font-mono tracking-widest uppercase">
                        MM2100 INDUSTRIAL
                    </span>
                </div>
            </a>
            <!-- Mobile Close Button -->
            <button type="button" onclick="document.getElementById('lmsSidebar').classList.add('-translate-x-full')" class="md:hidden p-1 text-slate-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto px-4 py-5 space-y-6 text-xs">
            <div>
                <p class="px-2 mb-2 text-[10px] font-heading font-bold uppercase tracking-widest text-[#54D6D0]">UTAMA</p>
                <div class="space-y-1">
                    <a href="{{ route('lms.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('lms.dashboard') ? 'bg-[#10A9BC] text-white font-bold shadow-sm' : 'text-[#D7E9F2] hover:text-white hover:bg-[#132B45]' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('lms.dashboard') ? 'text-white' : 'text-[#54D6D0]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard Kontrol
                    </a>
                    <a href="{{ route('lms.courses.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('lms.courses.*') ? 'bg-[#10A9BC] text-white font-bold shadow-sm' : 'text-[#D7E9F2] hover:text-white hover:bg-[#132B45]' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('lms.courses.*') ? 'text-white' : 'text-[#54D6D0]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Katalog Kursus
                    </a>
                </div>
            </div>

            <div>
                <p class="px-2 mb-2 text-[10px] font-heading font-bold uppercase tracking-widest text-[#54D6D0]">PEMBELAJARAN</p>
                <div class="space-y-1">
                    @if (auth()->user()->isInstructor())
                        <a href="{{ route('lms.materials.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('lms.materials.*') ? 'bg-[#10A9BC] text-white font-bold shadow-sm' : 'text-[#D7E9F2] hover:text-white hover:bg-[#132B45]' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Materi &amp; Silabus
                        </a>
                    @endif
                    <a href="{{ route('lms.assessments.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('lms.assessments.*') ? 'bg-[#10A9BC] text-white font-bold shadow-sm' : 'text-[#D7E9F2] hover:text-white hover:bg-[#132B45]' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        Ujian &amp; Evaluasi K3
                    </a>
                    <a href="{{ route('lms.certificates.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('lms.certificates.*') ? 'bg-[#10A9BC] text-white font-bold shadow-sm' : 'text-[#D7E9F2] hover:text-white hover:bg-[#132B45]' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Sertifikat Kompetensi
                    </a>
                    <a href="{{ route('lms.vouchers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('lms.vouchers.*') ? 'bg-[#10A9BC] text-white font-bold shadow-sm' : 'text-[#D7E9F2] hover:text-white hover:bg-[#132B45]' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        Voucher Subsidi
                    </a>
                </div>
            </div>

            @if (auth()->user()->isAdmin())
                <div>
                    <p class="px-2 mb-2 text-[10px] font-heading font-bold uppercase tracking-widest text-amber-400">PENGAWASAN &amp; AUDIT</p>
                    <div class="space-y-1">
                        <a href="{{ route('lms.audit.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('lms.audit.*') ? 'bg-amber-600 text-white font-bold shadow-sm' : 'text-[#D7E9F2] hover:text-white hover:bg-[#132B45]' }}">
                            <svg class="w-4 h-4 shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Log Audit Sistem
                        </a>
                        <a href="{{ route('lms.leads.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('lms.leads.*') ? 'bg-[#10A9BC] text-white font-bold shadow-sm' : 'text-[#D7E9F2] hover:text-white hover:bg-[#132B45]' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Permintaan Pelatihan
                        </a>
                        <a href="{{ route('lms.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('lms.users.*') ? 'bg-[#10A9BC] text-white font-bold shadow-sm' : 'text-[#D7E9F2] hover:text-white hover:bg-[#132B45]' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            Manajemen Pengguna
                        </a>
                        <a href="{{ route('lms.settings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('lms.settings.*') ? 'bg-[#10A9BC] text-white font-bold shadow-sm' : 'text-[#D7E9F2] hover:text-white hover:bg-[#132B45]' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Konfigurasi Sistem
                        </a>
                    </div>
                </div>
            @endif
        </nav>

        <!-- Current User Card in Industrial Frame -->
        <div class="p-4 border-t border-[#176D91]/30 bg-[#082236]/90">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-lg bg-[#10A9BC]/20 text-[#54D6D0] border border-[#10A9BC]/40 flex items-center justify-center font-heading font-bold text-base">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-mono font-bold bg-[#132B45] text-[#54D6D0] border border-[#176D91]/40">
                        {{ auth()->user()->role_name }}
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-between text-[11px] text-[#A5B4C2] pt-2 border-t border-[#176D91]/20">
                <a href="{{ route('home') }}" class="hover:text-white transition flex items-center gap-1">
                    ← Portal Web
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="hover:text-rose-400 text-slate-400 transition font-bold">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top App Header (Clean & Refined) -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 shrink-0 shadow-xs">
            <div class="flex items-center gap-3">
                <button type="button" onclick="document.getElementById('lmsSidebar').classList.toggle('-translate-x-full')" class="md:hidden p-1.5 rounded-lg text-slate-600 hover:bg-slate-100 transition" aria-label="Menu navigasi sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <span class="inline-block w-2 h-2 rounded-full bg-[#10A9BC]"></span>
                <span class="font-heading font-bold text-base sm:text-lg uppercase text-[#0C3553] tracking-wide truncate">
                    @yield('header_title', 'KONTROL UTAMA LMS MM2100')
                </span>
            </div>

            <div class="flex items-center gap-3 sm:gap-4 text-xs font-medium">
                <span class="hidden md:inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-slate-50 border border-slate-200 text-slate-600 font-mono text-[11px]">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>SISTEM AKTIF</span>
                </span>
                <a href="{{ route('home') }}" class="text-slate-600 hover:text-[#10A9BC] font-semibold text-xs transition hidden sm:inline-flex items-center gap-1">
                    <span>Beranda Publik</span>
                    <span class="text-slate-400">↗</span>
                </a>
                <span class="text-slate-300 hidden sm:inline">|</span>
                <a href="{{ route('profile.fkksm') }}" class="text-[#10A9BC] hover:underline font-bold text-xs flex items-center gap-1">
                    <span>Profile FKKSM</span>
                    <span>↗</span>
                </a>
            </div>
        </header>

        <!-- Main Workspace Flow -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-8 bg-[#F5F8FC]">
            @if (session('success'))
                <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs flex items-start gap-3 shadow-xs">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <div class="flex-1 font-medium">{{ session('success') }}</div>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-900 text-xs flex items-start gap-3 shadow-xs">
                    <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <div class="flex-1 font-medium">{{ session('error') }}</div>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-900 text-xs flex items-start gap-3 shadow-xs">
                    <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="flex-1">
                        <p class="font-bold mb-1 uppercase tracking-wider text-rose-900">Periksa Kembali Data Anda:</p>
                        <ul class="list-disc pl-5 space-y-0.5 text-rose-800">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
