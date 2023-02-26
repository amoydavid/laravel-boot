<?php

namespace App\Kernel;

/**
 * App\Kernel\ShardModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ShardModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShardModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShardModel query()
 * @mixin \Eloquent
 */
class ShardModel extends \Illuminate\Database\Eloquent\Model
{
    //设置静态表后缀
    protected static $suffix= null;

    public function getTable()
    {
        $tableName = parent::getTable();
        $suffix = (static::$suffix ? ('_'.static::$suffix) : '');
        if($suffix && !\Str::endsWith($tableName, $suffix)) {
            $tableName .= $suffix;
        }
        // \Log::info($tableName, debug_backtrace());
        return $tableName;
    }

    public function setSuffix($tableSuffix=null)
    {
        static::$suffix = null;
        if(!empty($tableSuffix)){
            static::$suffix = $tableSuffix;
        }
        return $this;
    }

    public static function setTableSuffix($suffix){
        $instance = new static;
        $instance->setSuffix($suffix);
        return $instance->newQuery();
    }
}
