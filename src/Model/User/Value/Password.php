<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Value;

use Plexikon\DevApp\Shared\Model\Value;
use function get_class;

abstract class Password implements Value
{
    private string $password;

    protected function __construct(string $password)
    {
        $this->password = $password;
    }

    public function toString(): string
    {
        return $this->password;
    }

    public function sameValueAs(Value $aValue): bool
    {
        return static::class === get_class($aValue)
            && $this->toString() === $aValue->toString();
    }
}
