<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticateUser
{
    public function handle(array $credentials): User
    {
        $user = User::whereEmail($credentials['email'])->first(); /** @phpstan-ignore-line */
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->updateLastLogin();

        return $user;
    }
}
