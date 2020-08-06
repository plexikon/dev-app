<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Infrastructure\Service;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Hashing\BcryptHasher;
use Plexikon\DevApp\Exception\RuntimeException;
use Plexikon\DevApp\Model\User\Service\PasswordEncoder;
use Plexikon\DevApp\Model\User\Value\BcryptPassword;
use Plexikon\DevApp\Model\User\Value\ClearPassword;
use Plexikon\DevApp\Model\User\Value\EncodedPassword;
use function get_class;

final class BcryptPasswordEncoder implements PasswordEncoder
{
    private Hasher $encoder;

    public function __construct(Hasher $encoder)
    {
        if (!$encoder instanceof BcryptHasher) {
            $message = "Invalid password encoder, expected " . BcryptHasher::class . ' got ' . get_class($encoder);
            throw new RuntimeException($message);
        }

        $this->encoder = $encoder;
    }

    public function encode(ClearPassword $clearPassword): EncodedPassword
    {
        $encodedPassword = $this->encoder->make($clearPassword->toString());

        return BcryptPassword::fromString($encodedPassword);
    }

    public function check(ClearPassword $clearPassword, EncodedPassword $currentPassword): bool
    {
        return $this->encoder->check($clearPassword->toString(), $currentPassword->toString());
    }
}
