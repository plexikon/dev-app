<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Value;

use Plexikon\DevApp\Shared\Model\Enum;

/**
 * @method static UserStatus PENDING_REGISTRATION()
 * @method static UserStatus ACTIVATED()
 */
final class UserStatus extends Enum
{
    public const PENDING_REGISTRATION = 'pending_registration';
    public const ACTIVATED = 'activated';
}
