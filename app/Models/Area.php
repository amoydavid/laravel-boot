<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    public function parent() {
        return $this->hasOne(Area::class, 'area_code', 'parent_code');
    }

    public static function guess($prov, $city): Area|null
    {
        $provArea = self::where(function($query)use ($prov) {
            $query->orWhere('short_name', $prov)
                ->orWhere('name', $prov)
            ;
        })->where('level', 0)->first();
        if($provArea) {
            $cityArea = self::where('level', 1)->where('parent_code', $provArea->area_code)
                ->where(function($query) use($city){
                    $query->orWhere('short_name', '=', $city)
                        ->orWhere('name','=', $city)
                        ->orWhere('short_name', 'like', mb_substr($city, 0, 2).'%')
                    ;
                })->first();
            return $cityArea;
        }
        return null;

    }
}
