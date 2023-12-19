<?php

namespace App\Models;

use App\Exceptions\ApiException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    const TYPE_MENU = 0; // 菜单
    const TYPE_ACTION = 1; // 操作

    protected $fillable = [
        'name', 'parent_id', 'path', 'title', 'component',
        'show_parent', 'frame_src', 'rank', 'icon', 'type', 'hidden', 'affix'
    ];

    protected static function boot() {
        parent::boot();
        static::saving(function(Permission $model){
            if($model->type == self::TYPE_ACTION) {
                // component 不可重复
                $query = self::query()->where("component", $model->component);
                if($model->id) {
                    $query->where('id', '!=', $model->id);
                }
                $exists = $query->exists();
                if($exists) {
                    throw new ApiException("同名按钮操作已存在");
                }
            }
        });
    }

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
