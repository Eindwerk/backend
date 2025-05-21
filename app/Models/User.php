<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * De velden die massaal toegekend mogen worden.
     *
     * @var list<string>
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
     * Verberg deze velden bij serialisatie.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Typeconversies van attributen.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Stel de standaardwaarde in voor 'role' als 'user' bij het aanmaken van een nieuwe gebruiker.
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->role)) {
                $user->role = 'user';
            }

            $user->id = random_int(1000000000, 9999999999);
        });
    }


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
}
