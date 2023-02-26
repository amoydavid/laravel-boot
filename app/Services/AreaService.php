<?php

namespace App\Services;

use App\Http\Resources\Api\AreaItem;
use App\Models\Area;
use App\Models\Option;
use App\Util\Helper;
use App\Util\XdbSearcher;
use Illuminate\Support\Str;

class AreaService
{
    public function ip2area(string $ip): Area|null
    {
        $inIp = [
            ['172.16.0.0','172.31.255.255'],
            ['192.168.0.0','192.168.255.255']
        ];
        foreach($inIp as $ip_range) {
            if(Helper::ipRange($ip, $ip_range[0], $ip_range[1])) {
                return null;
            }
        }

        $dbPath = app_path('Util/ip2region.xdb');
        $searcher = XdbSearcher::newWithFileOnly($dbPath);
        $result = $searcher->search($ip);
        $result_arr = explode("|", $result);
        $provName = $result_arr[2];
        $cityName = $result_arr[3];
        $area = Area::guess($provName, $cityName);
        return $area;
    }
}
