<?php

namespace App\Filters;

use Closure;
use Illuminate\Http\Request;

class Phone
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (! request()->has('phone')) {
            return $next($request);
        }

        return $next($request)->where('phone_number', request()->input('phone'));
    }
}
