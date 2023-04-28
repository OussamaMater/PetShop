<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class CreatedAt
{
    public function handle(Builder $request, Closure $next): mixed
    {
        if (! request()->has('created_at')) {
            return $next($request);
        }

        return $next($request)->whereDate('created_at', request()->input('created_at'));
    }
}
