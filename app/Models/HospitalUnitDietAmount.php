<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
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
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Diet amount {$eventName}")
            ->useLogName('diet_amounts');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
            // Set the created_by_userid to current authenticated user if not set
            if (empty($model->created_by_userid) && Auth::check()) {
                $model->created_by_userid = Auth::id();
            }
        });
        
        static::updating(function ($model) {
            // Update created_by_userid on updates as well
            if (Auth::check()) {
                $model->created_by_userid = Auth::id();
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
