@extends('layouts.lms')

@section('title', 'Dashboard | FKKSM Learning Management System')
@section('header_eyebrow', 'Selamat datang kembali')
@section('header_title', 'Ruang Belajar')

@section('content')
    @if (auth()->user()->isAdmin())
        <!-- Administrator Integrated Control Center -->
        <div class="space-y-6">
            <!-- Hero Status Banner -->
            <div class="bg-lms-hero rounded-xl p-6 text-white flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1.5">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/15 text-[11px] font-mono font-medium uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#54D6D0]"></span>
                        Admin &amp; CMS Control Center
                    </span>
                    <h2 class="text-2xl font-bold tracking-tight">
                        Selamat datang, {{ auth()->user()->name }}
                    </h2>
                    <p class="text-xs text-white/85 max-w-2xl">
                        Kelola seluruh data halaman utama (<code class="font-mono bg-white/15 px-1.5 py-0.5 rounded">/</code>), kursus beserta skill badges, voucher, subscription peserta, dan log audit keamanan sistem dari satu tempat.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <a href="{{ route('lms.landing-cms.index') }}" class="px-4 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs inline-flex items-center gap-2 lms-transition">
                        <span>Kelola Halaman Home (/)</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="{{ route('home') }}" target="_blank" class="px-4 py-2.5 rounded-lg bg-white/15 hover:bg-white/25 text-white font-semibold text-xs border border-white/25 lms-transition">
                        Pratinjau Web ↗
                    </a>
                </div>
            </div>

            <!-- Telemetry Metrics Grid (Monospace Tabular Nums) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <span class="text-xs font-medium text-slate-500 block">Total Pengguna</span>
                    <span class="font-mono tabular-nums font-bold text-2xl text-slate-900 mt-1 block">{{ $stats['total_users'] }}</span>
                    <a href="{{ route('lms.users.index') }}" class="text-[11px] font-medium text-[#0E97A8] hover:underline mt-1.5 inline-block">Kelola akun →</a>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <span class="text-xs font-medium text-slate-500 block">Program Kursus</span>
                    <span class="font-mono tabular-nums font-bold text-2xl text-slate-900 mt-1 block">{{ $stats['total_courses'] }}</span>
                    <a href="{{ route('lms.courses.index') }}" class="text-[11px] font-medium text-[#0E97A8] hover:underline mt-1.5 inline-block">Atur modul &amp; skill →</a>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <span class="text-xs font-medium text-slate-500 block">Subscription Aktif</span>
                    <span class="font-mono tabular-nums font-bold text-2xl text-[#0E97A8] mt-1 block">{{ $stats['active_subscriptions'] }}</span>
                    <a href="{{ route('lms.subscriptions.index') }}" class="text-[11px] font-medium text-[#0E97A8] hover:underline mt-1.5 inline-block">Kelola paket →</a>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <span class="text-xs font-medium text-slate-500 block">Sertifikat Sah</span>
                    <span class="font-mono tabular-nums font-bold text-2xl text-slate-900 mt-1 block">{{ $stats['issued_certificates'] }}</span>
                    <a href="{{ route('lms.certificates.index') }}" class="text-[11px] font-medium text-[#0E97A8] hover:underline mt-1.5 inline-block">Audit sertifikat →</a>
                </div>
            </div>

            <!-- Landing CMS Quick Matrix + Leads & Audit -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left: Landing Page CMS Sections Shortcut -->
                <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="font-semibold text-base text-slate-900">Konten Dinamis Halaman Home (<code class="font-mono text-xs text-[#0E97A8]">/</code>)</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Ubah banner, webinar, kategori, skill badges kursus terlaris, lowongan, dan FAQ secara langsung.</p>
                        </div>
                        <a href="{{ route('lms.landing-cms.index') }}" class="text-xs font-semibold text-[#0E97A8] hover:underline shrink-0">Buka CMS →</a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        @foreach ([
                            ['key' => 'hero_slides', 'label' => 'Hero Carousel Banners', 'desc' => 'Banner promo utama & CTA'],
                            ['key' => 'what_you_learn', 'label' => 'Live Webinar & Jadwal', 'desc' => 'Jadwal webinar & instruktur'],
                            ['key' => 'bestseller_courses', 'label' => 'Kursus Terlaris + Skills', 'desc' => 'Kartu kursus & skill badges'],
                            ['key' => 'prakerja_section', 'label' => 'Pelatihan Prakerja', 'desc' => 'Kartu Prakerja & skill badges'],
                            ['key' => 'job_vacancies', 'label' => 'Lowongan Kerja Karier', 'desc' => 'Bursa karier mitra MM2100'],
                            ['key' => 'faq_items', 'label' => 'FAQ & Testimoni', 'desc' => 'Tanya jawab & ulasan alumni'],
                        ] as $cmsLink)
                            <a href="{{ route('lms.landing-cms.index', ['section' => $cmsLink['key']]) }}" class="p-3.5 rounded-lg border border-slate-200 hover:border-[#10A9BC] hover:bg-[#E7F6F8]/40 lms-transition flex items-center justify-between group">
                                <div>
                                    <span class="font-semibold text-slate-800 group-hover:text-[#0E97A8] block">{{ $cmsLink['label'] }}</span>
                                    <span class="text-[11px] text-slate-500">{{ $cmsLink['desc'] }}</span>
                                </div>
                                <span class="text-slate-400 group-hover:text-[#0E97A8]">→</span>
                            </a>
                        @endforeach
                    </div>

                    <!-- Recent Audit Trail -->
                    <div class="pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-semibold text-sm text-slate-900">Aktivitas &amp; Audit Trail Terkini</h4>
                            <a href="{{ route('lms.audit.index') }}" class="text-xs font-semibold text-[#0E97A8] hover:underline">Lihat semua →</a>
                        </div>
                        <div class="space-y-2">
                            @forelse ($recentActivities->take(4) as $log)
                                <div class="p-3 rounded-lg bg-slate-50 border border-slate-200/70 flex items-center justify-between gap-3 text-xs">
                                    <div class="min-w-0">
                                        <span class="font-mono text-[11px] font-semibold text-[#0E97A8] uppercase">{{ $log->action_label }}</span>
                                        <p class="text-slate-700 truncate">{{ $log->details }}</p>
                                    </div>
                                    <span class="font-mono text-[11px] text-slate-400 shrink-0">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 py-4 text-center">Belum ada aktivitas.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right: Leads & Quick Actions -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="font-semibold text-base text-slate-900">Leads / Permintaan Masuk</h3>
                                <p class="text-xs text-slate-500"><span class="font-mono font-bold text-amber-600">{{ $stats['pending_leads'] }}</span> permintaan menunggu tindak lanjut.</p>
                            </div>
                            <a href="{{ route('lms.leads.index') }}" class="text-xs font-semibold text-[#0E97A8] hover:underline">Kelola →</a>
                        </div>
                        <div class="space-y-2.5">
                            @forelse ($recentLeads as $lead)
                                <div class="p-3 rounded-lg bg-slate-50 border border-slate-200/70 text-xs space-y-1">
                                    <div class="flex justify-between items-center">
                                        <span class="font-semibold text-slate-900">{{ $lead->full_name }}</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-medium {{ $lead->status === 'new' ? 'bg-amber-100 text-amber-800' : 'bg-slate-200 text-slate-700' }}">
                                            {{ $lead->status_label }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] font-mono text-slate-500">{{ $lead->email ?? $lead->phone }}</p>
                                    <p class="text-[11px] text-[#0E97A8] font-medium truncate">{{ $lead->interest }}</p>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 py-4 text-center">Belum ada leads baru.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-3">
                        <h4 class="font-semibold text-sm text-slate-900">Aksi Cepat Manajemen</h4>
                        <div class="grid grid-cols-2 gap-2.5 text-xs">
                            <a href="{{ route('lms.courses.create') }}" class="px-3 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-center lms-transition">+ Tambah Kursus</a>
                            <a href="{{ route('lms.vouchers.index') }}" class="px-3 py-2.5 rounded-lg border border-slate-200 hover:border-[#10A9BC] text-slate-700 font-semibold text-center lms-transition">Buat Voucher</a>
                            <a href="{{ route('lms.assessments.index') }}" class="px-3 py-2.5 rounded-lg border border-slate-200 hover:border-[#10A9BC] text-slate-700 font-semibold text-center lms-transition">Review Tugas ({{ $stats['submissions_to_review'] }})</a>
                            <a href="{{ route('lms.settings.index') }}" class="px-3 py-2.5 rounded-lg border border-slate-200 hover:border-[#10A9BC] text-slate-700 font-semibold text-center lms-transition">Pengaturan Portal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @elseif (auth()->user()->isInstructor())
        <!-- Instructor Dashboard -->
        <div class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-5 rounded-xl bg-white border border-slate-200">
                    <span class="text-xs font-medium text-slate-500 block">Kursus Diampu</span>
                    <span class="font-mono tabular-nums font-bold text-3xl text-[#0E97A8] mt-1 block">{{ $stats['my_courses'] }}</span>
                </div>
                <div class="p-5 rounded-xl bg-white border border-slate-200">
                    <span class="text-xs font-medium text-slate-500 block">Peserta Terdaftar</span>
                    <span class="font-mono tabular-nums font-bold text-3xl text-slate-900 mt-1 block">{{ $stats['total_students'] }}</span>
                </div>
                <div class="p-5 rounded-xl bg-white border border-slate-200">
                    <span class="text-xs font-medium text-slate-500 block">Menunggu Penilaian</span>
                    <span class="font-mono tabular-nums font-bold text-3xl text-rose-600 mt-1 block">{{ $stats['pending_submissions'] }}</span>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-slate-200">
                <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="font-semibold text-base text-slate-900">Kursus yang Anda Kelola</h3>
                        <p class="text-xs text-slate-500">Kelola kurikulum, skill badges, modul video, dan kuis kelulusan.</p>
                    </div>
                    <a href="{{ route('lms.courses.create') }}" class="px-4 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs lms-transition">
                        + Tambah Kursus Baru
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4">
                    @foreach ($myCourses as $course)
                        <div class="p-5 rounded-xl border border-slate-200 bg-white hover:border-[#10A9BC] lms-transition flex flex-col justify-between">
                            <div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-medium bg-[#E7F6F8] text-[#0E97A8]">
                                    {{ $course->category }}
                                </span>
                                <h4 class="font-semibold text-base text-slate-900 mt-2 leading-snug line-clamp-2">{{ $course->title }}</h4>
                                <div class="flex items-center gap-3 text-xs text-slate-500 mt-3 font-mono tabular-nums">
                                    <span>{{ $course->enrollments_count }} Peserta</span>
                                    <span>&bull;</span>
                                    <span>{{ $course->materials_count }} Materi</span>
                                </div>
                            </div>
                            <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                                <a href="{{ route('lms.courses.edit', $course) }}" class="text-xs font-medium text-slate-600 hover:text-slate-900">Edit Info &amp; Skills</a>
                                <a href="{{ route('lms.materials.index') }}" class="text-xs font-semibold text-[#0E97A8] hover:underline">Kelola Modul →</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    @else
        <!-- Student Dashboard (100% Matching Figma Layar 1 / Html -> Body (5).svg) -->
        <div class="space-y-6">
            <!-- Top Row: Hero Progress Card (8 cols) + Perjalanan belajarmu (4 cols) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Hero Card matching Figma -->
                <div class="lg:col-span-8 bg-lms-hero rounded-xl p-6 text-white flex items-center justify-between gap-6 relative overflow-hidden">
                    <div class="space-y-3 relative z-10 max-w-lg">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/15 text-[10px] font-semibold uppercase tracking-wider">
                            <svg class="w-3 h-3 text-[#54D6D0]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            SELANGKAH LEBIH MAJU
                        </span>

                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight leading-snug">
                            Ide hebat dimulai dari <span class="text-[#54D6D0]">kompetensi yang terus diasah.</span>
                        </h2>

                        @if ($featuredEnrollment && $featuredEnrollment->course)
                            @php
                                $fcMatTotal = $featuredEnrollment->course->materials->count();
                                $fcMatDone = $featuredEnrollment->course->materials->filter(fn($m) => isset($completedMaterialIds[$m->id]))->count();
                            @endphp
                            <p class="text-xs text-white/80 font-mono tabular-nums">
                                {{ $featuredEnrollment->course->title }} &bull; Materi {{ $fcMatDone }} dari {{ $fcMatTotal }} &bull; {{ $featuredEnrollment->course->duration_hours }} Jam
                            </p>
                            <div class="pt-1">
                                <a href="{{ route('lms.classroom.show', $featuredEnrollment->course) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs lms-transition">
                                    <span>Lanjutkan belajar</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        @else
                            <p class="text-xs text-white/80">
                                Mulailah perjalanan belajarmu sekarang dan raih sertifikat kompetensi industri MM2100.
                            </p>
                            <div class="pt-1">
                                <a href="{{ route('lms.courses.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs lms-transition">
                                    <span>Mulai belajar</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Decorative Book & Chat Bubbles SVG matching Figma -->
                    <div class="hidden sm:flex items-center justify-center shrink-0">
                        <svg class="w-32 h-32 opacity-90" viewBox="0 0 128 128" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="28" y="84" width="72" height="16" rx="4" fill="#A7F3D0" fill-opacity="0.35"/>
                            <rect x="32" y="66" width="66" height="16" rx="4" fill="#0C3553" stroke="#54D6D0" stroke-width="1.5"/>
                            <rect x="38" y="48" width="58" height="16" rx="4" fill="#54D6D0" fill-opacity="0.85"/>
                            <rect x="22" y="20" width="48" height="28" rx="10" fill="white" fill-opacity="0.9"/>
                            <rect x="64" y="12" width="46" height="26" rx="10" fill="#A7F3D0" fill-opacity="0.95"/>
                        </svg>
                    </div>
                </div>

                <!-- Perjalanan belajarmu Card matching Figma -->
                @php
                    $circumference = 2 * 3.14159 * 36;
                    $dashOffset = $circumference - ($progressPercent / 100) * $circumference;
                @endphp
                <div class="lg:col-span-4 bg-white rounded-xl border border-slate-200 p-5 flex flex-col justify-between gap-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-base text-slate-900">Perjalanan belajarmu</h3>
                        <span class="w-8 h-8 rounded-lg bg-[#E7F6F8] text-[#0E97A8] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </span>
                    </div>

                    <div class="flex items-center gap-5">
                        <!-- Circular Donut Ring SVG -->
                        <div class="relative w-24 h-24 shrink-0">
                            <svg class="w-full h-full -rotate-90" viewBox="0 0 88 88">
                                <defs>
                                    <linearGradient id="ringGrad" x1="0" y1="0" x2="1" y2="1">
                                        <stop offset="0%" stop-color="#0C3553"/>
                                        <stop offset="100%" stop-color="#54D6D0"/>
                                    </linearGradient>
                                </defs>
                                <circle cx="44" cy="44" r="36" fill="none" stroke="#F1F5F9" stroke-width="8"/>
                                <circle cx="44" cy="44" r="36" fill="none" stroke="url(#ringGrad)" stroke-width="8" stroke-linecap="round" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $dashOffset }}"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                <span class="font-mono tabular-nums font-bold text-lg text-slate-900 leading-none">{{ $progressPercent }}%</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">selesai</span>
                            </div>
                        </div>

                        <div class="space-y-2 text-xs font-medium text-slate-700 tabular-nums">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#10A9BC]"></span>
                                <span><strong class="font-mono text-slate-900">{{ $completedMaterials }}/{{ $totalMaterials }}</strong> materi selesai</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#10A9BC]"></span>
                                <span><strong class="font-mono text-slate-900">{{ $completedModules }}/{{ $totalModules }}</strong> modul tuntas</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                <span><strong class="font-mono text-slate-900">{{ $completedQuizzes }}/{{ $totalQuizzes }}</strong> kuis dikerjakan</span>
                            </div>
                        </div>
                    </div>

                    <div class="px-3.5 py-2 rounded-lg bg-emerald-50 border border-emerald-200/70 text-emerald-800 text-xs font-medium flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>
                            {{ $progressPercent >= 100 ? 'Seluruh materi tuntas. Pertahankan prestasi!' : ($progressPercent >= 50 ? 'Separuh jalan. Pertahankan semangatmu!' : 'Langkah awal yang baik. Ayo lanjutkan!') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Middle Row: Progres Pembelajaran & Rekapan Terakhir Pembelajaran -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
                    <div class="w-10 h-10 rounded-xl bg-[#E7F6F8] text-[#0E97A8] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-base text-slate-900">Progres Pembelajaran</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            Memantau persentase penyelesaian kelas aktif Anda (<strong class="font-mono text-slate-800">{{ $enrollments->count() }} program terdaftar</strong>, <strong class="font-mono text-slate-800">{{ $certificates->count() }} sertifikat terbit</strong>).
                        </p>
                    </div>
                    <div class="space-y-2.5 pt-2">
                        @forelse ($enrollments->take(2) as $enr)
                            @php
                                $eTotal = $enr->course?->materials->count() ?? 0;
                                $eDone = $enr->course?->materials->filter(fn($m) => isset($completedMaterialIds[$m->id]))->count() ?? 0;
                                $ePct = $eTotal > 0 ? (int) round(($eDone / $eTotal) * 100) : 0;
                            @endphp
                            <div class="p-3 rounded-lg bg-slate-50 border border-slate-200/70 space-y-1.5">
                                <div class="flex justify-between text-xs">
                                    <span class="font-semibold text-slate-800 truncate pr-2">{{ $enr->course->title }}</span>
                                    <span class="font-mono font-semibold text-[#0E97A8] tabular-nums shrink-0">{{ $ePct }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-[#10A9BC] h-1.5 rounded-full" style="width: {{ $ePct }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400">Belum ada program yang diikuti saat ini.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#0E97A8] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-base text-slate-900">Rekapan Terakhir Pembelajaran</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            Riwayat materi terakhir yang diselesaikan dan status keanggotaan paket belajar Anda.
                        </p>
                    </div>
                    <div class="space-y-2.5 pt-2">
                        @if ($latestProgress && $latestProgress->material)
                            <div class="p-3 rounded-lg bg-slate-50 border border-slate-200/70 flex items-center justify-between gap-3 text-xs">
                                <div class="min-w-0">
                                    <span class="font-semibold text-slate-800 block truncate">{{ $latestProgress->material->title }}</span>
                                    <span class="text-[11px] text-slate-500 block truncate">{{ $latestProgress->material->course->title ?? '' }}</span>
                                    <span class="text-[10px] font-mono text-slate-400">Selesai pada {{ $latestProgress->completed_at->format('d M Y, H:i') }}</span>
                                </div>
                                <a href="{{ route('lms.classroom.show', $latestProgress->material->course_id) }}" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:border-[#10A9BC] text-[11px] font-semibold shrink-0">
                                    Buka
                                </a>
                            </div>
                        @else
                            <div class="p-3 rounded-lg bg-slate-50 border border-slate-200/70 text-xs text-slate-500">
                                Belum ada materi yang ditandai selesai. Mulai materi pertama di menu <strong>Modul</strong>.
                            </div>
                        @endif

                        <div class="p-3 rounded-lg bg-slate-50 border border-slate-200/70 flex items-center justify-between text-xs">
                            <span class="text-slate-600">Paket Layanan Aktif: <strong class="text-slate-900">{{ $activeSubscription?->plan?->name ?? 'Akses Standar' }}</strong></span>
                            <a href="{{ route('lms.subscriptions.index') }}" class="text-[#0E97A8] font-semibold hover:underline">Detail →</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Row: Materi untukmu (Left) & Uji pemahamanmu (Right) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Materi untukmu -->
                <div class="lg:col-span-6 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-1 h-4 rounded-full bg-[#10A9BC]"></span>
                            <h3 class="font-semibold text-base text-slate-900">Materi untukmu</h3>
                        </div>
                        <a href="{{ route('lms.courses.index') }}" class="text-xs font-semibold text-[#0E97A8] hover:underline">Semua materi →</a>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">
                        @forelse ($recommendedMaterials as $mat)
                            @php $matDone = isset($completedMaterialIds[$mat->id]); @endphp
                            <div class="p-4 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-lg {{ $matDone ? 'bg-emerald-50 text-emerald-600' : 'bg-[#E7F6F8] text-[#0E97A8]' }} flex items-center justify-center shrink-0">
                                        @if ($mat->content_type === 'link')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-sm text-slate-900 truncate">{{ $mat->title }}</p>
                                        <p class="text-[11px] text-slate-500 truncate">{{ $mat->course->title }} &bull; {{ $mat->content_type_label }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    @if ($matDone)
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-medium">Selesai</span>
                                        <a href="{{ route('lms.classroom.show', ['course' => $mat->course_id, 'material' => $mat->id]) }}" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg" title="Tinjau Materi">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </a>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-[11px] font-medium">Belum selesai</span>
                                        <a href="{{ route('lms.classroom.show', ['course' => $mat->course_id, 'material' => $mat->id]) }}" class="w-8 h-8 rounded-full bg-[#10A9BC] hover:bg-[#0E97A8] text-white flex items-center justify-center lms-transition" title="Buka Materi">
                                            <svg class="w-3.5 h-3.5 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-xs text-slate-400">
                                Daftarlah ke kursus di menu <strong>Modul</strong> untuk melihat materi pembelajaran Anda.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Uji pemahamanmu -->
                <div class="lg:col-span-6 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-1 h-4 rounded-full bg-[#10A9BC]"></span>
                            <h3 class="font-semibold text-base text-slate-900">Uji pemahamanmu</h3>
                        </div>
                        <a href="{{ route('lms.assessments.index') }}" class="text-xs font-semibold text-[#0E97A8] hover:underline">Semua kuis →</a>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">
                        @forelse ($upcomingQuizzes as $quiz)
                            @php $sub = $quiz->submissions->first(); @endphp
                            <div class="p-4 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-lg {{ $sub ? 'bg-emerald-50 text-emerald-600' : 'bg-[#E7F6F8] text-[#0E97A8]' }} flex items-center justify-center shrink-0">
                                        @if ($sub)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-sm text-slate-900 truncate">{{ $quiz->title }}</p>
                                        <p class="text-[11px] text-slate-500 truncate">
                                            {{ $quiz->course->title }} &bull;
                                            @if ($sub && $sub->score !== null)
                                                <span class="text-emerald-700 font-semibold font-mono">Selesai &bull; Nilai {{ number_format((float)$sub->score, 0) }}/100</span>
                                            @elseif ($sub)
                                                <span class="text-amber-700 font-medium">Dikirim &bull; Menunggu nilai</span>
                                            @else
                                                <span class="text-[#0E97A8] font-medium">Siap dikerjakan</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="shrink-0">
                                    @if ($sub)
                                        <a href="{{ route('lms.assessments.show', $quiz) }}" class="px-3.5 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs lms-transition">
                                            Lihat hasil
                                        </a>
                                    @else
                                        <a href="{{ route('lms.assessments.show', $quiz) }}" class="px-4 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs lms-transition">
                                            Mulai kuis
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-xs text-slate-400">
                                Belum ada kuis atau evaluasi aktif pada program yang Anda ikuti.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
