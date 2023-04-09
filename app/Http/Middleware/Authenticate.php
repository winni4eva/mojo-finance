<?php

namespace App\Http\Middleware;

use App\Traits\HttpResponseTrait;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    use HttpResponseTrait;

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    protected function unauthenticated($request, array $guards)
    {
        abort($this->error('error', 'Unauthenticated.', Response::HTTP_UNAUTHORIZED));
    }
}
