<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Handler;

use Plexikon\DevApp\Model\User\Query\GetUserById;
use Plexikon\DevApp\Projection\User\UserFinder;
use React\Promise\Deferred;

final class GetUserByIdHandler
{
    private UserFinder $userFinder;

    public function __construct(UserFinder $userFinder)
    {
        $this->userFinder = $userFinder;
    }

    public function query(GetUserById $query, Deferred $promise): void
    {
        $user = $this->userFinder->userOfId($query->userId()->toString());

        $promise->resolve($user);
    }
}
