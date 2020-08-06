<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Value;

use Illuminate\Support\Str;
use Plexikon\DevApp\Exception\Assertion;
use Plexikon\DevApp\Shared\Model\Value;

final class ActivationToken implements Value
{
    public const LENGTH = 32;

    private string $token;

    private function __construct(string $token)
    {
        $this->token = $token;
    }

    public static function fromString(string $token): self
    {
        Assertion::length($token, self::LENGTH);

        return new self($token);
    }

    public static function create(): self
    {
        return new self(Str::random(self::LENGTH));
    }

    public function toString(): string
    {
        return $this->token;
    }

    public function sameValueAs(Value $aValue): bool
    {
        return $aValue instanceof $this && $this->toString() === $aValue->toString();
    }
}
