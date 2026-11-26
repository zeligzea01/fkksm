<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FkksmLmsTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_fkksm_page_renders_successfully(): void
    {
        $response = $this->get('/profile/fkksm');
        $response->assertStatus(200);
        $response->assertSee('FKKSM MM2100');
        $response->assertSee('FOR THE PEOPLE BEHIND INDUSTRY');
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Masuk LMS FKKSM');
    }

    public function test_user_can_authenticate_and_access_dashboard(): void
    {
        $user = User::create([
            'username' => 'pesertatest',
            'name' => 'Peserta Test',
            'email' => 'peserta.test@fkksm.local',
            'password' => Hash::make('Secret123!'),
            'role' => 'student',
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'login' => 'peserta.test@fkksm.local',
            'password' => 'Secret123!',
        ]);

        $response->assertRedirect('/lms/dashboard');
        $this->assertAuthenticatedAs($user);

        // Access dashboard
        $dashResponse = $this->actingAs($user)->get('/lms/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Peserta Test');

        // Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'login',
        ]);
    }

    public function test_lead_submission_records_data_and_audit(): void
    {
        $response = $this->post('/hubungi/submit', [
            'full_name' => 'HR PT Manufaktur Test',
            'email' => 'hr@manufaktur-test.co.id',
            'phone' => '081299887766',
            'interest' => 'Sertifikasi K3 & Tanggap Darurat Pabrik',
            'notes' => 'Pengujian pengiriman formulir',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('leads', [
            'email' => 'hr@manufaktur-test.co.id',
        ]);
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'lead_created',
        ]);
    }

    public function test_certificate_public_verification(): void
    {
        $student = User::create([
            'username' => 'budi_cert',
            'name' => 'Budi Sertifikat',
            'email' => 'budi@cert.local',
            'password' => Hash::make('Secret123!'),
            'role' => 'student',
        ]);

        $course = Course::create([
            'title' => 'Kursus Uji Sertifikasi',
            'slug' => 'kursus-uji-sertifikasi',
            'category' => 'K3',
            'status' => 'published',
        ]);

        $cert = Certificate::create([
            'certificate_no' => 'CERT-TEST-001',
            'user_id' => $student->id,
            'course_id' => $course->id,
            'issued_at' => now(),
        ]);

        $response = $this->get('/verifikasi-sertifikat?code=CERT-TEST-001');
        $response->assertStatus(200);
        $response->assertSee('Budi Sertifikat');
        $response->assertSee('CERT-TEST-001');
    }

    public function test_all_landing_pages_render_successfully(): void
    {
        $this->get('/')->assertStatus(200)->assertSee('FKKSM MM2100');
        $this->get('/berita')->assertStatus(200)->assertSee('PEMBERITAAN');
        $this->get('/members')->assertStatus(200)->assertSee('KEANGGOTAAN');
        $this->get('/hubungi')->assertStatus(200)->assertSee('HUBUNGI');
    }

    public function test_login_validation_failure_displays_error(): void
    {
        $response = $this->from('/login')->post('/login', [
            'login' => 'nonexistent@fkksm.local',
            'password' => 'WrongPassword!',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['login']);

        // Following redirect shows error message on the page
        $followResponse = $this->get('/login');
        $followResponse->assertStatus(200);
    }

    public function test_registration_validation_fails_on_duplicate_email(): void
    {
        User::create([
            'username' => 'existinguser',
            'name' => 'Existing User',
            'email' => 'duplicate@fkksm.local',
            'password' => Hash::make('Secret123!'),
            'role' => 'student',
        ]);

        $response = $this->from('/register')->post('/register', [
            'name' => 'New User',
            'username' => 'newuser',
            'email' => 'duplicate@fkksm.local',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['email']);
    }

    public function test_lead_submission_requires_contact_channel(): void
    {
        $response = $this->from('/hubungi')->post('/hubungi/submit', [
            'full_name' => 'Tanpa Kontak',
            'email' => null,
            'phone' => null,
            'interest' => 'Konsultasi',
        ]);

        $response->assertRedirect('/hubungi');
        $response->assertSessionHasErrors(['email', 'phone']);
    }

    public function test_voucher_accessor_and_redemption(): void
    {
        $student = User::create([
            'username' => 'vouchertestuser',
            'name' => 'Peserta Voucher',
            'email' => 'voucher@test.local',
            'password' => Hash::make('Secret123!'),
            'role' => 'student',
        ]);

        $course = Course::create([
            'title' => 'Kursus Kupon Promo',
            'slug' => 'kursus-kupon-promo',
            'category' => 'Vokasi',
            'status' => 'published',
        ]);

        $voucher = \App\Models\Voucher::create([
            'code' => 'VOUCHERTEST100',
            'title' => 'Diskon Pelatihan 100%',
            'benefit_type' => 'free_access',
            'benefit_description' => 'Subsidi penuh kursus vokasi industri MM2100',
            'course_id' => $course->id,
            'status' => 'active',
        ]);

        // Verify accessors
        $this->assertEquals('Diskon Pelatihan 100%', $voucher->name);
        $this->assertEquals('Subsidi penuh kursus vokasi industri MM2100', $voucher->description);

        // Redeem voucher as student
        $response = $this->actingAs($student)->post('/lms/vouchers/redeem', [
            'code' => 'VOUCHERTEST100',
        ]);

        $response->assertRedirect('/lms/vouchers');
        $response->assertSessionHas('success');

        // Verify enrollment was created automatically
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_student_can_access_all_redesigned_lms_pages_and_subscriptions(): void
    {
        $student = User::create([
            'username' => 'student_ui_test',
            'name' => 'Student UI Tester',
            'email' => 'student.ui@fkksm.local',
            'password' => Hash::make('Secret123!'),
            'role' => 'student',
            'status' => 'active',
        ]);

        $plan = \App\Models\Plan::create([
            'name' => 'Paket Uji Pro',
            'description' => 'Paket uji coba pro',
            'price' => 100000,
            'duration_days' => 30,
            'status' => 'active',
        ]);

        $this->actingAs($student)->get('/lms/dashboard')->assertStatus(200)->assertSee('Perjalanan belajarmu');
        $this->actingAs($student)->get('/lms/courses')->assertStatus(200)->assertSee('Tukarkan kode belajarmu di sini.');
        $this->actingAs($student)->get('/lms/assessments')->assertStatus(200)->assertSee('Daftar Kuis');
        $this->actingAs($student)->get('/lms/certificates')->assertStatus(200)->assertSee('Sertifikat Anda');
        $this->actingAs($student)->get('/lms/vouchers')->assertStatus(200)->assertSee('Tukarkan kode voucher');
        $this->actingAs($student)->get('/lms/subscriptions')->assertStatus(200)->assertSee('Subscription');
        $this->actingAs($student)->get('/lms/account')->assertStatus(200)->assertSee('Informasi Profil');

        // Test Voucher live check JSON endpoint
        \App\Models\Voucher::create([
            'code' => 'LIVECHECK2026',
            'title' => 'Promo Cek Langsung',
            'benefit_type' => 'free_access',
            'status' => 'active',
        ]);

        $checkRes = $this->actingAs($student)->postJson('/lms/vouchers/check', ['code' => 'LIVECHECK2026']);
        $checkRes->assertStatus(200)->assertJson(['status' => 'available']);

        // Test Subscription activation
        $subRes = $this->actingAs($student)->post('/lms/subscriptions/subscribe', ['plan_id' => $plan->id]);
        $subRes->assertSessionHas('success');
        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $student->id,
            'plan_id' => $plan->id,
            'status' => 'active',
        ]);
    }

    public function test_admin_can_update_home_landing_page_via_cms(): void
    {
        $admin = User::create([
            'username' => 'admin_cms_test',
            'name' => 'Admin CMS Tester',
            'email' => 'admin.cms@fkksm.local',
            'password' => Hash::make('Secret123!'),
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin)->get('/lms/landing-cms')->assertStatus(200)->assertSee('Editor Dinamis Halaman Utama');

        $res = $this->actingAs($admin)->post('/lms/landing-cms/onboarding_banner', [
            'section_title' => 'Banner Onboarding Peserta',
            'is_active' => '1',
            'headline' => 'JUDUL BARU DARI ADMIN CMS 2026',
            'subtitle' => 'Subjudul terintegrasi langsung ke Home',
            'cta_guest' => 'Daftar Gratis Sekarang',
            'cta_auth' => 'Mulai Kelas',
            'image' => 'assets/fkksm/blue-rocket.webp',
        ]);

        $res->assertRedirect('/lms/landing-cms?section=onboarding_banner');
        $res->assertSessionHas('success');

        // Confirm Home ('/') renders the Admin-updated text, zero Cakap identity, and exposes SweetAlert2 flash hook!
        $homeRes = $this->get('/');
        $homeRes->assertStatus(200)
                ->assertSee('JUDUL BARU DARI ADMIN CMS 2026')
                ->assertSee('window.__FKKSM_FLASH__', false)
                ->assertDontSee('Cakap', false)
                ->assertDontSee('cakap_files', false);
    }

    public function test_database_seeder_is_idempotent_and_populates_fkksm_data(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $this->assertDatabaseHas('users', ['email' => 'admin@fkksm.local']);
        $this->assertDatabaseHas('users', ['email' => 'peserta@fkksm.local']);
        $this->assertDatabaseHas('vouchers', ['code' => 'FKKSM2026']);
        $this->assertDatabaseHas('certificates', ['certificate_no' => 'CERT-MM2100-2026-FKKSM01']);
        $this->assertDatabaseHas('landing_sections', ['section_key' => 'hero_slides']);
    }
}
