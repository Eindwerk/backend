<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class Bundesliga2TeamsSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [
            'Hertha BSC',
            'Arminia Bielefeld',
            'VfL Bochum',
            'Eintracht Braunschweig',
            'Darmstadt 98',
            'Dynamo Dresden',
            'Fortuna Düsseldorf',
            'SV Elversberg',
            'Greuther Fürth',
            'Hannover 96',
            '1. FC Kaiserslautern',
            'Karlsruher SC',
            'Holstein Kiel',
            '1. FC Magdeburg',
            'Preußen Münster',
            '1. FC Nürnberg',
            'SC Paderborn',
            'Schalke 04',
        ];

        foreach ($teams as $name) {
            Team::create([
                'name' => $name,
                'league_id' => 2,
                'logo_url' => null,
                'banner_image' => null,
            ]);
        }
    }
}
