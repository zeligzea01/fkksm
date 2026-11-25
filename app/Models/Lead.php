<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'source',
        'interest',
        'status',
        'notes',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'new' => 'Baru',
            'contacted' => 'Sudah Dihubungi',
            'qualified' => 'Memenuhi Kriteria',
            'converted' => 'Dikonversi / Bergabung',
            'closed' => 'Ditutup',
            default => ucfirst($this->status),
        };
    }
}

