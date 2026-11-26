<?php

namespace Database\Seeders;

use App\Models\LandingSection;
use App\Models\User;
use Illuminate\Database\Seeder;

class LandingSectionSeeder extends Seeder
{
    /**
     * Seed 100% FKKSM MM2100 configurable landing page sections idempotently.
     */
    public function run(): void
    {
        $adminId = User::where('email', 'admin@fkksm.local')->value('id');

        foreach (LandingSection::defaults() as $key => $sectionData) {
            LandingSection::updateOrCreate(
                ['section_key' => $key],
                [
                    'title' => $sectionData['title'],
                    'is_active' => $sectionData['is_active'],
                    'content' => $sectionData['content'],
                    'updated_by' => $adminId,
                ]
            );
        }

        LandingSection::clearCache();
    }
}

