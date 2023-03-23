<?php

namespace App\Http\Resources\Admin;

/**
 * @property-read \App\Models\SysRoute $resource
 */
class RouteTreeNode extends \Illuminate\Http\Resources\Json\JsonResource
{
    protected $map = [];

    public function toArray($request)
    {
        return [
            "id" => $this->resource->id,
            "title" => $this->resource->title,
            "method" => $this->resource->method . ' '. $this->resource->route
        ];
    }
}
