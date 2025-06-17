<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'stadium_id',
        'home_team_id',
        'away_team_id',
        'match_date',
    ];

    protected $casts = [
        'stadium_id'    => 'integer',
        'home_team_id'  => 'integer',
        'away_team_id'  => 'integer',
        'match_date'    => 'datetime',
    ];

    /**
     * Bezoeken gekoppeld aan deze wedstrijd.
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Posts over deze wedstrijd.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function stadium(): BelongsTo
    {
        return $this->belongsTo(Stadium::class, 'stadium_id');
    }
}
