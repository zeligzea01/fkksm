<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F5F8FC]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('title', 'LMS FKKSM | Ruang Belajar & Manajemen Kompetensi')</title>
    <meta name="description" content="Portal pembelajaran daring, sertifikasi kompetensi, dan manajemen ekosistem pelatihan industri MM2100.">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0C3553">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('fkksm/logo-transparent.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('fkksm/logo-transparent.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700;800&family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex overflow-hidden font-sans text-[#132B45] bg-[#F5F8FC] antialiased selection:bg-[#10A9BC] selection:text-white">

    <!-- Left Sidebar (Unified FKKSM MM2100 Industrial Theme) -->
    <aside id="lmsSidebar" class="fixed inset-y-0 left-0 z-50 md:static w-64 2xl:w-72 bg-white border-r border-[#DCE6EE] flex flex-col shrink-0 select-none transition-transform duration-200 -translate-x-full md:translate-x-0">
        <!-- Brand Header matching Landing Page Navbar Lockup -->
        <div class="px-5 pt-4 pb-4 border-b border-[#DCE6EE]/80">
            <div class="flex items-center justify-between gap-2">
                <a href="{{ route('lms.dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-[#F5F8FC] border border-[#DCE6EE] flex items-center justify-center p-1.5 shadow-2xs group-hover:border-[#10A9BC]/50 transition-colors shrink-0">
                        <img src="{{ asset('fkksm/logo-transparent.png') }}" alt="Logo FKKSM" class="w-full h-full object-contain">
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1.5">
                            <span class="font-heading font-extrabold text-xl tracking-tight text-[#0A2540] leading-none">
                                FKKSM
                            </span>
                            <span class="px-1.5 py-0.5 rounded bg-[#E7F6F8] text-[#0E97A8] font-heading font-extrabold text-[11px] tracking-wide leading-none">
                                MM2100
                            </span>
                        </div>
                        <span class="text-[10px] font-medium text-[#61758A] tracking-wide mt-1">
                            Industrial HR &amp; LMS Hub
                        </span>
                    </div>
                </a>
                <button type="button" onclick="document.getElementById('lmsSidebar').classList.add('-translate-x-full')" class="md:hidden p-1.5 rounded-lg text-slate-400 hover:text-[#132B45] hover:bg-[#F5F8FC]" aria-label="Tutup Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5 text-[13px]">
            <div>
                <p class="px-3 mb-2 text-[10px] font-mono font-bold uppercase tracking-wider text-[#61758A]">MENU UTAMA</p>
                <div class="space-y-1">
                    <a href="{{ route('lms.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium lms-transition {{ request()->routeIs('lms.dashboard') ? 'bg-[#0C3553] text-white font-semibold shadow-xs' : 'text-[#61758A] hover:text-[#0C3553] hover:bg-[#EDF5FA]' }}">
                        <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('lms.dashboard') ? 'text-[#54D6D0]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('lms.courses.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium lms-transition {{ request()->routeIs('lms.courses.*') || request()->routeIs('lms.classroom.*') ? 'bg-[#0C3553] text-white font-semibold shadow-xs' : 'text-[#61758A] hover:text-[#0C3553] hover:bg-[#EDF5FA]' }}">
                        <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('lms.courses.*') || request()->routeIs('lms.classroom.*') ? 'text-[#54D6D0]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span>Modul</span>
                    </a>

                    <a href="{{ route('lms.assessments.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium lms-transition {{ request()->routeIs('lms.assessments.*') ? 'bg-[#0C3553] text-white font-semibold shadow-xs' : 'text-[#61758A] hover:text-[#0C3553] hover:bg-[#EDF5FA]' }}">
                        <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('lms.assessments.*') ? 'text-[#54D6D0]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Kuis</span>
                    </a>

                    <a href="{{ route('lms.certificates.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium lms-transition {{ request()->routeIs('lms.certificates.*') ? 'bg-[#0C3553] text-white font-semibold shadow-xs' : 'text-[#61758A] hover:text-[#0C3553] hover:bg-[#EDF5FA]' }}">
                        <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('lms.certificates.*') ? 'text-[#54D6D0]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Sertifikat</span>
                    </a>

                    <a href="{{ route('lms.vouchers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium lms-transition {{ request()->routeIs('lms.vouchers.*') ? 'bg-[#0C3553] text-white font-semibold shadow-xs' : 'text-[#61758A] hover:text-[#0C3553] hover:bg-[#EDF5FA]' }}">
                        <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('lms.vouchers.*') ? 'text-[#54D6D0]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        <span>Voucher</span>
                    </a>

                    <a href="{{ route('lms.subscriptions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium lms-transition {{ request()->routeIs('lms.subscriptions.*') ? 'bg-[#0C3553] text-white font-semibold shadow-xs' : 'text-[#61758A] hover:text-[#0C3553] hover:bg-[#EDF5FA]' }}">
                        <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('lms.subscriptions.*') ? 'text-[#54D6D0]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Subscription</span>
                    </a>
                </div>
            </div>

            @if ($authUser->isInstructor())
                <div>
                    <p class="px-3 mb-2 text-[10px] font-mono font-bold uppercase tracking-wider text-[#61758A]">KURIKULUM PENGAJAR</p>
                    <div class="space-y-1">
                        <a href="{{ route('lms.materials.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium lms-transition {{ request()->routeIs('lms.materials.*') ? 'bg-[#0C3553] text-white font-semibold shadow-xs' : 'text-[#61758A] hover:text-[#0C3553] hover:bg-[#EDF5FA]' }}">
                            <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('lms.materials.*') ? 'text-[#54D6D0]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>Materi &amp; Silabus</span>
                        </a>
                    </div>
                </div>
            @endif

            @if ($authUser->isAdmin())
                <div>
                    <p class="px-3 mb-2 text-[10px] font-mono font-bold uppercase tracking-wider text-[#0E97A8]">ADMIN &amp; KONTEN WEB</p>
                    <div class="space-y-1">
                        <a href="{{ route('lms.landing-cms.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium lms-transition {{ request()->routeIs('lms.landing-cms.*') ? 'bg-[#0C3553] text-white font-semibold shadow-xs' : 'text-[#61758A] hover:text-[#0C3553] hover:bg-[#E7F6F8]/80' }}">
                            <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('lms.landing-cms.*') ? 'text-[#54D6D0]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                            <span>Kelola Landing Page</span>
                        </a>
                        <a href="{{ route('lms.leads.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium lms-transition {{ request()->routeIs('lms.leads.*') ? 'bg-[#0C3553] text-white font-semibold shadow-xs' : 'text-[#61758A] hover:text-[#0C3553] hover:bg-[#EDF5FA]' }}">
                            <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('lms.leads.*') ? 'text-[#54D6D0]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Leads Masuk</span>
                        </a>
                        <a href="{{ route('lms.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium lms-transition {{ request()->routeIs('lms.users.*') ? 'bg-[#0C3553] text-white font-semibold shadow-xs' : 'text-[#61758A] hover:text-[#0C3553] hover:bg-[#EDF5FA]' }}">
                            <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('lms.users.*') ? 'text-[#54D6D0]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>Pengguna Sistem</span>
                        </a>
                        <a href="{{ route('lms.audit.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium lms-transition {{ request()->routeIs('lms.audit.*') ? 'bg-[#0C3553] text-white font-semibold shadow-xs' : 'text-[#61758A] hover:text-[#0C3553] hover:bg-[#EDF5FA]' }}">
                            <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('lms.audit.*') ? 'text-[#54D6D0]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Log Audit</span>
                        </a>
                        <a href="{{ route('lms.settings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium lms-transition {{ request()->routeIs('lms.settings.*') ? 'bg-[#0C3553] text-white font-semibold shadow-xs' : 'text-[#61758A] hover:text-[#0C3553] hover:bg-[#EDF5FA]' }}">
                            <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('lms.settings.*') ? 'text-[#54D6D0]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                            <span>Konfigurasi Portal</span>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Sidebar Help Box (Unified Cyan Industrial Surface) -->
            <div class="pt-2">
                <div class="p-4 rounded-xl bg-[#E7F6F8] border border-[#C7E8EE] space-y-2.5">
                    <div class="w-8 h-8 rounded-lg bg-white border border-[#C7E8EE] flex items-center justify-center text-[#10A9BC] shadow-2xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-xs text-[#0C3553]">Butuh bantuan belajar?</p>
                        <p class="text-[11px] text-[#61758A] leading-relaxed mt-0.5">
                            Panduan penggunaan platform pembelajaran.
                        </p>
                    </div>
                    <button type="button" onclick="document.getElementById('guideModal').classList.remove('hidden')" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0E97A8] hover:text-[#0C3553] hover:underline cursor-pointer">
                        <span>Buka panduan</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Sidebar Bottom Settings / Logout -->
        <div class="p-3 border-t border-[#DCE6EE]/80 space-y-1 text-[13px]">
            <a href="{{ $authUser->isAdmin() ? route('lms.settings.index') : route('lms.account.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium lms-transition {{ request()->routeIs('lms.account.*') ? 'bg-[#EDF5FA] text-[#0C3553] font-semibold' : 'text-[#61758A] hover:text-[#0C3553] hover:bg-[#EDF5FA]' }}">
                <svg class="w-[18px] h-[18px] text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Pengaturan</span>
            </a>
            <form action="{{ route('logout') }}" method="POST" data-confirm="Anda yakin ingin keluar dari sesi LMS FKKSM?" data-confirm-title="Konfirmasi Keluar" data-confirm-btn="Ya, Keluar">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-xl font-medium text-rose-600 hover:bg-rose-50 lms-transition text-left cursor-pointer">
                    <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar</span>
                </button>
            </form>
            <p class="px-3 pt-2 text-[10px] font-mono text-[#61758A]">
                &copy; {{ date('Y') }} FKKSM MM2100
            </p>
        </div>
    </aside>

    <!-- Main Workspace Column -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top Header Bar matching Landing Page Header Style -->
        <header class="h-16 bg-white/95 backdrop-blur-md border-b border-[#DCE6EE] flex items-center justify-between px-4 sm:px-6 lg:px-8 2xl:px-10 shrink-0 gap-4 shadow-[0_1px_2px_rgba(12,53,83,0.03)]">
            <div class="flex items-center gap-3 min-w-0">
                <button type="button" onclick="document.getElementById('lmsSidebar').classList.toggle('-translate-x-full')" class="md:hidden p-1.5 rounded-lg text-[#61758A] hover:bg-[#F5F8FC]" aria-label="Menu Sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="min-w-0">
                    <p class="text-[11px] font-mono font-semibold text-[#0E97A8] uppercase tracking-wider leading-none truncate">
                        @yield('header_eyebrow', 'Selamat datang kembali')
                    </p>
                    <h1 class="font-heading text-lg sm:text-xl font-extrabold uppercase text-[#0C3553] tracking-tight truncate mt-0.5">
                        @yield('header_title', 'Ruang Belajar')
                    </h1>
                </div>
            </div>

            <!-- Right Search, Notifications & User Context -->
            <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                <!-- Search Pill -->
                <form action="{{ route('lms.courses.index') }}" method="GET" class="hidden lg:flex items-center relative w-64 xl:w-80 2xl:w-96">
                    <svg class="w-4 h-4 text-[#61758A] absolute left-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari materi atau kursus..." class="w-full pl-9 pr-3 py-1.5 rounded-xl bg-[#F5F8FC] border border-[#DCE6EE] text-xs text-[#132B45] placeholder-[#61758A] focus:outline-none focus:border-[#10A9BC] focus:bg-white lms-transition">
                </form>

                <!-- Portal Shortcut -->
                <a href="{{ route('home') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-[#DCE6EE] bg-[#F5F8FC] hover:border-[#10A9BC]/50 hover:bg-[#E7F6F8] text-xs font-semibold text-[#0C3553] lms-transition whitespace-nowrap">
                    <span>Portal Utama</span>
                    <svg class="w-3.5 h-3.5 text-[#10A9BC]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>

                <!-- Notification Bell Dropdown -->
                <div class="relative">
                    <button type="button" onclick="document.getElementById('notifPopover').classList.toggle('hidden')" class="relative p-2 rounded-xl text-[#61758A] hover:text-[#0C3553] hover:bg-[#EDF5FA] lms-transition cursor-pointer" aria-label="Notifikasi">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @if ($unreadNotifCount > 0)
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
                        @endif
                    </button>

                    <div id="notifPopover" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl border border-[#DCE6EE] shadow-lg z-50 overflow-hidden">
                        <div class="px-4 py-3 border-b border-[#DCE6EE]/70 flex items-center justify-between bg-[#F5F8FC]">
                            <span class="font-semibold text-xs text-[#0C3553]">Notifikasi Aktivitas</span>
                            @if ($unreadNotifCount > 0)
                                <form action="{{ route('lms.notifications.read-all') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[11px] font-semibold text-[#10A9BC] hover:underline cursor-pointer">Tandai dibaca</button>
                                </form>
                            @endif
                        </div>
                        <div class="divide-y divide-slate-100 max-h-64 overflow-y-auto">
                            @forelse ($userNotifications as $notif)
                                <button type="button" data-notif-title="{{ $notif->title }}" data-notif-body="{{ $notif->body }}" data-notif-time="{{ $notif->created_at->diffForHumans() }}" onclick="window.fkksmShowNotificationDetail(this.dataset.notifTitle, this.dataset.notifBody, this.dataset.notifTime)" class="w-full text-left p-3.5 text-xs hover:bg-[#F5F8FC] transition {{ is_null($notif->read_at) ? 'bg-[#E7F6F8]/50' : '' }}">
                                    <div class="flex items-start justify-between gap-2">
                                        <p class="font-semibold text-[#132B45]">{{ $notif->title }}</p>
                                        @if (is_null($notif->read_at))
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#10A9BC] mt-1.5 shrink-0"></span>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-[#61758A] mt-0.5 leading-relaxed line-clamp-2">{{ $notif->body }}</p>
                                    <span class="text-[10px] font-mono text-slate-400 mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                                </button>
                            @empty
                                <p class="p-6 text-center text-xs text-slate-400">Belum ada notifikasi baru.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- User Profile Pill matching Landing Page Navy & Cyan -->
                <a href="{{ route('lms.account.index') }}" class="flex items-center gap-2.5 pl-3 border-l border-[#DCE6EE]">
                    <div class="w-9 h-9 rounded-full {{ $authUser->isAdmin() ? 'bg-[#0C3553]' : 'bg-[#10A9BC]' }} text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-2xs">
                        {{ strtoupper(substr($authUser->name, 0, 2)) }}
                    </div>
                    <div class="hidden sm:block text-left leading-tight">
                        <span class="block text-xs font-bold text-[#132B45] max-w-[140px] truncate">{{ $authUser->name }}</span>
                        <span class="block text-[11px] text-[#61758A]">{{ $authUser->role_name }}</span>
                    </div>
                </a>
            </div>
        </header>

        <!-- Main Workspace Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 2xl:p-10 bg-[#F5F8FC]">
            <div class="fkksm-lms-container">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Learning Platform Guide Modal ("Butuh bantuan belajar? -> Buka panduan") -->
    <div id="guideModal" class="fixed inset-0 z-50 hidden bg-[#0A2540]/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-xl border border-[#DCE6EE] max-w-lg w-full p-6 shadow-xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[#DCE6EE]">
                <h3 class="font-heading font-extrabold text-lg uppercase tracking-tight text-[#0C3553]">Panduan Platform Pembelajaran FKKSM</h3>
                <button type="button" onclick="document.getElementById('guideModal').classList.add('hidden')" class="p-1 text-slate-400 hover:text-[#132B45] rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="space-y-3 text-xs text-[#61758A] leading-relaxed">
                <div class="p-3 rounded-lg bg-[#E7F6F8] border border-[#C7E8EE]">
                    <strong class="text-[#0C3553] block mb-0.5">1. Modul &amp; Ruang Belajar</strong>
                    Pilih kelas di menu <strong>Modul</strong>, tinjau silabus dan persyaratan program, lalu klik tombol akses untuk mempelajari materi hingga progres mencapai 100%.
                </div>
                <div class="p-3 rounded-lg bg-[#F5F8FC] border border-[#DCE6EE]">
                    <strong class="text-[#0C3553] block mb-0.5">2. Kuis &amp; Evaluasi</strong>
                    Selesaikan ujian/tugas pada menu <strong>Kuis</strong> setelah membaca materi. Nilai akan dinilai langsung oleh instruktur kawasan.
                </div>
                <div class="p-3 rounded-lg bg-[#F5F8FC] border border-[#DCE6EE]">
                    <strong class="text-[#0C3553] block mb-0.5">3. Sertifikat, Voucher &amp; Subscription</strong>
                    Tukarkan kode sponsor perusahaan di menu <strong>Voucher</strong>, kelola paket akses di <strong>Subscription</strong>, dan unduh sertifikat PDF resmi di menu <strong>Sertifikat</strong>.
                </div>
            </div>
            <div class="pt-2 flex justify-end">
                <button type="button" onclick="document.getElementById('guideModal').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs lms-transition cursor-pointer">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 Session Flash Payload -->
    <script>
        window.__FKKSM_FLASH__ = {!! \Illuminate\Support\Js::from([
            'success' => session('success'),
            'error'   => session('error'),
            'warning' => session('warning'),
            'info'    => session('info'),
            'errors'  => $errors->any() ? $errors->all() : [],
        ]) !!};
    </script>

    @stack('scripts')
</body>
</html>
