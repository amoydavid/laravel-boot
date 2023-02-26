<?php

namespace App\Models;

use App\Traits\HasSettingsProperty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\HasApiTokens;

class WxUser extends Authenticatable
{
    use HasApiTokens, HasSettingsProperty;

    use HasFactory;

    const TYPE_MINI = 0; //小程序用户
    const TYPE_MP = 1; //公众号用户
    const TYPE_QY = 2; //企微用户


    protected $fillable = [
        'open_id', 'union_id', 'session_key', 'type',
    ];

    protected static function boot()
    {
        parent::boot();
    }


    public function isAdmin()
    {
        return $this->is_admin > 0;
    }
}
