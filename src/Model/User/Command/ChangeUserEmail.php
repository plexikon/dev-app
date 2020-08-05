<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Command;

use Plexikon\Chronicle\Reporter\Command;
use Plexikon\DevApp\Model\User\Value\EmailAddress;
use Plexikon\DevApp\Model\User\Value\UserId;

final class ChangeUserEmail extends Command
{
    public static function forUser(string $userid, string $newEmail): self
    {
        return new self([
            'user_id' => $userid,
            'new_user_email' => $newEmail
        ]);
    }

    public function userId(): UserId
    {
        return UserId::fromString($this->payload['user_id']);
    }

    public function newEmail(): EmailAddress
    {
        return EmailAddress::fromString($this->payload['new_user_email']);
    }
}
