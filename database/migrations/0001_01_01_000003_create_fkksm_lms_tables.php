<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Courses Table
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title', 180);
            $table->string('slug', 200)->unique();
            $table->string('category', 120)->nullable();
            $table->string('summary', 500)->nullable();
            $table->text('description')->nullable();
            $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('duration_hours', 6, 2)->nullable();
            $table->enum('access_type', ['free', 'paid'])->default('free');
            $table->decimal('price', 12, 2)->default(0);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->string('thumbnail')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('category');
            $table->index('access_type');
        });

        // 2. Course Modules Table
        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title', 180);
            $table->text('summary')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['course_id', 'sort_order']);
        });

        // 3. Materials Table
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('module_id')->nullable()->constrained('course_modules')->nullOnDelete();
            $table->string('title', 180);
            $table->enum('content_type', ['text', 'link', 'file'])->default('text');
            $table->mediumText('content_text')->nullable();
            $table->string('content_url', 1000)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['course_id', 'sort_order']);
        });

        // 4. Enrollments Table
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamp('enrolled_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
            $table->index(['course_id', 'status']);
        });

        // 5. User Progress Table
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->timestamp('completed_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'material_id']);
        });

        // 6. Assessments Table
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title', 180);
            $table->enum('assessment_type', ['quiz', 'assignment', 'evaluation'])->default('quiz');
            $table->text('instructions')->nullable();
            $table->boolean('is_optional')->default(false);
            $table->boolean('is_published')->default(true);
            $table->dateTime('due_at')->nullable();
            $table->timestamps();

            $table->index('course_id');
        });

        // 7. Assessment Submissions Table
        Schema::create('assessment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->mediumText('response_text')->nullable();
            $table->decimal('score', 6, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->enum('status', ['submitted', 'reviewed', 'returned'])->default('submitted');
            $table->timestamp('submitted_at')->useCurrent();
            $table->dateTime('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['assessment_id', 'status']);
            $table->index('user_id');
        });

        // 8. Certificates Table
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_no', 80)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_revoked')->default(false);
            $table->timestamp('issued_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
        });

        // 9. Vouchers Table
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 80)->unique();
            $table->string('title', 180);
            $table->enum('benefit_type', ['free_access', 'discount', 'program_access'])->default('free_access');
            $table->string('benefit_description', 500)->nullable();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->decimal('discount_value', 12, 2)->default(0);
            $table->unsignedInteger('max_uses')->nullable();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->enum('status', ['active', 'paused', 'expired'])->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 10. Voucher Redemptions Table
        Schema::create('voucher_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained('vouchers')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('redeemed_at')->useCurrent();
            $table->timestamps();

            $table->unique(['voucher_id', 'user_id']);
            $table->index('user_id');
        });

        // 11. Plans Table
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 140);
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->unsignedInteger('duration_days')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('status');
        });

        // 12. Subscriptions Table
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('plans')->restrictOnDelete();
            $table->enum('status', ['pending', 'active', 'expired', 'cancelled'])->default('pending');
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        // 13. Leads Table
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 160);
            $table->string('email', 190)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('source', 140)->nullable();
            $table->string('interest', 180)->nullable();
            $table->enum('status', ['new', 'contacted', 'qualified', 'converted', 'closed'])->default('new');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });

        // 14. Notifications Table
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('title', 180);
            $table->text('body')->nullable();
            $table->enum('channel', ['email', 'in_app', 'other'])->default('in_app');
            $table->dateTime('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'read_at']);
        });

        // 15. Settings Table
        Schema::create('settings', function (Blueprint $table) {
            $table->string('setting_key', 120)->primary();
            $table->text('setting_value')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 16. Activity Logs (Audit Trail)
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 160);
            $table->text('details')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('created_at');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('voucher_redemptions');
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('assessment_submissions');
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('user_progress');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('course_modules');
        Schema::dropIfExists('courses');
    }
};

