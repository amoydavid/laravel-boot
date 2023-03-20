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
                "label" => $this->resource->title,
                "requiresAuth" => true,
                "hideInMenu" => $this->resource->hidden,
                "hideChildrenInMenu" => !$this->resource->always_show,
                "activeMenu" => $this->resource->active_menu,
                "noAffix" => !$this->resource->affix,
                "frameSrc" => $this->resource->frame_src,
                "order" => $this->resource->rank,
                "icon" => $this->resource->icon,
                "roles" => ['*'],
                "ignoreCache" => $this->resource->no_cache,
            ],
        ];
    }
}
