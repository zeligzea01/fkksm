@extends('layouts.app')

@section('title', 'FKKSM MM2100 | Industrial HR Community — Profile')
@section('meta_title', 'FKKSM MM2100 | Industrial HR Community & Komite Kawasan MM2100')
@section('meta_description', 'Wadah sinergi pemimpin HRD, praktisi K3, direksi manufaktur, dan pengembang kawasan industri MM2100 Cikarang Barat.')

@section('content')
    <!-- 1. Hero Section & Upcoming Summit 2026 -->
    @include('profile.partials.hero')

    <!-- 2. Stats Grid & Ecosystem Telemetry Donut Chart -->
    @include('profile.partials.stats-telemetry')

    <!-- 3. Rangkaian Kegiatan FKKSM 2026 (Interactive Activity Band) -->
    @include('profile.partials.activities-slider')

    <!-- 4. 4 Pilar Layanan Utama (HR, K3, Industri 4.0, CEO Board) -->
    @include('profile.partials.pillars')

    <!-- 5. Ekosistem Digital FKKSM (LMS, Forum, Dukungan Sekretariat) -->
    @include('profile.partials.digital-services')

    <!-- 6. Informasi & Edaran Terkini -->
    @include('profile.partials.news-section')

    <!-- 7. Call To Action (Perkuat Jaringan HR & Keanggotaan) -->
    @include('profile.partials.cta-membership')

    <!-- 8. Kemitraan & Ekosistem Industri MM2100 -->
    @include('profile.partials.partners-ecosystem')

    <!-- 9. Lokasi Sekretariat & Interactive Google Map -->
    @include('profile.partials.secretariat-location')
@endsection

@push('scripts')
<script>
    // Hero Summit Slider Logic
    let currentHeroSlide = 0;
    const heroSlides = document.querySelectorAll('.hero-summit-slide');
    const heroDots = document.querySelectorAll('.hero-dot');

    function showHeroSlide(index) {
        if (!heroSlides.length) return;
        currentHeroSlide = (index + heroSlides.length) % heroSlides.length;
        heroSlides.forEach((slide, idx) => {
            if (idx === currentHeroSlide) {
                slide.classList.remove('opacity-0', 'pointer-events-none');
                slide.classList.add('opacity-100');
            } else {
                slide.classList.remove('opacity-100');
                slide.classList.add('opacity-0', 'pointer-events-none');
            }
        });
        heroDots.forEach((dot, idx) => {
            if (idx === currentHeroSlide) {
                dot.className = 'hero-dot w-6 h-1 rounded bg-[#10A9BC] transition-all';
            } else {
                dot.className = 'hero-dot w-2 h-1 rounded bg-white/60 hover:bg-white transition-all';
            }
        });
    }

    function changeHeroSlide(dir) {
        showHeroSlide(currentHeroSlide + dir);
    }

    function setHeroSlide(idx) {
        showHeroSlide(idx);
    }

    setInterval(() => {
        changeHeroSlide(1);
    }, 5000);

    // Activity Band Slider Logic
    const activitiesData = [
        {
            img: "{{ asset('fkksm/assets/images/activity-01.jpg') }}",
            tag: "AGENDA RESMI 2026",
            title: "Forum Koordinasi Komite K3 & Hubungan Industrial MM2100"
        },
        {
            img: "{{ asset('fkksm/assets/images/activity-02.jpg') }}",
            tag: "WORKSHOP KETENAGAKERJAAN",
            title: "Sosialisasi Fleksibilitas Waktu Kerja & Regulasi Disnaker"
        },
        {
            img: "{{ asset('fkksm/assets/images/activity-03.jpg') }}",
            tag: "KTT NASIONAL",
            title: "Konferensi Tahunan Pimpinan HR Manufaktur Otomotif & Elektronik"
        },
        {
            img: "{{ asset('fkksm/assets/images/activity-04.jpg') }}",
            tag: "SINERGI DIREKSI",
            title: "Dialog Meja Bundar Direksi Perusahaan MM2100"
        },
        {
            img: "{{ asset('fkksm/assets/images/activity-05.jpg') }}",
            tag: "AUDIT KESELAMATAN",
            title: "Pelatihan Ahli K3 Umum & Simulasi Tanggap Darurat Bencana"
        },
        {
            img: "{{ asset('fkksm/assets/images/activity-06.jpg') }}",
            tag: "KEMITRAAN VOKASI",
            title: "Sinergi Kurikulum SMK MM2100 & Penyerapan Tenaga Kerja Baru"
        }
    ];

    let currentActivity = 0;
    function selectActivity(index) {
        currentActivity = index;
        const data = activitiesData[index];
        const img = document.getElementById('activityMainImg');
        const tag = document.getElementById('activityCaptionTag');
        const title = document.getElementById('activityCaptionTitle');
        const counter = document.getElementById('activityCounter');

        if (img) {
            img.style.opacity = '0';
            setTimeout(() => {
                img.src = data.img;
                img.style.opacity = '1';
            }, 100);
        }
        if (tag) tag.textContent = data.tag;
        if (title) title.textContent = data.title;
        if (counter) counter.textContent = (index + 1) + ' / 6';

        const thumbs = document.querySelectorAll('.act-thumb');
        thumbs.forEach((thumb, idx) => {
            if (idx === index) {
                thumb.className = 'act-thumb relative rounded-md overflow-hidden aspect-[4/3] border border-[#54D6D0] ring-1 ring-[#54D6D0] transition-all';
            } else {
                thumb.className = 'act-thumb relative rounded-md overflow-hidden aspect-[4/3] border border-white/20 hover:border-white/60 transition-all';
            }
        });
    }

    function stepActivity(dir) {
        let next = (currentActivity + dir + 6) % 6;
        selectActivity(next);
    }
</script>
@endpush
