<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Handler;

use Plexikon\DevApp\Model\User\Command\RenewActivationToken;
use Plexikon\DevApp\Model\User\Command\RequestActivationToken;
use Plexikon\DevApp\Model\User\Exception\UserNotFound;
use Plexikon\DevApp\Model\User\Repository\UserCollection;

final class RenewActivationTokenHandler
{
    private UserCollection $userCollection;

    public function __construct(UserCollection $userCollection)
    {
        $this->userCollection = $userCollection;
    }

    public function command(RenewActivationToken $command): void
    {
        $userId = $command->userId();

        if (!$user = $this->userCollection->get($userId)) {
            throw UserNotFound::withUserId($userId);
        }

        $user->renewActivationToken($command->activationToken());

        $this->userCollection->store($user);
    }
}
