<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Command;

use Plexikon\Chronicle\Reporter\Command;
use Plexikon\DevApp\Model\User\Value\ClearPassword;
use Plexikon\DevApp\Model\User\Value\EmailAddress;
use Plexikon\DevApp\Model\User\Value\UserId;

final class RegisterUser extends Command
{
    public static function withData(string $userId, string $email, string $clearPassword): self
    {
        return new self([
            'user_id' => $userId,
            'user_email' => $email,
            'clear_password' => $clearPassword
        ]);
    }

    public function userId(): UserId
    {
        return UserId::fromString($this->payload['user_id']);
    }

    public function email(): EmailAddress
    {
        return EmailAddress::fromString($this->payload['email']);
    }

    public function clearPassword(): ClearPassword
    {
        return ClearPassword::fromString($this->payload['clear_password']);
    }
}
