<?php

namespace Plexikon\DevApp\Model\User\Repository;

use Plexikon\DevApp\Model\User\Value\ClearPassword;
use Plexikon\DevApp\Model\User\Value\EncodedPassword;

interface PasswordEncoder
{
    public function __invoke(ClearPassword $clearPassword): EncodedPassword;
}
