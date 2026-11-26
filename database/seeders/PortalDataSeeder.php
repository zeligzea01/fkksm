<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Lead;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class PortalDataSeeder extends Seeder
{
    /**
     * Seed initial leads, audit logs, and user notifications idempotently.
     */
    public function run(): void
    {
        $superAdmin = User::where('email', 'admin@fkksm.local')->first();
        $admin = User::where('email', 'sekretariat@fkksm.local')->first();
        $instructor1 = User::where('email', 'hendra@fkksm.local')->first();
        $student1 = User::where('email', 'peserta@fkksm.local')->first();
        $student2 = User::where('email', 'dewi@fkksm.local')->first();

        Lead::firstOrCreate(
            ['email' => 'ahmad.fauzi@pt-manufaktur-cikarang.co.id'],
            [
                'full_name' => 'Ahmad Fauzi, S.T.',
                'phone' => '081298765432',
                'source' => 'Website Portal FKKSM',
                'interest' => 'Pendaftaran Keanggotaan Industri MM2100 & Program Pelatihan K3',
                'notes' => 'Menanyakan kuota sertifikasi K3 untuk 25 karyawan lini produksi baru kuartal 3.',
                'status' => 'new',
                'created_by' => null,
            ]
        );

        Lead::firstOrCreate(
            ['email' => 'ratna.hrd@component-tech.id'],
            [
                'full_name' => 'Ratna Wulandari',
                'phone' => '085712348900',
                'source' => 'Formulir Kontak FKKSM',
                'interest' => 'Konsultasi Penyusunan Perjanjian Kerja Bersama (PKB) Bipartit',
                'notes' => 'Sudah dikontak via WhatsApp sekretariat, jadwal pertemuan tanggal 15 bulan depan.',
                'status' => 'contacted',
                'created_by' => $admin?->id,
            ]
        );

        $logs = [
            [
                'user_id' => $superAdmin?->id,
                'action' => 'login',
                'details' => 'Administrator Utama berhasil masuk ke sistem pengawasan.',
            ],
            [
                'user_id' => $superAdmin?->id,
                'action' => 'settings_saved',
                'details' => 'Konfigurasi parameter audit dan kanal notifikasi disimpan.',
            ],
            [
                'user_id' => $instructor1?->id,
                'action' => 'course_created',
                'details' => 'Menerbitkan kursus baru: Standar K3 Industri & Keselamatan Kerja Kawasan MM2100',
            ],
            [
                'user_id' => $student1?->id,
                'action' => 'certificate_issued',
                'details' => 'Penerbitan Sertifikat Kompetensi CERT-MM2100-2026-FKKSM01 untuk Budi Santoso',
            ],
        ];

        foreach ($logs as $log) {
            ActivityLog::firstOrCreate(
                ['action' => $log['action'], 'details' => $log['details']],
                [
                    'user_id' => $log['user_id'],
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                ]
            );
        }

        if ($student1) {
            Notification::firstOrCreate(
                ['user_id' => $student1->id, 'title' => 'Sertifikat Kompetensi K3 Diterbitkan'],
                [
                    'body' => 'Selamat! Sertifikat CERT-MM2100-2026-FKKSM01 telah terbit dan siap diunduh di menu Sertifikat.',
                    'channel' => 'in_app',
                ]
            );

            Notification::firstOrCreate(
                ['user_id' => $student1->id, 'title' => 'Kursus Hubungan Industrial Tersedia'],
                [
                    'body' => 'Lanjutkan Modul 1: Bipartit Efektif & Penyusunan PKB agar progres belajar mencapai 100%.',
                    'channel' => 'in_app',
                ]
            );

            Notification::firstOrCreate(
                ['user_id' => $student1->id, 'title' => 'Kode Voucher FKKSM2026 Aktif'],
                [
                    'body' => 'Gunakan kode FKKSM2026 di menu Voucher untuk membuka akses gratis kelas Pelatihan Kepemimpinan Supervisor Manufaktur.',
                    'channel' => 'in_app',
                ]
            );
        }

        if ($student2) {
            Notification::firstOrCreate(
                ['user_id' => $student2->id, 'title' => 'Selamat Datang di FKKSM Upskill'],
                [
                    'body' => 'Mulai pelajari Modul Smart Factory & Otomasi SDM Industri 4.0 dan selesaikan evaluasi akhir.',
                    'channel' => 'in_app',
                ]
            );
        }

        if ($superAdmin) {
            Notification::firstOrCreate(
                ['user_id' => $superAdmin->id, 'title' => 'Lead Perusahaan Baru Masuk'],
                [
                    'body' => 'Permintaan kuota sertifikasi K3 baru dari PT Manufaktur Cikarang siap ditindaklanjuti pada menu Leads Masuk.',
                    'channel' => 'in_app',
                ]
            );
        }
    }
}

