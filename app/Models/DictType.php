<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DictType extends Model
{
    use HasFactory;
    use Filterable;

    protected $fillable = ['name', 'alias'];
    protected $filterable = ['name'];
}
