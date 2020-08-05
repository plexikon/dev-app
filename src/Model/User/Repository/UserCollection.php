<?php

namespace Plexikon\DevApp\Model\User\Repository;

use Plexikon\Chronicle\Support\Contract\Chronicling\Aggregate\AggregateRoot;
use Plexikon\DevApp\Model\User\User;
use Plexikon\DevApp\Model\User\Value\UserId;

interface UserCollection
{
    /**
     * @param UserId $userId
     * @return User|AggregateRoot|null
     */
    public function get(UserId $userId): ?User;

    /**
     * @param User $user
     */
    public function store(User $user): void;
}
