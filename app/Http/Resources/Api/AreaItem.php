<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Str;

/**
 * @property-read \App\Models\Area $resource
 */
class AreaItem extends \Illuminate\Http\Resources\Json\JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'prov_name'=>$this->resource->parent_code?$this->resource->parent->name:new MissingValue(),
            'name' => Str::startsWith($this->resource->name, '直辖')?$this->resource->short_name:$this->resource->name,
            'short_name' => $this->resource->short_name,
            'area_code' => $this->resource->area_code,
            'level' => $this->resource->level
        ];
    }
}
