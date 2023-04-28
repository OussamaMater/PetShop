<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateUser
{
    public function handle(array $data, bool $isAdmin): User
    {
        /** @phpstan-ignore-next-line */
        return User::create([
            'uuid' => Str::orderedUuid(),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'avatar' => $data['avatar'],
            'address' => $data['address'],
            'phone_number' => $data['phone_number'],
            'is_marketing' => $data['is_marketing'] ?? null,
            'is_admin' => $isAdmin,
        ]);
    }
}
