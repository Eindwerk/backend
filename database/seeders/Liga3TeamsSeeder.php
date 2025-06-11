<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class Liga3TeamsSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [
            'Alemannia Aachen',
            'Erzgebirge Aue',
            'Energie Cottbus',
            'MSV Duisburg',
            'Rot-Weiss Essen',
            'TSV Havelse',
            'TSG Hoffenheim II',
            'FC Ingolstadt',
            'Viktoria Köln',
            'Waldhof Mannheim',
            '1860 Munich',
            'VfL Osnabrück',
            'Jahn Regensburg',
            'Hansa Rostock',
            '1. FC Saarbrücken',
            '1. FC Schweinfurt',
            'VfB Stuttgart II',
            'SSV Ulm',
            'SC Verl',
            'Wehen Wiesbaden',
        ];

        foreach ($teams as $name) {
            Team::create([
                'name' => $name,
                'league_id' => 3,
                'logo_url' => null,
                'banner_image' => null,
            ]);
        }
    }
}
