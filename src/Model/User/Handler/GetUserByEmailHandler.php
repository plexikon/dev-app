<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Handler;

use Plexikon\DevApp\Model\User\Query\GetUserByEmail;
use Plexikon\DevApp\Projection\User\UserFinder;
use React\Promise\Deferred;

final class GetUserByEmailHandler
{
    private UserFinder $userFinder;

    public function __construct(UserFinder $userFinder)
    {
        $this->userFinder = $userFinder;
    }

    public function query(GetUserByEmail $query, Deferred $promise): void
    {
        $user = $this->userFinder->userOfEmail($query->email()->toString());

        $promise->resolve($user);
    }
}
