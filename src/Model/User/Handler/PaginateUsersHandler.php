<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Handler;

use Plexikon\DevApp\Model\User\Query\PaginateUsers;
use Plexikon\DevApp\Projection\User\UserFinder;
use React\Promise\Deferred;

final class PaginateUsersHandler
{
    private UserFinder $userFinder;

    public function __construct(UserFinder $userFinder)
    {
        $this->userFinder = $userFinder;
    }

    public function query(PaginateUsers $query, Deferred $promise): void
    {
        $users = $this->userFinder->paginate(
            $query->limit(), $query->column(), $query->direction(), $query->scopes()
        );

        $promise->resolve($users);
    }
}
