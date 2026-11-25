<!-- Ekosistem Digital FKKSM: Layanan Terpadu Mandiri -->
<section class="py-16 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-[10px] font-mono font-medium text-[#10A9BC] uppercase tracking-widest block mb-1">
                EKOSISTEM DIGITAL FKKSM
            </span>
            <h2 class="font-heading font-bold text-3xl text-slate-900 uppercase tracking-tight">
                Layanan Terpadu Mandiri Anggota
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Akses instan modul pelatihan bersertifikat, forum konsultasi bipartit daring, dan meja bantuan administrasi.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 rounded-xl bg-slate-50 border border-slate-200 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 text-[10px] font-mono font-medium uppercase">
                            LMS PORTAL
                        </span>
                    </div>
                    <h3 class="font-heading font-bold text-xl text-slate-900">
                        FKKSM Learning System
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Platform pembelajaran online terintegrasi untuk sertifikasi kompetensi K3, ISO manufaktur, dan hubungan industrial bagi tenaga kerja anggota kawasan.
                    </p>
                    <div class="flex items-center gap-2 text-[11px] text-slate-700 font-medium pt-2">
                        <span>250+ Modul Aktif</span>
                        <span>·</span>
                        <span class="text-emerald-700 font-semibold">QR Verified</span>
                    </div>
                </div>
                <div class="pt-5 border-t border-slate-200 mt-5 flex gap-2">
                    <a href="{{ route('lms.dashboard') }}" class="flex-1 py-2 rounded-lg bg-[#0C3553] hover:bg-[#10A9BC] text-white font-heading font-bold text-xs uppercase tracking-wider text-center transition">
                        Akses LMS →
                    </a>
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg bg-white hover:bg-slate-100 text-slate-800 font-bold text-xs border border-slate-200 transition">
                        Upskill
                    </a>
                </div>
            </div>

            <div class="p-6 rounded-xl bg-slate-50 border border-slate-200 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-mono font-medium uppercase">
                            FORUM RESMI
                        </span>
                    </div>
                    <h3 class="font-heading font-bold text-xl text-slate-900">
                        Forum Diskusi MM2100
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Ruang tukar pikiran interaktif bagi praktisi industri untuk konsultasi penanganan sengketa kerja, kenaikan UMK, dan regulasi dinas tenaga kerja.
                    </p>
                    <div class="flex items-center gap-2 text-[11px] text-slate-700 font-medium pt-2">
                        <span>1.200+ Anggota Aktif</span>
                        <span>·</span>
                        <span class="text-blue-700 font-semibold">Moderasi Ahli</span>
                    </div>
                </div>
                <div class="pt-5 border-t border-slate-200 mt-5">
                    <a href="{{ route('berita') }}" class="block w-full py-2 rounded-lg bg-white hover:bg-slate-100 text-slate-800 border border-slate-200 font-heading font-bold text-xs uppercase tracking-wider text-center transition">
                        Buka Forum &amp; Berita →
                    </a>
                </div>
            </div>

            <div class="p-6 rounded-xl bg-slate-50 border border-slate-200 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-[10px] font-mono font-medium uppercase">
                            SEKRETARIAT
                        </span>
                    </div>
                    <h3 class="font-heading font-bold text-xl text-slate-900">
                        Dukungan Operasional
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Layanan bantuan teknis dan validasi keanggotaan korporasi langsung dari tim sekretariat gedung pengelola MM2100 Cikarang Barat.
                    </p>
                    <div class="flex items-center gap-2 text-[11px] text-slate-700 font-medium pt-2">
                        <span>Senin – Jumat</span>
                        <span>·</span>
                        <span>08:30 – 17:00 WIB</span>
                    </div>
                </div>
                <div class="pt-5 border-t border-slate-200 mt-5">
                    <a href="{{ route('hubungi') }}" class="block w-full py-2 rounded-lg bg-white hover:bg-slate-100 text-slate-800 border border-slate-200 font-heading font-bold text-xs uppercase tracking-wider text-center transition">
                        Hubungi Sekretariat →
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

