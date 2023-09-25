<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();
        // 删除时同时移除对应所有的用户角色，并且要重新登录
        self::deleted(function(Role $model) {
            $userIds = UserRole::where('role_id', $model->id)->get()->pluck('user_id');
            UserRole::where("role_id", $model->id)->delete();
            /**
             * @var User[] $users
             */
            $users = User::whereIn("id", $userIds)->get();
            foreach($users as $user) {
                $user->tokens()->delete();
            }
        });
    }

    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class, 'role_id', 'id');
    }

    public function updatePermissions($role_ids = [])
    {
        $old_ids = $this->rolePermissions->pluck('permission_id')->toArray();
        $keep_ids = array_intersect($role_ids, $old_ids);
        $delete_ids = array_diff($old_ids, $keep_ids);
        $add_ids = array_diff($role_ids, $keep_ids);
        return \DB::transaction(function () use($delete_ids, $add_ids) {
            if($delete_ids) {
                RolePermission::where('role_id', $this->id)->whereIn('permission_id', $delete_ids)->delete();
            }
            if($add_ids) {
                foreach($add_ids as $_id) {
                    $model = new RolePermission();
                    $model->role_id = $this->id;
                    $model->permission_id = $_id;
                    if(!$model->save()) {
                        return false;
                    }
                }
            }
            return true;
        });
    }
}
