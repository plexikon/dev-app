<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Event;

use Plexikon\Chronicle\Chronicling\Aggregate\AggregateChanged;
use Plexikon\DevApp\Model\User\Value\EmailAddress;
use Plexikon\DevApp\Model\User\Value\UserId;

final class UserEmailChanged extends AggregateChanged
{
    private ?EmailAddress $currentEmail;
    private ?EmailAddress $oldEmail;

    public static function forUser(UserId $userId, EmailAddress $currentEmail, EmailAddress $oldEmail): self
    {
        $self = self::occur($userId->toString(), [
            'current_email' => $currentEmail->toString(),
            'old_email' => $oldEmail->toString()
        ]);

        $self->currentEmail = $currentEmail;
        $self->oldEmail = $oldEmail;

        return $self;
    }

    public function userId(): UserId
    {
        return UserId::fromString($this->aggregateRootId());
    }

    public function currentEmail(): EmailAddress
    {
        return $this->currentEmail ?? EmailAddress::fromString($this->payload['current_email']);
    }

    public function oldEmail(): EmailAddress
    {
        return $this->old ?? EmailAddress::fromString($this->payload['old_email']);
    }
}
