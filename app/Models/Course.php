<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'summary',
        'description',
        'requirements',
        'instructor_id',
        'duration_hours',
        'schedule_label',
        'access_type',
        'price',
        'original_price',
        'status',
        'thumbnail',
        'featured_section',
        'rating',
        'reviews_count',
        'skill_badges',
    ];

    protected $casts = [
        'duration_hours' => 'decimal:2',
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'reviews_count' => 'integer',
        'skill_badges' => 'array',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function modules(): HasMany
    {
        return $this->hasMany(CourseModule::class)->orderBy('sort_order');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class)->orderBy('sort_order');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getIsFreeAttribute(): bool
    {
        return $this->access_type === 'free' || (float) $this->price <= 0;
    }

    public function getFormattedPriceAttribute(): string
    {
        return $this->is_free
            ? 'Gratis'
            : 'Rp ' . number_format((float) $this->price, 0, ',', '.');
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
                return $this->thumbnail;
            }
            return asset(ltrim($this->thumbnail, '/'));
        }
        return 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=800&q=80';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'published' => 'Terbit',
            'draft' => 'Draf',
            'archived' => 'Diarsipkan',
            default => ucfirst($this->status),
        };
    }

    /**
     * Catalog of Skill Badge icons stored in public/assets/fkksm/.
     */
    public static function availableSkillBadgeIcons(): array
    {
        return [
            ['icon' => '1765179223375_166389665.png', 'default_name' => 'Organic Input Production - Basic'],
            ['icon' => '1765180051349_1286725957.png', 'default_name' => 'Organic Pest & Weed Management - Basic'],
            ['icon' => '1765180942518_2059284853.png', 'default_name' => 'Culinary Business Strategy & Ideation - Dasar'],
            ['icon' => '1765180983228_806302746.png', 'default_name' => 'Culinary Product & Financial Planning - Dasar'],
            ['icon' => '1765181023900_1548929450.png', 'default_name' => 'Culinary Marketing & Business Development - Dasar'],
            ['icon' => '1748333962862_2131417080.png', 'default_name' => 'Front Office - Basic'],
            ['icon' => '1765270249133_1527721171.png', 'default_name' => 'Japanese Tour Facilitation & Coordination - Dasar'],
            ['icon' => '1765270277280_1142546686.png', 'default_name' => 'Japanese Tour Leadership & Management - Dasar'],
            ['icon' => '1765270300739_414434307.png', 'default_name' => 'Japanese Service Recovery & Complaint Handling - Dasar'],
            ['icon' => '1763708650958_2074870672.png', 'default_name' => 'Interview Prep & Practice - Dasar'],
            ['icon' => '1763708702867_1074007178.png', 'default_name' => 'Japanese Honorifics - Dasar'],
            ['icon' => '1765793972955_1772123099.png', 'default_name' => 'Product Discovery & Definition - Dasar'],
            ['icon' => '1765793978027_1027588230.png', 'default_name' => 'Product Reliability & Support - Dasar'],
            ['icon' => '1765794082625_1289476765.png', 'default_name' => 'Stakeholder Alignment & Execution - Dasar'],
            ['icon' => '1766373528567_723602149.png', 'default_name' => 'Basic Workplace Communication (English) - Dasar'],
            ['icon' => '1766375618222_589836390.png', 'default_name' => 'Office Administrations Skills - Pemula'],
            ['icon' => '1765780573638_1844925480.png', 'default_name' => 'Creative Advertising Strategy - Dasar'],
            ['icon' => '1765780617687_734637452.png', 'default_name' => 'Media & Client Management - Dasar'],
            ['icon' => '1765780691003_1299763240.png', 'default_name' => 'Operational English & Ethics - Dasar'],
            ['icon' => '1765769136090_613011060.png', 'default_name' => 'Customer Service & Complaint Handling - Pemula'],
        ];
    }
}
