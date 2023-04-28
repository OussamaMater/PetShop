<?php

namespace App\Services;

use App\Exceptions\JWTParseFailed;
use Carbon\Carbon;
use DateTimeImmutable;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Validator;

class JWTService
{
    private array $claims = [];

    private array $headers = [];

    private DateTimeImmutable $expiresAt;

    public function __construct(
        private Key $privateKey,
        private Key $publicKey,
        private Rsa $algorithm
    ) {
    }

    public function setClaims(array $claims): self
    {
        $this->claims = $claims;

        return $this;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function setExpiresAt(Carbon $date): self
    {
        $this->expiresAt = $date->toDateTimeImmutable();

        return $this;
    }

    public function getToken(): UnencryptedToken
    {
        $builder = new Builder(new JoseEncoder(), ChainedFormatter::default());

        $builder
            ->issuedBy(config('pet-shop.issuedBy'))
            ->issuedAt(now()->toDateTimeImmutable());

        if (isset($this->expiresAt)) {
            $builder = $builder->expiresAt($this->expiresAt);
        }

        foreach ($this->claims as $key => $value) {
            $builder = $builder->withClaim($key, $value); // @phpstan-ignore-line
        }

        foreach ($this->headers as $key => $value) {
            $builder = $builder->withHeader($key, $value); // @phpstan-ignore-line
        }

        return $builder->getToken($this->algorithm, $this->privateKey);
    }

    public function parseToken(string $token): Token
    {
        $parser = new Parser(new JoseEncoder());

        try {
            return $parser->parse($token); // @phpstan-ignore-line
        } catch (CannotDecodeContent|InvalidTokenStructure|UnsupportedHeaderFound $e) {
            throw new JWTParseFailed($e);
        }
    }

    public function validateToken(Token $token): bool
    {
        return (new Validator())
            ->validate(
                $token,
                new SignedWith(
                    $this->algorithm,
                    $this->publicKey
                )
            );
    }
}
