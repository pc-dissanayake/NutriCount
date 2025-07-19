<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Patient extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'bht',
        'phn',
        'nic',
        'title',
        'full_name',
        'name',
        'gender',
        'date_of_birth',
        'civil_status',
        'ethnicity',
        'religion',
        'blood_group',
        'occupation',
        'contact_home',
        'contact_mobile',
        'address',
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
}
