<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response|ApiResponse
    {
        if (auth()->check() && auth()->user()->isAdmin()) { /** @phpstan-ignore-line */
            return $next($request);
        }

        return new ApiResponse(
            success: 0,
            status: 403,
            error: 'You don\'t have permission to access this resource'
        );
    }
}
