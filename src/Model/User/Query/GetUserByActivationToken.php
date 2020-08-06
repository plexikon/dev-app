<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Query;

use Plexikon\DevApp\Model\User\Value\ActivationToken;

final class GetUserByActivationToken
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function token(): ActivationToken
    {
        return ActivationToken::fromString($this->token);
    }
}
