<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
        while ($recordsProcessed < 90) {
            $str = fgets($geoFile);
            if (preg_match('/lat="([\d]{2}\.[\d]{5,7})" lon="([\d]{2}\.[\d]{5,7})"/',$str,$matches)) {

                DB::table('users')->insert([
                    'name' => Str::random(10),
                    'email' => Str::random(10).'@gmail.com',
                    'password' => Hash::make('password'),
                    'lat' => $matches[1],
                    'lng' => $matches[2],
                    'gps_point' => DB::Raw('POINT('.$matches[2].','.$matches[1].')')
                ]);
                DB::commit();
                //Сделать отдельную таблицу на правильные координаты с триггером
                $recordsProcessed ++;
            }
        }
    }
}
