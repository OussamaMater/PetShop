<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUser
{
    public function handle(User $user, array $data): bool
    {
        return $user->forceFill([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'avatar' => $data['avatar'] ?? null,
            'address' => $data['address'],
            'phone_number' => $data['phone_number'],
            'is_marketing' => $data['is_marketing'] ?? false,
        ])->save();
    }
}
