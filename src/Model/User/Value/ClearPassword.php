<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Value;

use Assert\AssertionFailedException;
use Plexikon\DevApp\Exception\Assertion;

class ClearPassword extends Password
{
    const MIN_LENGTH = 10;
    const MAX_LENGTH = 255;

    public static function fromString(string $clearPassword): self
    {
        self::validate($clearPassword);

        return new static($clearPassword);
    }

    /**
     * @param string $clearPassword
     * @return bool
     * @throws AssertionFailedException
     */
    public static function validate(string $clearPassword): bool
    {
        Assertion::between($clearPassword, self::MIN_LENGTH, self::MAX_LENGTH);

        return true;
    }
}
