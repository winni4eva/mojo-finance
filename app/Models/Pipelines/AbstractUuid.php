<?php

namespace App\Models\Pipelines;

use App\Models\Account;
use Closure;

abstract class AbstractUuid
{
    public function handle($request, Closure $next)
    {
        $builder = $next($request);

        return $this->mutate($builder);
    }

    abstract protected function mutate(Account $builder);
}
