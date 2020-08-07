<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Event;

use Plexikon\Chronicle\Chronicling\Aggregate\AggregateChanged;
use Plexikon\DevApp\Model\User\Value\ActivationTokenWithExpiration;
use Plexikon\DevApp\Model\User\Value\UserId;
use Plexikon\DevApp\Model\User\Value\UserStatus;

final class ActivationTokenRequested extends AggregateChanged
{
    private ?ActivationTokenWithExpiration $oldToken = null;
    private ?ActivationTokenWithExpiration $currentToken;
    private ?UserStatus $userStatus;

    public static function forUser(UserId $userId,
                                   ActivationTokenWithExpiration $currentToken,
                                   ?ActivationTokenWithExpiration $oldToken,
                                   UserStatus $userStatus): self
    {
        $self = self::occur($userId->toString(), [
            'current_token' => [
                'token' => $currentToken->token()->toString(),
                'expired_at' => $currentToken->formatExpiredAt(),
            ],
            'old_token' => null === $oldToken ? null : [
                'token' => $oldToken->token()->toString(),
                'expired_at' => $oldToken->formatExpiredAt(),
            ],
            'user_status' => $userStatus->getValue()
        ]);

        $self->currentToken = $currentToken;
        $self->oldToken = $oldToken;
        $self->userStatus = $userStatus;

        return $self;
    }

    public function userId(): UserId
    {
        return UserId::fromString($this->aggregateRootId());
    }

    public function currentActivationToken(): ActivationTokenWithExpiration
    {
        return $this->currentToken ?? ActivationTokenWithExpiration::fromString(
                $this->payload['current_token']['token'],
                $this->payload['current_token']['expired_at']
            );
    }

    public function oldActivationToken(): ?ActivationTokenWithExpiration
    {
        if ($this->oldToken) {
            return $this->oldToken;
        }

        if (null === $this->payload['old_token']) {
            return null;
        }

        return ActivationTokenWithExpiration::fromString(
            $this->payload['old_token']['token'],
            $this->payload['old_token']['expired_at']
        );
    }

    public function userStatus(): UserStatus
    {
        return $this->userStatus ?? UserStatus::byValue($this->payload['user_status']);
    }
}
