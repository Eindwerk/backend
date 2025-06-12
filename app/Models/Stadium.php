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
        'image',
        'capacity',
        'location',
        'banner_image',
        'team_id',
    ];

    protected $casts = [
        'location' => 'array',
    ];

    protected $appends = [
        'profile_image',
        'team_name',
        'location_object',
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
    public function followers()
    {
        return $this->morphToMany(User::class, 'followable', 'follows');
    }

    /**
     * Het team dat hier speelt.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Profile image van het team (logo)
     */
    public function getProfileImageAttribute(): ?string
    {
        return $this->team?->logo_url;
    }

    /**
     * Teamnaam
     */
    public function getTeamNameAttribute(): ?string
    {
        return $this->team?->name;
    }

    /**
     * Nettere locatie output
     */
    public function getLocationObjectAttribute(): array
    {
        return [
            'latitude' => $this->location['latitude'] ?? null,
            'altitude' => $this->location['altitude'] ?? null,
        ];
    }
}
