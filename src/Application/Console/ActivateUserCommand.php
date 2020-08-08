<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Application\Console;

use Illuminate\Console\Command;
use Plexikon\Chronicle\Reporter\LazyReporter;
use Plexikon\DevApp\Model\User\Command\ActivateUser;
use Plexikon\DevApp\Model\User\Exception\UserNotFound;
use Plexikon\DevApp\Model\User\Query\GetUserByActivationToken;
use Plexikon\DevApp\Model\User\Value\ActivationToken;
use Plexikon\DevApp\Model\User\Value\ActivationTokenWithExpiration;
use Plexikon\DevApp\Model\User\Value\UserId;
use Plexikon\DevApp\Projection\User\UserActivationModel;
use Plexikon\DevApp\Projection\User\UserModel;

final class ActivateUserCommand extends Command
{
    protected $signature = 'app:activate_user {activation_token}';

    protected LazyReporter $reporter;

    public function handle(): void
    {
        /** @var LazyReporter $reporter */
        $this->reporter = $this->getLaravel()->get(LazyReporter::class);

        $user = $this->queryUser(ActivationToken::fromString($this->argument('activation_token')));

        $token = $user->token();

        $this->activateUser($user->getUserId(), $token);

        $this->info("User with id {$user->getUserId()->toString()} activated with token {$token->token()->toString()}");
    }

    private function queryUser(ActivationToken $token): UserActivationModel
    {
        $query = GetUserByActivationToken::fromPayload(['token' => $token->toString()]);

        $user = $this->reporter->handlePromise(
            $this->reporter->publishQuery($query)
        );

        if (!$user instanceof UserModel || !$user->activation instanceof UserActivationModel) {
            throw new UserNotFound("User/Token not found");
        }

        return $user->activation;
    }

    private function activateUser(UserId $userId, ActivationTokenWithExpiration $token): void
    {
        $command = ActivateUser::forUser($userId->toString(), $token->token()->toString(), $token->formatExpiredAt());

        $this->reporter->publishCommand($command);
    }
}
