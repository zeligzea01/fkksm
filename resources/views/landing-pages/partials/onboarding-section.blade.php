@php
    $onb = $sections['onboarding_banner'] ?? null;
    $oc = $onb['content'] ?? [];
@endphp

@if($onb['is_active'] ?? true)
<!-- Onboarding Section: Industrial Skill Hub banner for learners (Dynamic via Admin CMS) -->
<section class="fkksm-container py-4 sm:py-6">
    <div class="bg-white rounded-xl border border-slate-200 p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4 text-center sm:text-left">
            <div class="w-12 h-12 shrink-0 rounded-lg bg-[#EDF5FA] border border-[#10A9BC]/25 text-[#10A9BC] flex items-center justify-center p-2">
                @if(!empty($oc['image']) && !str_contains($oc['image'], 'blue-rocket'))
                    <img src="{{ \App\Models\LandingSection::resolveImageUrl($oc['image']) }}" alt="Onboarding" class="w-8 h-8 object-contain">
                @else
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                @endif
            </div>
            <div>
                <p class="font-heading font-bold text-base sm:text-lg text-[#132B45] leading-snug">
                    {{ $oc['headline'] ?? 'Dapatkan voucher eksklusif dan kursus terbaik sesuai minatmu.' }}
                </p>
                <p class="text-xs sm:text-sm text-slate-500 font-normal">
                    {{ $oc['subtitle'] ?? 'Bergabung bersama ribuan profesional industri MM2100 sekarang juga!' }}
                </p>
            </div>
        </div>
        <div class="shrink-0 w-full sm:w-auto">
            @guest
                <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold text-xs uppercase tracking-wider transition shadow-xs">
                    {{ $oc['cta_guest'] ?? 'Daftar Sekarang' }}
                </a>
            @else
                <a href="{{ route('lms.dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold text-xs uppercase tracking-wider transition shadow-xs">
                    {{ $oc['cta_auth'] ?? 'Masuk Ruang Belajar' }}
                </a>
            @endguest
        </div>
    </div>
</section>
@endif
