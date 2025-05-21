<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Follow extends Model
{
    protected $fillable = [
        'followable_id',
        'followable_type',
    ];

    public function followable(): MorphTo
    {
        return $this->morphTo();
    }
}
