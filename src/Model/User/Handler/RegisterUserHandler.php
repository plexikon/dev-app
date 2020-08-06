<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Handler;

use Plexikon\DevApp\Infrastructure\Service\BcryptPasswordEncoder;
use Plexikon\DevApp\Model\User\Command\RegisterUser;
use Plexikon\DevApp\Model\User\Exception\UserAlreadyExists;
use Plexikon\DevApp\Model\User\Service\PasswordEncoder;
use Plexikon\DevApp\Model\User\Repository\UserCollection;
use Plexikon\DevApp\Model\User\Service\UniqueEmail;
use Plexikon\DevApp\Model\User\User;

final class RegisterUserHandler
{
    private UserCollection $userCollection;
    private UniqueEmail $uniqueEmail;

    /**
     * @var PasswordEncoder|BcryptPasswordEncoder
     */
    private PasswordEncoder $passwordEncoder;

    public function __construct(UserCollection $userCollection,
                                UniqueEmail $uniqueEmail,
                                PasswordEncoder $passwordEncoder)
    {
        $this->userCollection = $userCollection;
        $this->uniqueEmail = $uniqueEmail;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function command(RegisterUser $command): void
    {
        $userId = $command->userId();

        if ($aUser = $this->userCollection->get($userId)) {
            throw UserAlreadyExists::withUserId($userId);
        }

        $email = $command->email();

        if ($aUserid = ($this->uniqueEmail)($email)) {
            throw UserAlreadyExists::withEmail($email);
        }

        $encodedPassword = $this->passwordEncoder->encode($command->clearPassword());

        $user = User::register($userId, $email, $encodedPassword);

        $this->userCollection->store($user);
    }
}
