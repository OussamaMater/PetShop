<?php

namespace App\Providers;

use App\Services\JWTService;
use Illuminate\Support\ServiceProvider;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class JWTServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(JWTService::class, function () {
            $algorithm = new Sha256();
            $privateKey = InMemory::file(storage_path('keys/private.key'));
            $publicKey = InMemory::file(storage_path('keys/public.key'));

            return new JWTService(
                $privateKey,
                $publicKey,
                $algorithm
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
