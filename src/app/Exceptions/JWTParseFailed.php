<?php

namespace App\Exceptions;

use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Throwable;

class JWTParseFailed extends Exception
{
    public function __construct(
        protected Throwable $exception
    ) {
    }

    public function render(): Responsable
    {
        return new ApiResponse(
            success: 0,
            status: 401,
            exception: $this->exception,
            error: 'Invalid Token.',
        );
    }
}
