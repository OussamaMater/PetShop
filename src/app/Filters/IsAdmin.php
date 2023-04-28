<?php

namespace App\Filters;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next): mixed
    {
        return $next($request)->where('is_admin', false);
    }
}
