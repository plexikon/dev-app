<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User;

use Plexikon\Chronicle\Chronicling\Aggregate\Concerns\HasAggregateRoot;
use Plexikon\Chronicle\Support\Contract\Chronicling\Aggregate\AggregateId;
use Plexikon\Chronicle\Support\Contract\Chronicling\Aggregate\AggregateRoot;
use Plexikon\DevApp\Model\User\Event\UserEmailChanged;
use Plexikon\DevApp\Model\User\Event\UserRegistered;
use Plexikon\DevApp\Model\User\Value\BcryptPassword;
use Plexikon\DevApp\Model\User\Value\EmailAddress;
use Plexikon\DevApp\Model\User\Value\UserId;

final class User implements AggregateRoot
{
    use HasAggregateRoot;

    private ?EmailAddress $email;
    private ?BcryptPassword $password;

    public static function register(UserId $userId, EmailAddress $email, BcryptPassword $password): self
    {
        $self = new static($userId);
        $self->recordThat(UserRegistered::withData($userId, $email, $password));

        return $self;
    }

    public function changeEmail(EmailAddress $newEmail): void
    {
        if ($this->email->sameValueAs($newEmail)) {
            return;
        }

        $this->recordThat(UserEmailChanged::forUser($this->userId(), $newEmail, $this->email));
    }

    public function applyUserRegistered(UserRegistered $event): void
    {
        $this->email = $event->email();
        $this->password = $event->password();
    }

    public function applyUserEmailChanged(UserEmailChanged $event): void
    {
        $this->email = $event->currentEmail();
    }

    /**
     * @return UserId|AggregateId
     */
    public function userId(): UserId
    {
        return $this->aggregateId();
    }

    public function email(): EmailAddress
    {
        return $this->email;
    }

    public function password(): BcryptPassword
    {
        return $this->password;
    }
}
