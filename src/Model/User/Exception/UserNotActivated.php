<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Exception;

use Plexikon\DevApp\Model\User\Value\UserId;

final class UserNotActivated extends UserStatusException
{
    public static function withUserId(UserId $userId): self
    {
        return new self("User with id {$userId->toString()} not activated");
    }
}
