<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $table = 'roles-permissions';
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
}
