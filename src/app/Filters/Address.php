<?php

namespace App\Filters;

use Closure;
use Illuminate\Http\Request;

class Address
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (! request()->has('address')) {
            return $next($request);
        }

        return $next($request)->where('address', 'like', '%'.request()->input('address').'%');
    }
}
