<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User;

use Plexikon\Chronicle\Chronicling\Aggregate\Concerns\HasAggregateRoot;
use Plexikon\Chronicle\Support\Contract\Chronicling\Aggregate\AggregateId;
use Plexikon\Chronicle\Support\Contract\Chronicling\Aggregate\AggregateRoot;
use Plexikon\DevApp\Model\User\Event\UserEmailChanged;
use Plexikon\DevApp\Model\User\Event\UserPasswordChanged;
use Plexikon\DevApp\Model\User\Event\UserRegistered;
use Plexikon\DevApp\Model\User\Exception\InvalidActivationToken;
use Plexikon\DevApp\Model\User\Exception\UserAlreadyActivated;
use Plexikon\DevApp\Model\User\Value\ActivationTokenWithExpiration;
use Plexikon\DevApp\Model\User\Value\BcryptPassword;
use Plexikon\DevApp\Model\User\Value\EmailAddress;
use Plexikon\DevApp\Model\User\Value\UserId;
use Plexikon\DevApp\Model\User\Value\UserStatus;

final class User implements AggregateRoot
{
    use HasAggregateRoot;

    private EmailAddress $email;
    private BcryptPassword $password;
    private UserStatus $status;
    private ActivationTokenWithExpiration $activationToken;

    public static function register(UserId $userId, EmailAddress $email, BcryptPassword $password): self
    {
        $self = new static($userId);
        $self->recordThat(UserRegistered::withData($userId, $email, $password, UserStatus::PENDING_REGISTRATION()));

        return $self;
    }

    public function changeEmail(EmailAddress $newEmail): void
    {
        if ($this->email->sameValueAs($newEmail)) {
            return;
        }

        $this->recordThat(UserEmailChanged::forUser($this->userId(), $newEmail, $this->email));
    }

    public function changePassword(BcryptPassword $password): void
    {
        $this->recordThat(UserPasswordChanged::withData($this->userId(), $password, $this->password));
    }

    public function requestActivationToken(ActivationTokenWithExpiration $activationToken)
    {
        if ($this->isEnabled()) {
            throw UserAlreadyActivated::withUserId($this->userId());
        }

        if ($activationToken->isExpired()) {
            throw InvalidActivationToken::invalid($this->userId(), $activationToken);
        }

        if ($activationToken->sameValueAs($this->activationToken)) {
            return;
        }


    }

    public function isEnabled(): bool
    {
        return $this->status->sameValueAs(UserStatus::ACTIVATED());
    }

    public function isNotEnabled(): bool
    {
        return !$this->isEnabled();
    }

    public function applyUserRegistered(UserRegistered $event): void
    {
        $this->email = $event->email();
        $this->password = $event->password();
        $this->status = $event->userStatus();
    }

    public function applyUserEmailChanged(UserEmailChanged $event): void
    {
        $this->email = $event->currentEmail();
    }

    public function applyUserPasswordChanged(UserPasswordChanged $event): void
    {
        $this->password = $event->currentPassword();
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

    public function status(): UserStatus
    {
        return $this->status;
    }
}
