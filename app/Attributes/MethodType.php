<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_FUNCTION|Attribute::TARGET_METHOD|Attribute::TARGET_CLASS)]
class MethodType
{
    public string $type;

    public function __construct($type)
    {
        $this->type = $type;
    }
}
