<?php
namespace App\Filters;

abstract class AbstractFilter
{
    protected $filters = [];

    public function receivedFilters()
    {
        return request()->only(array_keys($this->filters));
    }
}