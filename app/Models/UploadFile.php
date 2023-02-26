<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadFile extends Model
{
    use HasFactory;

    /**
     * @return WxUser|User|\Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function user() {
        return $this->morphTo();
    }
}
