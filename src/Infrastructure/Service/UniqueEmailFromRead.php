<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Infrastructure\Service;

use Plexikon\DevApp\Model\User\Service\UniqueEmail;
use Plexikon\DevApp\Model\User\Value\EmailAddress;
use Plexikon\DevApp\Model\User\Value\UserId;
use Plexikon\DevApp\Projection\User\UserFinder;

final class UniqueEmailFromRead implements UniqueEmail
{
    private UserFinder $userFinder;

    public function __construct(UserFinder $userFinder)
    {
        $this->userFinder = $userFinder;
    }

    public function __invoke(EmailAddress $email): ?UserId
    {
        $user = $this->userFinder->userOfEmail($email->toString());

        return $user ? $user->getId() : null;
    }
}
