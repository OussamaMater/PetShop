<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Marketing
{
    public function handle(Builder $request, Closure $next): mixed
    {
        if (! request()->has('marketing')) {
            return $next($request);
        }

        return $next($request)->where('is_marketing', request()->boolean('marketing'));
    }
}
