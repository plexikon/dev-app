<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Application\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Plexikon\Chronicle\Reporter\LazyReporter;
use Plexikon\DevApp\Model\User\Query\PaginateUsers;
use Plexikon\DevApp\Model\User\Value\UserStatus;
use Plexikon\DevApp\Projection\User\UserModel;

final class ActivateUsersCommand extends Command
{
    protected $signature = 'app:activate_users';

    private LazyReporter $reporter;

    public function handle(): void
    {
        /** @var LazyReporter $reporter */
        $this->reporter = $this->getLaravel()->get(LazyReporter::class);

        $notEnabledUsers = $this->queryNotEnabledUsers();

        $notEnabledUsers->each(function (UserModel $user): void {
            $this->call('app:activate_user',
                ['activation_token' => $user->getRelation('activation')->token]
            );
        });

        $this->info("{$notEnabledUsers->count()} users activated");
    }

    private function queryNotEnabledUsers(): Collection
    {
        $promise = $this->reporter->publishQuery(
            PaginateUsers::fromPayload([
                'limit' => 10000,
                'status' => UserStatus::PENDING_REGISTRATION()->toString()
            ])
        );

        return $this->reporter->handlePromise($promise)->getCollection();
    }
}
