<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Application\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Plexikon\Chronicle\Reporter\LazyReporter;
use Plexikon\DevApp\Model\User\Query\PaginateUsers;
use Plexikon\DevApp\Projection\User\UserModel;

final class ChangeAndResetUsersPasswordCommand extends Command
{
    protected $signature = 'app:change_users_password';

    protected LazyReporter $reporter;

    public function handle()
    {
        $this->reporter = $this->getLaravel()->get(LazyReporter::class);

        $this->queryUsers()->each(function (UserModel $user): void {
            if ($user->isEnabled()) {
                $fixedPassword = 'password123';
                $otherPassword = 'fixMePassword123';

                $this->callSilent('app:change_user_password', [
                    'user_id' => $user->getId()->toString(),
                    'current_password' => $fixedPassword,
                    'new_password' => $otherPassword,
                    'new_password_confirmed' => $otherPassword,
                ]);

                $this->callSilent('app:change_user_password', [
                    'user_id' => $user->getId()->toString(),
                    'current_password' => $otherPassword,
                    'new_password' => $fixedPassword,
                    'new_password_confirmed' => $fixedPassword,
                ]);
            }
        });

        $this->info("Password users changed and reset to default");
    }

    private function queryUsers(): Collection
    {
        $query = $this->reporter->publishQuery(new PaginateUsers(1000));

        return $this->reporter->handlePromise($query)->getCollection();
    }
}
