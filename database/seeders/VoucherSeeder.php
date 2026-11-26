<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Seed promotional and corporate subsidy vouchers idempotently.
     */
    public function run(): void
    {
        $adminId = User::where('email', 'admin@fkksm.local')->value('id');
        $course4Id = Course::where('slug', 'pelatihan-kepemimpinan-foreperson-supervisor-manufaktur')->value('id');
        $course5Id = Course::where('slug', 'bahasa-jepang-komunikasi-kerja-horenso-n5')->value('id');

        Voucher::updateOrCreate(
            ['code' => 'FKKSM2026'],
            [
                'title' => 'Akses Penuh Anggota FKKSM MM2100',
                'benefit_type' => 'free_access',
                'benefit_description' => 'Akses gratis kursus berbayar khusus anggota komite industri MM2100.',
                'course_id' => $course4Id,
                'discount_value' => 0,
                'max_uses' => 500,
                'starts_at' => now()->subDays(10),
                'expires_at' => now()->addMonths(12),
                'status' => 'active',
                'created_by' => $adminId,
            ]
        );

        Voucher::updateOrCreate(
            ['code' => 'DISKON50'],
            [
                'title' => 'Potongan 50% Pelatihan Supervisor',
                'benefit_type' => 'discount',
                'benefit_description' => 'Diskon biaya pelatihan Foreperson & Supervisor Manufaktur bagi pekerja kawasan.',
                'course_id' => $course4Id,
                'discount_value' => 175000,
                'max_uses' => 100,
                'starts_at' => now()->subDays(10),
                'expires_at' => now()->addMonths(6),
                'status' => 'active',
                'created_by' => $adminId,
            ]
        );

        Voucher::updateOrCreate(
            ['code' => 'MM2100PRO'],
            [
                'title' => 'Subsidi Kelas Bahasa Jepang Industri PMA',
                'benefit_type' => 'program_access',
                'benefit_description' => 'Akses program khusus komunikasi industri Jepang & budaya kerja Ho-Ren-So.',
                'course_id' => $course5Id ?? $course4Id,
                'discount_value' => 0,
                'max_uses' => 250,
                'starts_at' => now()->subDays(5),
                'expires_at' => now()->addMonths(9),
                'status' => 'active',
                'created_by' => $adminId,
            ]
        );
    }
}

