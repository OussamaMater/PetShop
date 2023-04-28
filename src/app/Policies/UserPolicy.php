<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function update(User $admin, User $toUpdate): Response
    {
        return $toUpdate->isAdmin()
        ? Response::deny('Can\'t edit this resource.', 403)
        : Response::allow();
    }
}
