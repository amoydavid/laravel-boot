<?php

namespace App\Util;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ShardingScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $builder->from($model->getTable().$model::getSuffix());
    }


}
