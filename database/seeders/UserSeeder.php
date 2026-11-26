<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the core FKKSM MM2100 LMS and Portal accounts idempotently.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('AdminFKKSM2026!');

        $users = [
            [
                'email' => 'admin@fkksm.local',
                'username' => 'adminfkksm',
                'name' => 'Administrator Utama FKKSM',
                'role' => 'super_admin',
                'status' => 'active',
                'last_login_at' => now(),
            ],
            [
                'email' => 'sekretariat@fkksm.local',
                'username' => 'sekretariat',
                'name' => 'Sekretariat Komite MM2100',
                'role' => 'admin',
                'status' => 'active',
                'last_login_at' => now()->subHours(2),
            ],
            [
                'email' => 'hendra@fkksm.local',
                'username' => 'hendra_k3',
                'name' => 'Ir. Hendra Kusuma, M.T.',
                'role' => 'instructor',
                'status' => 'active',
                'last_login_at' => now()->subDay(),
            ],
            [
                'email' => 'siti.hr@fkksm.local',
                'username' => 'siti_hr',
                'name' => 'Siti Rahmawati, S.Psi., CHRP',
                'role' => 'instructor',
                'status' => 'active',
                'last_login_at' => now()->subDays(2),
            ],
            [
                'email' => 'rudi.otomasi@fkksm.local',
                'username' => 'rudi_plc',
                'name' => 'Rudi Hartono, S.T., IPM',
                'role' => 'instructor',
                'status' => 'active',
                'last_login_at' => now()->subDays(3),
            ],
            [
                'email' => 'peserta@fkksm.local',
                'username' => 'budi_santoso',
                'name' => 'Budi Santoso',
                'role' => 'student',
                'status' => 'active',
                'last_login_at' => now()->subHours(3),
            ],
            [
                'email' => 'dewi@fkksm.local',
                'username' => 'dewi_lestari',
                'name' => 'Dewi Lestari',
                'role' => 'student',
                'status' => 'active',
                'last_login_at' => now()->subDays(4),
            ],
            [
                'email' => 'dimas.qa@fkksm.local',
                'username' => 'dimas_qa',
                'name' => 'Dimas Pratama',
                'role' => 'student',
                'status' => 'active',
                'last_login_at' => now()->subDays(1),
            ],
            [
                'email' => 'hr@mm2100.local',
                'username' => 'hr_mm2100_tenant',
                'name' => 'HR PT MM2100 Member Industrial',
                'role' => 'company',
                'status' => 'active',
                'last_login_at' => now()->subWeek(),
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'username' => $data['username'],
                    'name' => $data['name'],
                    'password' => $defaultPassword,
                    'role' => $data['role'],
                    'status' => $data['status'],
                    'last_login_at' => $data['last_login_at'],
                ]
            );
        }
    }
}

