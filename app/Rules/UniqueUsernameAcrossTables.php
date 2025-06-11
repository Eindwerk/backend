<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueUsernameAcrossTables implements Rule
{
    protected $userId;

    public function __construct(?int $userId = null)
    {
        $this->userId = $userId;
    }

    public function passes($attribute, $value): bool
    {
        // Check of username al bestaat bij andere users
        $userExists = DB::table('users')
            ->where('username', $value)
            ->where('id', '!=', $this->userId)
            ->exists();

        // Check of username gelijk is aan een teamnaam
        $teamExists = DB::table('teams')
            ->where('name', $value)
            ->exists();

        // Check of username gelijk is aan een stadionnaam
        $stadiumExists = DB::table('stadia')
            ->where('name', $value)
            ->exists();

        return !$userExists && !$teamExists && !$stadiumExists;
    }

    public function message(): string
    {
        return 'This username is already taken or conflicts with an existing team or stadium name.';
    }
}
