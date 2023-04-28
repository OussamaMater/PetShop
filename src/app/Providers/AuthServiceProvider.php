<?php

namespace App\Providers;

use App\Models\JwtToken;
use App\Services\JWTService;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $jwtService = app()->make(JWTService::class);

        Auth::viaRequest('jwt', function (Request $request) use ($jwtService) {
            if (! $request->bearerToken()) {
                return null;
            }

            $token = $jwtService->parseToken($request->bearerToken());

            // Check if the token is valid.
            if (! $jwtService->validateToken($token) || $token->isExpired(now()->toDateTimeImmutable())) {
                return null;
            }

            // Return the user associated with the token, or null (the user logged out) which triggers a 401.
            if (! ($token = JwtToken::whereUniqueId($token->claims()->get('unique_id'))->first())) {
                return null;
            }

            $token->update(['last_used_at' => now()]);

            return $token->user;
        });
    }
}
