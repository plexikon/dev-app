<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Value;

use Plexikon\DevApp\Exception\Assertion;

final class ClearPasswordWithConfirmation extends ClearPassword
{
    public static function fromString(string $clearPassword, ?string $clearPasswordConfirmed = null): self
    {
        self::validate($clearPasswordConfirmed);

        Assertion::eq($clearPassword, $clearPasswordConfirmed);

        return new static($clearPassword);
    }
}
