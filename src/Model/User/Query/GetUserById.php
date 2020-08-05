<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Query;

use Plexikon\DevApp\Model\User\Value\UserId;

final class GetUserById
{
    private string $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public function userId(): UserId
    {
        return UserId::fromString($this->userId);
    }
}
