<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Seed subscription plans and demo user memberships idempotently.
     */
    public function run(): void
    {
        $adminId = User::where('email', 'admin@fkksm.local')->value('id');
        $student1 = User::where('email', 'peserta@fkksm.local')->first();

        $freePlan = Plan::updateOrCreate(
            ['name' => 'Akses Gratis Komunitas'],
            [
                'description' => 'Akses kursus dasar K3, seminar reguler, dan materi publik FKKSM MM2100.',
                'price' => 0,
                'duration_days' => 365,
                'status' => 'active',
            ]
        );

        Plan::updateOrCreate(
            ['name' => 'Paket Kompetensi Pro (Bulanan)'],
            [
                'description' => 'Akses penuh seluruh kursus bersertifikat BNSP/SKKNI, kuis evaluasi, dan prioritas sertifikat PDF.',
                'price' => 149000,
                'duration_days' => 30,
                'status' => 'active',
            ]
        );

        Plan::updateOrCreate(
            ['name' => 'Paket Akselerasi Karier (Tahunan)'],
            [
                'description' => 'Akses 12 bulan tanpa batas ke seluruh modul manufaktur, HR, bahasa kerja, dan konsultasi karier.',
                'price' => 1200000,
                'duration_days' => 365,
                'status' => 'active',
            ]
        );

        if ($student1) {
            Subscription::firstOrCreate(
                ['user_id' => $student1->id, 'plan_id' => $freePlan->id, 'status' => 'active'],
                [
                    'starts_at' => now()->subDays(5),
                    'ends_at' => now()->addDays(360),
                    'created_by' => $adminId,
                ]
            );
        }
    }
}

