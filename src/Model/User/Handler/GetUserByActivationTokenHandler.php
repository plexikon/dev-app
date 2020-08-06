<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Handler;

use Plexikon\DevApp\Model\User\Query\GetUserByActivationToken;
use Plexikon\DevApp\Projection\User\UserFinder;
use React\Promise\Deferred;

final class GetUserByActivationTokenHandler
{
    private UserFinder $userFinder;

    public function __construct(UserFinder $userFinder)
    {
        $this->userFinder = $userFinder;
    }

    public function query(GetUserByActivationToken $query, Deferred $promise): void
    {
        $user = $this->userFinder->userOfValidActivationToken($query->token()->toString());

        $promise->resolve($user);
    }
}
