<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'game_id',
        'visited_at',
        'notes',
    ];

    /**
     * Automatically cast visited_at to a Carbon date instance.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'visited_at' => 'date',
    ];

    /**
     * Add custom attributes to the model's array and JSON form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'visited_at_formatted',
    ];

    /**
     * The user who visited the game.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The game that was visited.
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * A formatted version of the visited_at date.
     */
    public function getVisitedAtFormattedAttribute(): ?string
    {
        return $this->visited_at?->format('Y-m-d');
    }
}
