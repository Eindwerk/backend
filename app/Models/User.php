<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\FrontendVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use HasApiTokens, Notifiable;

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

    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_verification_token_expires_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->role)) {
                $user->role = 'user';
            }

            // Genereer random ID
            $user->id = random_int(1000000000, 9999999999);
        });
    }

    // RELATIES

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

    public function followedTeams(): MorphToMany
    {
        return $this->morphedByMany(Team::class, 'followable', 'follows');
    }

    public function followedStadiums(): MorphToMany
    {
        return $this->morphedByMany(Stadium::class, 'followable', 'follows');
    }

    public function followedUsers(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'followable', 'follows');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Stuur aangepaste e-mailverificatie met platte token
     */
    public function sendEmailVerificationNotification()
    {
        $plainToken = Str::random(32);

        $this->email_verification_token = hash_hmac('sha256', $plainToken, config('app.key'));
        $this->email_verification_token_expires_at = now()->addMinutes(
            config('auth.verification.expire', 60)
        );

        $this->save();

        $this->notify(new FrontendVerifyEmail($plainToken));
    }
}
