<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class League extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected $casts = [
        'name' => 'string',
    ];

    /**
     * Teams die behoren tot deze competitie.
     */
    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
}
