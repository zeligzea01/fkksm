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
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'original_price')) {
                $table->decimal('original_price', 12, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('courses', 'requirements')) {
                $table->string('requirements', 500)->nullable()->after('description');
            }
            if (!Schema::hasColumn('courses', 'schedule_label')) {
                $table->string('schedule_label', 120)->nullable()->after('duration_hours');
            }
            if (!Schema::hasColumn('courses', 'featured_section')) {
                $table->string('featured_section', 60)->default('catalog')->after('thumbnail');
            }
            if (!Schema::hasColumn('courses', 'rating')) {
                $table->decimal('rating', 3, 2)->default(4.90)->after('featured_section');
            }
            if (!Schema::hasColumn('courses', 'reviews_count')) {
                $table->unsignedInteger('reviews_count')->default(120)->after('rating');
            }
            if (!Schema::hasColumn('courses', 'skill_badges')) {
                $table->json('skill_badges')->nullable()->after('reviews_count');
            }
        });

        if (!Schema::hasTable('landing_sections')) {
            Schema::create('landing_sections', function (Blueprint $table) {
                $table->string('section_key', 100)->primary();
                $table->string('title', 180);
                $table->boolean('is_active')->default(true);
                $table->json('content');
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_sections');

        Schema::table('courses', function (Blueprint $table) {
            foreach (['original_price', 'requirements', 'schedule_label', 'featured_section', 'rating', 'reviews_count', 'skill_badges'] as $col) {
                if (Schema::hasColumn('courses', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

