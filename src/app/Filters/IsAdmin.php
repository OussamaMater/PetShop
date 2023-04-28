<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class IsAdmin
{
    public function handle(Builder $request, Closure $next): mixed
    {
        return $next($request)->where('is_admin', false);
    }
}
