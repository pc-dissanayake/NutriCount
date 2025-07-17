<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Unit extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = [
        'unit_name',
        'active',
        'unit_description',
        'unit_type',
    ];

    protected $casts = [
        'active' => 'boolean',
        'unit_type' => \App\Enums\HospitalRoomType::class,
    ];
}
