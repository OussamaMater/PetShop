<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Services\JWTService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Pest\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'is_admin',
        'uuid',
        'email',
        'password',
        'avatar',
        'address',
        'phone_number',
        'is_marketing',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_marketing' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function jwtToken(): HasOne
    {
        return $this->hasOne(JwtToken::class);
    }

    public function createToken(Carbon $expiresAt, string $tokenTitle): string
    {
        $this->jwtToken()->delete(); /** @phpstan-ignore-line */
        $tokenUniqueId = Str::random(60);

        $token = (app()->make(JWTService::class))
            ->setClaims([
                'uuid' => $this->uuid, /** @phpstan-ignore-line */
                'unique_id' => $tokenUniqueId,
            ])
            ->setExpiresAt($expiresAt)
            ->getToken();

        $this->jwtToken()->create([
            'unique_id' => $tokenUniqueId,
            'token_title' => $tokenTitle,
            'expires_at' => $expiresAt,
        ]);

        return $token->toString();
    }

    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;  /** @phpstan-ignore-line */
    }
}
