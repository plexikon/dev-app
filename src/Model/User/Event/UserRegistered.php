<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Event;

use Plexikon\Chronicle\Chronicling\Aggregate\AggregateChanged;
use Plexikon\DevApp\Model\User\Value\BcryptPassword;
use Plexikon\DevApp\Model\User\Value\EmailAddress;
use Plexikon\DevApp\Model\User\Value\UserId;
use Plexikon\DevApp\Model\User\Value\UserStatus;

final class UserRegistered extends AggregateChanged
{
    private ?EmailAddress $email;
    private ?BcryptPassword $password;
    private ?UserStatus $userStatus;

    public static function withData(UserId $userId, EmailAddress $email, BcryptPassword $password, UserStatus $userStatus): self
    {
        $self = self::occur($userId->toString(), [
            'email' => $email->toString(),
            'password' => $password->toString(),
            'status' => $userStatus->getValue()
        ]);

        $self->email = $email;
        $self->password = $password;
        $self->userStatus = $userStatus;

        return $self;
    }

    public function userId(): UserId
    {
        return UserId::fromString($this->aggregateRootId());
    }

    public function email(): EmailAddress
    {
        return $this->email ?? EmailAddress::fromString($this->payload['email']);
    }

    public function password(): BcryptPassword
    {
        return $this->password ?? BcryptPassword::fromString($this->payload['password']);
    }

    public function userStatus(): UserStatus
    {
        return $this->userStatus ?? UserStatus::byValue($this->password['status']);
    }
}
