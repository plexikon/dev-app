<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Exception;

use Plexikon\DevApp\Model\User\Value\EmailAddress;
use Plexikon\DevApp\Model\User\Value\UserId;

final class UserAlreadyExists extends UserException
{
    public static function withUserId(UserId $userId): self
    {
        return new self("User with id {$userId->toString()} already exists");
    }

    public static function withEmail(EmailAddress $email): self
    {
        return new self("User with email {$email->toString()} already exists");
    }
}
