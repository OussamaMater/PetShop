<?php

namespace App\Http\Controllers\V1\Auth;

use App\Actions\AuthenticateUser;
use App\Actions\CreateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;

class AdminAuthController extends Controller
{
    public function login(LoginRequest $request, AuthenticateUser $authenticator): ApiResponse
    {
        $user = $authenticator->handle(credentials: $request->validated());

        $token = $user->createToken(
            expiresAt: now()->addMinutes(config('pet-shop.expires_in')),
            tokenTitle: 'Admin Access Token'
        );

        return new ApiResponse(
            success: 1,
            status: 200,
            data: [
                'token' => $token,
            ]
        );
    }

    public function create(RegisterRequest $request, CreateUser $userCreator): ApiResponse
    {
        $user = $userCreator->handle(
            data: $request->all(),
            isAdmin: true,
        );

        return new ApiResponse(
            success: 1,
            status: 201,
            data: [
                new UserResource(
                    $user,
                    $user->createToken(
                        expiresAt: now()->addMinutes(config('pet-shop.expires_in')),
                        tokenTitle: 'Admin Access Token'
                    )
                ),
            ]
        );
    }

    public function logout(): ApiResponse
    {
        auth()->user()->jwtToken()->delete();  /** @phpstan-ignore-line */

        return new ApiResponse(
            success: 1,
            status: 200,
        );
    }
}
