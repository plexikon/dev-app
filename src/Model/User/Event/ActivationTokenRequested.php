<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Event;

use Plexikon\Chronicle\Chronicling\Aggregate\AggregateChanged;
use Plexikon\DevApp\Model\User\Value\ActivationTokenWithExpiration;
use Plexikon\DevApp\Model\User\Value\UserId;
use Plexikon\DevApp\Model\User\Value\UserStatus;

final class ActivationTokenRequested extends AggregateChanged
{
    private ?ActivationTokenWithExpiration $activationToken;
    private ?UserStatus $userStatus;

    public static function withData(UserId $userId,
                                    ActivationTokenWithExpiration $activationToken,
                                    UserStatus $userStatus): self
    {
        $self = self::occur($userId->toString(), [
            'activation_token' => $activationToken->token()->toString(),
            'token_expired_at' => $activationToken->formatExpiredAt()
        ]);

        $self->activationToken = $activationToken;
        $self->userStatus = $userStatus;

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
}
