<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class FirstName
{
    public function handle(Builder $request, Closure $next): mixed
    {
        if (! request()->has('first_name')) {
            return $next($request);
        }

        return $next($request)->where('first_name', request()->input('first_name'));
    }
}
