<?php

namespace App\Http\Resources\Admin;

/**
 * @property-read \App\Models\Permission $resource
 */
class MenuTreeNode extends \Illuminate\Http\Resources\Json\JsonResource
{
    protected $map = [];

    public function toArray($request)
    {
        return [
            "id" => $this->resource->id,
            "title" => $this->resource->title,
        ];
    }
}
