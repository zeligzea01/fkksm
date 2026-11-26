<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->string('content_type', 40)->default('text')->change();
            $table->string('video_path', 500)->nullable()->after('content_url');
            $table->unsignedSmallInteger('duration_minutes')->default(5)->after('video_path');
            $table->json('quiz_payload')->nullable()->after('duration_minutes');
        });

        Schema::table('assessments', function (Blueprint $table) {
            $table->json('quiz_payload')->nullable()->after('instructions');
            $table->unsignedTinyInteger('passing_score')->default(70)->after('quiz_payload');
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn(['video_path', 'duration_minutes', 'quiz_payload']);
        });

        Schema::table('assessments', function (Blueprint $table) {
            $table->dropColumn(['quiz_payload', 'passing_score']);
        });
    }
};

