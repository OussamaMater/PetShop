<?php

namespace App\Filters;

use Closure;
use Illuminate\Http\Request;

class Email
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (! request()->has('email')) {
            return $next($request);
        }

        return $next($request)->whereEmail(request()->input('email'));
    }
}
