<!-- Frequently Asked Questions Accordion -->
<section class="py-16 bg-[#F8FAFC] border-b border-slate-200" id="faq">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-section-header
            badge="PERTANYAAN UMUM"
            title="Pertanyaan yang Sering Diajukan (FAQ)"
            subtitle="Informasi lengkap seputar mekanisme pendaftaran, subsidi voucher, dan keabsahan sertifikat kompetensi FKKSM."
            align="center"
        />

        <div class="space-y-4">
            <details class="group bg-white rounded-xl border border-slate-200 p-5 shadow-2xs open:border-[#10A9BC]">
                <summary class="font-heading font-bold text-base text-[#132B45] cursor-pointer flex items-center justify-between select-none">
                    <span>Apakah sertifikat yang diterbitkan resmi dan diakui perusahaan di MM2100?</span>
                    <span class="transition-transform group-open:rotate-180 text-[#10A9BC]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </summary>
                <div class="mt-3 pt-3 border-t border-slate-100 text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Ya. Sertifikat FKKSM diterbitkan berdasarkan kurikulum SKKNI dan pengawasan Komite Pembina Kawasan MM2100. Dokumen dilengkapi nomor seri unik dan QR Code yang dapat diverifikasi secara publik kapan saja melalui halaman verifikasi resmi portal FKKSM.
                </div>
            </details>

            <details class="group bg-white rounded-xl border border-slate-200 p-5 shadow-2xs open:border-[#10A9BC]">
                <summary class="font-heading font-bold text-base text-[#132B45] cursor-pointer flex items-center justify-between select-none">
                    <span>Bagaimana cara menggunakan voucher subsidi pelatihan (misal: FKKSM2026)?</span>
                    <span class="transition-transform group-open:rotate-180 text-[#10A9BC]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </summary>
                <div class="mt-3 pt-3 border-t border-slate-100 text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Klik tombol "Tukarkan Voucher" di bagian atas halaman atau masukkan kode voucher pada form pendaftaran kursus. Jika Anda belum masuk, sistem akan meminta Anda login terlebih dahulu untuk merekam kupon subsidi ke akun Anda.
                </div>
            </details>

            <details class="group bg-white rounded-xl border border-slate-200 p-5 shadow-2xs open:border-[#10A9BC]">
                <summary class="font-heading font-bold text-base text-[#132B45] cursor-pointer flex items-center justify-between select-none">
                    <span>Bisakah perusahaan memesan pelatihan in-house khusus di lokasi pabrik?</span>
                    <span class="transition-transform group-open:rotate-180 text-[#10A9BC]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </summary>
                <div class="mt-3 pt-3 border-t border-slate-100 text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Sangat bisa. FKKSM menyediakan program pelatihan in-company khusus bagi tenant kawasan industri MM2100. Trainer dan asesor kami akan hadir langsung ke fasilitas pabrik Anda sesuai jadwal dan modul yang disepakati. Silakan hubungi sekretariat via menu Kontak.
                </div>
            </details>

            <details class="group bg-white rounded-xl border border-slate-200 p-5 shadow-2xs open:border-[#10A9BC]">
                <summary class="font-heading font-bold text-base text-[#132B45] cursor-pointer flex items-center justify-between select-none">
                    <span>Di mana saya dapat melihat profil lengkap organisasi FKKSM MM2100?</span>
                    <span class="transition-transform group-open:rotate-180 text-[#10A9BC]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </summary>
                <div class="mt-3 pt-3 border-t border-slate-100 text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Anda dapat mengakses halaman profile resmi FKKSM melalui menu navigasi atas dengan mengklik menu "PROFILE" atau langsung membuka tautan <a href="{{ route('profile.fkksm') }}" class="text-[#10A9BC] font-bold underline">Profile FKKSM MM2100</a>.
                </div>
            </details>
        </div>
    </div>
</section>

