<?php

namespace App\Filters;

abstract class AbstractFilter
{
    protected $filters = [];

    public function apply($query)
    {
        logger("applying filter");
        foreach ($this->receivedFilters() as $name => $value) {
            $filterInstance = new $this->filters[$name];
            logger($name);
            logger(serialize($filterInstance));
            $query = $filterInstance($query, $value);
        }

        return $query;
    }

    public function receivedFilters()
    {
        return request()->only(array_keys($this->filters));
    }
}
