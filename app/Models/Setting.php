<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $primaryKey = 'setting_key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'setting_key',
        'setting_value',
        'updated_by',
    ];

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function get(string $key, $default = null)
    {
        $value = Cache::remember('fkksm_setting_' . $key, 3600, function () use ($key) {
            return static::where('setting_key', $key)->value('setting_value');
        });

        return $value !== null ? $value : $default;
    }

    public static function set(string $key, $value, ?int $userId = null): void
    {
        static::updateOrCreate(
            ['setting_key' => $key],
            ['setting_value' => $value, 'updated_by' => $userId]
        );
        Cache::forget('fkksm_setting_' . $key);
    }
}

