<?php

namespace Plexikon\DevApp\Model\User\Service;

use Plexikon\DevApp\Model\User\Value\ClearPassword;
use Plexikon\DevApp\Model\User\Value\EncodedPassword;

interface PasswordEncoder
{
    /**
     * @param ClearPassword $clearPassword
     * @return EncodedPassword
     */
    public function encode(ClearPassword $clearPassword): EncodedPassword;

    /**
     * @param ClearPassword $clearPassword
     * @param EncodedPassword $password
     * @return bool
     */
    public function check(ClearPassword $clearPassword, EncodedPassword $password): bool;
}
