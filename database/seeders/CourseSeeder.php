<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\AssessmentSubmission;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Enrollment;
use App\Models\Material;
use App\Models\User;
use App\Models\UserProgress;
use App\Support\SecurityHelper;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Seed courses, modules, 4-stage sequential materials (Video -> Materi -> Latihan Soal -> Latihan Akhir),
     * enrollments, progress, and certificates idempotently.
     */
    public function run(): void
    {
        $sampleVideoPath = SecurityHelper::ensureSampleVideoExists();

        $instructor1 = User::where('email', 'hendra@fkksm.local')->first() ?? User::first();
        $instructor2 = User::where('email', 'siti.hr@fkksm.local')->first() ?? $instructor1;
        $instructor3 = User::where('email', 'rudi.otomasi@fkksm.local')->first() ?? $instructor1;
        $student1 = User::where('email', 'peserta@fkksm.local')->first();
        $student2 = User::where('email', 'dewi@fkksm.local')->first();

        /*
        |--------------------------------------------------------------------------
        | COURSE 1: Standar K3 Industri & Keselamatan Kerja Kawasan MM2100
        |--------------------------------------------------------------------------
        */
        $course1 = Course::updateOrCreate(
            ['slug' => 'standar-k3-industri-keselamatan-kerja-mm2100'],
            [
                'title' => 'Standar K3 Industri & Keselamatan Kerja Kawasan MM2100',
                'category' => 'Keselamatan Kerja (K3)',
                'summary' => 'Panduan komprehensif implementasi Sistem Manajemen K3 (SMK3), identifikasi potensi bahaya risiko pabrik manufaktur, dan protokol tanggap darurat di kawasan industri modern.',
                'description' => "Kursus ini dirancang khusus untuk para praktisi industri, teknisi, supervisor, dan staf operasional pabrik di Kawasan Industri MM2100. Peserta akan mempelajari regulasi K3 nasional, identifikasi bahaya kerja (HIRADC), tata cara tanggap darurat kebakaran dan kecelakaan kerja, serta budaya zero-accident di lingkungan manufaktur.",
                'requirements' => 'Terbuka untuk seluruh staf, operator, dan praktisi kawasan industri.',
                'instructor_id' => $instructor1?->id,
                'duration_hours' => 4.5,
                'access_type' => 'free',
                'price' => 0,
                'original_price' => 500000,
                'status' => 'published',
                'featured_section' => 'bestseller',
                'rating' => 4.9,
                'reviews_count' => 16766,
                'thumbnail' => 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=800&q=80',
                'skill_badges' => [
                    ['icon' => '1765793972955_1772123099.png', 'name' => 'Safety Discovery & Risk Assessment'],
                    ['icon' => '1765793978027_1027588230.png', 'name' => 'Emergency Reliability & K3'],
                ],
            ]
        );

        $mod1_1 = CourseModule::updateOrCreate(
            ['course_id' => $course1->id, 'title' => 'Landasan Hukum & Budaya K3 Manufaktur'],
            [
                'summary' => 'Mengenal UU No. 1 Tahun 1970, regulasi SMK3 PP No. 50 Tahun 2012, dan identifikasi bahaya HIRADC.',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $c1_m1_video = Material::updateOrCreate(
            ['course_id' => $course1->id, 'title' => 'Video: Pengenalan Budaya K3 & Zero Accident Kawasan MM2100'],
            [
                'module_id' => $mod1_1->id,
                'content_type' => 'video',
                'video_path' => $sampleVideoPath,
                'duration_minutes' => 8,
                'content_text' => "Dalam video pengantar ini, Ir. Hendra Wijaya memaparkan penerapan budaya K3 (Keselamatan dan Kesehatan Kerja) di lini produksi manufaktur Kawasan Industri MM2100.\n\nFokus utama video:\n• Hierarki pengendalian risiko (Eliminasi, Substitusi, Rekayasa Teknik, Administratif, APD)\n• Peran aktif Panitia Pembina Keselamatan dan Kesehatan Kerja (P2K3)\n• Standar pemeriksaan APD sebelum memasuki zona mesin berputar.",
                'sort_order' => 1,
                'is_published' => true,
            ]
        );

        $c1_m1_materi = Material::updateOrCreate(
            ['course_id' => $course1->id, 'title' => 'E-Book: Dasar Hukum K3 & Penyusunan Matriks Risiko HIRADC'],
            [
                'module_id' => $mod1_1->id,
                'content_type' => 'ebook',
                'duration_minutes' => 12,
                'content_text' => "BAB 1: LANDASAN HUKUM & KEWAJIBAN SMK3 MANUFAKTUR\n\nKeselamatan dan Kesehatan Kerja (K3) diatur dalam UU No. 1 Tahun 1970 dan PP No. 50 Tahun 2012 tentang Penerapan SMK3. Setiap perusahaan di Kawasan Industri MM2100 wajib menjamin keselamatan seluruh personel dan tamu pabrik.\n\n1. Penyediaan Alat Pelindung Diri (APD) standar SNI/ISO yang sesuai risiko spesifik (helm safety, sepatu safety toe-cap baja, pelindung pendengaran ear-plug di area >85 dB).\n2. Penyusunan Job Safety Analysis (JSA) dan HIRADC (Hazard Identification, Risk Assessment, and Determining Control) pada setiap stasiun kerja.\n3. Pemasangan Lockout Tagout (LOTO) saat teknisi melakukan perawatan berkala pada panel kelistrikan maupun mesin hidrolik.\n4. Pemisahan jalur pejalan kaki (pedestrian green-walkway) dengan jalur lintasan forklift di area gudang dan loading dock.",
                'sort_order' => 2,
                'is_published' => true,
            ]
        );

        $c1_m1_latihan = Material::updateOrCreate(
            ['course_id' => $course1->id, 'title' => 'Latihan Soal Modul 1: Regulasi SMK3 & Pengendalian Bahaya'],
            [
                'module_id' => $mod1_1->id,
                'content_type' => 'practice',
                'duration_minutes' => 10,
                'content_text' => 'Kerjakan latihan soal Modul 1 berikut untuk menguji pemahaman Anda tentang dasar hukum SMK3 dan hierarki pengendalian bahaya sebelum melanjutkan ke Modul 2.',
                'quiz_payload' => [
                    [
                        'question' => 'Langkah paling efektif pada urutan pertama dalam Hierarki Pengendalian Risiko K3 di pabrik manufaktur adalah...',
                        'options' => [
                            'Eliminasi sumber bahaya secara langsung dari proses kerja',
                            'Pembagian Alat Pelindung Diri (APD) kepada operator',
                            'Pemasangan poster himbauan keselamatan di dinding pabrik',
                            'Rotasi jadwal shift kerja operator',
                        ],
                        'answer' => 0,
                    ],
                    [
                        'question' => 'Prosedur penguncian sumber energi listrik/mekanik sebelum teknisi melakukan perbaikan mesin disebut...',
                        'options' => [
                            'First Aid Response (P3K)',
                            'Lockout Tagout (LOTO)',
                            'Material Safety Data Sheet (MSDS)',
                            ' Poka-Yoke Inspection',
                        ],
                        'answer' => 1,
                    ],
                ],
                'sort_order' => 3,
                'is_published' => true,
            ]
        );

        $mod1_2 = CourseModule::updateOrCreate(
            ['course_id' => $course1->id, 'title' => 'Tanggap Darurat & Evakuasi Pabrik'],
            [
                'summary' => 'SOP jalur evakuasi, titik kumpul (assembly point), dan teknik pemadaman dengan APAR.',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        $c1_m2_video = Material::updateOrCreate(
            ['course_id' => $course1->id, 'title' => 'Video: Simulasi Evakuasi Kebakaran & Penggunaan APAR Metode PASS'],
            [
                'module_id' => $mod1_2->id,
                'content_type' => 'video',
                'video_path' => $sampleVideoPath,
                'duration_minutes' => 9,
                'content_text' => "Video simulasi tanggap darurat di fasilitas pabrik MM2100:\n• Prosedur penghentian darurat (Emergency Stop) saat alarm berbunyi\n• Teknik penggunaan APAR dengan metode PASS (Pull, Aim, Squeeze, Sweep)\n• Koordinasi Floor Warden di Titik Kumpul (Assembly Point).",
                'sort_order' => 4,
                'is_published' => true,
            ]
        );

        $c1_m2_materi = Material::updateOrCreate(
            ['course_id' => $course1->id, 'title' => 'E-Book: Prosedur Operasi Standar Tanggap Darurat & P3K'],
            [
                'module_id' => $mod1_2->id,
                'content_type' => 'ebook',
                'duration_minutes' => 10,
                'content_text' => "BAB 2: PROTOKOL TANGGAP DARURAT FASILITAS PABRIK\n\nSaat sirine alarm bahaya berbunyi di fasilitas pabrik:\n1. Jangan panik. Tekan tombol Emergency Stop dan matikan saklar daya mesin bila aman dilakukan.\n2. Tinggalkan seluruh barang bawaan pribadi yang dapat menghambat kelancaran evakuasi.\n3. Ikuti rambu jalur evakuasi berwarna hijau menuju pintu keluar darurat (Emergency Exit) terdekat.\n4. Berjalan cepat (tidak berlari atau saling mendorong) menuju Titik Kumpul (Assembly Point) dan laporkan kehadiran kepada Floor Warden untuk penghitungan personel (headcount).\n5. Dilarang kembali ke dalam gedung hingga Komandan Tanggap Darurat (ERT Commander) menyatakan kondisi aman (All Clear).",
                'sort_order' => 5,
                'is_published' => true,
            ]
        );

        $c1_m2_latihan = Material::updateOrCreate(
            ['course_id' => $course1->id, 'title' => 'Latihan Soal Modul 2: Prosedur Evakuasi & Penggunaan APAR'],
            [
                'module_id' => $mod1_2->id,
                'content_type' => 'practice',
                'duration_minutes' => 10,
                'content_text' => 'Selesaikan latihan soal Modul 2 ini untuk membuka akses ke Latihan Akhir (Penilaian Akhir) kursus.',
                'quiz_payload' => [
                    [
                        'question' => 'Apa arti tahapan "PASS" yang benar dalam penggunaan Alat Pemadam Api Ringan (APAR)?',
                        'options' => [
                            'Pull (Tarik pin), Aim (Arahkan ke pangkal api), Squeeze (Tekan tuas), Sweep (Sapukan dari sisi ke sisi)',
                            'Push (Dorong), Alert (Bunyikan alarm), Spray (Semprot ke atas asap), Stop (Berhenti)',
                            'Prepare (Siapkan), Aim (Arahkan ke ujung asap), Shake (Kocok tabung), Sweep (Sapukan)',
                            'Pull (Tarik selang), Activate (Aktifkan sirine), Shoot (Tembakkan), Save (Simpan)',
                        ],
                        'answer' => 0,
                    ],
                    [
                        'question' => 'Ke mana seluruh karyawan wajib segera berkumpul setelah keluar melalui pintu darurat saat terjadi keadaan darurat?',
                        'options' => [
                            'Area parkir kendaraan pribadi',
                            'Titik Kumpul (Assembly Point) untuk dilakukan headcount oleh Floor Warden',
                            'Ruang lobi resepsionis utama',
                            'Gudang logistik bahan baku',
                        ],
                        'answer' => 1,
                    ],
                ],
                'sort_order' => 6,
                'is_published' => true,
            ]
        );

        $assess1 = Assessment::updateOrCreate(
            ['course_id' => $course1->id, 'title' => 'Latihan Akhir: Uji Kompetensi K3 & Prosedur Evakuasi Pabrik'],
            [
                'assessment_type' => 'quiz',
                'instructions' => 'Kerjakan seluruh soal evaluasi komprehensif berikut dengan batas nilai kelulusan minimal 70% untuk menerbitkan Sertifikat Kompetensi K3 FKKSM MM2100.',
                'passing_score' => 70,
                'quiz_payload' => [
                    [
                        'question' => 'Regulasi pemerintah Indonesia yang mengatur pedoman penerapan Sistem Manajemen Keselamatan dan Kesehatan Kerja (SMK3) adalah...',
                        'options' => [
                            'PP No. 50 Tahun 2012',
                            'PP No. 10 Tahun 2000',
                            'Permenaker No. 99 Tahun 1990',
                            'UU Hak Cipta Industri',
                        ],
                        'answer' => 0,
                    ],
                    [
                        'question' => 'Ketika menemukan tumpahan bahan kimia berbahaya (B3) di lantai produksi, dokumen acuan utama yang wajib dilihat untuk penanganan yang tepat adalah...',
                        'options' => [
                            'Jadwal Cuti Tahunan Karyawan',
                            'Material Safety Data Sheet (MSDS) / Lembar Data Keselamatan Bahan',
                            'Daftar Inventaris Meja Kantor',
                            'Formulir Klaim Lembur',
                        ],
                        'answer' => 1,
                    ],
                    [
                        'question' => 'Siapakah yang berwenang mengizinkan pekerja kembali masuk ke gedung pabrik setelah proses evakuasi kebakaran selesai?',
                        'options' => [
                            'Rekan kerja satu shift',
                            'Komandan Tanggap Darurat (ERT Commander) setelah menyatakan situasi All Clear',
                            'Petugas pengiriman barang ekspedisi',
                            'Setiap operator secara masing-masing',
                        ],
                        'answer' => 1,
                    ],
                ],
                'is_published' => true,
                'due_at' => now()->addDays(30),
            ]
        );

        // Clean up any legacy material rows on course 1 that aren't part of the 6 sequential items
        Material::where('course_id', $course1->id)
            ->whereNotIn('id', [
                $c1_m1_video->id,
                $c1_m1_materi->id,
                $c1_m1_latihan->id,
                $c1_m2_video->id,
                $c1_m2_materi->id,
                $c1_m2_latihan->id,
            ])->delete();

        /*
        |--------------------------------------------------------------------------
        | COURSE 2: Manajemen Hubungan Industrial & Regulasi Ketenagakerjaan 2026
        |--------------------------------------------------------------------------
        */
        $course2 = Course::updateOrCreate(
            ['slug' => 'manajemen-hubungan-industrial-regulasi-ketenagakerjaan-2026'],
            [
                'title' => 'Manajemen Hubungan Industrial & Regulasi Ketenagakerjaan 2026',
                'category' => 'Human Resources',
                'summary' => 'Strategi pengelolaan hubungan industrial yang harmonis, pemahaman PP/PKB, penanganan perselisihan bipartit, dan kepatuhan norma ketenagakerjaan terkini.',
                'description' => 'Membekali praktisi HRD kawasan industri MM2100 dengan pemahaman regulasi perburuhan terkini, teknik negosiasi dengan serikat pekerja, serta mekanisme bipartit yang adil demi stabilitas iklim usaha dan produktivitas karyawan.',
                'requirements' => 'Ditujukan bagi praktisi HRD, Legal Industrial, dan perwakilan Serikat Pekerja.',
                'instructor_id' => $instructor2?->id,
                'duration_hours' => 6.0,
                'access_type' => 'free',
                'price' => 0,
                'original_price' => 450000,
                'status' => 'published',
                'featured_section' => 'bestseller',
                'rating' => 4.8,
                'reviews_count' => 8420,
                'thumbnail' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=800&q=80',
                'skill_badges' => [
                    ['icon' => '1765179223375_166389665.png', 'name' => 'Industrial Law Compliance'],
                ],
            ]
        );

        $mod2_1 = CourseModule::updateOrCreate(
            ['course_id' => $course2->id, 'title' => 'Bipartit Efektif & Penyusunan PKB'],
            [
                'summary' => 'Teknik dialog sosial LKS Bipartit dan penyusunan klausul Perjanjian Kerja Bersama.',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $c2_m1_video = Material::updateOrCreate(
            ['course_id' => $course2->id, 'title' => 'Video: Strategi Dialog Sosial & Komunikasi LKS Bipartit'],
            [
                'module_id' => $mod2_1->id,
                'content_type' => 'video',
                'video_path' => $sampleVideoPath,
                'duration_minutes' => 7,
                'content_text' => 'Video pembahasan tata cara membangun komunikasi efektif antara Manajemen Perusahaan dan Serikat Pekerja melalui forum Lembaga Kerja Sama (LKS) Bipartit di kawasan industri.',
                'sort_order' => 1,
                'is_published' => true,
            ]
        );

        $c2_m1_materi = Material::updateOrCreate(
            ['course_id' => $course2->id, 'title' => 'E-Book: Panduan Pembentukan Forum Bipartit & Penyusunan PKB'],
            [
                'module_id' => $mod2_1->id,
                'content_type' => 'ebook',
                'duration_minutes' => 15,
                'content_text' => "BAB 1: TATA KELOLA LKS BIPARTIT & PERJANJIAN KERJA BERSAMA (PKB)\n\nLembaga Kerja Sama (LKS) Bipartit adalah forum komunikasi dan konsultasi mengenai hal-hal yang berkaitan dengan hubungan industrial di satu perusahaan.\n\nKetentuan Utama:\n1. Perusahaan yang mempekerjakan 50 orang pekerja atau lebih wajib membentuk LKS Bipartit.\n2. Komposisi keanggotaan terdiri dari unsur pengusaha dan unsur pekerja/serikat pekerja dengan perbandingan 1:1.\n3. Pertemuan rutin diadakan minimal 1 kali dalam sebulan dan dituangkan ke dalam risalah resmi.\n4. Penyelesaian perselisihan hubungan industrial wajib mengupayakan perundingan bipartit secara musyawarah untuk mufakat dalam jangka waktu paling lama 30 hari kerja.",
                'sort_order' => 2,
                'is_published' => true,
            ]
        );

        $c2_m1_latihan = Material::updateOrCreate(
            ['course_id' => $course2->id, 'title' => 'Latihan Soal Modul 1: Kepatuhan LKS Bipartit & PKB'],
            [
                'module_id' => $mod2_1->id,
                'content_type' => 'practice',
                'duration_minutes' => 10,
                'content_text' => 'Jawablah soal latihan berikut mengenai ketentuan LKS Bipartit dan perundingan PKB.',
                'quiz_payload' => [
                    [
                        'question' => 'Berapa jumlah minimum pekerja di sebuah perusahaan yang mewajibkan pembentukan LKS Bipartit?',
                        'options' => [
                            'Minimal 50 orang pekerja',
                            'Minimal 5 orang pekerja',
                            'Minimal 1.000 orang pekerja',
                            'Tidak ada batas jumlah pekerja',
                        ],
                        'answer' => 0,
                    ],
                    [
                        'question' => 'Berapa batas waktu maksimal penyelesaian perundingan bipartit dalam perselisihan hubungan industrial menurut regulasi?',
                        'options' => [
                            '3 hari kerja',
                            '30 hari kerja sejak tanggal dimulainya perundingan',
                            '180 hari kerja',
                            '5 tahun',
                        ],
                        'answer' => 1,
                    ],
                ],
                'sort_order' => 3,
                'is_published' => true,
            ]
        );

        Assessment::updateOrCreate(
            ['course_id' => $course2->id, 'title' => 'Latihan Akhir: Evaluasi Hukum Hubungan Industrial & Perundingan Bipartit'],
            [
                'assessment_type' => 'quiz',
                'instructions' => 'Selesaikan evaluasi akhir Hubungan Industrial ini dengan skor minimal 70% untuk memperoleh Sertifikat Kompetensi HRD FKKSM MM2100.',
                'passing_score' => 70,
                'quiz_payload' => [
                    [
                        'question' => 'Perbandingan komposisi perwakilan unsur pengusaha dan unsur pekerja dalam kepengurusan LKS Bipartit adalah...',
                        'options' => [
                            '1 : 1 (Seimbang antara unsur pengusaha dan pekerja)',
                            '3 : 1',
                            '1 : 4',
                            'Ditentukan sepihak tanpa perwakilan pekerja',
                        ],
                        'answer' => 0,
                    ],
                    [
                        'question' => 'Masa berlaku maksimal Perjanjian Kerja Bersama (PKB) sebelum dapat diperpanjang adalah...',
                        'options' => [
                            '2 tahun (dan dapat diperpanjang paling lama 1 tahun berdasarkan kesepakatan tertulis)',
                            '10 tahun tanpa evaluasi',
                            '1 bulan',
                            '3 minggu',
                        ],
                        'answer' => 0,
                    ],
                ],
                'is_published' => true,
                'due_at' => now()->addDays(30),
            ]
        );

        Material::where('course_id', $course2->id)
            ->whereNotIn('id', [$c2_m1_video->id, $c2_m1_materi->id, $c2_m1_latihan->id])
            ->delete();

        /*
        |--------------------------------------------------------------------------
        | COURSE 3: Transformasi Smart Factory & Otomasi SDM Industri 4.0
        |--------------------------------------------------------------------------
        */
        $course3 = Course::updateOrCreate(
            ['slug' => 'transformasi-smart-factory-sdm-industri-4-0'],
            [
                'title' => 'Transformasi Smart Factory & Otomasi SDM Industri 4.0',
                'category' => 'Teknologi & Otomasi',
                'summary' => 'Pelatihan kesiapan tenaga kerja manufaktur dalam mengadopsi IoT industri, PLC dasar, monitoring data lini produksi, dan efisiensi OEE.',
                'description' => 'Pabrik di kawasan MM2100 bertransformasi menuju efisiensi tinggi berbasis data. Kursus ini membedah implementasi sensor IoT, pembacaan dasbor kinerja lini, dan kolaborasi teknisi dengan robotika kolaboratif (cobot).',
                'requirements' => 'Direkomendasikan memiliki pengalaman dasar operasional mesin atau pemeliharaan pabrik.',
                'instructor_id' => $instructor3?->id,
                'duration_hours' => 8.0,
                'access_type' => 'free',
                'price' => 0,
                'original_price' => 650000,
                'status' => 'published',
                'featured_section' => 'bestseller',
                'rating' => 4.9,
                'reviews_count' => 6215,
                'thumbnail' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80',
                'skill_badges' => [
                    ['icon' => '1765793978027_1027588230.png', 'name' => 'Smart Manufacturing & OEE'],
                ],
            ]
        );

        $mod3_1 = CourseModule::updateOrCreate(
            ['course_id' => $course3->id, 'title' => 'Fondasi Industri 4.0, Sensor IoT & Penghitungan OEE'],
            [
                'summary' => 'Pengenalan sensor cerdas, arsitektur SCADA/MES, dan rumus OEE lini perakitan.',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $c3_m1_video = Material::updateOrCreate(
            ['course_id' => $course3->id, 'title' => 'Video: Arsitektur Smart Factory & Monitoring Real-Time SCADA'],
            [
                'module_id' => $mod3_1->id,
                'content_type' => 'video',
                'video_path' => $sampleVideoPath,
                'duration_minutes' => 10,
                'content_text' => 'Video penjelasan integrasi PLC, sensor IIoT, SCADA, dan Manufacturing Execution System (MES) di lini perakitan komponen presisi.',
                'sort_order' => 1,
                'is_published' => true,
            ]
        );

        $c3_m1_materi = Material::updateOrCreate(
            ['course_id' => $course3->id, 'title' => 'E-Book: Rumus Overall Equipment Effectiveness (OEE) & Eliminasi Six Big Losses'],
            [
                'module_id' => $mod3_1->id,
                'content_type' => 'ebook',
                'duration_minutes' => 14,
                'content_text' => "BAB 1: PENGUKURAN KINERJA LINI PRODUKSI DENGAN OEE\n\nOverall Equipment Effectiveness (OEE) adalah standar emas untuk mengukur produktivitas manufaktur di pabrik Smart Factory MM2100.\n\nRumus Dasar OEE:\nOEE = Availability × Performance × Quality\n\n1. Availability (Ketersediaan): Mengukur waktu operasi aktual dibandingkan waktu produksi yang direncanakan (memperhitungkan Breakdown & Setup/Adjustment losses).\n2. Performance (Kinerja): Mengukur kecepatan operasi aktual dibandingkan Ideal Cycle Time (memperhitungkan Idling, Minor Stops, & Reduced Speed).\n3. Quality (Kualitas): Mengukur rasio produk Good Part dibandingkan Total Part yang diproduksi (memperhitungkan Scrap & Rework). Standar World-Class OEE berada pada angka ≥ 85%.",
                'sort_order' => 2,
                'is_published' => true,
            ]
        );

        $c3_m1_latihan = Material::updateOrCreate(
            ['course_id' => $course3->id, 'title' => 'Latihan Soal Modul 1: Komponen OEE & Arsitektur MES'],
            [
                'module_id' => $mod3_1->id,
                'content_type' => 'practice',
                'duration_minutes' => 10,
                'content_text' => 'Kerjakan latihan soal perhitungan komponen OEE dan arsitektur Smart Factory berikut.',
                'quiz_payload' => [
                    [
                        'question' => 'Tiga faktor pengali utama dalam rumus Overall Equipment Effectiveness (OEE) adalah...',
                        'options' => [
                            'Availability × Performance × Quality',
                            'Speed × Voltage × Temperature',
                            'Manpower × Budget × Overtime',
                            'Inventory × Shipping × Packaging',
                        ],
                        'answer' => 0,
                    ],
                ],
                'sort_order' => 3,
                'is_published' => true,
            ]
        );

        Assessment::updateOrCreate(
            ['course_id' => $course3->id, 'title' => 'Latihan Akhir: Evaluasi Implementasi Sensor IoT & Penghitungan OEE'],
            [
                'assessment_type' => 'assignment',
                'instructions' => 'Hitung nilai Overall Equipment Effectiveness (Availability x Performance x Quality) berdasarkan studi kasus lini perakitan yang diberikan.',
                'passing_score' => 70,
                'quiz_payload' => [
                    [
                        'question' => 'Berapakah tolok ukur standar World-Class OEE pada industri manufaktur modern?',
                        'options' => [
                            'Minimal 85%',
                            'Minimal 25%',
                            'Minimal 40%',
                            'Minimal 50%',
                        ],
                        'answer' => 0,
                    ],
                ],
                'is_published' => true,
                'due_at' => now()->addDays(45),
            ]
        );

        Material::where('course_id', $course3->id)
            ->whereNotIn('id', [$c3_m1_video->id, $c3_m1_materi->id, $c3_m1_latihan->id])
            ->delete();

        /*
        |--------------------------------------------------------------------------
        | COURSE 4: Pelatihan Kepemimpinan Foreperson & Supervisor Manufaktur
        |--------------------------------------------------------------------------
        */
        $course4 = Course::updateOrCreate(
            ['slug' => 'pelatihan-kepemimpinan-foreperson-supervisor-manufaktur'],
            [
                'title' => 'Pelatihan Kepemimpinan Foreperson & Supervisor Manufaktur',
                'category' => 'Leadership',
                'summary' => 'Keterampilan kepemimpinan operasional, komunikasi instruktif di lantai pabrik, 5S/5R, kaizen, dan pembinaan tim operator produksi.',
                'description' => 'Mencetak supervisor tangguh yang mampu memotivasi tim, menjaga target output harian (QCDS), mengeliminasi pemborosan (muda, muri, mura), dan menciptakan lingkungan kerja kondusif.',
                'requirements' => 'Terbuka bagi team leader, foreperson, dan calon supervisor pabrik.',
                'instructor_id' => $instructor2?->id,
                'duration_hours' => 5.0,
                'access_type' => 'paid',
                'price' => 350000,
                'original_price' => 700000,
                'status' => 'published',
                'featured_section' => 'bestseller',
                'rating' => 4.9,
                'reviews_count' => 4980,
                'thumbnail' => 'https://images.unsplash.com/photo-1581092335397-9583fe92d232?auto=format&fit=crop&w=800&q=80',
                'skill_badges' => [
                    ['icon' => '1765179223375_166389665.png', 'name' => 'Shopfloor Leadership & Kaizen'],
                ],
            ]
        );

        $mod4_1 = CourseModule::updateOrCreate(
            ['course_id' => $course4->id, 'title' => 'Manajemen KPI Harian QCDS & Metode 5R'],
            [
                'summary' => 'Quality, Cost, Delivery, Safety dan penerapan budaya kerja Jepang di lini produksi.',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $c4_m1_video = Material::updateOrCreate(
            ['course_id' => $course4->id, 'title' => 'Video: Kepemimpinan Shopfloor & Briefing Pagi (Chourei) Efektif'],
            [
                'module_id' => $mod4_1->id,
                'content_type' => 'video',
                'video_path' => $sampleVideoPath,
                'duration_minutes' => 8,
                'content_text' => 'Video panduan pelaksanaan briefing pergantian shift dan pemantauan papan indikator QCDS (Quality, Cost, Delivery, Safety).',
                'sort_order' => 1,
                'is_published' => true,
            ]
        );

        $c4_m1_materi = Material::updateOrCreate(
            ['course_id' => $course4->id, 'title' => 'E-Book: Standar Audit 5R Harian & Eliminasi 7 Pemborosan Pabrik'],
            [
                'module_id' => $mod4_1->id,
                'content_type' => 'ebook',
                'duration_minutes' => 12,
                'content_text' => 'Ringkas, Rapi, Resik, Rawat, Rajin (5R / 5S) merupakan disiplin dasar produktivitas di kawasan manufaktur MM2100. Materi ini mencakup checklist inspeksi shift dan eskalasi masalah berbasis Andon.',
                'sort_order' => 2,
                'is_published' => true,
            ]
        );

        $c4_m1_latihan = Material::updateOrCreate(
            ['course_id' => $course4->id, 'title' => 'Latihan Soal Modul 1: Penerapan 5R & Siklus PDCA Kaizen'],
            [
                'module_id' => $mod4_1->id,
                'content_type' => 'practice',
                'duration_minutes' => 8,
                'content_text' => 'Jawab soal latihan kepemimpinan lini produksi berikut.',
                'quiz_payload' => [
                    [
                        'question' => 'Urutan prinsip 5R (5S) yang tepat dalam manajemen area kerja manufaktur adalah...',
                        'options' => [
                            'Ringkas (Seiri), Rapi (Seiton), Resik (Seiso), Rawat (Seiketsu), Rajin (Shitsuke)',
                            'Rencana, Riset, Evaluasi, Revisi, Rehat',
                            'Rotasi, Reparasi, Rekondisi, Reduksi, Registrasi',
                            'Rapat, Risalah, Rekapitulasi, Laporan, Arsip',
                        ],
                        'answer' => 0,
                    ],
                ],
                'sort_order' => 3,
                'is_published' => true,
            ]
        );

        Assessment::updateOrCreate(
            ['course_id' => $course4->id, 'title' => 'Latihan Akhir: Studi Kasus Penanganan Konflik Shift & Kaizen Lini'],
            [
                'assessment_type' => 'quiz',
                'instructions' => 'Susun rencana perbaikan kaizen (PDCA) untuk menurunkan tingkat defect lini sebesar 15% dalam 30 hari.',
                'passing_score' => 70,
                'quiz_payload' => [
                    [
                        'question' => 'Empat tahapan utama dalam siklus perbaikan berkelanjutan (Kaizen PDCA) adalah...',
                        'options' => [
                            'Plan (Rencanakan), Do (Kerjakan), Check (Periksa), Act (Tindak Lanjuti)',
                            'Prepare, Produce, Pack, Post',
                            'Predict, Delay, Cancel, Archive',
                            'Purchase, Distribute, Consume, Audit',
                        ],
                        'answer' => 0,
                    ],
                ],
                'is_published' => true,
                'due_at' => now()->addDays(60),
            ]
        );

        Material::where('course_id', $course4->id)
            ->whereNotIn('id', [$c4_m1_video->id, $c4_m1_materi->id, $c4_m1_latihan->id])
            ->delete();

        /*
        |--------------------------------------------------------------------------
        | COURSE 5: Bahasa Jepang Komunikasi Industri & Budaya Kerja Ho-Ren-So
        |--------------------------------------------------------------------------
        */
        Course::updateOrCreate(
            ['slug' => 'bahasa-jepang-komunikasi-kerja-horenso-n5'],
            [
                'title' => 'Bahasa Jepang Komunikasi Industri & Budaya Kerja Ho-Ren-So',
                'category' => 'Bahasa',
                'summary' => 'Komunikasi praktis lantai pabrik untuk perusahaan penanaman modal Jepang (PMA) di kawasan industri MM2100, termasuk terminologi teknis K3 dan QC.',
                'description' => 'Dirancang bersama supervisor berpengalaman PMA Jepang di Cikarang. Menguasai pelaporan cepat (Hokoku-Renraku-Soudan), kosakata standar inspeksi mutu, dan etika pertemuan pagi (Chourei).',
                'requirements' => 'Terbuka untuk pemula yang bekerja atau akan bekerja di perusahaan manufaktur PMA.',
                'instructor_id' => $instructor3?->id,
                'duration_hours' => 7.5,
                'access_type' => 'paid',
                'price' => 420000,
                'original_price' => 840000,
                'status' => 'published',
                'featured_section' => 'prakerja',
                'rating' => 5.0,
                'reviews_count' => 3150,
                'thumbnail' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80',
                'skill_badges' => [
                    ['icon' => '1765793972955_1772123099.png', 'name' => 'Business Japanese N5'],
                ],
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Demo Student Enrollments, Progress & Certificates
        |--------------------------------------------------------------------------
        */
        if ($student1) {
            // Course 1: 100% Completed across all 6 materials + Final Assessment + Certificate
            Enrollment::updateOrCreate(
                ['user_id' => $student1->id, 'course_id' => $course1->id],
                [
                    'status' => 'completed',
                    'enrolled_at' => now()->subDays(5),
                    'completed_at' => now()->subDay(),
                ]
            );

            $course1Mats = [
                $c1_m1_video->id => 4,
                $c1_m1_materi->id => 4,
                $c1_m1_latihan->id => 3,
                $c1_m2_video->id => 2,
                $c1_m2_materi->id => 2,
                $c1_m2_latihan->id => 1,
            ];
            foreach ($course1Mats as $materialId => $daysAgo) {
                UserProgress::firstOrCreate(
                    ['user_id' => $student1->id, 'material_id' => $materialId],
                    ['completed_at' => now()->subDays($daysAgo)]
                );
            }

            AssessmentSubmission::updateOrCreate(
                ['assessment_id' => $assess1->id, 'user_id' => $student1->id],
                [
                    'response_text' => 'Seluruh soal Latihan Akhir K3 dijawab dengan benar dan SOP tanggap darurat siap diterapkan di lini produksi.',
                    'score' => 100,
                    'status' => 'reviewed',
                    'feedback' => 'Sangat baik! Lulus Uji Kompetensi K3 Kawasan MM2100.',
                    'submitted_at' => now()->subDay(),
                    'reviewed_at' => now()->subDay(),
                ]
            );

            Certificate::updateOrCreate(
                ['user_id' => $student1->id, 'course_id' => $course1->id],
                [
                    'certificate_no' => 'CERT-MM2100-2026-FKKSM01',
                    'issued_by' => $instructor1?->id,
                    'is_revoked' => false,
                    'issued_at' => now()->subDay(),
                ]
            );

            // Course 2: Active enrollment on Step 1 (Video unlocked, Materi & Latihan Soal & Latihan Akhir locked!)
            Enrollment::updateOrCreate(
                ['user_id' => $student1->id, 'course_id' => $course2->id],
                [
                    'status' => 'active',
                    'enrolled_at' => now()->subDays(2),
                ]
            );
        }

        if ($student2) {
            Enrollment::firstOrCreate(
                ['user_id' => $student2->id, 'course_id' => $course3->id],
                [
                    'status' => 'active',
                    'enrolled_at' => now()->subDays(3),
                ]
            );
        }
    }
}
