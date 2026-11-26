@php
    $seo = $sections['seo_content'] ?? null;
    $sc = $seo['content'] ?? [];
@endphp

@if($seo['is_active'] ?? true)
<!-- Section: SEO Copy & Educational Ecosystem Information (Dynamic via Admin CMS) -->
<section class="fkksm-container py-8 lg:py-12 border-t border-slate-200">
    <div class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-4">
        <h2 class="font-heading font-bold text-xl sm:text-2xl text-slate-900 tracking-tight">
            {{ $sc['heading'] ?? 'Belajar Kapan Saja, Di Mana Saja bersama FKKSM Upskill — Kursus Online Bersertifikat' }}
        </h2>

        <div class="space-y-3 text-xs sm:text-sm text-slate-600 leading-relaxed">
            <p>{{ $sc['paragraph_1'] ?? '' }}</p>
            <p>
                Beberapa topik yang bisa kamu pelajari di platform ini antara lain:
                <a href="{{ route('lms.courses.index', ['category' => 'Bahasa']) }}" class="text-[#10A9BC] font-semibold hover:underline">Bahasa Asing</a>,
                <a href="{{ route('lms.courses.index', ['category' => 'Media']) }}" class="text-[#10A9BC] font-semibold hover:underline">Media Kreatif</a>,
                <a href="{{ route('lms.courses.index', ['category' => 'Bisnis']) }}" class="text-[#10A9BC] font-semibold hover:underline">Bisnis &amp; Manajemen</a>,
                <a href="{{ route('lms.courses.index', ['category' => 'Teknologi']) }}" class="text-[#10A9BC] font-semibold hover:underline">Teknologi &amp; Rekayasa</a>,
                <a href="{{ route('lms.courses.index', ['category' => 'Pertanian']) }}" class="text-[#10A9BC] font-semibold hover:underline">Agribisnis</a>, dan
                <a href="{{ route('lms.courses.index', ['category' => 'Karier']) }}" class="text-[#10A9BC] font-semibold hover:underline">Pengembangan Karier &amp; Peningkatan Diri</a>.
            </p>
            <p>{{ $sc['paragraph_2'] ?? '' }}</p>
        </div>
    </div>
</section>
@endif
