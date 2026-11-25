<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'details',
        'ip_address',
        'user_agent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(?int $userId, string $action, string $details = ''): self
    {
        return static::create([
            'user_id' => $userId,
            'action' => $action,
            'details' => $details,
            'ip_address' => request()->ip(),
            'user_agent' => substr(request()->userAgent() ?? '', 0, 500),
        ]);
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'login' => 'Masuk Sistem',
            'logout' => 'Keluar Sistem',
            'register' => 'Registrasi Akun Baru',
            'password_changed' => 'Mengubah Password',
            'course_created' => 'Menambahkan Kursus',
            'course_updated' => 'Memperbarui Kursus',
            'course_enrolled' => 'Mendaftar Kursus',
            'material_created' => 'Menambahkan Materi',
            'material_completed' => 'Menyelesaikan Materi Pembelajaran',
            'assessment_created' => 'Menambahkan Evaluasi',
            'assessment_submitted' => 'Mengumpulkan Jawaban Evaluasi',
            'assessment_graded' => 'Menilai Jawaban Evaluasi',
            'certificate_issued' => 'Menerbitkan Sertifikat Kompetensi',
            'certificate_revoked' => 'Mencabut Sertifikat',
            'voucher_created' => 'Membuat Voucher Promo',
            'voucher_redeemed' => 'Menukarkan Voucher Promo',
            'lead_created' => 'Mencatat Calon Peserta (Lead Website)',
            'lead_updated' => 'Memperbarui Status Lead',
            'record_updated' => 'Memperbarui Data Master',
            'record_deleted' => 'Menghapus Data',
            'settings_saved' => 'Menyimpan Pengaturan Sistem',
            default => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }
}

