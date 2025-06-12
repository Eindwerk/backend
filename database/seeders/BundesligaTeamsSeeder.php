<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BundesligaStadiumSeeder extends Seeder
{
    public function run(): void
    {
        $stadiums = [
            ['WWK Arena',                         48.32306, 10.88611],
            ['Stadion An der Alten Försterei',    52.4572,  13.5681],
            ['Wohninvest Weserstadion',           53.0668,  8.8379],
            ['Signal Iduna Park',                 51.4926,  7.4519],
            ['Deutsche Bank Park',                50.0686,  8.6455],
            ['Europa-Park Stadion',               48.0216,  7.8297],
            ['Volksparkstadion',                  53.5872,  9.8986],
            ['Voith-Arena',                       48.6685, 10.1393],
            ['PreZero Arena',                     50.2857, 18.6860],
            ['RheinEnergieStadion',               50.9335,  6.8752],
            ['Red Bull Arena',                    51.3458, 12.3483],
            ['BayArena',                          51.0382,  7.0023],
            ['MEWA ARENA',                         49.9839,  8.2244],
            ['BORUSSIA-PARK',                     51.1746,  6.3855],
            ['Allianz Arena',                     48.2188, 11.6247],
            ['Millerntor-Stadion',               53.5546,  9.9678],
            ['MHPArena',                          48.7923,  9.2321],
            ['Volkswagen Arena',                  52.4327, 10.8039],
        ];

        foreach ($stadiums as $i => [$stadiumName, $latitude, $longitude]) {
            DB::table('stadia')->insert([
                'name'           => $stadiumName,
                'team_id'        => $i + 1,
                'profile_image'  => null,
                'banner_image'   => null,
                'location'       => json_encode([
                    'latitude'  => $latitude,
                    'longitude' => $longitude,
                ]),
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
