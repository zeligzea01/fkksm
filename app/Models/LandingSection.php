<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class LandingSection extends Model
{
    use HasFactory;

    protected $primaryKey = 'section_key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'section_key',
        'title',
        'is_active',
        'content',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'content' => 'array',
    ];

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function cacheKey(): string
    {
        return 'fkksm_landing_sections_all';
    }

    public static function clearCache(): void
    {
        Cache::forget(static::cacheKey());
        Cache::forget('fkksm_portal_stats');
        Cache::forget('fkksm_active_vouchers');
    }

    /**
     * Resolve an image path (either a full https:// internet URL or a relative local asset path) safely.
     */
    public static function resolveImageUrl(?string $path, string $fallback = 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=900&q=80'): string
    {
        $clean = trim((string) $path);
        if ($clean === '') {
            $clean = $fallback;
        }
        if (str_starts_with($clean, 'http://') || str_starts_with($clean, 'https://')) {
            return $clean;
        }
        return asset(ltrim($clean, '/'));
    }

    /**
     * Resolve an internal link or external URL so subdirectory deployments (e.g. /ffksm) work seamlessly.
     */
    public static function resolveUrl(?string $path, string $fallback = '#katalog-kursus'): string
    {
        $clean = trim((string) $path);
        if ($clean === '') {
            $clean = $fallback;
        }
        if ($clean === '' || str_starts_with($clean, '#') || str_starts_with($clean, 'http://') || str_starts_with($clean, 'https://')) {
            return $clean;
        }
        return url(ltrim($clean, '/'));
    }

    /**
     * Authoritative 100% FKKSM MM2100 default content for every configurable section on '/' (Home Upskill Page).
     * Uses curated high-resolution internet photography (Unsplash industrial/training photos) — zero Cakap images.
     */
    public static function defaults(): array
    {
        return [
            'hero_slides' => [
                'title' => 'Hero Banner Carousel',
                'is_active' => true,
                'content' => [
                    'slides' => [
                        [
                            'badge' => 'SERTIFIKASI KERJA RESMI 2026',
                            'headline' => 'KURSUS ONLINE & KELAS KETERAMPILAN KERJA FKKSM',
                            'subtitle' => 'Akselerasi keahlian praktisi manufaktur, manajemen HR, otomasi industri, dan keselamatan kerja (K3) bersama komite instruktur ahli kawasan MM2100.',
                            'cta_label' => 'Jelajahi Kelas',
                            'cta_url' => '#katalog-kursus',
                            'secondary_label' => 'Profile FKKSM',
                            'secondary_url' => '/profile/fkksm',
                            'image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1400&q=80',
                        ],
                        [
                            'badge' => 'CORPORATE INDUSTRIAL UPSKILL',
                            'headline' => 'PELATIHAN KORPORASI SESUAI KEBUTUHAN PABRIK',
                            'subtitle' => 'Kurikulum disesuaikan dengan kebutuhan in-house training perusahaan di kawasan industri MM2100 untuk meningkatkan efisiensi dan kepatuhan K3.',
                            'cta_label' => 'Konsultasi Program Perusahaan',
                            'cta_url' => '/hubungi',
                            'secondary_label' => '',
                            'secondary_url' => '',
                            'image' => 'https://images.unsplash.com/photo-1565514020179-026b92b84bb6?auto=format&fit=crop&w=1400&q=80',
                        ],
                        [
                            'badge' => 'PROGRAM VOKASI & MAGANG KERJA',
                            'headline' => 'KONSORSIUM SMK & LINK AND MATCH INDUSTRI',
                            'subtitle' => 'Menghubungkan lulusan vokasi siap pakai dengan kebutuhan rekrutmen ratusan tenant manufaktur di Kawasan MM2100.',
                            'cta_label' => 'Lihat Direktori Tenant MM2100',
                            'cta_url' => '/members',
                            'secondary_label' => '',
                            'secondary_url' => '',
                            'image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1400&q=80',
                        ],
                    ],
                ],
            ],
            'onboarding_banner' => [
                'title' => 'Banner Onboarding Peserta',
                'is_active' => true,
                'content' => [
                    'headline' => 'Dapatkan voucher eksklusif dan kursus terbaik sesuai minatmu.',
                    'subtitle' => 'Bergabung bersama ribuan profesional industri MM2100 sekarang juga!',
                    'cta_guest' => 'Daftar Sekarang',
                    'cta_auth' => 'Masuk Ruang Belajar',
                    'image' => '',
                ],
            ],
            'what_you_learn' => [
                'title' => 'Kursus Apa yang Ingin Kamu Pelajari?',
                'is_active' => true,
                'content' => [
                    'heading' => 'Kursus Apa yang Ingin Kamu Pelajari?',
                    'subtitle' => 'Pilih format belajar yang paling sesuai: webinar langsung bersama mentor industri atau belajar mandiri online.',
                    'webinars' => [
                        [
                            'schedule' => 'Besok | 15.30',
                            'discount_badge' => 'DISKON 100%',
                            'category' => 'K3 Industri • Live Webinar',
                            'title' => 'Audit SMK3 & Identifikasi Bahaya HIRADC Lini Produksi',
                            'tutor' => 'Ir. Hendra Wijaya, M.T. (Komite K3 MM2100)',
                            'tutor_initials' => 'HW',
                            'original_price' => 'Rp 259.000',
                            'price_label' => 'GRATIS',
                            'cta_label' => 'Ikut Gratis',
                            'image' => 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=800&q=80',
                            'is_live_soon' => true,
                        ],
                        [
                            'schedule' => '26 November 2026 | 15.30',
                            'discount_badge' => 'DISKON 100%',
                            'category' => 'Human Resources • Live Webinar',
                            'title' => 'Strategi Perundingan Bipartit & Penyusunan PKB Manufaktur',
                            'tutor' => 'Dra. Siti Nurhaliza, M.Psi. (HR Senior MM2100)',
                            'tutor_initials' => 'SN',
                            'original_price' => 'Rp 259.000',
                            'price_label' => 'GRATIS',
                            'cta_label' => 'Ikut Gratis',
                            'image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=800&q=80',
                            'is_live_soon' => false,
                        ],
                        [
                            'schedule' => '27 November 2026 | 15.30',
                            'discount_badge' => 'DISKON 100%',
                            'category' => 'Smart Factory • Live Webinar',
                            'title' => 'Optimasi Overall Equipment Effectiveness (OEE) & Sensor IIoT',
                            'tutor' => 'Rudi Hartono, S.T. (Lead Automation Engineer)',
                            'tutor_initials' => 'RH',
                            'original_price' => 'Rp 259.000',
                            'price_label' => 'GRATIS',
                            'cta_label' => 'Ikut Gratis',
                            'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80',
                            'is_live_soon' => false,
                        ],
                    ],
                ],
            ],
            'course_categories' => [
                'title' => 'Kategori Kursus',
                'is_active' => true,
                'content' => [
                    'heading' => 'Kategori Kursus',
                    'subtitle' => 'Jelajahi beragam program spesialisasi keahlian sesuai bidang minatmu di ekosistem FKKSM.',
                    'items' => [
                        ['name' => 'Keselamatan Kerja (K3)', 'category_param' => 'K3', 'theme' => 'sky', 'icon_key' => 'tech'],
                        ['name' => 'Teknologi & Otomasi', 'category_param' => 'Teknologi', 'theme' => 'indigo', 'icon_key' => 'tech'],
                        ['name' => 'Human Resources', 'category_param' => 'Human Resources', 'theme' => 'emerald', 'icon_key' => 'education'],
                        ['name' => 'Leadership & 5R', 'category_param' => 'Leadership', 'theme' => 'amber', 'icon_key' => 'business'],
                        ['name' => 'Bahasa Industri', 'category_param' => 'Bahasa', 'theme' => 'teal', 'icon_key' => 'language'],
                        ['name' => 'Karier & Vokasi SMK', 'category_param' => 'Karier', 'theme' => 'purple', 'icon_key' => 'career'],
                        ['name' => 'Manufaktur & QC', 'category_param' => 'Manufaktur', 'theme' => 'rose', 'icon_key' => 'media'],
                    ],
                ],
            ],
            'kids_teens_programs' => [
                'title' => 'Program Vokasi & Bahasa FKKSM',
                'is_active' => true,
                'content' => [
                    'heading' => 'Program Vokasi & Bahasa Industri untuk Pelajar & Calon Tenaga Kerja',
                    'subtitle' => 'Program pembelajaran bahasa industri dan kesiapan vokasi SMK/Politeknik terpadu binaan komite FKKSM MM2100.',
                    'programs' => [
                        [
                            'title' => 'FKKSM Digital Vocational Hub (Kelas Daring)',
                            'mode_badge' => 'Online',
                            'mode_theme' => 'blue',
                            'meta' => 'Pelajar SMK, Mahasiswa & Calon Tenaga Kerja',
                            'description' => 'Kelas interaktif live mentoring daring dengan kurikulum budaya kerja industri, K3 dasar, bahasa Jepang/Inggris pabrik, dan persiapan prakerin kawasan MM2100.',
                            'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80',
                            'cta_label' => 'Lihat Detail',
                            'cta_url' => '/lms/courses?category=Bahasa',
                        ],
                        [
                            'title' => 'FKKSM Industrial Training Center (Tatap Muka)',
                            'mode_badge' => 'Offline',
                            'mode_theme' => 'amber',
                            'meta' => 'Sekretariat & Workshop Kawasan MM2100 Cikarang',
                            'description' => 'Pusat pelatihan tatap muka langsung di Cikarang Barat dengan laboratorium simulasi otomasi, praktik APAR & K3, serta pembinaan kompetensi bersertifikat.',
                            'image' => 'https://images.unsplash.com/photo-1581092335397-9583fe92d232?auto=format&fit=crop&w=900&q=80',
                            'cta_label' => 'Lihat Detail',
                            'cta_url' => '/lms/courses',
                        ],
                    ],
                ],
            ],
            'voucher_banner' => [
                'title' => 'Banner Tukar Kode Belajar',
                'is_active' => true,
                'content' => [
                    'heading' => 'Punya kode belajar dari perusahaan atau sponsor?',
                    'description' => 'Tukar kode belajarmu di sini untuk membuka akses materi pelatihan dan kelas sertifikasi resmi FKKSM.',
                    'cta_label' => 'Tukar Kode',
                    'image' => '',
                ],
            ],
            'bestseller_courses' => [
                'title' => 'Kursus Terlaris (Dengan Skill Badges)',
                'is_active' => true,
                'content' => [
                    'heading' => 'Kursus Terlaris',
                    'subtitle' => 'Program pelatihan vokasi dan keterampilan kerja dengan peminat terbanyak di FKKSM Upskill.',
                    'items' => [
                        [
                            'title' => 'Standar K3 Industri & Keselamatan Kerja Kawasan MM2100',
                            'category' => 'Keselamatan Kerja (K3)',
                            'rating' => '4.9',
                            'reviews' => '(16.766)',
                            'price' => 'GRATIS',
                            'thumbnail' => 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=700&q=80',
                            'badges' => [
                                ['icon' => '1765793972955_1772123099.png', 'name' => 'Safety Discovery & Risk Assessment'],
                                ['icon' => '1765793978027_1027588230.png', 'name' => 'Emergency Reliability & K3'],
                            ],
                        ],
                        [
                            'title' => 'Manajemen Hubungan Industrial & Regulasi Ketenagakerjaan 2026',
                            'category' => 'Human Resources',
                            'rating' => '5.0',
                            'reviews' => '(14.815)',
                            'price' => 'GRATIS',
                            'thumbnail' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=700&q=80',
                            'badges' => [
                                ['icon' => '1765179223375_166389665.png', 'name' => 'Industrial Law Compliance'],
                                ['icon' => '1765794082625_1289476765.png', 'name' => 'Bipartite Negotiation & PKB'],
                            ],
                        ],
                        [
                            'title' => 'Transformasi Smart Factory & Otomasi SDM Industri 4.0',
                            'category' => 'Teknologi & Otomasi',
                            'rating' => '4.9',
                            'reviews' => '(8.215)',
                            'price' => 'GRATIS',
                            'thumbnail' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=700&q=80',
                            'badges' => [
                                ['icon' => '1765793978027_1027588230.png', 'name' => 'Smart Manufacturing & OEE'],
                            ],
                        ],
                        [
                            'title' => 'Pelatihan Kepemimpinan Foreperson & Supervisor Manufaktur',
                            'category' => 'Leadership & 5R',
                            'rating' => '5.0',
                            'reviews' => '(4.980)',
                            'price' => 'Rp 350.000',
                            'thumbnail' => 'https://images.unsplash.com/photo-1581092335397-9583fe92d232?auto=format&fit=crop&w=700&q=80',
                            'badges' => [
                                ['icon' => '1765179223375_166389665.png', 'name' => 'Shopfloor Leadership & Kaizen'],
                                ['icon' => '1765270277280_1142546686.png', 'name' => '5S / 5R Audit Management'],
                            ],
                        ],
                        [
                            'title' => 'Bahasa Jepang Komunikasi Industri & Budaya Kerja Ho-Ren-So',
                            'category' => 'Bahasa Industri',
                            'rating' => '5.0',
                            'reviews' => '(3.150)',
                            'price' => 'Rp 420.000',
                            'thumbnail' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=700&q=80',
                            'badges' => [
                                ['icon' => '1763708650958_2074870672.png', 'name' => 'Industrial Japanese N5'],
                                ['icon' => '1763708702867_1074007178.png', 'name' => 'Ho-Ren-So Workplace Ethics'],
                            ],
                        ],
                    ],
                ],
            ],
            'job_vacancies' => [
                'title' => 'Lowongan Pekerjaan Bursa Karier FKKSM',
                'is_active' => true,
                'content' => [
                    'heading' => 'Lowongan Pekerjaan Terbaru di Bursa Karier FKKSM',
                    'subtitle' => 'Peluang karier langsung bersama tenant & mitra industri MM2100. Lamar dengan sertifikat kompetensimu.',
                    'jobs' => [
                        ['title' => 'HSE & K3 Supervisor', 'company' => 'PT Otomotif Nusantara MM2100', 'initial' => 'ON', 'type' => 'Full-Time', 'location' => 'Cikarang Barat, Bekasi', 'time' => '3 jam lalu'],
                        ['title' => 'Industrial Relations Specialist', 'company' => 'PT Elektronik Presisi Indonesia', 'initial' => 'EP', 'type' => 'Full-Time', 'location' => 'Kawasan MM2100, Bekasi', 'time' => '6 jam lalu'],
                        ['title' => 'PLC & Automation Engineer', 'company' => 'PT Manufaktur Komponen Baja', 'initial' => 'MK', 'type' => 'Full-Time', 'location' => 'Kawasan MM2100, Bekasi', 'time' => '12 jam lalu'],
                        ['title' => 'QC & Production Foreperson', 'company' => 'PT Logistik Terpadu MM2100', 'initial' => 'LT', 'type' => 'Full-Time', 'location' => 'Cikarang Barat, Bekasi', 'time' => '1 hari lalu'],
                    ],
                ],
            ],
            'prakerja_section' => [
                'title' => 'Pelatihan Vokasi & Subsidi Kompetensi FKKSM',
                'is_active' => true,
                'content' => [
                    'heading' => 'Program Sertifikasi Vokasi & Subsidi Kompetensi FKKSM',
                    'subtitle' => 'Gunakan voucher subsidi perusahaan anggota FKKSM untuk mengambil kelas keterampilan kerja bersertifikat resmi.',
                    'banner_heading' => 'Tukar Kode Voucher Subsidi Pelatihan FKKSM di Sini',
                    'banner_description' => 'Masukkan kode kupon resmi dari HRD perusahaan atau sekretariat FKKSM Anda untuk langsung mengaktifkan kelas.',
                    'items' => [
                        [
                            'title' => 'Manajemen Mutu ISO 9001 & Seven QC Tools Manufaktur',
                            'category' => 'Quality Control',
                            'price' => 'Rp 450.000',
                            'rating' => '4.9 (512)',
                            'thumbnail' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=700&q=80',
                            'badges' => [
                                ['icon' => '1765793972955_1772123099.png', 'name' => 'ISO 9001 Quality Management'],
                                ['icon' => '1765793978027_1027588230.png', 'name' => 'Seven QC Tools Analysis'],
                            ],
                        ],
                        [
                            'title' => 'Bahasa Inggris Korespondensi & Administrasi Pabrik PMA',
                            'category' => 'Bahasa & Administrasi',
                            'price' => 'Rp 380.000',
                            'rating' => '4.9 (339)',
                            'thumbnail' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=700&q=80',
                            'badges' => [
                                ['icon' => '1766373528567_723602149.png', 'name' => 'Workplace Communication (English)'],
                                ['icon' => '1766375618222_589836390.png', 'name' => 'Industrial Administration Skills'],
                            ],
                        ],
                        [
                            'title' => 'Manajemen Pergudangan Modern, Forklift Safety & Supply Chain',
                            'category' => 'Logistik & Gudang',
                            'price' => 'Rp 490.000',
                            'rating' => '4.8 (410)',
                            'thumbnail' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=700&q=80',
                            'badges' => [
                                ['icon' => '1765780573638_1844925480.png', 'name' => 'Warehouse & Logistics Safety'],
                                ['icon' => '1765780617687_734637452.png', 'name' => 'Supply Chain Efficiency'],
                            ],
                        ],
                        [
                            'title' => 'Pemeliharaan Mesin Preventif (Total Productive Maintenance)',
                            'category' => 'Engineering & TPM',
                            'price' => 'Rp 520.000',
                            'rating' => '5.0 (285)',
                            'thumbnail' => 'https://images.unsplash.com/photo-1565514020179-026b92b84bb6?auto=format&fit=crop&w=700&q=80',
                            'badges' => [
                                ['icon' => '1765780691003_1299763240.png', 'name' => 'Preventive Maintenance (TPM)'],
                                ['icon' => '1765769136090_613011060.png', 'name' => 'Equipment Reliability'],
                            ],
                        ],
                    ],
                ],
            ],
            'b2b_corporate' => [
                'title' => 'Solusi Pelatihan Korporasi B2B',
                'is_active' => true,
                'content' => [
                    'badge' => 'FKKSM FOR CORPORATE & FACTORY',
                    'headline' => 'SOLUSI PELATIHAN KHUSUS PABRIK & KARYAWAN TENANT MM2100',
                    'description' => 'Perusahaan Anda memerlukan pelatihan in-house bersertifikasi K3, Refreshment K3 Kebakaran Pabrik, Audit Ergonomi Manufaktur, atau Pendampingan Perjanjian Kerja Bersama (PKB)? FKKSM menghadirkan trainer praktisi industri berpengalaman langsung ke lokasi perusahaan Anda.',
                    'cta_label' => 'Ajukan Proposal Training Pabrik',
                    'phone' => '(021) 898-2100 / ext. 104',
                    'email' => 'sekretariat@fkksm-mm2100.or.id',
                ],
            ],
            'testimonials' => [
                'title' => 'Testimoni Alumni & Praktisi',
                'is_active' => true,
                'content' => [
                    'badge' => 'ULASAN ALUMNI & PRAKTISI',
                    'heading' => 'Apa Kata Rekan Praktisi HR & K3 MM2100?',
                    'subtitle' => 'Testimoni nyata dari manajer HR, supervisor K3, dan teknisi manufaktur yang telah menuntaskan program sertifikasi FKKSM.',
                    'items' => [
                        [
                            'quote' => 'Pelatihan Ahli K3 Umum di FKKSM sangat aplikatif. Studi kasusnya diangkat langsung dari insiden riil pabrik di MM2100, bukan sekadar teori buku. Sertifikasi digitalnya juga mudah diverifikasi oleh kantor pusat di Jepang.',
                            'name' => 'Bambang Ardianto',
                            'role' => 'HSE Superintendent — PT Otomotif Tier-1 MM2100',
                            'initials' => 'BA',
                        ],
                        [
                            'quote' => 'Program Hubungan Industrial & Bipartit memberi kami strategi mediasi perselisihan kerja yang konstruktif. Terbukti efektif menjaga keharmonisan serikat pekerja dan manajemen operasional pabrik selama 3 tahun terakhir.',
                            'name' => 'Siti Rahmawati, S.Psi.',
                            'role' => 'HR Senior Manager — Tenant Manufaktur Elektronik',
                            'initials' => 'SR',
                        ],
                        [
                            'quote' => 'Melalui voucher program FKKSM, teknisi lini perakitan kami dapat mempelajari modul PLC dan Lean Kaizen secara gratis. Kualitas materi dan platform LMS-nya sangat intuitif dan mudah diakses dari ponsel.',
                            'name' => 'Fajar Hidayat',
                            'role' => 'Engineering Section Head — Precision Tools Factory',
                            'initials' => 'FH',
                        ],
                    ],
                ],
            ],
            'faq_items' => [
                'title' => 'Pertanyaan Umum (FAQ)',
                'is_active' => true,
                'content' => [
                    'badge' => 'PERTANYAAN UMUM',
                    'heading' => 'Pertanyaan yang Sering Diajukan (FAQ)',
                    'subtitle' => 'Informasi lengkap seputar mekanisme pendaftaran, subsidi voucher, dan keabsahan sertifikat kompetensi FKKSM.',
                    'items' => [
                        [
                            'question' => 'Apakah sertifikat yang diterbitkan resmi dan diakui perusahaan di MM2100?',
                            'answer' => 'Ya. Sertifikat FKKSM diterbitkan berdasarkan kurikulum SKKNI dan pengawasan Komite Pembina Kawasan MM2100. Dokumen dilengkapi nomor seri unik dan QR Code yang dapat diverifikasi secara publik kapan saja melalui halaman verifikasi resmi portal FKKSM.',
                        ],
                        [
                            'question' => 'Bagaimana cara menggunakan voucher subsidi pelatihan (misal: FKKSM2026)?',
                            'answer' => 'Klik tombol "Tukar Kode" di bagian atas halaman atau masukkan kode voucher pada form penukaran di halaman Voucher LMS. Jika Anda belum masuk, sistem akan meminta Anda login terlebih dahulu untuk merekam kupon subsidi ke akun Anda.',
                        ],
                        [
                            'question' => 'Bisakah perusahaan memesan pelatihan in-house khusus di lokasi pabrik?',
                            'answer' => 'Sangat bisa. FKKSM menyediakan program pelatihan in-company khusus bagi tenant kawasan industri MM2100. Trainer dan asesor kami akan hadir langsung ke fasilitas pabrik Anda sesuai jadwal dan modul yang disepakati.',
                        ],
                        [
                            'question' => 'Di mana saya dapat melihat profil lengkap organisasi FKKSM MM2100?',
                            'answer' => 'Anda dapat mengakses halaman profile resmi FKKSM melalui menu navigasi atas dengan mengklik menu "Profile" atau membuka tautan Profile FKKSM MM2100.',
                        ],
                    ],
                ],
            ],
            'seo_content' => [
                'title' => 'Konten Informasi & SEO Homepage',
                'is_active' => true,
                'content' => [
                    'heading' => 'Belajar Kapan Saja, Di Mana Saja bersama FKKSM Upskill — Kursus Online Bersertifikat',
                    'paragraph_1' => 'Kursus online bersertifikat yang diselenggarakan oleh FKKSM Upskill MM2100 memberikan fleksibilitas dan kenyamanan bagi praktisi industri, pencari kerja, dan pelajar vokasi untuk meningkatkan keterampilan kerja dari mana saja. Dengan akses ke berbagai modul berbasis standar SKKNI, peserta dapat belajar melalui video interaktif, bahan ajar terstruktur, dan evaluasi langsung bersama instruktur berpengalaman.',
                    'paragraph_2' => 'Keunggulan utama platform pembelajaran FKKSM MM2100 adalah kurikulum berbasis kompetensi kerja nyata, sertifikat resmi yang dapat diverifikasi publik, serta integrasi langsung dengan bursa karier ratusan tenant industri di kawasan MM2100.',
                ],
            ],
        ];
    }

    /**
     * Fetch all sections merged cleanly with authoritative defaults and cached.
     */
    public static function getAllForHome(): array
    {
        return Cache::remember(static::cacheKey(), 3600, function () {
            $defaults = static::defaults();
            if (!Schema::hasTable('landing_sections')) {
                return $defaults;
            }

            $stored = static::all()->keyBy('section_key');
            $merged = [];

            foreach ($defaults as $key => $def) {
                if (isset($stored[$key])) {
                    $row = $stored[$key];
                    $merged[$key] = [
                        'title' => $row->title,
                        'is_active' => (bool) $row->is_active,
                        'content' => array_merge($def['content'], is_array($row->content) ? $row->content : []),
                        'updated_at' => $row->updated_at,
                    ];
                } else {
                    $merged[$key] = $def + ['updated_at' => null];
                }
            }

            return $merged;
        });
    }

    public static function saveSection(string $key, string $title, bool $isActive, array $content, ?int $userId = null): self
    {
        $record = static::updateOrCreate(
            ['section_key' => $key],
            [
                'title' => $title,
                'is_active' => $isActive,
                'content' => $content,
                'updated_by' => $userId,
            ]
        );

        static::clearCache();

        return $record;
    }
}
