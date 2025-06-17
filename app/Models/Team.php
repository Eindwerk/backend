<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'profile_image',
        'banner_image',
        'league_id',
    ];

    public function homeGames(): HasMany
    {
        return $this->hasMany(Game::class, 'home_team_id');
    }

    public function awayGames(): HasMany
    {
        return $this->hasMany(Game::class, 'away_team_id');
    }

    public function followers(): MorphToMany
    {
        return $this->morphToMany(User::class, 'followable', 'follows')->withTimestamps();
    }

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }
}
