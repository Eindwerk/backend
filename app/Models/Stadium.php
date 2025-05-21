<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stadium extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'city',
        'image',
        'capacity',
        'location',
        'banner_image',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'location' => 'array',
    ];

    /**
     * Get the games played in this stadium.
     */
    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function followers()
    {
        return $this->morphToMany(User::class, 'followable', 'follows');
    }
}
