<?php

namespace App\Filters;

use Closure;
use Illuminate\Http\Request;

class Marketing
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (! request()->has('marketing')) {
            return $next($request);
        }

        return $next($request)->where('is_marketing', request()->boolean('marketing'));
    }
}
