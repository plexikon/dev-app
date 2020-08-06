<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Event;

use Plexikon\Chronicle\Chronicling\Aggregate\AggregateChanged;
use Plexikon\DevApp\Model\User\Value\ActivationTokenWithExpiration;
use Plexikon\DevApp\Model\User\Value\UserId;
use Plexikon\DevApp\Model\User\Value\UserStatus;

final class UserActivated extends AggregateChanged
{
    private ?ActivationTokenWithExpiration $activationToken;
    private ?UserStatus $currentUserStatus;
    private ?UserStatus $oldUserStatus;

    public static function forUser(UserId $userId,
                                   ActivationTokenWithExpiration $activationToken,
                                   UserStatus $currentUserStatus,
                                   UserStatus $oldUserStatus): self
    {
        $self = self::occur($userId->toString(), [
            'activation_token' => $activationToken->token()->toString(),
            'token_expired_at' => $activationToken->formatExpiredAt()
        ]);

        $self->activationToken = $activationToken;
        $self->currentUserStatus = $currentUserStatus;
        $self->oldUserStatus = $oldUserStatus;

        return $self;
    }

    public function userId(): UserId
    {
        return UserId::fromString($this->aggregateRootId());
    }

    public function activationToken(): ActivationTokenWithExpiration
    {
        return $this->activationToken ?? ActivationTokenWithExpiration::fromString(
                $this->payload['activation_token'],
                $this->payload['token_expired_at']
            );
    }

    public function currentUserStatus(): UserStatus
    {
        return $this->currentUserStatus ?? UserStatus::byValue($this->payload['current_user_status']);
    }

    public function oldUserStatus(): UserStatus
    {
        return $this->oldUserStatus ?? UserStatus::byValue($this->payload['old_user_status']);
    }
}
