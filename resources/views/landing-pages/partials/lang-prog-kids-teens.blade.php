@php
    $kt = $sections['kids_teens_programs'] ?? null;
    $kc = $kt['content'] ?? [];
    $programs = $kc['programs'] ?? [];
@endphp

@if($kt['is_active'] ?? true)
<!-- Section: Program Vokasi & Bahasa FKKSM (Dynamic via Admin CMS) -->
<section class="fkksm-container py-8 border-t border-slate-200">
    <div class="mb-6">
        <h2 class="font-heading font-bold text-2xl sm:text-3xl text-slate-900 tracking-tight">
            {{ $kc['heading'] ?? 'Program Vokasi & Bahasa Industri untuk Pelajar & Calon Tenaga Kerja' }}
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
            {{ $kc['subtitle'] ?? 'Program pembelajaran bahasa industri dan kesiapan vokasi SMK/Politeknik terpadu binaan komite FKKSM MM2100.' }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($programs as $prog)
            @php $isAmber = ($prog['mode_theme'] ?? 'blue') === 'amber'; @endphp
            <article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
                <div>
                    <div class="relative aspect-[16/9] overflow-hidden bg-slate-900">
                        <img src="{{ \App\Models\LandingSection::resolveImageUrl($prog['image'] ?? null, 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80') }}" onerror="this.onerror=null;this.src='{{ asset('fkksm/assets/images/activity-03.jpg') }}'" alt="{{ $prog['title'] ?? '' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute top-3 left-3">
                            <span class="px-2.5 py-1 rounded-md {{ $isAmber ? 'bg-amber-600' : 'bg-[#0C3553]' }} text-white text-[11px] font-mono font-bold tracking-wider uppercase shadow-xs">
                                {{ $prog['mode_badge'] ?? 'Online' }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5 space-y-2">
                        <h3 class="font-heading font-bold text-xl text-slate-900 {{ $isAmber ? 'group-hover:text-amber-600' : 'group-hover:text-[#10A9BC]' }} transition-colors">
                            {{ $prog['title'] ?? '' }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            {{ $prog['description'] ?? '' }}
                        </p>
                    </div>
                </div>

                <div class="p-5 pt-3 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500 font-mono">{{ $prog['meta'] ?? '' }}</span>
                    <a href="{{ \App\Models\LandingSection::resolveUrl($prog['cta_url'] ?? null, route('lms.courses.index', ['category' => 'Bahasa'])) }}" class="px-5 py-2 rounded-lg {{ $isAmber ? 'bg-slate-800 hover:bg-slate-900' : 'bg-[#10A9BC] hover:bg-[#0E97A8]' }} text-white font-heading font-bold text-xs uppercase tracking-wider transition shadow-xs flex items-center gap-1.5">
                        <span>{{ $prog['cta_label'] ?? 'Lihat Detail' }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif
