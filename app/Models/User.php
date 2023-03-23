<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isSuperAdmin() {
        return $this->id == 1;
    }

    public function roles() {
        return $this->hasManyThrough(Role::class, UserRole::class);
    }

    public function roleRelations() {
        return $this->hasMany(UserRole::class);
    }

    public function updateRole($role_ids = [])
    {
        $old_role_ids = $this->roleRelations->pluck('role_id')->toArray();
        $keep_ids = array_intersect($role_ids, $old_role_ids);
        $delete_ids = array_diff($old_role_ids, $keep_ids);
        $add_ids = array_diff($role_ids, $keep_ids);
        return \DB::transaction(function () use($delete_ids, $add_ids) {
            if($delete_ids) {
                UserRole::where('user_id', $this->id)->whereIn('role_id', $delete_ids)->delete();
            }
            if($add_ids) {
                foreach($add_ids as $_id) {
                    $model = new UserRole();
                    $model->user_id = $this->id;
                    $model->role_id = $_id;
                    if(!$model->save()) {
                        return false;
                    }
                }
            }
            return true;
        });
    }
}
