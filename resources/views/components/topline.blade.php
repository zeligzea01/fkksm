<!-- FKKSM Clean Utility Topline Bar -->
<div class="bg-[#0A2540] text-slate-300 text-xs border-b border-slate-800/80 select-none">
    <div class="fkksm-container h-10 flex items-center justify-between gap-4">
        
        <!-- Left: Association Identity & Tagline -->
        <div class="flex items-center gap-3 min-w-0">
            <div class="hidden sm:inline-flex items-center gap-2 px-2.5 py-0.5 rounded bg-[#10A9BC]/15 text-[#54D6D0] border border-[#10A9BC]/30 text-[10px] font-mono font-bold uppercase tracking-wider shrink-0">
                <span class="w-1.5 h-1.5 rounded-full bg-[#10A9BC]"></span>
                <span>FKKSM MM2100</span>
            </div>
            
            <span class="font-mono text-[11px] font-semibold tracking-wider uppercase text-slate-200 truncate">FOR THE PEOPLE BEHIND INDUSTRY</span>

            <span class="text-slate-600 hidden lg:inline">·</span>

            <span class="text-[11px] text-slate-400 hidden lg:inline truncate">
                Kawasan Industri MM2100 Cikarang Barat
            </span>
        </div>

        <!-- Right: Practical Utilities & Quick Access -->
        <div class="flex items-center gap-4 text-[11px] font-medium shrink-0">
            <a href="{{ route('certificates.verify') }}" class="inline-flex items-center gap-1.5 text-slate-300 hover:text-white transition">
                <svg class="w-3.5 h-3.5 text-[#54D6D0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span class="hidden sm:inline">Verifikasi Sertifikat</span>
            </a>

            <span class="text-slate-700 hidden sm:inline">|</span>

            <a href="{{ route('members') }}" class="hidden md:inline-flex items-center gap-1 text-slate-300 hover:text-white transition">
                <span>Direktori Industri</span>
            </a>

            <span class="text-slate-700 hidden md:inline">|</span>

            <a href="{{ route('hubungi') }}" class="inline-flex items-center gap-1.5 text-slate-300 hover:text-white transition">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>Sekretariat</span>
            </a>
        </div>
    </div>
</div>
