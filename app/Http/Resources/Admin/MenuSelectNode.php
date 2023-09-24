<?php

namespace App\Http\Resources\Admin;

/**
 * @property-read \App\Models\Permission $resource
 */
class MenuSelectNode extends \Illuminate\Http\Resources\Json\JsonResource
{
    protected $map = [];

    public function toArray($request)
    {
        return [
            "id" => $this->resource->id,
            "parent_id" => $this->resource->parent_id,
            "name" => $this->resource->name,
            "path" => $this->resource->path,
            "component" => $this->resource->component,
            "title" => $this->resource->title,
            "hidden" => $this->resource->hidden,
            "always_show" => $this->resource->always_show,
            "active_menu" => $this->resource->active_menu,
            "affix" => $this->resource->affix,
            "frame_src" => $this->resource->frame_src,
            "rank" => $this->resource->rank,
            "icon" => $this->resource->icon,
            "no_cache" => $this->resource->no_cache,
            "route_ids" => $this->whenLoaded('routeRelations', $this->resource->routeRelations->pluck('route_id'), []),
        ];
    }
}
