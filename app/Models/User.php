<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\FrontendVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    /**
     * Welke velden mag je mass-assignen?
     * We voegen ’email_verification_token’ en ’email_verification_token_expires_at’ 
     * NIET toe aan fillable—deze worden server-side ingesteld.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'description',
        'role',
        'profile_image',
        'username',
        'banner_image',
    ];

    /**
     * Velden die je niet in JSON wilt teruggeven.
     * We verbergen de (gehasht) verificatie-token.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_token',
    ];

    /**
     * Casts voor datetime-velden en automatisch hashen van password.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_verification_token_expires_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Booted-hook: bij het aanmaken (creating) willen we
     * automatisch het ’role’-veld instellen en een willekeurige ID genereren.
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->role)) {
                $user->role = 'user';
            }

            // Jouw eigen ID-logica (zoals eerder):
            $user->id = random_int(1000000000, 9999999999);
        });
    }

    /** RELATIES */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function friendships(): HasMany
    {
        return $this->hasMany(Friendship::class);
    }

    public function friendsOf(): HasMany
    {
        return $this->hasMany(Friendship::class, 'friend_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function follows(): HasMany
    {
        return $this->hasMany(\App\Models\Follow::class);
    }

    public function followedTeams()
    {
        return $this->morphedByMany(Team::class, 'followable', 'follows');
    }

    public function followedStadiums()
    {
        return $this->morphedByMany(Stadium::class, 'followable', 'follows');
    }

    public function followedUsers()
    {
        return $this->morphedByMany(User::class, 'followable', 'follows');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * OVERSCHRIJF sendEmailVerificationNotification:
     * - genereer een “platte” token (32 tekens)
     * - hasht die token in de database
     * - stel expiratie in (bv. nu + 60 minuten)
     * - sla op en stuur daarna onze eigen notification
     */
    public function sendEmailVerificationNotification()
    {
        // 1) Genereer een random “plain-token” (32 karakters)
        $plainToken = Str::random(32);

        // 2) Hash de token en sla op in de DB
        $this->email_verification_token = hash_hmac('sha256', $plainToken, config('app.key'));

        // 3) Expiratietijd
        $this->email_verification_token_expires_at = now()->addMinutes(
            config('auth.verification.expire', 60)
        );

        $this->save();

        // 4) Stuur de FrontendVerifyEmail-notification met de ‘platte’ token
        $this->notify(new FrontendVerifyEmail($plainToken));
    }
}
