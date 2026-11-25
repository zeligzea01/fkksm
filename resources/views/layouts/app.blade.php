<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F5F8FC]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Primary Meta Tags -->
    <title>@yield('title', 'FKKSM MM2100 | Industrial HR Community & LMS Upskill')</title>
    <meta name="title" content="@yield('meta_title', 'FKKSM MM2100 | Industrial HR Community & LMS Upskill')">
    <meta name="description" content="@yield('meta_description', 'Platform resmi Human Resources & Pelatihan Vokasi Bersertifikasi Kawasan Industri MM2100 Cikarang Barat. K3, Hubungan Industrial, dan Transformasi Manufaktur.')">
    <meta name="keywords" content="FKKSM, MM2100, Kawasan Industri MM2100, Pelatihan K3, Hubungan Industrial, Bipartit, HR Community, Sertifikasi Profesi, Vokasi SMK, Cikarang Barat, Bekasi, Upskill">
    <meta name="author" content="FKKSM Kawasan Industri MM2100">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Indonesian">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Theme & Mobile App Meta -->
    <meta name="theme-color" content="#132B45">
    <meta name="msapplication-TileColor" content="#0C3553">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="FKKSM MM2100">

    <!-- Favicon & Meta Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Open Graph / Facebook / LinkedIn / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'FKKSM MM2100 | Industrial HR Community & LMS Upskill')">
    <meta property="og:description" content="@yield('og_description', 'Platform resmi Human Resources & Pelatihan Vokasi Bersertifikasi Kawasan Industri MM2100 Cikarang Barat.')">
    <meta property="og:image" content="{{ asset('images/logo.jpeg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="FKKSM MM2100">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card Meta -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('twitter_title', 'FKKSM MM2100 | Industrial HR Community & LMS Upskill')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Platform resmi Human Resources & Pelatihan Vokasi Bersertifikasi Kawasan Industri MM2100 Cikarang Barat.')">
    <meta name="twitter:image" content="{{ asset('images/logo.jpeg') }}">

    <!-- Google Fonts Matching fkksm.blade.php -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700;800&family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full flex-col font-sans text-[#132B45] bg-[#F5F8FC] antialiased selection:bg-[#10A9BC] selection:text-white">

    <!-- Topline & Ticker Component -->
    <x-topline />

    <!-- Site Navigation Header Component -->
    <x-navbar />

    <!-- Flash Notifications & Validation Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4 space-y-3">
        @if (session('success'))
            <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm flex items-start gap-3 shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="flex-1 font-medium">{{ session('success') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-900 text-sm flex items-start gap-3 shadow-xs">
                <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div class="flex-1 font-medium">{{ session('error') }}</div>
            </div>
        @endif
        @if ($errors->any())
            <div class="p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-900 text-sm flex items-start gap-3 shadow-xs">
                <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="flex-1">
                    <p class="font-bold mb-1 text-xs uppercase tracking-wider text-rose-900">Periksa Kembali Data Anda:</p>
                    <ul class="list-disc pl-5 space-y-0.5 text-xs text-rose-800">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content Flow -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Industrial Footer Component -->
    <x-footer />

    <!-- Floating WhatsApp Support Button (Authentic Cakap WA CS) -->
    <aside aria-label="Customer Support WhatsApp" class="fixed bottom-6 right-6 z-40">
        <a href="https://wa.me/6281211112222?text=Halo+admin!+Aku+ingin+tanya-tanya+seputar+FKKSM+Upskill" target="_blank" rel="noopener noreferrer" class="group flex items-center gap-2.5 bg-[#25D366] hover:bg-[#20ba59] text-white px-4 py-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-200">
            <img src="{{ asset('cakap_files/whatsapp.webp') }}" alt="WhatsApp" class="w-6 h-6 object-contain">
            <span class="font-heading font-bold text-xs uppercase tracking-wider hidden sm:inline-block">
                Chat Tim Ahli
            </span>
        </a>
    </aside>

    @stack('scripts')
</body>
</html>
