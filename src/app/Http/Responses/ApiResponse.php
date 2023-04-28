<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Throwable;

class ApiResponse implements Responsable
{
    public function __construct(
        protected int $success,
        protected int $status,
        protected mixed $data = [],
        protected Throwable|null $exception = null,
        protected string|null $error = null,
        protected array $extra = [],
        protected array $headers = [],
    ) {
    }

    public function toResponse($request): JsonResponse
    {
        $response = [
            'success' => $this->success,
            'data' => $this->data,
            'error' => $this->error,
            'extra' => [],
        ];

        if (config('app.debug') && isset($this->exception)) {
            $response['errors'] = [
                'message' => $this->exception->getMessage(),
                'line' => $this->exception->getLine(),
                'trace' => $this->exception->getTrace(),
            ];
        }

        return Response::json(
            $response,
            $this->status,
            $this->headers
        );
    }
}
