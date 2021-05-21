<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class users_coords extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($recordsProcessed = 0; $recordsProcessed < 90000; $recordsProcessed++) {
            $lat = rand(-9000000, 9000000) / 100000;
            $lng = rand(-18000000, 18000000) / 100000;
            DB::table('users')->insert([
                'name' => Str::random(10),
                'email' => Str::random(10) . '@gmail.com',
                'password' => Hash::make('password'),
                'lat' => $lat,
                'lng' => $lng,
                'gps_point' => DB::raw("st_geomfromtext('POINT($lat $lng)', 4326, 'axis-order=lat-long')")
            ]);

            DB::commit();
        }

    }
}
