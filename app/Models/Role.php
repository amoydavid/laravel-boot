<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

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
}
