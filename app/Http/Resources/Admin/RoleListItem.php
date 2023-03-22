<?php

namespace App\Http\Resources\Admin;

use App\Models\Role;

/**
 * @property-read Role $resource
 */
class RoleListItem extends \Illuminate\Http\Resources\Json\JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'permission_ids' => $this->whenLoaded('rolePermissions', $this->resource->rolePermissions->pluck('permission_id'), []),
        ];
    }
}
