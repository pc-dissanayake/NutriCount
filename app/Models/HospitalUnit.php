<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HospitalUnit extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'tags' => 'array',
    ];

    protected $fillable = [
        'identifier',
        'active',
        'type',
        'specialty',
        'name',
        'alias',
        'description',
        'contact',
        'part_of',
        'endpoint',
        'qualification_code',
        'qualification_identifier',
        'qualification_period_start',
        'qualification_period_end',
        'qualification_issuer',
        'tags',
        'order_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function contactUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'contact');
    }
}
