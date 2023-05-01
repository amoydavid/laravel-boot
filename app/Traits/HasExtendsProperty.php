<?php


namespace App\Traits;

use Illuminate\Support\Fluent;

/**
 * @property \Illuminate\Support\Fluent $settings
 */
trait HasExtendsProperty
{
    public function setExtendsAttribute(array $settings)
    {
        $this->attributes['extends'] = json_encode($settings);
    }

    public function getExtendsAttribute(): Fluent
    {
        return new Fluent($this->getExtends());
    }

    public function getExtends(): array
    {
        return \array_replace_recursive(\defined('static::DEFAULT_EXTENDS') ? \constant('static::DEFAULT_EXTENDS') : [], \json_decode($this->attributes['extends'] ?? '{}', true) ?? []);
    }
}
