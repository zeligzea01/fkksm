<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Enrollment;
use App\Models\Material;
use App\Models\Plan;
use App\Models\User;
use App\Support\SecurityHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SecurityAndSsoAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_headers_are_applied_to_responses(): void
    {
        $res = $this->get('/');
        $res->assertStatus(200);
        $res->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $res->assertHeader('X-Content-Type-Options', 'nosniff');
        $res->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $res->assertHeader('Content-Security-Policy', "frame-ancestors 'self'");
    }

    public function test_idor_blocks_unenrolled_classroom_completion_and_cross_course_material(): void
    {
        $instructor = User::create([
            'username' => 'inst_sec',
            'name' => 'Instruktur Security',
            'email' => 'inst.sec@fkksm.local',
            'password' => Hash::make('Secret123!'),
            'role' => 'instructor',
        ]);

        $student = User::create([
            'username' => 'stud_sec',
            'name' => 'Student Security',
            'email' => 'stud.sec@fkksm.local',
            'password' => Hash::make('Secret123!'),
            'role' => 'student',
        ]);

        $courseA = Course::create([
            'title' => 'Course A',
            'slug' => 'course-a-sec',
            'instructor_id' => $instructor->id,
            'status' => 'published',
        ]);

        $courseB = Course::create([
            'title' => 'Course B',
            'slug' => 'course-b-sec',
            'instructor_id' => $instructor->id,
            'status' => 'published',
        ]);

        $matB = Material::create([
            'course_id' => $courseB->id,
            'title' => 'Material B1',
            'content_type' => 'text',
            'is_published' => true,
        ]);

        $matA = Material::create([
            'course_id' => $courseA->id,
            'title' => 'Material A1',
            'content_type' => 'text',
            'is_published' => true,
        ]);

        // 1. Unenrolled student cannot complete material in Course A
        $this->actingAs($student)
            ->post("/lms/classroom/{$courseA->id}/materials/{$matA->id}/complete")
            ->assertStatus(403);

        // 2. Enrolled student in Course A cannot supply Material ID from Course B
        Enrollment::create(['user_id' => $student->id, 'course_id' => $courseA->id, 'status' => 'active']);
        $this->actingAs($student)
            ->post("/lms/classroom/{$courseA->id}/materials/{$matB->id}/complete")
            ->assertStatus(404);

        // 3. Regular student cannot access instructor material manager
        $this->actingAs($student)->get('/lms/materials')->assertStatus(403);

        $this->assertDatabaseMissing('certificates', ['user_id' => $student->id]);
    }

    public function test_idor_blocks_cross_instructor_editing_and_unenrolled_assessment(): void
    {
        $instA = User::create([
            'username' => 'inst_a',
            'name' => 'Inst A',
            'email' => 'insta@fkksm.local',
            'password' => Hash::make('Secret123!'),
            'role' => 'instructor',
        ]);
        $instB = User::create([
            'username' => 'inst_b',
            'name' => 'Inst B',
            'email' => 'instb@fkksm.local',
            'password' => Hash::make('Secret123!'),
            'role' => 'instructor',
        ]);
        $student = User::create([
            'username' => 'stud_eval',
            'name' => 'Stud Eval',
            'email' => 'studeval@fkksm.local',
            'password' => Hash::make('Secret123!'),
            'role' => 'student',
        ]);

        $course = Course::create([
            'title' => 'Kursus Inst A',
            'slug' => 'kursus-inst-a',
            'instructor_id' => $instA->id,
            'status' => 'published',
        ]);

        $assessment = Assessment::create([
            'course_id' => $course->id,
            'title' => 'Ujian Rahasia',
            'assessment_type' => 'quiz',
            'is_published' => true,
        ]);

        // Inst B cannot edit Inst A's course
        $this->actingAs($instB)->get("/lms/courses/{$course->id}/edit")->assertStatus(403);

        // Unenrolled student cannot view or submit Course assessment
        $this->actingAs($student)->get("/lms/assessments/{$assessment->id}")->assertStatus(403);
        $this->actingAs($student)
            ->post("/lms/assessments/{$assessment->id}/submit", ['response_text' => 'Bypass jawaban'])
            ->assertStatus(403);
    }

    public function test_business_logic_blocks_cancelled_paid_reenroll_and_inactive_plans(): void
    {
        $student = User::create([
            'username' => 'stud_paid',
            'name' => 'Student Paid',
            'email' => 'stud.paid@fkksm.local',
            'password' => Hash::make('Secret123!'),
            'role' => 'student',
        ]);

        $paidCourse = Course::create([
            'title' => 'Kursus Berbayar Ketat',
            'slug' => 'kursus-berbayar-ketat',
            'access_type' => 'paid',
            'price' => 500000,
            'status' => 'published',
        ]);

        // Even with a cancelled prior enrollment, student cannot reactivate without active paid plan/voucher
        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $paidCourse->id,
            'status' => 'cancelled',
        ]);

        $res = $this->actingAs($student)->post("/lms/courses/{$paidCourse->id}/enroll");
        $res->assertRedirect('/lms/vouchers');
        $res->assertSessionHas('warning');

        $inactivePlan = Plan::create([
            'name' => 'Paket Arsip Lama',
            'price' => 1000,
            'duration_days' => 365,
            'status' => 'inactive',
        ]);

        $subRes = $this->actingAs($student)->post('/lms/subscriptions/subscribe', ['plan_id' => $inactivePlan->id]);
        $subRes->assertSessionHas('error');
        $this->assertDatabaseMissing('subscriptions', ['user_id' => $student->id, 'plan_id' => $inactivePlan->id]);
    }

    public function test_upload_and_url_xss_protections_reject_svg_and_javascript_urls(): void
    {
        $admin = User::create([
            'username' => 'admin_sec',
            'name' => 'Admin Sec',
            'email' => 'admin.sec@fkksm.local',
            'password' => Hash::make('Secret123!'),
            'role' => 'super_admin',
        ]);

        $course = Course::create([
            'title' => 'Course Sec Upload',
            'slug' => 'course-sec-upload',
            'instructor_id' => $admin->id,
            'status' => 'published',
        ]);

        // Reject SVG upload containing XSS script tag on course creation
        $svg = UploadedFile::fake()->createWithContent('evil.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>');
        $res = $this->actingAs($admin)->post('/lms/courses', [
            'title' => 'Course With SVG',
            'access_type' => 'free',
            'status' => 'published',
            'thumbnail_file' => $svg,
        ]);
        $res->assertSessionHasErrors(['thumbnail_file']);

        // Reject javascript: URI in material URL
        $matRes = $this->actingAs($admin)->post("/lms/courses/{$course->id}/materials", [
            'title' => 'Evil Link Material',
            'content_type' => 'link',
            'content_url' => 'javascript:alert(document.cookie)',
        ]);
        $matRes->assertSessionHasErrors(['content_url']);

        // SecurityHelper checks
        $this->assertSame('#katalog-kursus', SecurityHelper::sanitizeUrl('javascript:alert(1)'));
        $this->assertSame('#katalog-kursus', SecurityHelper::sanitizeUrl('//evil.com/phish'));
        $this->assertFalse(SecurityHelper::isTrustedYoutubeEmbed('https://evil.com/youtube.com/embed/123'));
        $this->assertTrue(SecurityHelper::isTrustedYoutubeEmbed('https://www.youtube.com/embed/dQw4w9WgXcQ'));
    }

    public function test_login_rate_limiter_blocks_brute_force_and_password_change_rotates_token(): void
    {
        $user = User::create([
            'username' => 'bf_target',
            'name' => 'Target User',
            'email' => 'bf@fkksm.local',
            'password' => Hash::make('CorrectPass123!'),
            'role' => 'student',
            'status' => 'active',
        ]);
        $oldRemember = 'oldremembertoken123456';
        $user->forceFill(['remember_token' => $oldRemember])->save();

        // Password change rotates remember_token
        $this->actingAs($user)->post('/lms/account/password', [
            'current_password' => 'CorrectPass123!',
            'password' => 'NewPass456789!',
            'password_confirmation' => 'NewPass456789!',
        ])->assertSessionHas('success');

        $this->assertNotEquals($oldRemember, $user->fresh()->remember_token);
        auth()->logout();

        // 5 failed login attempts trigger rate limiter on 6th
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['login' => 'bf@fkksm.local', 'password' => 'Wrong!' . $i])->assertSessionHasErrors(['login']);
        }
        $throttled = $this->post('/login', ['login' => 'bf@fkksm.local', 'password' => 'NewPass456789!']);
        $throttled->assertSessionHasErrors(['login']);
        $this->assertStringContainsString('Terlalu banyak percobaan login', session('errors')->first('login'));
    }

    public function test_google_sso_graceful_fallback_and_hardened_pkce_oidc_callback(): void
    {
        // 1. Unconfigured SSO degrades safely with warning flash
        Config::set('services.google.client_id', null);
        $this->get('/auth/google/redirect')->assertRedirect('/login')->assertSessionHas('warning');

        // 2. Configured SSO redirect issues state, nonce & PKCE S256 challenge
        Config::set('services.google.client_id', 'test-client-id.apps.googleusercontent.com');
        Config::set('services.google.client_secret', 'test-secret');
        Config::set('services.google.redirect', 'http://localhost/auth/google/callback');

        $redirectRes = $this->get('/auth/google/redirect');
        $redirectRes->assertRedirectContains('https://accounts.google.com/o/oauth2/v2/auth');
        $redirectRes->assertRedirectContains('code_challenge_method=S256');
        $state = session('sso.google.state');
        $nonce = session('sso.google.nonce');
        $this->assertNotEmpty($state);
        $this->assertNotEmpty($nonce);

        // 3. Tampered state rejected (CSRF protection)
        $this->get('/auth/google/callback?code=fake&state=wrongstate')
            ->assertRedirect('/login')
            ->assertSessionHas('error');

        // 4. Re-initiate clean flow and complete full PKCE + Nonce callback via mocked IdP
        $this->get('/auth/google/redirect');
        $validState = session('sso.google.state');
        $validNonce = session('sso.google.nonce');

        $jwtHeader = rtrim(strtr(base64_encode(json_encode(['alg' => 'RS256'])), '+/', '-_'), '=');
        $jwtPayload = rtrim(strtr(base64_encode(json_encode([
            'iss' => 'https://accounts.google.com',
            'aud' => 'test-client-id.apps.googleusercontent.com',
            'nonce' => $validNonce,
            'sub' => 'google-sub-999888',
        ])), '+/', '-_'), '=');
        $fakeIdToken = "{$jwtHeader}.{$jwtPayload}.fakesig";

        Http::fake([
            'https://oauth2.googleapis.com/token' => Http::response([
                'access_token' => 'ya29.fake-access-token',
                'id_token' => $fakeIdToken,
            ], 200),
            'https://openidconnect.googleapis.com/v1/userinfo' => Http::response([
                'sub' => 'google-sub-999888',
                'email' => 'sso.member@fkksm.local',
                'email_verified' => true,
                'name' => 'Anggota SSO Industri',
                'picture' => 'https://lh3.googleusercontent.com/a/avatar',
            ], 200),
        ]);

        $cbRes = $this->get('/auth/google/callback?code=valid-auth-code&state=' . urlencode($validState));
        $cbRes->assertRedirect('/lms/dashboard');
        $cbRes->assertSessionHas('success');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'sso.member@fkksm.local',
            'google_sub' => 'google-sub-999888',
            'role' => 'student',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'sso_login',
        ]);
    }

    public function test_sequential_4_stage_learning_flow_and_local_storage_video_stream(): void
    {
        $student = User::create([
            'username' => 'seq_student',
            'name' => 'Sequential Learner',
            'email' => 'seq@fkksm.local',
            'password' => Hash::make('Password123!'),
            'role' => 'student',
            'status' => 'active',
        ]);

        $course = Course::create([
            'title' => 'Kursus Berurutan 4 Tahap',
            'slug' => 'kursus-berurutan-4-tahap',
            'access_type' => 'free',
            'status' => 'published',
        ]);

        $module = \App\Models\CourseModule::create([
            'course_id' => $course->id,
            'title' => 'Modul 1: Dasar K3',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $step1Video = Material::create([
            'course_id' => $course->id,
            'module_id' => $module->id,
            'title' => 'Video Pengantar',
            'content_type' => 'video',
            'video_path' => 'videos/fkksm-sample.mp4',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $step2Materi = Material::create([
            'course_id' => $course->id,
            'module_id' => $module->id,
            'title' => 'E-Book SOP K3',
            'content_type' => 'ebook',
            'content_text' => 'Isi materi bacaan lengkap.',
            'sort_order' => 2,
            'is_published' => true,
        ]);

        $step3Practice = Material::create([
            'course_id' => $course->id,
            'module_id' => $module->id,
            'title' => 'Latihan Soal Modul 1',
            'content_type' => 'practice',
            'quiz_payload' => [
                [
                    'question' => 'Apa langkah pertama pengendalian risiko?',
                    'options' => ['Eliminasi', 'APD'],
                    'answer' => 0,
                ],
            ],
            'sort_order' => 3,
            'is_published' => true,
        ]);

        $step4Final = Assessment::create([
            'course_id' => $course->id,
            'title' => 'Latihan Akhir Kompetensi',
            'assessment_type' => 'quiz',
            'passing_score' => 70,
            'quiz_payload' => [
                [
                    'question' => 'Apa kepanjangan APAR?',
                    'options' => ['Alat Pemadam Api Ringan', 'Alat Pendingin Air Ruangan'],
                    'answer' => 0,
                ],
            ],
            'is_published' => true,
        ]);

        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        // 1. Lightweight local storage video streaming endpoint returns 200 video/mp4
        $videoRes = $this->actingAs($student)->get('/lms/media/video/fkksm-sample.mp4');
        $videoRes->assertStatus(200);
        $this->assertStringContainsString('video/mp4', (string) $videoRes->headers->get('Content-Type'));

        // 2. Trying to jump to or complete Step 2 (Materi) before Step 1 (Video) is blocked
        $this->actingAs($student)
            ->get("/lms/classroom/{$course->id}?material={$step2Materi->id}")
            ->assertRedirect("/lms/classroom/{$course->id}?material={$step1Video->id}")
            ->assertSessionHas('warning');

        $this->actingAs($student)
            ->post("/lms/classroom/{$course->id}/materials/{$step2Materi->id}/complete")
            ->assertRedirect("/lms/classroom/{$course->id}?material={$step1Video->id}")
            ->assertSessionHas('warning');

        $this->assertDatabaseMissing('user_progress', [
            'user_id' => $student->id,
            'material_id' => $step2Materi->id,
        ]);

        // 3. Complete Step 1 (Video) -> unlocks Step 2 (Materi)
        $this->actingAs($student)
            ->post("/lms/classroom/{$course->id}/materials/{$step1Video->id}/complete", ['watch_confirmed' => 1])
            ->assertRedirect("/lms/classroom/{$course->id}?material={$step2Materi->id}")
            ->assertSessionHas('success');

        // 4. Complete Step 2 (Materi) -> unlocks Step 3 (Latihan Soal)
        $this->actingAs($student)
            ->post("/lms/classroom/{$course->id}/materials/{$step2Materi->id}/complete", ['read_confirmed' => 1])
            ->assertRedirect("/lms/classroom/{$course->id}?material={$step3Practice->id}")
            ->assertSessionHas('success');

        // 5. Submitting wrong answer on Step 3 (Latihan Soal) fails validation and does not mark complete
        $this->actingAs($student)
            ->post("/lms/classroom/{$course->id}/materials/{$step3Practice->id}/complete", [
                'answers' => [0 => 1],
            ])
            ->assertSessionHas('warning');

        $this->assertDatabaseMissing('user_progress', [
            'user_id' => $student->id,
            'material_id' => $step3Practice->id,
        ]);

        // 6. Submitting correct answer on Step 3 completes module and redirects to Step 4 (Latihan Akhir)
        $this->actingAs($student)
            ->post("/lms/classroom/{$course->id}/materials/{$step3Practice->id}/complete", [
                'answers' => [0 => 0],
            ])
            ->assertRedirect("/lms/classroom/{$course->id}?step=final")
            ->assertSessionHas('success');

        // 7. Passing Step 4 (Latihan Akhir) issues the official Certificate
        $this->actingAs($student)
            ->post("/lms/classroom/{$course->id}/final-assessment/{$step4Final->id}", [
                'answers' => [0 => 0],
                'response_text' => 'Siap menerapkan SOP K3 di pabrik.',
            ])
            ->assertRedirect("/lms/classroom/{$course->id}?step=certificate")
            ->assertSessionHas('success');

        $this->assertDatabaseHas('certificates', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);
    }
}
