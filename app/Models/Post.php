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
        'image',
    ];

    /**
     * The user who made the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The game this post refers to.
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Comments on this post.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Likes on this post.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Computed title: HomeTeam vs AwayTeam @ Stadium
     */
    public function getTitleAttribute(): string
    {
        $this->loadMissing('game.homeTeam', 'game.awayTeam', 'game.stadium');

        $home = $this->game?->homeTeam?->name ?? 'Home Team';
        $away = $this->game?->awayTeam?->name ?? 'Away Team';
        $stadium = $this->game?->stadium?->name ?? 'Stadium';

        return "$home vs $away @ $stadium";
    }
}
