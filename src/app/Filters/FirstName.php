<?php

namespace App\Filters;

use Closure;
use Illuminate\Http\Request;

class FirstName
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (! request()->has('first_name')) {
            return $next($request);
        }

        return $next($request)->where('first_name', request()->input('first_name'));
    }
}
