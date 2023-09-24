<?php

namespace App\Http\Resources\Admin;

/**
 * @property-read \App\Models\Permission $resource
 */
class ElePermission extends \Illuminate\Http\Resources\Json\JsonResource
{
    protected $map = [];

    public function toArray($request)
    {
        $transfer_icons = [
            'IconApps' => 'HomeFilled',
            'IconSettings' => 'Setting',
        ];
        $icon = $transfer_icons[$this->resource->icon]??$this->resource->icon;
        return [
            "id" => $this->resource->id,
            "name" => $this->resource->name,
            "path" => $this->resource->path,
            "component" => $this->resource->component,
            "meta" => [
                "title" => $this->resource->title,
                "requiresAuth" => true,
                "isHide" => $this->resource->hidden,
                "hideChildrenInMenu" => !$this->resource->always_show,
                "activeMenu" => $this->resource->active_menu,
                "isAffix" => $this->resource->affix,
                "isLink" => $this->resource->frame_src,
                "order" => $this->resource->rank,
                "icon" => $icon,
                "roles" => ['*'],
                "isKeepAlive" => !$this->resource->no_cache,
            ],
        ];
    }
}
