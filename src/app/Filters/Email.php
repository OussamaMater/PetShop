<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Email
{
    public function handle(Builder $request, Closure $next): mixed
    {
        if (! request()->has('email')) {
            return $next($request);
        }

        return $next($request)->whereEmail(request()->input('email'));
    }
}
