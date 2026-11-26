@php
    $jv = $sections['job_vacancies'] ?? null;
    $jvc = $jv['content'] ?? [];
    $jobs = $jvc['jobs'] ?? [];
@endphp

@if($jv['is_active'] ?? true)
<!-- Section: Lowongan Pekerjaan Terbaru di Bursa Karier FKKSM (Dynamic via Admin CMS) -->
<section class="fkksm-container py-8 border-t border-slate-200" id="lowongan-kerja">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-slate-900 tracking-tight">
                {{ $jvc['heading'] ?? 'Lowongan Pekerjaan Terbaru di Bursa Karier FKKSM' }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                {{ $jvc['subtitle'] ?? 'Karier impian bersama mitra industri terkemuka. Lamar langsung dengan sertifikat kompetensimu.' }}
            </p>
        </div>
        <a href="{{ route('members') }}" class="hidden sm:inline-flex items-center gap-1 text-xs font-heading font-bold text-[#10A9BC] hover:underline uppercase tracking-wider">
            <span>Lihat Semua Lowongan</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @foreach($jobs as $job)
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs hover:border-[#10A9BC] hover:shadow-md transition-all flex flex-col justify-between group">
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-red-50 border border-red-100 text-red-700 font-heading font-extrabold flex items-center justify-center text-xs shrink-0">
                            {{ $job['initial'] ?? 'IN' }}
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-sm sm:text-base text-slate-900 group-hover:text-[#10A9BC] transition-colors leading-snug line-clamp-1">
                                {{ $job['title'] ?? '' }}
                            </h3>
                            <p class="text-xs text-slate-500 font-medium">
                                {{ $job['company'] ?? '' }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-1.5 text-xs text-slate-600 font-mono">
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>{{ $job['type'] ?? 'Full-Time' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>{{ $job['location'] ?? 'Bekasi, Indonesia' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-400 text-[11px]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $job['time'] ?? '' }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-slate-100">
                    <a href="{{ route('members') }}" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg bg-slate-50 hover:bg-[#10A9BC] text-slate-700 hover:text-white font-heading font-bold text-xs uppercase tracking-wider transition">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif
