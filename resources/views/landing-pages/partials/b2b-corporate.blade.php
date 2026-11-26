@php
    $b2b = $sections['b2b_corporate'] ?? null;
    $bc = $b2b['content'] ?? [];
@endphp

@if($b2b['is_active'] ?? true)
<!-- B2B In-Company Training Banner (Dynamic via Admin CMS) -->
<section class="py-14 bg-[#0C3553] text-white border-b border-[#176D91]/30 relative overflow-hidden">
    <div class="absolute inset-0 bg-industrial-grid-dark opacity-15 pointer-events-none"></div>

    <div class="fkksm-container relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            <div class="lg:col-span-7 space-y-4">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded bg-[#132B45] border border-[#176D91]/50 text-[#54D6D0] text-xs font-mono font-medium tracking-wider uppercase">
                    <span class="w-2 h-2 rounded-full bg-[#10A9BC]"></span>
                    {{ $bc['badge'] ?? 'FKKSM FOR CORPORATE & FACTORY' }}
                </span>

                <h2 class="font-heading font-extrabold text-3xl sm:text-4xl lg:text-5xl uppercase tracking-tight text-white leading-tight">
                    {{ $bc['headline'] ?? 'SOLUSI PELATIHAN KHUSUS PABRIK & KARYAWAN TENANT MM2100' }}
                </h2>

                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-2xl">
                    {{ $bc['description'] ?? '' }}
                </p>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4">
                    <div class="p-3.5 rounded-lg bg-[#132B45] border border-[#176D91]/40">
                        <span class="font-heading font-bold text-lg text-[#54D6D0] block">In-House</span>
                        <span class="text-[11px] text-slate-300">Jadwal &amp; materi fleksibel</span>
                    </div>
                    <div class="p-3.5 rounded-lg bg-[#132B45] border border-[#176D91]/40">
                        <span class="font-heading font-bold text-lg text-[#54D6D0] block">SKKNI &amp; BNSP</span>
                        <span class="text-[11px] text-slate-300">Diakui Kemnaker RI</span>
                    </div>
                    <div class="p-3.5 rounded-lg bg-[#132B45] border border-[#176D91]/40">
                        <span class="font-heading font-bold text-lg text-[#54D6D0] block">Subsidi FKKSM</span>
                        <span class="text-[11px] text-slate-300">Tarif khusus tenant MM2100</span>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <a href="{{ route('hubungi') }}" class="px-6 py-3 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold text-xs uppercase tracking-wider transition shadow-sm">
                        {{ $bc['cta_label'] ?? 'Ajukan Proposal Training Pabrik' }} →
                    </a>
                    <a href="{{ route('profile.fkksm') }}" class="text-xs font-bold uppercase tracking-wider text-[#54D6D0] hover:text-white transition">
                        Profile FKKSM
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="rounded-2xl border border-[#176D91]/50 bg-[#132B45] p-6 text-white space-y-4 shadow-xl">
                    <h3 class="font-heading font-bold text-lg uppercase tracking-wide text-white border-b border-[#176D91]/40 pb-3">
                        KONSULTASI CEPAT KETENAGAKERJAAN
                    </h3>
                    <p class="text-xs text-slate-300">
                        Isi form permohonan in-company training atau konsultasi hubungan industrial melalui hotline resmi sekretariat:
                    </p>
                    <div class="space-y-3 text-xs">
                        <div class="p-3 rounded-lg bg-[#0C3553] border border-[#176D91]/40 flex items-center gap-3">
                            <svg class="w-5 h-5 text-[#54D6D0] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <div>
                                <span class="text-[10px] text-slate-400 font-mono block">TELEPON SEKRETARIAT</span>
                                <strong class="text-white font-mono">{{ $bc['phone'] ?? ($siteSettings['contact_phone'] ?? '(021) 898-2100') }}</strong>
                            </div>
                        </div>
                        <div class="p-3 rounded-lg bg-[#0C3553] border border-[#176D91]/40 flex items-center gap-3">
                            <svg class="w-5 h-5 text-[#54D6D0] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <div>
                                <span class="text-[10px] text-slate-400 font-mono block">EMAIL RESMI</span>
                                <strong class="text-white font-mono">{{ $bc['email'] ?? ($siteSettings['contact_email'] ?? 'sekretariat@fkksm-mm2100.or.id') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
