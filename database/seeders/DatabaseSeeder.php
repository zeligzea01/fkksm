<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Assessment;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Enrollment;
use App\Models\Lead;
use App\Models\Material;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserProgress;
use App\Models\Voucher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('AdminFKKSM2026!');

        // 1. Seed Users
        $superAdmin = User::create([
            'username' => 'adminfkksm',
            'name' => 'Administrator Utama FKKSM',
            'email' => 'admin@fkksm.local',
            'password' => $defaultPassword,
            'role' => 'super_admin',
            'status' => 'active',
            'last_login_at' => now(),
        ]);

        $admin = User::create([
            'username' => 'sekretariat',
            'name' => 'Sekretariat Komite MM2100',
            'email' => 'sekretariat@fkksm.local',
            'password' => $defaultPassword,
            'role' => 'admin',
            'status' => 'active',
            'last_login_at' => now()->subHours(2),
        ]);

        $instructor1 = User::create([
            'username' => 'hendra_k3',
            'name' => 'Ir. Hendra Kusuma, M.T.',
            'email' => 'hendra@fkksm.local',
            'password' => $defaultPassword,
            'role' => 'instructor',
            'status' => 'active',
            'last_login_at' => now()->subDay(),
        ]);

        $instructor2 = User::create([
            'username' => 'siti_hr',
            'name' => 'Siti Rahmawati, S.Psi., CHRP',
            'email' => 'siti.hr@fkksm.local',
            'password' => $defaultPassword,
            'role' => 'instructor',
            'status' => 'active',
            'last_login_at' => now()->subDays(2),
        ]);

        $student1 = User::create([
            'username' => 'budi_santoso',
            'name' => 'Budi Santoso',
            'email' => 'peserta@fkksm.local',
            'password' => $defaultPassword,
            'role' => 'student',
            'status' => 'active',
            'last_login_at' => now()->subHours(3),
        ]);

        $student2 = User::create([
            'username' => 'dewi_lestari',
            'name' => 'Dewi Lestari',
            'email' => 'dewi@fkksm.local',
            'password' => $defaultPassword,
            'role' => 'student',
            'status' => 'active',
            'last_login_at' => now()->subDays(4),
        ]);

        $companyUser = User::create([
            'username' => 'hr_toyota_tsusho',
            'name' => 'HR PT MM2100 Member Industrial',
            'email' => 'hr@mm2100.local',
            'password' => $defaultPassword,
            'role' => 'company',
            'status' => 'active',
            'last_login_at' => now()->subWeek(),
        ]);

        // 2. Seed Settings
        Setting::set('app_title', 'FKKSM MM2100 Learning Management System', $superAdmin->id);
        Setting::set('notification_channel', 'in_app', $superAdmin->id);
        Setting::set('notification_events', 'course_enrollment,assessment_submission,new_lead', $superAdmin->id);
        Setting::set('security_session_minutes', '120', $superAdmin->id);
        Setting::set('certificate_signature_name', 'Dr. Ir. H. Bambang Sudarsono, M.M.', $superAdmin->id);
        Setting::set('certificate_signature_title', 'Ketua Umum Komite FKKSM MM2100', $superAdmin->id);
        Setting::set('contact_email', 'sekretariat@fkksm-mm2100.org', $superAdmin->id);
        Setting::set('contact_phone', '+62 21 8980 1234', $superAdmin->id);
        Setting::set('address', 'Kawasan Industri MM2100, Cikarang Barat, Bekasi, Jawa Barat 17530', $superAdmin->id);

        // 3. Seed Courses
        // Course 1: K3 Industri MM2100
        $course1 = Course::create([
            'title' => 'Standar K3 Industri & Keselamatan Kerja Kawasan MM2100',
            'slug' => 'standar-k3-industri-keselamatan-kerja-mm2100',
            'category' => 'Keselamatan Kerja (K3)',
            'summary' => 'Panduan komprehensif implementasi Sistem Manajemen K3 (SMK3), identifikasi potensi bahaya risiko pabrik manufaktur, dan protokol tanggap darurat di kawasan industri modern.',
            'description' => "Kursus ini dirancang khusus untuk para praktisi industri, teknisi, supervisor, dan staf operasional pabrik di Kawasan Industri MM2100. Peserta akan mempelajari regulasi K3 nasional, identifikasi bahaya kerja (HIRADC), tata cara tanggap darurat kebakaran dan kecelakaan kerja, serta budaya zero-accident di lingkungan manufaktur.",
            'instructor_id' => $instructor1->id,
            'duration_hours' => 4.5,
            'access_type' => 'free',
            'price' => 0,
            'status' => 'published',
        ]);

        $mod1 = CourseModule::create([
            'course_id' => $course1->id,
            'title' => 'Modul 1: Landasan Hukum & Budaya K3 Manufaktur',
            'summary' => 'Mengenal UU No. 1 Tahun 1970, regulasi turunan, dan tanggung jawab hukum manajemen.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $mat1_1 = Material::create([
            'course_id' => $course1->id,
            'module_id' => $mod1->id,
            'title' => 'Dasar Hukum K3 & Kewajiban Perusahaan Manufaktur',
            'content_type' => 'text',
            'content_text' => "Keselamatan dan Kesehatan Kerja (K3) adalah pilar fundamental di Kawasan Industri MM2100.\n\nSetiap pekerja memiliki hak atas perlindungan keselamatan dalam melakukan pekerjaan untuk kesejahteraan hidup dan meningkatkan produksi serta produktivitas nasional.\n\nPoin utama yang wajib diterapkan:\n1. Penyediaan Alat Pelindung Diri (APD) standar SNI/ISO yang sesuai risiko spesifik.\n2. Pembentukan Panitia Pembina Keselamatan dan Kesehatan Kerja (P2K3).\n3. Pelaksanaan inspeksi keselamatan kerja berkala dan audit internal SMK3.\n4. Rambu-rambu keselamatan yang jelas di area lintasan forklift dan zona mesin berputar.",
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $mat1_2 = Material::create([
            'course_id' => $course1->id,
            'module_id' => $mod1->id,
            'title' => 'Job Safety Analysis (JSA) & Penilaian Risiko HIRADC',
            'content_type' => 'link',
            'content_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'content_text' => 'Video peragaan langkah penyusunan Job Safety Analysis (JSA) untuk operator permesinan manufaktur dan staf logistik pergudangan.',
            'sort_order' => 2,
            'is_published' => true,
        ]);

        $mod2 = CourseModule::create([
            'course_id' => $course1->id,
            'title' => 'Modul 2: Tanggap Darurat & Evakuasi Pabrik',
            'summary' => 'SOP jalur evakuasi, titik kumpul (assembly point), dan penggunaan APAR.',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $mat2_1 = Material::create([
            'course_id' => $course1->id,
            'module_id' => $mod2->id,
            'title' => 'Prosedur Operasi Standar Tanggap Darurat Kebakaran',
            'content_type' => 'text',
            'content_text' => "Saat alarm bahaya berbunyi di fasilitas pabrik:\n1. Jangan panik. Matikan saklar daya mesin bila memungkinkan tanpa membahayakan diri.\n2. Tinggalkan seluruh barang bawaan pribadi yang menghambat evakuasi.\n3. Ikuti jalur tanda panah hijau menuju pintu darurat terdekat.\n4. Berkumpul di Titik Kumpul (Assembly Point) dan laporkan diri kepada Floor Warden.\n5. Dilarang kembali ke dalam gedung hingga komandan pemadam menyatakan situasi aman (All Clear).",
            'sort_order' => 3,
            'is_published' => true,
        ]);

        Assessment::create([
            'course_id' => $course1->id,
            'title' => 'Uji Kompetensi Pemahaman K3 & Prosedur Evakuasi Pabrik',
            'assessment_type' => 'quiz',
            'instructions' => 'Jawablah pertanyaan mengenai tindakan tanggap darurat saat sirine pabrik berbunyi dan langkah penyusunan JSA secara komprehensif.',
            'is_published' => true,
            'due_at' => now()->addDays(30),
        ]);

        // Course 2: Hubungan Industrial 2026
        $course2 = Course::create([
            'title' => 'Manajemen Hubungan Industrial & Regulasi Ketenagakerjaan 2026',
            'slug' => 'manajemen-hubungan-industrial-regulasi-ketenagakerjaan-2026',
            'category' => 'Human Resources',
            'summary' => 'Strategi pengelolaan hubungan industrial yang harmonis, pemahaman PP/PKB, penanganan perselisihan bipartit, dan kepatuhan norma ketenagakerjaan terkini.',
            'description' => 'Membekali praktisi HRD kawasan industri MM2100 dengan pemahaman regulasi perburuhan terkini, teknik negosiasi dengan serikat pekerja, serta mekanisme bipartit yang adil demi stabilitas iklim usaha dan produktivitas karyawan.',
            'instructor_id' => $instructor2->id,
            'duration_hours' => 6.0,
            'access_type' => 'free',
            'price' => 0,
            'status' => 'published',
        ]);

        $mod2_1 = CourseModule::create([
            'course_id' => $course2->id,
            'title' => 'Modul 1: Bipartit Efektif & Penyusunan PKB',
            'summary' => 'Teknik dialog sosial dan penyusunan klausul Perjanjian Kerja Bersama.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Material::create([
            'course_id' => $course2->id,
            'module_id' => $mod2_1->id,
            'title' => 'Panduan Pembentukan Forum Bipartit di Lingkungan Pabrik',
            'content_type' => 'text',
            'content_text' => 'Lembaga Kerja Sama (LKS) Bipartit adalah forum komunikasi dan konsultasi mengenai hal-hal yang berkaitan dengan hubungan industrial di satu perusahaan. Materi ini membahas jadwal pertemuan berkala, pembuatan risalah kesepakatan, dan pencegahan sengketa ketenagakerjaan.',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        // Course 3: Otomasi Smart Factory
        $course3 = Course::create([
            'title' => 'Transformasi Smart Factory & Otomasi SDM Industri 4.0',
            'slug' => 'transformasi-smart-factory-sdm-industri-4-0',
            'category' => 'Teknologi & Otomasi',
            'summary' => 'Pelatihan kesiapan tenaga kerja manufaktur dalam mengadopsi IoT industri, PLC dasar, monitoring data lini produksi, dan efisiensi OEE.',
            'description' => 'Pabrik di kawasan MM2100 bertransformasi menuju efisiensi tinggi berbasis data. Kursus ini membedah implementasi sensor IoT, pembacaan dasbor kinerja lini, dan kolaborasi teknisi dengan robotika kolaboratif (cobot).',
            'instructor_id' => $instructor1->id,
            'duration_hours' => 8.0,
            'access_type' => 'free',
            'price' => 0,
            'status' => 'published',
        ]);

        $mod3_1 = CourseModule::create([
            'course_id' => $course3->id,
            'title' => 'Modul 1: Fondasi Industri 4.0 & Sensor IoT',
            'summary' => 'Pengenalan sensor cerdas dan komunikasi data lini perakitan.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Material::create([
            'course_id' => $course3->id,
            'module_id' => $mod3_1->id,
            'title' => 'Arsitektur Smart Factory di Pabrik Komponen Presisi',
            'content_type' => 'text',
            'content_text' => 'Membahas integrasi ERP, MES (Manufacturing Execution System), dan SCADA di lantai produksi kawasan industri MM2100 untuk pemantauan downtime mesin secara real-time.',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        // Course 4: Kepemimpinan Supervisor
        $course4 = Course::create([
            'title' => 'Pelatihan Kepemimpinan Foreperson & Supervisor Manufaktur',
            'slug' => 'pelatihan-kepemimpinan-foreperson-supervisor-manufaktur',
            'category' => 'Leadership',
            'summary' => 'Keterampilan kepemimpinan operasional, komunikasi instruktif di lantai pabrik, 5S/5R, kaizen, dan pembinaan tim operator produksi.',
            'description' => 'Mencetak supervisor tangguh yang mampu memotivasi tim, menjaga target output harian (QCDS), mengeliminasi pemborosan (muda, muri, mura), dan menciptakan lingkungan kerja kondusif.',
            'instructor_id' => $instructor2->id,
            'duration_hours' => 5.0,
            'access_type' => 'paid',
            'price' => 350000,
            'status' => 'published',
        ]);

        // 4. Enroll student1 in Course 1 with full completion & certificate
        $enrollment1 = Enrollment::create([
            'user_id' => $student1->id,
            'course_id' => $course1->id,
            'status' => 'completed',
            'enrolled_at' => now()->subDays(5),
            'completed_at' => now()->subDay(),
        ]);

        UserProgress::create([
            'user_id' => $student1->id,
            'material_id' => $mat1_1->id,
            'completed_at' => now()->subDays(4),
        ]);

        UserProgress::create([
            'user_id' => $student1->id,
            'material_id' => $mat1_2->id,
            'completed_at' => now()->subDays(3),
        ]);

        UserProgress::create([
            'user_id' => $student1->id,
            'material_id' => $mat2_1->id,
            'completed_at' => now()->subDay(),
        ]);

        $certNo = 'CERT-MM2100-2026-FKKSM01';
        Certificate::create([
            'certificate_no' => $certNo,
            'user_id' => $student1->id,
            'course_id' => $course1->id,
            'issued_by' => $instructor1->id,
            'is_revoked' => false,
            'issued_at' => now()->subDay(),
        ]);

        // Enroll student1 in Course 2 (in progress)
        Enrollment::create([
            'user_id' => $student1->id,
            'course_id' => $course2->id,
            'status' => 'active',
            'enrolled_at' => now()->subDays(2),
        ]);

        // 5. Seed Vouchers
        Voucher::create([
            'code' => 'FKKSM2026',
            'title' => 'Akses Penuh Anggota FKKSM MM2100',
            'benefit_type' => 'free_access',
            'benefit_description' => 'Akses gratis kursus berbayar khusus anggota komite industri MM2100.',
            'course_id' => $course4->id,
            'max_uses' => 500,
            'starts_at' => now()->subDays(10),
            'expires_at' => now()->addMonths(12),
            'status' => 'active',
            'created_by' => $superAdmin->id,
        ]);

        Voucher::create([
            'code' => 'DISKON50',
            'title' => 'Potongan 50% Pelatihan Supervisor',
            'benefit_type' => 'discount',
            'discount_value' => 175000,
            'course_id' => $course4->id,
            'max_uses' => 100,
            'starts_at' => now()->subDays(10),
            'expires_at' => now()->addMonths(6),
            'status' => 'active',
            'created_by' => $superAdmin->id,
        ]);

        // 6. Seed Sample Leads
        Lead::create([
            'full_name' => 'Ahmad Fauzi, S.T.',
            'email' => 'ahmad.fauzi@pt-manufaktur-cikarang.co.id',
            'phone' => '081298765432',
            'source' => 'Website Portal FKKSM',
            'interest' => 'Pendaftaran Keanggotaan Industri MM2100 & Program Pelatihan K3',
            'notes' => 'Menanyakan kuota sertifikasi K3 untuk 25 karyawan lini produksi baru kuartal 3.',
            'status' => 'new',
            'created_by' => null,
            'created_at' => now()->subHours(5),
        ]);

        Lead::create([
            'full_name' => 'Ratna Wulandari',
            'email' => 'ratna.hrd@component-tech.id',
            'phone' => '085712348900',
            'source' => 'Formulir Kontak FKKSM',
            'interest' => 'Konsultasi Penyusunan Perjanjian Kerja Bersama (PKB) Bipartit',
            'notes' => 'Sudah dikontak via WhatsApp sekretariat, jadwal pertemuan tanggal 15 bulan depan.',
            'status' => 'contacted',
            'created_by' => $admin->id,
            'created_at' => now()->subDays(2),
        ]);

        // 7. Seed Initial Audit Logs
        ActivityLog::create([
            'user_id' => $superAdmin->id,
            'action' => 'login',
            'details' => 'Administrator Utama berhasil masuk ke sistem pengawasan.',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'created_at' => now()->subHours(6),
        ]);

        ActivityLog::create([
            'user_id' => $superAdmin->id,
            'action' => 'settings_saved',
            'details' => 'Konfigurasi parameter audit dan kanal notifikasi disimpan.',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'created_at' => now()->subHours(5),
        ]);

        ActivityLog::create([
            'user_id' => $instructor1->id,
            'action' => 'course_created',
            'details' => "Menerbitkan kursus baru: {$course1->title}",
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'created_at' => now()->subDays(5),
        ]);

        ActivityLog::create([
            'user_id' => $student1->id,
            'action' => 'certificate_issued',
            'details' => "Penerbitan Sertifikat Kompetensi {$certNo} untuk {$student1->name}",
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'created_at' => now()->subDay(),
        ]);
    }
}
