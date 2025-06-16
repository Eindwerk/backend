<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender_id',
        'type',
        'game_id',
        'post_id',
    ];

    /**
     * Ontvanger van de notificatie.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verzender van de notificatie (bijv. degene die reageerde of een bezoek toevoegde).
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Gerelateerde game (indien van toepassing).
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Gerelateerde post (indien van toepassing).
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
