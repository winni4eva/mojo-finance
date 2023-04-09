<?php

namespace App\Traits;

use App\Filters\FilterInterface;

trait FilterTrait
{
    public function scopeFilter($query, FilterInterface $filters)
    {
        return $filters->apply($query);
    }
}
