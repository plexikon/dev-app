<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Query;

use Plexikon\Chronicle\Reporter\Query;
use Plexikon\DevApp\Model\User\Value\EmailAddress;

final class GetUserByEmail extends Query
{
    public function email(): EmailAddress
    {
        return EmailAddress::fromString($this->payload['email']);
    }
}
