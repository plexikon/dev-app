<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Exception;

final class BadCredentials extends UserException
{
    public static function invalid(): self
    {
        return new self('invalid password');
    }
}
