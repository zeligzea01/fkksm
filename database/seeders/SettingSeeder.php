<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Seed FKKSM global portal configuration parameters idempotently.
     */
    public function run(): void
    {
        $adminId = User::where('email', 'admin@fkksm.local')->value('id');

        $defaults = [
            'app_title' => 'FKKSM MM2100 Learning Management System',
            'notification_channel' => 'in_app',
            'notification_events' => 'course_enrollment,assessment_submission,new_lead',
            'security_session_minutes' => '120',
            'certificate_signature_name' => 'Dr. Ir. H. Bambang Sudarsono, M.M.',
            'certificate_signature_title' => 'Ketua Umum Komite FKKSM MM2100',
            'contact_email' => 'sekretariat@fkksm-mm2100.org',
            'contact_phone' => '+62 21 8980 1234',
            'address' => 'Kawasan Industri MM2100, Cikarang Barat, Bekasi, Jawa Barat 17530',
        ];

        foreach ($defaults as $key => $value) {
            Setting::set($key, $value, $adminId);
        }
    }
}

