<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'lng',
        'lat',
        'gps_point'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'gps_point'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function findNears($lat, $long, $radiusKM, $numResults)
    {
        $latDelta = 1 / 111 * $radiusKM;
        $lngDelta = 1 / (111 * abs(cos(deg2rad($long)))) * $radiusKM;

        $rawFieldsQuery="
    name,
    ST_distance_sphere(gps_point, st_geomfromtext(concat('point(',$lat,' ',$long,')'),4326,'axis-order=lat-long')) as distance,
    lat,
    lng";

        $rawWhereQuery="
    ST_WITHIN(
        gps_point,
        ST_geomfromtext(
            concat('POLYGON((',
                " . ( ($lat - $latDelta) < -90 ? $lat : $lat - $latDelta) . ",' '," . ( ($long - $lngDelta) < -180 ? $long : $long - $lngDelta) . ",',',
                " . ( ($lat - $latDelta) < -90 ? $lat : $lat - $latDelta) . ",' '," . ( ($long + $lngDelta) > 180 ? $long : $long + $lngDelta) . ",',',
                " . ( ($lat + $latDelta) > 90 ? $lat : $lat + $latDelta) . ",' '," . ( ($long + $lngDelta) > 180 ? $long : $long + $lngDelta) . ",',',
                " . ( ($lat + $latDelta) > 90 ? $lat : $lat + $latDelta) . ",' '," . ( ($long - $lngDelta) < -180 ? $long : $long - $lngDelta) . ",',',
                " . ( ($lat - $latDelta) < -90 ? $lat : $lat - $latDelta) . ",' '," . ( ($long - $lngDelta) < -180 ? $long : $long - $lngDelta) . "
                ,'))'
            ),
            4326,
            'axis-order=lat-long'
        )
    )";

    $rawOrderQuery="
    ST_distance_sphere(gps_point, st_geomfromtext(concat('point(',$lat,' ',$long,')'),4326,'axis-order=lat-long'))";

        $nearObjects = DB::table('users')
            ->select(DB::raw($rawFieldsQuery))
            ->whereRaw($rawWhereQuery)
            ->orderByRaw($rawOrderQuery)
            ->limit($numResults)
            ->get();

        return $nearObjects;
    }
}
