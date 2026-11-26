@extends('layouts.app')

@section('title', 'FKKSM Upskill | Kursus Online Bersertifikat & Keterampilan Kerja Industri MM2100')
@section('meta_title', 'FKKSM Upskill — Platform Pelatihan & Sertifikasi Kerja Resmi Kawasan MM2100')
@section('meta_description', 'Tingkatkan keterampilan kerja industri bersama praktisi ahli. Kursus bersertifikasi K3, hubungan industrial, otomatisasi manufaktur, dan vokasi terakreditasi.')

@section('content')
    <!-- 1. Hero Promo & FKKSM Upskill Carousel Slider -->
    @include('landing-pages.partials.hero-carousel')

    <!-- 2. Rocket Onboarding Banner -->
    @include('landing-pages.partials.onboarding-section')

    <!-- 3. Kursus Apa yang Ingin Kamu Pelajari? (Live Webinar & Kursus Mandiri) -->
    @include('landing-pages.partials.what-you-want-to-learn')

    <!-- 4. Kategori Kursus (7 Categories with Round Icons) -->
    @include('landing-pages.partials.category-grid')

    <!-- 5. Program Spesialisasi Industri & Vokasi (FKKSM Next & FKKSM Learning Center) -->
    @include('landing-pages.partials.lang-prog-kids-teens')

    <!-- 6. Voucher Promo Banner & Coupon Redemption Modal -->
    @include('landing-pages.partials.voucher-section')

    <!-- 7. Kursus Terlaris with Skill Badges -->
    @include('landing-pages.partials.bestseller-courses')

    <!-- 8. Lowongan Pekerjaan Terbaru di Bursa Karier FKKSM -->
    @include('landing-pages.partials.job-vacancies')

    <!-- 9. Pelatihan Prakerja with Skill Badges & Co-Branding -->
    @include('landing-pages.partials.prakerja-section')

    <!-- 10. Paginated Courses Catalog with Modular Course Card Component -->
    @include('landing-pages.partials.course-section')

    <!-- 11. B2B Corporate In-Company Training Banner -->
    @include('landing-pages.partials.b2b-corporate')

    <!-- 12. Verified Industrial Alumni Testimonials -->
    @include('landing-pages.partials.testimonials')

    <!-- 14. Frequently Asked Questions Accordion -->
    @include('landing-pages.partials.faq-accordion')

    <!-- 15. SEO Educational Copy & Internal Category Navigation -->
    @include('landing-pages.partials.homepage-seo')
@endsection

@push('scripts')
<script>
    // Hero Carousel Script
    let activeFkksmSlide = 0;
    const fkksmSlides = document.querySelectorAll('.fkksm-slide');
    const fkksmDots = document.querySelectorAll('#fkksmCarouselDots button');

    function updateFkksmSlider() {
        fkksmSlides.forEach((s, idx) => {
            if (idx === activeFkksmSlide) {
                s.classList.remove('opacity-0', 'pointer-events-none');
                s.classList.add('opacity-100');
            } else {
                s.classList.remove('opacity-100');
                s.classList.add('opacity-0', 'pointer-events-none');
            }
        });

        fkksmDots.forEach((dot, idx) => {
            if (idx === activeFkksmSlide) {
                dot.classList.remove('bg-white/60');
                dot.classList.add('bg-[#10A9BC]');
            } else {
                dot.classList.remove('bg-[#10A9BC]');
                dot.classList.add('bg-white/60');
            }
        });
    }

    function changeFkksmSlide(delta) {
        if (!fkksmSlides.length) return;
        activeFkksmSlide = (activeFkksmSlide + delta + fkksmSlides.length) % fkksmSlides.length;
        updateFkksmSlider();
    }

    function goToFkksmSlide(index) {
        if (!fkksmSlides.length) return;
        activeFkksmSlide = index;
        updateFkksmSlider();
    }

    // Auto-advance carousel every 6 seconds
    setInterval(() => {
        changeFkksmSlide(1);
    }, 6000);
</script>
@endpush
