<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class HospitalUnitDietAmount extends Model
{
    use HasFactory, LogsActivity;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'hospital_unit_id',
        'simple_diet_id',
        'patient_id',
        'date',
        'amount',
        'created_by_userid',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
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

    public function hospitalUnit()
    {
        return $this->belongsTo(HospitalUnit::class);
    }

    public function simpleDiet()
    {
        return $this->belongsTo(SimpleDiet::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
