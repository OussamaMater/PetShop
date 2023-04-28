<?php

namespace App\Http\Controllers\V1\Admin;

use App\Actions\UpdateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserEditRequest;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class AdminUserController extends Controller
{
    public function list(Request $request): ApiResponse
    {
        $users = app(Pipeline::class)
            ->send(User::query())
            ->through([
                \App\Filters\Address::class,
                \App\Filters\Email::class,
                \App\Filters\FirstName::class,
                \App\Filters\Phone::class,
                \App\Filters\Marketing::class,
                \App\Filters\CreatedAt::class,
                \App\Filters\IsAdmin::class,
            ])
            ->thenReturn();

        return new ApiResponse(
            success: 1,
            status: 200,
            data: $users->paginate($request->input('limit') ?? 5)
        );
    }

    public function edit(User $user, UserEditRequest $request, UpdateUser $updater): ApiResponse
    {
        $this->authorize('update', $user);

        $updater->handle(
            user: $user,
            data: $request->validated()
        );

        return new ApiResponse(
            success: 1,
            status: 204
        );
    }

    public function delete(User $user): ApiResponse
    {
        $this->authorize('update', $user);

        $user->delete();

        return new ApiResponse(
            success: 1,
            status: 204
        );
    }
}
