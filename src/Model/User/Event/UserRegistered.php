<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Event;

use Plexikon\Chronicle\Chronicling\Aggregate\AggregateChanged;
use Plexikon\DevApp\Model\User\Value\BcryptPassword;
use Plexikon\DevApp\Model\User\Value\EmailAddress;
use Plexikon\DevApp\Model\User\Value\UserId;

final class UserRegistered extends AggregateChanged
{
    private ?EmailAddress $email;
    private ?BcryptPassword $password;

    public static function withData(UserId $userId, EmailAddress $email, BcryptPassword $password): self
    {
        $self = self::occur($userId->toString(), [
            'email' => $email->toString(),
            'password' => $password->toString()
        ]);

        $self->email = $email;
        $self->password = $password;

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
}
