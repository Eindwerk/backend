<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name'               => 'Super Admin',
            'username'           => 'superadmin',
            'email'              => 'super@admin.be',
            'email_verified_at'  => now(),
            'password'           => Hash::make('superadmin'),
            'role'               => 'super_admin',
            'profile_image'      => null,
            'banner_image'       => null,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);
    }
}
