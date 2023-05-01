<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read \App\Models\DictValue $resource
 */
class DictValue extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'dict_label' => $this->resource->dict_label,
            'dict_value' => is_numeric($this->resource->dict_value)?(intval($this->resource->dict_value)):$this->resource->dict_value,
        ];
    }
}
