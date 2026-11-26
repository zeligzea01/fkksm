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
    <link rel="icon" type="image/png" href="{{ asset('fkksm/logo-transparent.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('fkksm/logo-transparent.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Open Graph / Facebook / LinkedIn / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'FKKSM MM2100 | Industrial HR Community & LMS Upskill')">
    <meta property="og:description" content="@yield('og_description', 'Platform resmi Human Resources & Pelatihan Vokasi Bersertifikasi Kawasan Industri MM2100 Cikarang Barat.')">
    <meta property="og:image" content="{{ asset('fkksm/logo-transparent.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="FKKSM MM2100">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card Meta -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('twitter_title', 'FKKSM MM2100 | Industrial HR Community & LMS Upskill')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Platform resmi Human Resources & Pelatihan Vokasi Bersertifikasi Kawasan Industri MM2100 Cikarang Barat.')">
    <meta name="twitter:image" content="{{ asset('fkksm/logo-transparent.png') }}">

    <!-- Google Fonts (Uniform with LMS Portal) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700;800&family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full flex-col font-sans text-[#132B45] bg-[#F5F8FC] antialiased selection:bg-[#10A9BC] selection:text-white">

    <!-- Topline & Ticker Component -->
    <x-topline />

    <!-- Site Navigation Header Component -->
    <x-navbar />

    <!-- Main Content Flow -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Industrial Footer Component -->
    <x-footer />

    <!-- Floating WhatsApp Support Button -->
    <aside aria-label="Customer Support WhatsApp" class="fixed bottom-6 right-6 z-40">
        <a href="https://wa.me/6281211112222?text=Halo+admin!+Aku+ingin+tanya-tanya+seputar+FKKSM+Upskill" target="_blank" rel="noopener noreferrer" class="group flex items-center gap-2.5 bg-[#25D366] hover:bg-[#20ba59] text-white px-4 py-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-200">
            <img src="{{ asset('assets/fkksm/whatsapp.webp') }}" alt="WhatsApp" class="w-6 h-6 object-contain">
            <span class="font-heading font-bold text-xs uppercase tracking-wider hidden sm:inline-block">
                Chat Tim Ahli
            </span>
        </a>
    </aside>

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
