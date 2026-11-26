<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'module_id',
        'title',
        'content_type',
        'content_text',
        'content_url',
        'video_path',
        'duration_minutes',
        'quiz_payload',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'sort_order' => 'integer',
            'duration_minutes' => 'integer',
            'quiz_payload' => 'array',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Canonical stage key in the 4-stage flow: video -> materi -> latihan_soal
     */
    public function getStageKeyAttribute(): string
    {
        return match ($this->content_type) {
            'video' => 'video',
            'practice', 'quiz' => 'practice',
            default => 'materi',
        };
    }

    /**
     * Human-readable stage label for syllabus & sidebar
     */
    public function getStageLabelAttribute(): string
    {
        return match ($this->content_type) {
            'video' => 'Video',
            'ebook' => 'Materi & E-Book',
            'text' => 'Materi Bacaan',
            'link' => 'Tautan Materi',
            'file' => 'Berkas Materi',
            'practice', 'quiz' => 'Latihan Soal',
            default => 'Materi',
        };
    }

    /**
     * Short sidebar title matching the reference viewer ("Video", "Materi / E-book", "Latihan Soal")
     */
    public function getSidebarTypeLabelAttribute(): string
    {
        return match ($this->content_type) {
            'video' => 'Video',
            'practice', 'quiz' => 'Latihan Soal',
            'link' => 'Tautan Materi',
            default => 'Materi & E-book',
        };
    }

    /**
     * Resolve local storage video streaming URL
     */
    public function getVideoStreamUrlAttribute(): ?string
    {
        if (!empty($this->video_path)) {
            return route('lms.media.video', ['filename' => basename($this->video_path)]);
        }
        if ($this->content_type === 'video') {
            return route('lms.media.video', ['filename' => 'fkksm-sample.mp4']);
        }

        return null;
    }
}
