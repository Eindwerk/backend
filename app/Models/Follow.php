<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Follow extends Model
{
    protected $fillable = [
        'user_id',
        'followable_id',
        'followable_type',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'followable_id' => 'integer',
        'followable_type' => 'string',
    ];

    /**
     * Het polymorfe object dat gevolgd wordt (User, Team, Stadium, ...).
     */
    public function followable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * De gebruiker die volgt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
