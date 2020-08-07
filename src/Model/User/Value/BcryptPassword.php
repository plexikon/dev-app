<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Value;

use Plexikon\DevApp\Model\User\Exception\BadCredentials;

final class BcryptPassword extends EncodedPassword
{
    public static function fromString(string $encodedPassword): self
    {
        $info = password_get_info($encodedPassword);

        if ('bcrypt' !== $info['algoName'] ?? null) {
            throw BadCredentials::invalid();
        }

        return new static($encodedPassword);
    }
}
