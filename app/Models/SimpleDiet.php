<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class SimpleDiet extends Model
{
    use HasFactory , LogsActivity;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'DietName_en',
        'DietName_si',
        'DietName_tm',
        'active',
        'primary_amount_value',
        'primary_amount_unit',
        'list_order',
        'multiply_values',
        'auto_populate',
        'category',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'multiply_values' => 'boolean',
            'auto_populate' => 'boolean',
            'category' => 'array',
            'description' => 'array',
        ];
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
