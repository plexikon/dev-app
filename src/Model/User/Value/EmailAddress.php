<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Value;

use Plexikon\DevApp\Exception\Assertion;
use Plexikon\DevApp\Shared\Model\Value;

final class EmailAddress implements Value
{
    private string $email;

    private function __construct(string $email)
    {
        $this->email = $email;
    }

    public static function fromString(string $email): self
    {
        Assertion::email($email);

        return new self($email);
    }

    public function toString(): string
    {
        return $this->email;
    }

    public function sameValueAs(Value $aValue): bool
    {
        return $aValue instanceof $this && $this->toString() === $aValue->toString();
    }
}
