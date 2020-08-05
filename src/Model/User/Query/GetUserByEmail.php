<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Query;

use Plexikon\DevApp\Model\User\Value\EmailAddress;

final class GetUserByEmail
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function email(): EmailAddress
    {
        return EmailAddress::fromString($this->email);
    }
}
