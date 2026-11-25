<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'assessment_type',
        'instructions',
        'is_optional',
        'is_published',
        'due_at',
    ];

    protected $casts = [
        'is_optional' => 'boolean',
        'is_published' => 'boolean',
        'due_at' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssessmentSubmission::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->assessment_type) {
            'quiz' => 'Kuis',
            'assignment' => 'Tugas Praktik',
            'evaluation' => 'Evaluasi Akhir',
            default => ucfirst($this->assessment_type),
        };
    }
}

