<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add performance indexes on frequently filtered LMS and Portal columns.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->index(['status', 'featured_section'], 'courses_status_featured_idx');
            $table->index('instructor_id', 'courses_instructor_idx');
        });

        Schema::table('vouchers', function (Blueprint $table) {
            $table->index(['status', 'expires_at'], 'vouchers_status_expires_idx');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'enrollments_user_status_idx');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'notifications_user_created_idx');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index(['action', 'created_at'], 'activity_logs_action_created_idx');
        });
    }

    /**
     * Reverse performance index additions.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('activity_logs_action_created_idx');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_user_created_idx');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('enrollments_user_status_idx');
        });

        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropIndex('vouchers_status_expires_idx');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex('courses_status_featured_idx');
            $table->dropIndex('courses_instructor_idx');
        });
    }
};

