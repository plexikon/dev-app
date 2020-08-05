<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Value;

final class BcryptPassword extends EncodedPassword
{
    public static function fromString(string $encodedPassword): self
    {
        $info = password_get_info($encodedPassword);

        // todo

        return new static($encodedPassword);
    }
}
