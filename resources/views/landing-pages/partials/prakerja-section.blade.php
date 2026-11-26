@php
    $pk = $sections['prakerja_section'] ?? null;
    $pkc = $pk['content'] ?? [];
    $prakerjaCourses = $pkc['items'] ?? [];
@endphp

@if($pk['is_active'] ?? true)
<!-- Section: Pelatihan Vokasi & Subsidi Kompetensi FKKSM (Dynamic via Admin CMS) -->
<section class="fkksm-container py-8 border-t border-slate-200" id="prakerja">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="inline-flex items-center gap-2">
                    <img src="{{ asset('fkksm/logo-transparent.png') }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.jpeg') }}'" alt="FKKSM MM2100" class="h-7 w-auto object-contain">
                    <span class="font-heading font-extrabold text-sm text-[#0C3553] tracking-wider uppercase">FKKSM MM2100</span>
                </div>
                <span class="text-slate-300 font-bold text-lg">&bull;</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-[#E7F6F8] text-[#10A9BC] text-[11px] font-mono font-bold uppercase tracking-wider">
                    SUBSIDI VOKASI &amp; KOMPETENSI
                </span>
            </div>
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-slate-900 tracking-tight">
                {{ $pkc['heading'] ?? 'Program Sertifikasi Vokasi & Subsidi Kompetensi FKKSM' }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                {{ $pkc['subtitle'] ?? 'Gunakan voucher subsidi perusahaan anggota FKKSM untuk mengambil kelas keterampilan kerja bersertifikat resmi.' }}
            </p>
        </div>
    </div>

    <div class="bg-gradient-to-r from-[#0C3553] to-[#10A9BC] rounded-xl p-5 sm:p-6 mb-8 text-white shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4 text-center sm:text-left">
            <div class="w-12 h-12 rounded-lg bg-white/10 flex items-center justify-center p-2 shrink-0">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-heading font-bold text-base sm:text-lg">
                    {{ $pkc['banner_heading'] ?? 'Tukar Kode Voucher Subsidi Pelatihan FKKSM di Sini' }}
                </h3>
                <p class="text-xs text-cyan-100">
                    {{ $pkc['banner_description'] ?? 'Masukkan kode kupon resmi dari HRD perusahaan atau sekretariat FKKSM Anda untuk langsung mengaktifkan kelas.' }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 w-full sm:w-auto">
            <button type="button" onclick="openVoucherModal()" class="w-full sm:w-auto px-5 py-2.5 rounded-lg bg-white hover:bg-slate-100 text-[#0C3553] font-heading font-bold text-xs uppercase tracking-wider transition shadow-xs cursor-pointer">
                Tukar Kode
            </button>
            <a href="{{ route('hubungi') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-lg bg-white/15 hover:bg-white/25 text-white font-heading font-bold text-xs uppercase tracking-wider border border-white/30 transition text-center">
                Cara Tukar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @foreach($prakerjaCourses as $pkItem)
            <article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md hover:border-[#10A9BC] transition-all flex flex-col justify-between group">
                <div>
                    <div class="relative aspect-[16/9] overflow-hidden bg-slate-900">
                        <img src="{{ \App\Models\LandingSection::resolveImageUrl($pkItem['thumbnail'] ?? null, 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=700&q=80') }}" onerror="this.onerror=null;this.src='{{ asset('fkksm/assets/images/activity-04.jpg') }}'" alt="{{ $pkItem['title'] ?? '' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute top-2 left-2">
                            <span class="px-2 py-0.5 rounded bg-cyan-600 text-white text-[9px] font-mono font-bold uppercase tracking-wider">
                                {{ $pkItem['category'] ?? 'Vokasi FKKSM' }}
                            </span>
                        </div>
                    </div>

                    @if(!empty($pkItem['badges']))
                        <div class="relative px-3.5 -mt-5 z-10 flex items-center gap-1.5 mb-1">
                            @foreach($pkItem['badges'] as $b)
                                <div class="relative group/badge" title="{{ $b['name'] ?? '' }}">
                                    <button type="button" class="w-9 h-9 rounded-full bg-white border border-slate-200 shadow-sm hover:scale-110 transition-transform flex items-center justify-center text-[#0C3553]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 hidden group-hover/badge:block whitespace-nowrap bg-slate-900 text-white text-[10px] font-mono px-2 py-0.5 rounded shadow-lg pointer-events-none z-30">
                                        {{ $b['name'] ?? '' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="p-3.5 pt-2 space-y-2">
                        <h3 class="font-heading font-bold text-xs sm:text-sm text-slate-900 group-hover:text-[#10A9BC] transition-colors line-clamp-2 leading-snug">
                            {{ $pkItem['title'] ?? '' }}
                        </h3>

                        @if(!empty($pkItem['rating']))
                            <div class="flex items-center gap-1 text-[11px] font-mono text-amber-500">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="font-bold text-slate-800">{{ $pkItem['rating'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="p-3.5 pt-2 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between">
                    <span class="font-mono font-extrabold text-[#0C3553] text-sm tabular-nums">
                        {{ $pkItem['price'] ?? '' }}
                    </span>
                    <a href="{{ route('lms.courses.index') }}" class="px-2.5 py-1 rounded bg-slate-100 group-hover:bg-[#10A9BC] text-slate-700 group-hover:text-white font-heading font-bold text-[10px] uppercase tracking-wider transition">
                        Daftar
                    </a>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif
