<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Phone
{
    public function handle(Builder $request, Closure $next): mixed
    {
        if (! request()->has('phone')) {
            return $next($request);
        }

        return $next($request)->where('phone_number', request()->input('phone'));
    }
}
