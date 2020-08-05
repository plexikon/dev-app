<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Handler;

use Plexikon\DevApp\Model\User\Command\ChangeUserEmail;
use Plexikon\DevApp\Model\User\Exception\UserAlreadyExists;
use Plexikon\DevApp\Model\User\Exception\UserNotFound;
use Plexikon\DevApp\Model\User\Repository\UserCollection;
use Plexikon\DevApp\Model\User\Service\UniqueEmail;

final class ChangeUserEmailHandler
{
    private UserCollection $userCollection;
    private UniqueEmail $uniqueEmail;

    public function __construct(UserCollection $userCollection, UniqueEmail $uniqueEmail)
    {
        $this->userCollection = $userCollection;
        $this->uniqueEmail = $uniqueEmail;
    }

    public function command(ChangeUserEmail $command): void
    {
        $userId = $command->userId();

        if (!$user = $this->userCollection->get($userId)) {
            throw UserNotFound::withUserId($userId);
        }

        $newEmail = $command->newEmail();

        if ($aUserid = ($this->uniqueEmail)($newEmail)) {
            throw UserAlreadyExists::withEmail($newEmail);
        }

        $user->changeEmail($newEmail);

        $this->userCollection->store($user);
    }
}
