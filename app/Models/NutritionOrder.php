<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NutritionOrder extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'identifier',
        'status',
        'intent',
        'patient_id',
        'orderer_id',
        'date_time',
        'oral_diet',
        'supplement',
        'enteral_formula',
    ];

    protected $casts = [
        'oral_diet' => 'array',
        'supplement' => 'array',
        'enteral_formula' => 'array',
        'date_time' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function patient()
    {
        return $this->belongsTo(\App\Models\Patient::class, 'patient_id');
    }

    public function orderer()
    {
        return $this->belongsTo(\App\Models\User::class, 'orderer_id');
    }
}
