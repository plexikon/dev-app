<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Event;

use Plexikon\Chronicle\Reporter\DomainEvent;
use Plexikon\DevApp\Model\User\Value\EmailAddress;

class NewActivationTokenRequested extends DomainEvent
{
    public static function withEmail(string $email): self
    {
        return new self(['email' => $email]);
    }

    public function email(): EmailAddress
    {
        return EmailAddress::fromString($this->payload['email']);
    }
}
