<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Query;

use Plexikon\Chronicle\Reporter\Query;
use Plexikon\DevApp\Model\User\Value\ActivationToken;

final class GetUserByActivationToken extends Query
{
    public function token(): ActivationToken
    {
        return ActivationToken::fromString($this->payload['token']);
    }
}
