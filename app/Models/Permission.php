<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'parent_id', 'path', 'title', 'component',
        'show_parent', 'frame_src', 'rank', 'icon', 'type', 'hidden', 'affix'
    ];

    public function routeRelations()
    {
        return $this->hasMany(PermissionRoute::class, 'permission_id', 'id');
    }

    public function updateRoutes($ids = [])
    {
        $old_ids = $this->routeRelations->pluck('route_id')->toArray();
        $keep_ids = array_intersect($ids, $old_ids);
        $delete_ids = array_diff($old_ids, $keep_ids);
        $add_ids = array_diff($ids, $keep_ids);
        return \DB::transaction(function () use($delete_ids, $add_ids) {
            if($delete_ids) {
                PermissionRoute::where('permission_id', $this->id)->whereIn('permission_id', $delete_ids)->delete();
            }
            if($add_ids) {
                foreach($add_ids as $_id) {
                    $model = new PermissionRoute();
                    $model->permission_id = $this->id;
                    $model->route_id = $_id;
                    if(!$model->save()) {
                        return false;
                    }
                }
            }
            return true;
        });
    }
}
