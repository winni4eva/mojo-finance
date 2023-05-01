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

    protected abstract function mutate(Account $builder);
}