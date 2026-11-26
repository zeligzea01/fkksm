<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database cleanly in modular order.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SettingSeeder::class,
            CourseSeeder::class,
            PlanSeeder::class,
            VoucherSeeder::class,
            PortalDataSeeder::class,
            LandingSectionSeeder::class,
        ]);
    }
}
