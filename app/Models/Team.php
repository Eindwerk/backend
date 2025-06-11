<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_url',
        'league_id',
        'banner_image',
    ];

    public function homeGames(): HasMany
    {
        return $this->hasMany(Game::class, 'home_team_id');
    }

    public function awayGames(): HasMany
    {
        return $this->hasMany(Game::class, 'away_team_id');
    }

    public function followers()
    {
        return $this->morphToMany(User::class, 'followable', 'follows');
    }

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }
}
