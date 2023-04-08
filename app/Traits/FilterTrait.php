<?php

namespace App\Traits;

trait FilterTrait
{
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
