<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Address
{
    public function handle(Builder $request, Closure $next): mixed
    {
        if (! request()->has('address')) {
            return $next($request);
        }

        return $next($request)->where('address', 'like', '%'.request()->input('address').'%');
    }
}
