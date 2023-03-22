<?php

namespace App\Http\Resources\Admin;

use App\Models\User;

/**
 * @property-read User $resource
 */
class UserListItem extends \Illuminate\Http\Resources\Json\JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'phone' => $this->resource->phone,
            'email' => $this->resource->email,
            'created_at' => $this->resource->created_at->format("Y-m-d H:i:s"),
            'role_ids' => $this->resource->roleRelations->pluck('role_id'),
        ];
    }
}
