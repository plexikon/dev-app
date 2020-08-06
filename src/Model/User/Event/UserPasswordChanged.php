<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Event;

use Plexikon\Chronicle\Chronicling\Aggregate\AggregateChanged;
use Plexikon\DevApp\Model\User\Value\BcryptPassword;
use Plexikon\DevApp\Model\User\Value\UserId;

final class UserPasswordChanged extends AggregateChanged
{
    private ?BcryptPassword $currentPassword;
    private ?BcryptPassword $oldPassword;

    public static function withData(UserId $userId,
                                    BcryptPassword $currentPassword,
                                    BcryptPassword $oldPassword): self
    {
        $self = self::occur($userId->toString(), [
            'user_current_password' => $currentPassword->toString(),
            'user_old_password' => $oldPassword->toString()
        ]);

        $self->currentPassword = $currentPassword;
        $self->oldPassword = $oldPassword;

        return $self;
    }

    public function userId(): UserId
    {
        return UserId::fromString($this->aggregateRootId());
    }

    public function currentPassword(): BcryptPassword
    {
        return $this->currentPassword ?? BcryptPassword::fromString($this->payload['user_current_password']);
    }

    public function oldPassword(): BcryptPassword
    {
        return $this->oldPassword ?? BcryptPassword::fromString($this->payload['user_old_password']);
    }
}
