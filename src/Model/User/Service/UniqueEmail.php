<?php

namespace Plexikon\DevApp\Model\User\Service;

use Plexikon\DevApp\Model\User\Value\EmailAddress;
use Plexikon\DevApp\Model\User\Value\UserId;

interface UniqueEmail
{
    /**
     * @param EmailAddress $email
     * @return UserId|null
     */
    public function __invoke(EmailAddress $email): ?UserId;
}
