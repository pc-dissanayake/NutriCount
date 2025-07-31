<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class RolePermission extends Model
{
    use HasFactory , LogsActivity;

    protected $table = 'role_permissions';
    public $timestamps = false;
    protected $fillable = [
        'permission',
        'admin',
        'level1',
        'level2',
        'user',
        'guest',
        'api_only',
    ];
    protected $casts = [
        'admin' => 'boolean',
        'user' => 'boolean',
        'guest' => 'boolean',
        'api_only' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
