<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_FUNCTION|Attribute::TARGET_METHOD|Attribute::TARGET_CLASS)]
class RbacTitle
{
    public string $title;

    public function __construct($title)
    {
        $this->title = $title;
    }
}
