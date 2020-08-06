<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Command;

use Plexikon\Chronicle\Reporter\Command;
use Plexikon\DevApp\Model\User\Value\ClearPassword;
use Plexikon\DevApp\Model\User\Value\ClearPasswordWithConfirmation;
use Plexikon\DevApp\Model\User\Value\UserId;

final class ChangeUserPassword extends Command
{
    public static function forUser(string $userId,
                                   string $currentPassword,
                                   string $newPassword,
                                   string $newPasswordConfirmed): self
    {
        return new self([
            'user_id' => $userId,
            'user_current_password' => $currentPassword,
            'user_new_password' => $newPassword,
            'user_new_password_confirmed' => $newPasswordConfirmed,
        ]);
    }

    public function userId(): UserId
    {
        return UserId::fromString($this->payload['user_id']);
    }

    public function currentPassword(): ClearPassword
    {
        return ClearPassword::fromString($this->payload['user_current_password']);
    }

    public function newPasswordWithConfirmation(): ClearPasswordWithConfirmation
    {
        return ClearPasswordWithConfirmation::fromString(
            $this->payload['user_new_password'],
            $this->payload['user_new_password_confirmed'],
        );
    }
}
