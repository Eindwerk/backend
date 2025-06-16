<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'stadium_id',
        'image_path',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    // Helper accessors om teamnamen makkelijk te krijgen

    public function getHomeTeamAttribute()
    {
        return $this->game?->homeTeam;
    }

    public function getAwayTeamAttribute()
    {
        return $this->game?->awayTeam;
    }

    public function getStadiumAttribute()
    {
        return $this->game?->stadium;
    }

    public function getTitleAttribute(): string
    {
        $home = $this->homeTeam?->name ?? 'Home Team';
        $away = $this->awayTeam?->name ?? 'Away Team';
        $stadium = $this->stadium?->name ?? 'Stadium';

        return "$home vs $away @ $stadium";
    }
}
