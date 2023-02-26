<?php

namespace App\Http\Resources\Admin;

/**
 * @property-read \App\Models\Permission $resource
 */
class Permission extends \Illuminate\Http\Resources\Json\JsonResource
{
    protected $map = [];

    public function toArray($request)
    {
        return [
            "id" => $this->resource->id,
            "name" => $this->resource->name,
            "path" => $this->resource->path,
            "component" => $this->resource->component,
            "meta" => [
                "title" => $this->resource->title,
                "showParent" => !!$this->resource->show_parent,
                "frameSrc" => $this->resource->frame_src,
                "rank" => $this->resource->rank,
                "icon" => $this->resource->icon,
            ],
        ];
    }
}
