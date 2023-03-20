<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'parent_id', 'path', 'title', 'component',
        'show_parent', 'frame_src', 'rank', 'icon', 'type', 'hidden',
    ];
}
