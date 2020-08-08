<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Query;

use Plexikon\Chronicle\Reporter\Query;
use Plexikon\DevApp\Model\User\Value\UserId;

final class GetUserById extends Query
{
    public function userId(): UserId
    {
        return UserId::fromString($this->payload['user_id']);
    }
}
