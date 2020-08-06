<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Handler;

use Plexikon\DevApp\Model\User\Command\ChangeUserPassword;
use Plexikon\DevApp\Model\User\Exception\BadCredentials;
use Plexikon\DevApp\Model\User\Exception\UserNotFound;
use Plexikon\DevApp\Model\User\Repository\UserCollection;
use Plexikon\DevApp\Model\User\Service\PasswordEncoder;

final class ChangeUserPasswordHandler
{
    private UserCollection $userCollection;
    private PasswordEncoder $encoder;

    public function __construct(UserCollection $userCollection, PasswordEncoder $encoder)
    {
        $this->userCollection = $userCollection;
        $this->encoder = $encoder;
    }

    public function command(ChangeUserPassword $command): void
    {
        $userId = $command->userId();

        if (!$user = $this->userCollection->get($userId)) {
            throw UserNotFound::withUserId($userId);
        }

        if (!$this->encoder->check($command->currentPassword(), $user->password())) {
            throw BadCredentials::invalid();
        }

        $newEncodedPassword = $this->encoder->encode($command->newPasswordWithConfirmation());

        $user->changePassword($newEncodedPassword);

        $this->userCollection->store($user);
    }
}
