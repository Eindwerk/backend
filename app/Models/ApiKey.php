<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $fillable = ['name', 'key'];

    public $timestamps = false;

    protected $casts = [
        'key' => 'string',
        'name' => 'string',
    ];
}
