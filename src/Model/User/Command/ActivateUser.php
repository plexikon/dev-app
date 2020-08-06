<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Command;

use Plexikon\Chronicle\Reporter\Command;
use Plexikon\DevApp\Model\User\Value\ActivationTokenWithExpiration;
use Plexikon\DevApp\Model\User\Value\UserId;

final class ActivateUser extends Command
{
    public static function forUser($userId, string $token, string $tokenExpiredAt): self
    {
        return new self([
            'user_id' => $userId,
            'activation_token' => $token,
            'token_expired_at' => $tokenExpiredAt
        ]);
    }

    public function userId(): UserId
    {
        return UserId::fromString($this->payload['user_id']);
    }

    public function activationToken(): ActivationTokenWithExpiration
    {
        return ActivationTokenWithExpiration::fromString(
            $this->payload['activation_token'],
            $this->payload['token_expired_at'],
        );
    }
}
