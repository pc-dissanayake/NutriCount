<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Patient extends Model
{
    use HasFactory , LogsActivity;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'unit_id',
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

public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $casts = [
        'address' => 'array',
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
    /**
     * Get the unit this patient belongs to.
     */
    public function unit()
    {
        return $this->belongsTo(\App\Models\HospitalUnit::class);
    }
}
