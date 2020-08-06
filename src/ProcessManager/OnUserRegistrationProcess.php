<?php
declare(strict_types=1);

namespace Plexikon\DevApp\ProcessManager;

use Plexikon\Chronicle\Reporter\ReportCommand;
use Plexikon\DevApp\Model\User\Command\RequestActivationToken;
use Plexikon\DevApp\Model\User\Event\UserRegistered;
use Plexikon\DevApp\Model\User\Value\ActivationTokenWithExpiration;

final class OnUserRegistrationProcess
{
    private ReportCommand $reporter;

    public function __construct(ReportCommand $reporter)
    {
        $this->reporter = $reporter;
    }

    public function onEvent(UserRegistered $event): void
    {
        $token = ActivationTokenWithExpiration::create();

        $this->reporter->publish(
            RequestActivationToken::forUser(
                $event->userId()->toString(),
                $token->token()->toString(),
                $token->formatExpiredAt()
            )
        );
    }
}
