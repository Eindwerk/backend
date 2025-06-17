<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stadium extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'profile_image',
        'capacity',
        'latitude',
        'longitude',
        'banner_image',
        'team_id',
    ];

    /**
     * Get the games played in this stadium.
     */
    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    /**
     * Followers (polymorphic)
     */
    public function followers(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(User::class, 'followable', 'follows')->withTimestamps();
    }

    /**
     * Het team dat hier speelt.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Teamnaam
     */
    public function getTeamNameAttribute(): ?string
    {
        return $this->team?->name;
    }
}
