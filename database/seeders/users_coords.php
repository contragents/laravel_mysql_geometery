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
        $geoFile = fopen('storage/central-fed-district-latest.osm','r');
        $recordsProcessed = 0;
        while ($recordsProcessed <=90000) {
            $str = fgets($geoFile);
            if (preg_match('/lat="(56\.4116203)" lon="(38\.5101559)"/',$str,$matches)) {
                print_r($matches); exit();
                DB::table('users')->insert([
                    'name' => Str::random(10),
                    'email' => Str::random(10).'@gmail.com',
                    'password' => Hash::make('password'),
                ]);
                $recordsProcessed ++;
            }


        }
        //
    }
}
