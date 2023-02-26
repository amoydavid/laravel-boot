<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'config'
    ];

    protected $casts = [
        'config' => 'json'
    ];

    public static function getValue($key, $default = null) {
        $cache_key = 'option.'.$key;
        $option = \Cache::get($cache_key);
        if($option === false || config('app.env') === 'local') {
            $option = self::query()->where('key', $key)->select('config')->first();
            if($option) {
                \Cache::set($cache_key, $option, 3600);
            }
        }
        if(!$option) {
            return $default;
        } else {
            return $option['config'];
        }
    }

    public static function saveValue($key, $value) {
        $option = self::query()->where('key', $key)->firstOrNew([
            'key' => $key
        ]);
        $option->config = $value;
        return $option->save();
    }
}
