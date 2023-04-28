<?php

namespace Tests\Feature;

use App\Actions\AuthenticateUser;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AdminAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_successful_login()
    {
        $user = User::factory()->create([
            'password' => Hash::make('admin'),
            'is_admin' => true,
        ]);

        $authenticator = $this->mock(AuthenticateUser::class);
        $authenticator->shouldReceive('handle')
            ->once()
            ->with(['email' => $user->email, 'password' => 'admin'])
            ->andReturn($user);

        $request = new LoginRequest([
            'email' => $user->email,
            'password' => 'admin',
        ]);

        $response = $this->post(
            route('admin.login'),
            $request->toArray(),
            ['accept' => 'application/json']
        );

        $response->assertStatus(200);

        $response->assertJson([
            'success' => 1,
            'data' => [
                'token' => true,
            ],
        ]);
    }

    public function test_missing_credentials(): void
    {
        $request = new LoginRequest([]);

        $response = $this->post(
            route('admin.login'),
            $request->toArray(),
            ['accept' => 'application/json']
        );

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $authenticator = $this->mock(AuthenticateUser::class);
        $authenticator->shouldReceive('handle')
            ->once()
            ->with(['email' => $user->email, 'password' => 'incorrect_password'])
            ->andThrow(ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]));

        $request = new LoginRequest([
            'email' => $user->email,
            'password' => 'incorrect_password',
        ]);

        $response = $this->post(
            route('admin.login'),
            $request->toArray(),
            ['accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The provided credentials are incorrect.',
            'errors' => true,
        ]);
    }

    public function test_user_can_register()
    {
        $data = [
            'first_name' => 'oussama',
            'last_name' => 'oussama',
            'email' => 'oussama@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'avatar' => '990a9b41-aa1d-4927-93a2-44869a39e13f',
            'address' => 'tunis',
            'phone_number' => '999999',
        ];

        $response = $this->post(
            route('admin.create'),
            $data,
            ['accept' => 'application/json']
        );

        $response->assertStatus(201);
    }

    public function test_user_can_not_register_with_missing_infos()
    {
        $data = [];

        $response = $this->post(
            route('admin.create'),
            $data,
            ['accept' => 'application/json']
        );

        $response->assertStatus(422);
    }

    public function test_user_logout_successfully()
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $token = $user->createToken(
            now()->addMinutes(15),
            'test'
        );

        $this->assertEquals(1, $user->jwtToken->count());

        $response = $this->get(
            route('admin.logout'),
            [
                'accept' => 'application/json',
                'Authorization' => "Bearer $token",
            ]
        );

        $response->assertStatus(200);
        $this->assertEquals(0, $user->jwtToken->count());
    }

    public function test_user_cannot_user_logout_when_not_authenticated()
    {
        $response = $this->get(
            route('admin.logout'),
            [
                'accept' => 'application/json',
            ]
        );

        $response->assertStatus(401);
    }
}
