<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class BundesligaTeamsSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [
            'FC Augsburg',
            'Union Berlin',
            'Werder Bremen',
            'Borussia Dortmund',
            'Eintracht Frankfurt',
            'SC Freiburg',
            'Hamburger SV',
            '1. FC Heidenheim',
            'TSG Hoffenheim',
            '1. FC Köln',
            'RB Leipzig',
            'Bayer Leverkusen',
            'Mainz 05',
            'Borussia Mönchengladbach',
            'Bayern Munich',
            'FC St. Pauli',
            'VfB Stuttgart',
            'VfL Wolfsburg',
        ];

        foreach ($teams as $name) {
            Team::create([
                'name' => $name,
                'league_id' => 1,
                'logo_url' => null,
                'banner_image' => null,
            ]);
        }
    }
}
