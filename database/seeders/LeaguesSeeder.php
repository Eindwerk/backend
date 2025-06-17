<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeaguesSeeder extends Seeder
{
    public function run(): void
    {
        $leagues = [
            ['id' => 1, 'name' => 'Bundesliga'],
            ['id' => 2, 'name' => '2. Bundesliga'],
            ['id' => 3, 'name' => '3. Liga'],
        ];

        foreach ($leagues as $league) {
            DB::table('leagues')->insert([
                'id'         => $league['id'],
                'name'       => $league['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
