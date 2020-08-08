<?php
declare(strict_types=1);

namespace Plexikon\DevApp\ProcessManager;

use Plexikon\Chronicle\Reporter\LazyReporter;
use Plexikon\DevApp\Model\User\Command\RenewActivationToken;
use Plexikon\DevApp\Model\User\Event\NewActivationTokenRequested;
use Plexikon\DevApp\Model\User\Query\GetUserByEmail;
use Plexikon\DevApp\Model\User\Value\ActivationTokenWithExpiration;
use Plexikon\DevApp\Model\User\Value\EmailAddress;
use Plexikon\DevApp\Projection\User\UserModel;

final class NewActivationTokenRequestedProcess
{
    private LazyReporter $reporter;

    public function __construct(LazyReporter $reporter)
    {
        $this->reporter = $reporter;
    }

    public function onEvent(NewActivationTokenRequested $event): void
    {
        if (!$user = $this->queryUserByEmail($event->email())) {
            return;
        }

        $token = ActivationTokenWithExpiration::create();

        $this->reporter->publishCommand(
            RenewActivationToken::forUser(
                $user->getId()->toString(),
                $token->token()->toString(),
                $token->formatExpiredAt()
            )
        );
    }

    private function queryUserByEmail(EmailAddress $email): ?UserModel
    {
        $query = GetUserByEmail::fromPayload(['email' => $email->toString()]);

        return $this->reporter->handlePromise(
            $this->reporter->publishQuery($query)
        );
    }
}
