<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BundesligaStadiumSeeder extends Seeder
{
    public function run(): void
    {
        $stadiums = [
            ['team_id' => 1,  'name' => 'WWK Arena',                      'latitude' => 48.32306, 'longitude' => 10.88611],
            ['team_id' => 2,  'name' => 'Stadion An der Alten Försterei', 'latitude' => 52.45722, 'longitude' => 13.56806],
            ['team_id' => 3,  'name' => 'Wohninvest Weserstadion',        'latitude' => 53.06680, 'longitude' => 8.83790],
            ['team_id' => 4,  'name' => 'Signal Iduna Park',              'latitude' => 51.49260, 'longitude' => 7.45190],
            ['team_id' => 5,  'name' => 'Deutsche Bank Park',             'latitude' => 50.06860, 'longitude' => 8.64550],
            ['team_id' => 6,  'name' => 'Europa-Park Stadion',            'latitude' => 48.02160, 'longitude' => 7.82970],
            ['team_id' => 7,  'name' => 'Volksparkstadion',               'latitude' => 53.58720, 'longitude' => 9.89860],
            ['team_id' => 8,  'name' => 'Voith-Arena',                    'latitude' => 48.66850, 'longitude' => 10.13930],
            ['team_id' => 9,  'name' => 'PreZero Arena',                  'latitude' => 50.28572, 'longitude' => 18.68603],
            ['team_id' => 10, 'name' => 'RheinEnergieStadion',            'latitude' => 50.93350, 'longitude' => 6.87520],
            ['team_id' => 11, 'name' => 'Red Bull Arena',                 'latitude' => 51.34580, 'longitude' => 12.34830],
            ['team_id' => 12, 'name' => 'BayArena',                       'latitude' => 51.03820, 'longitude' => 7.00230],
            ['team_id' => 13, 'name' => 'MEWA ARENA',                     'latitude' => 49.98390, 'longitude' => 8.22440],
            ['team_id' => 14, 'name' => 'BORUSSIA-PARK',                  'latitude' => 51.17460, 'longitude' => 6.38550],
            ['team_id' => 15, 'name' => 'Allianz Arena',                  'latitude' => 48.21880, 'longitude' => 11.62470],
            ['team_id' => 16, 'name' => 'Millerntor-Stadion',             'latitude' => 53.55460, 'longitude' => 9.96780],
            ['team_id' => 17, 'name' => 'MHPArena',                       'latitude' => 48.79230, 'longitude' => 9.23210],
            ['team_id' => 18, 'name' => 'Volkswagen Arena',               'latitude' => 52.43270, 'longitude' => 10.80390],
        ];

        foreach ($stadiums as $stadium) {
            DB::table('stadia')->insert([
                'name'           => $stadium['name'],
                'team_id'        => $stadium['team_id'],
                'profile_image'  => null,
                'banner_image'   => null,
                'latitude'       => $stadium['latitude'],
                'longitude'      => $stadium['longitude'],
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
