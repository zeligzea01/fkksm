<!-- FKKSM MM2100 Clean Industrial Footer -->
<footer class="bg-[#0C3553] text-white border-t border-[#176D91]/40 mt-16">
    <!-- Main Footer Directory -->
    <div class="fkksm-container py-12">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
            <!-- Col 1: About & Identity -->
            <div class="md:col-span-5 space-y-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('fkksm/logo-transparent.png') }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.jpeg') }}'" alt="Logo FKKSM" class="h-10 w-auto object-contain">
                    <div>
                        <span class="font-heading font-extrabold text-xl text-white tracking-wide block leading-none">FKKSM MM2100</span>
                        <span class="text-[9px] font-bold text-[#54D6D0] uppercase tracking-widest">Industrial HR Community &amp; Upskill LMS</span>
                    </div>
                </div>
                <p class="text-xs text-[#A5B4C2] leading-relaxed pr-4">
                    Wadah representasi industri, praktisi Human Resources, pakar K3, dan institusi pendidikan vokasi di Kawasan Industri MM2100 Cikarang Barat, Bekasi. Memacu transformasi tenaga kerja manufaktur, kepatuhan regulasi ketenagakerjaan, dan daya saing vokasi nasional.
                </p>
                <div class="pt-1 text-xs text-[#D7E9F2] space-y-2">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-[#54D6D0] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Sekretariat FKKSM: {{ \App\Models\Setting::get('address', 'Kawasan Industri MM2100, Cikarang Barat, Bekasi 17520') }}</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-[#54D6D0] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>{{ \App\Models\Setting::get('contact_email', 'sekretariat@fkksm-mm2100.or.id') }} &bull; {{ \App\Models\Setting::get('contact_phone', '+62 21 8980 1234') }}</span>
                    </div>
                </div>
            </div>

            <!-- Col 2: Program Pelatihan -->
            <div class="md:col-span-3">
                <h4 class="font-heading font-bold text-sm uppercase tracking-wider text-white mb-4 border-l-2 border-[#10A9BC] pl-2.5">
                    PROGRAM UNGGULAN
                </h4>
                <ul class="space-y-2.5 text-xs text-[#A5B4C2]">
                    <li><a href="{{ route('lms.courses.index', ['category' => 'K3']) }}" class="hover:text-[#54D6D0] transition">Standar K3 &amp; Keselamatan Pabrik</a></li>
                    <li><a href="{{ route('lms.courses.index', ['category' => 'Human Resources']) }}" class="hover:text-[#54D6D0] transition">Hubungan Industrial &amp; Bipartit</a></li>
                    <li><a href="{{ route('lms.courses.index', ['category' => 'Teknologi']) }}" class="hover:text-[#54D6D0] transition">Smart Factory &amp; Otomasi 4.0</a></li>
                    <li><a href="{{ route('lms.courses.index', ['category' => 'Leadership']) }}" class="hover:text-[#54D6D0] transition">Kepemimpinan Supervisor &amp; 5R</a></li>
                    <li><a href="{{ route('lms.courses.index', ['category' => 'Bahasa']) }}" class="hover:text-[#54D6D0] transition">Bahasa Jepang &amp; Inggris Industri</a></li>
                </ul>
            </div>

            <!-- Col 3: Ekosistem Kawasan -->
            <div class="md:col-span-2">
                <h4 class="font-heading font-bold text-sm uppercase tracking-wider text-white mb-4 border-l-2 border-[#10A9BC] pl-2.5">
                    NAVIGASI PORTAL
                </h4>
                <ul class="space-y-2.5 text-xs text-[#A5B4C2]">
                    <li><a href="{{ route('profile.fkksm') }}" class="hover:text-[#54D6D0] transition">Profile FKKSM MM2100</a></li>
                    <li><a href="{{ route('members') }}" class="hover:text-[#54D6D0] transition">Direktori Anggota</a></li>
                    <li><a href="{{ route('berita') }}" class="hover:text-[#54D6D0] transition">Berita &amp; Forum HR</a></li>
                    <li><a href="{{ route('certificates.verify') }}" class="hover:text-[#54D6D0] transition">Verifikasi Sertifikat</a></li>
                    <li><a href="{{ route('hubungi') }}" class="hover:text-[#54D6D0] transition">Hubungi Sekretariat</a></li>
                </ul>
            </div>

            <!-- Col 4: Layanan Sertifikasi -->
            <div class="md:col-span-2">
                <h4 class="font-heading font-bold text-sm uppercase tracking-wider text-white mb-4 border-l-2 border-[#10A9BC] pl-2.5">
                    SERTIFIKASI RESMI
                </h4>
                <p class="text-xs text-[#A5B4C2] leading-relaxed mb-4">
                    Setiap sertifikat kelulusan dilengkapi kode unik yang dapat diverifikasi secara daring oleh HRD perusahaan kawasan MM2100.
                </p>
                <a href="{{ route('certificates.verify') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-[#132B45] hover:bg-[#19385A] border border-[#176D91]/50 text-xs font-bold text-[#54D6D0] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Cek Sertifikat</span>
                </a>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="mt-10 pt-6 border-t border-[#176D91]/30 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-[#718CA1]">
            <p>&copy; {{ date('Y') }} Forum Komunikasi Komite Sekolah Menengah &amp; HR Kawasan Industri MM2100 (FKKSM). Hak cipta dilindungi undang-undang.</p>
            <div class="flex items-center gap-6">
                <a href="{{ route('certificates.verify') }}" class="hover:text-[#54D6D0] transition">Validasi Sertifikat</a>
                <span>&bull;</span>
                <a href="{{ route('profile.fkksm') }}" class="hover:text-[#54D6D0] transition">Profile</a>
                <span>&bull;</span>
                <a href="{{ route('hubungi') }}" class="hover:text-[#54D6D0] transition">Kontak Sekretariat</a>
            </div>
        </div>
    </div>
</footer>
