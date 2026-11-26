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
        'quiz_payload',
        'passing_score',
        'is_published',
        'due_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'due_at' => 'datetime',
            'quiz_payload' => 'array',
            'passing_score' => 'integer',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssessmentSubmission::class);
    }
}
