<?php

namespace Tests\Feature;

use App\Exceptions\JWTParseFailed;
use App\Services\JWTService;
use Carbon\Carbon;
use Lcobucci\JWT\Signer\Hmac\Sha256 as Symertic;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Lcobucci\JWT\Validation\Validator;
use Tests\TestCase;

class JWTServiceTest extends TestCase
{
    public function test_set_claims()
    {
        $service = app()->make(JWTService::class);

        $claims = ['foo' => 'bar'];
        $service->setClaims($claims);

        $this->assertEquals($claims, $service->getToken()->claims()->all());
    }

    public function test_set_headers()
    {
        $service = app()->make(JWTService::class);

        $headers = ['alg' => 'RS256', 'typ' => 'JWT'];
        $service->setHeaders($headers);

        $this->assertEquals($headers, $service->getToken()->headers()->all());
    }

    public function test_set_expires_at()
    {
        $service = app()->make(JWTService::class);

        $expiresAt = Carbon::now()->addHour();
        $service->setExpiresAt($expiresAt);

        $this->assertEquals($expiresAt, $service->getToken()->claims()->get('exp'));
    }

    public function test_get_token()
    {
        $service = app()->make(JWTService::class);

        $claims = ['foo' => 'bar'];
        $headers = ['alg' => 'RS256', 'typ' => 'JWT'];
        $expiresAt = Carbon::now()->addHour();

        $token = $service
            ->setClaims($claims)
            ->setHeaders($headers)
            ->setExpiresAt($expiresAt)
            ->getToken();

        $this->assertArrayHasKey('foo', $token->claims()->all());
        $this->assertArrayHasKey('exp', $token->claims()->all());
        $this->assertEquals($headers, $token->headers()->all());
        $this->assertEquals($expiresAt, $token->claims()->get('exp'));
    }

    public function test_parse_token()
    {
        $service = app()->make(JWTService::class);

        $token = $service
            ->setClaims(['foo' => 'bar'])
            ->getToken()
            ->toString();

        $parsedToken = $service->parseToken($token);

        $this->assertEquals('bar', $parsedToken->claims()->get('foo'));
    }

    public function test_parse_invalid_token()
    {
        $service = app()->make(JWTService::class);

        $this->expectException(JWTParseFailed::class);

        $service->parseToken('will_not_be_parsed');
    }

    public function test_validate_token_with_valid_signature_should_return_true()
    {
        $service = app()->make(JWTService::class);

        $claims = ['foo' => 'bar'];

        $token = $service->setClaims($claims)->getToken();

        $isValid = $service->validateToken($token);

        $this->assertTrue($isValid);
    }

    public function test_validate_token_with_invalid_signature_should_throw_exception()
    {
        $service = app()->make(JWTService::class);

        $invalidAlgorithm = new Symertic();
        $invalidKey = InMemory::plainText(random_bytes(32));

        $claims = ['foo' => 'bar'];

        $token = $service->setClaims($claims)->getToken();

        $validator = new Validator();
        $this->assertThrows(
            function () use ($validator, $token, $invalidAlgorithm, $invalidKey) {
                $validator->assert($token, new SignedWith($invalidAlgorithm, $invalidKey));
            },
            RequiredConstraintsViolated::class
        );
    }
}
