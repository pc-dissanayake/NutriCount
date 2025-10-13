<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'category',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'value' => 'string',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        return $setting ? $setting->getCastedValue() : $default;
    }

    /**
     * Set a setting value by key
     */
    public static function set(string $key, $value, string $type = 'string', string $category = 'general', string $description = null): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'category' => $category,
                'description' => $description,
            ]
        );
    }

    /**
     * Get the casted value based on type
     */
    public function getCastedValue()
    {
        return match ($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            'array', 'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    /**
     * Get all settings as key-value pairs
     */
    public static function getAll(): array
    {
        return static::all()->mapWithKeys(function ($setting) {
            return [$setting->key => $setting->getCastedValue()];
        })->toArray();
    }

    /**
     * Get settings by category
     */
    public static function getByCategory(string $category): array
    {
        return static::where('category', $category)->get()->mapWithKeys(function ($setting) {
            return [$setting->key => $setting->getCastedValue()];
        })->toArray();
    }
}