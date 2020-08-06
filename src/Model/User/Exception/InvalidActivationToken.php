<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Exception;

use Plexikon\DevApp\Model\User\Value\ActivationTokenWithExpiration;
use Plexikon\DevApp\Model\User\Value\UserId;

final class InvalidActivationToken extends ActivationTokenException
{
    public static function invalid(UserId $userId, ActivationTokenWithExpiration $activationToken): self
    {
        $message = "Activation token {$activationToken->token()->toString()} ";
        $message .= "for user id {$userId->toString()} not found or has been expired";

        return new self($message);
    }
}
