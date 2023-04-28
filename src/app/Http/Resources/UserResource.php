<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function __construct(
        $resource,
        protected string $token
    ) {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid, /** @phpstan-ignore-line */
            'first_name' => $this->first_name, /** @phpstan-ignore-line */
            'last_name' => $this->last_name, /** @phpstan-ignore-line */
            'email' => $this->email, /** @phpstan-ignore-line */
            'address' => $this->address, /** @phpstan-ignore-line */
            'phone_number' => $this->phone_number, /** @phpstan-ignore-line */
            'updated_at' => $this->updated_at, /** @phpstan-ignore-line */
            'created_at' => $this->created_at, /** @phpstan-ignore-line */
            'token' => $this->token,
        ];
    }
}
