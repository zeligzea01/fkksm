<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Lms\AssessmentController;
use App\Http\Controllers\Lms\AuditController;
use App\Http\Controllers\Lms\CertificateController;
use App\Http\Controllers\Lms\ClassroomController;
use App\Http\Controllers\Lms\CourseController;
use App\Http\Controllers\Lms\DashboardController;
use App\Http\Controllers\Lms\LeadController;
use App\Http\Controllers\Lms\MaterialController;
use App\Http\Controllers\Lms\SettingController;
use App\Http\Controllers\Lms\UserController;
use App\Http\Controllers\Lms\VoucherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Frontend Routes (FKKSM MM2100 Portal)
|--------------------------------------------------------------------------
*/
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/profile/fkksm', [FrontendController::class, 'profileFkksm'])->name('profile.fkksm');
Route::get('/profil-fkksm', fn () => redirect()->route('profile.fkksm'));
Route::get('/profile', fn () => redirect()->route('profile.fkksm'));
Route::get('/berita', [FrontendController::class, 'berita'])->name('berita');
Route::get('/members', [FrontendController::class, 'members'])->name('members');
Route::get('/hubungi', [FrontendController::class, 'hubungi'])->name('hubungi');
Route::post('/hubungi/submit', [FrontendController::class, 'submitLead'])->name('leads.submit');
Route::get('/verifikasi-sertifikat/{code?}', [FrontendController::class, 'verifyCertificate'])->name('certificates.verify');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change');

    /*
    |--------------------------------------------------------------------------
    | LMS Core Routes (Authenticated Users)
    |--------------------------------------------------------------------------
    */
    Route::prefix('lms')->name('lms.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Course Catalog & Details
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');
        Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');

        // Virtual Classroom & Learning Player
        Route::get('/classroom/{course}', [ClassroomController::class, 'show'])->name('classroom.show');
        Route::post('/classroom/{course}/materials/{material}/complete', [ClassroomController::class, 'completeMaterial'])->name('classroom.material.complete');

        // Materials & Modules (Instructor & Admin)
        Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
        Route::middleware('instructor')->group(function () {
            Route::get('/courses-create', [CourseController::class, 'create'])->name('courses.create');
            Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
            Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
            Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');

            Route::post('/courses/{course}/modules', [MaterialController::class, 'storeModule'])->name('modules.store');
            Route::post('/courses/{course}/materials', [MaterialController::class, 'storeMaterial'])->name('materials.store');
            Route::delete('/materials/{material}', [MaterialController::class, 'destroyMaterial'])->name('materials.destroy');
            Route::delete('/modules/{module}', [MaterialController::class, 'destroyModule'])->name('modules.destroy');

            // Assessment grading
            Route::post('/assessments', [AssessmentController::class, 'store'])->name('assessments.store');
            Route::post('/submissions/{submission}/grade', [AssessmentController::class, 'grade'])->name('submissions.grade');
        });

        // Assessments / Quizzes
        Route::get('/assessments', [AssessmentController::class, 'index'])->name('assessments.index');
        Route::get('/assessments/{assessment}', [AssessmentController::class, 'show'])->name('assessments.show');
        Route::post('/assessments/{assessment}/submit', [AssessmentController::class, 'submit'])->name('assessments.submit');

        // Certificates
        Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
        Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');

        // Vouchers
        Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
        Route::post('/vouchers/redeem', [VoucherController::class, 'redeem'])->name('vouchers.redeem');

        /*
        |--------------------------------------------------------------------------
        | Administrator Only Routes
        |--------------------------------------------------------------------------
        */
        Route::middleware('admin')->group(function () {
            // Certificates Management
            Route::post('/certificates/{certificate}/toggle-revoke', [CertificateController::class, 'toggleRevoke'])->name('certificates.toggle-revoke');

            // Voucher Creation
            Route::post('/vouchers', [VoucherController::class, 'store'])->name('vouchers.store');

            // Leads Management (Website Prospects)
            Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
            Route::patch('/leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.update-status');

            // Comprehensive Audit Trail & Activity Logs
            Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');

            // User Management
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
            Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');

            // Platform Settings
            Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
            Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Legacy /fkksm Backward Compatibility Redirects
|--------------------------------------------------------------------------
*/
Route::get('/fkksm', fn () => redirect('/'));
Route::get('/fkksm/index.html', fn () => redirect('/'));
Route::get('/fkksm/berita.html', fn () => redirect()->route('berita'));
Route::get('/fkksm/members.html', fn () => redirect()->route('members'));
Route::get('/fkksm/hubungi.html', fn () => redirect()->route('hubungi'));
Route::get('/fkksm/lms', fn () => redirect()->route('lms.dashboard'));
Route::get('/fkksm/lms/index.php', fn () => redirect()->route('lms.dashboard'));

