<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DictValue extends Model
{
    use HasFactory;
    use Filterable;

    protected $fillable = ['dict_id', 'alias', 'dict_value', 'dict_label', 'sort_order'];
    protected $filterable = ['dict_id', 'alias', 'dict_value', 'dict_label', 'sort_order'];

    /**
     * 字典转map数组
     * @param $dictAlias
     * @return array
     */
    public static function toMap($dictAlias)
    {
        $dictType = DictType::query()->where('alias', $dictAlias)->first();
        $allValue = self::query()->where('dict_id', $dictType->id)->orderBy('sort_order')->orderBy('id')->get();
        $map = [];
        foreach($allValue as $_item) {
            $value = $_item->dict_value;
            if(is_numeric($value)) {
                $value = intval($value);
            }
            $map[$value] = $_item->dict_label;
        }
        return $map;
    }
}
