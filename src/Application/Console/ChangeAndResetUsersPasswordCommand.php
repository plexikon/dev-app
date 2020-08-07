<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Application\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Plexikon\Chronicle\Reporter\LazyReporter;
use Plexikon\DevApp\Model\User\Query\PaginateUsers;
use Plexikon\DevApp\Model\User\Value\UserId;
use Plexikon\DevApp\Projection\User\UserModel;

final class ChangeAndResetUsersPasswordCommand extends Command
{
    protected $signature = 'app:change_users_password';

    protected LazyReporter $reporter;

    public function handle()
    {
        $this->reporter = $this->getLaravel()->get(LazyReporter::class);

        $enabledUsers = $this->queryUsers();

        $enabledUsers->each(function (UserModel $user): void {
            if ($user->isEnabled()) {
                $fixedPassword = 'password123';
                $otherPassword = 'fixMePassword123';

                $this->callChangePasswordCommand($user->getId(), $fixedPassword, $otherPassword);

                $this->callChangePasswordCommand($user->getId(), $otherPassword, $fixedPassword);
            }
        });

        $this->info("{$enabledUsers->count()} Password user(s) changed and reset to default");
    }

    private function callChangePasswordCommand(UserId $userId, string $currentPassword, string $newPassword): void
    {
        $this->callSilent('app:change_user_password', [
            'user_id' => $userId->toString(),
            'current_password' => $currentPassword,
            'new_password' => $newPassword,
            'new_password_confirmed' => $newPassword,
        ]);
    }

    private function queryUsers(): Collection
    {
        $query = $this->reporter->publishQuery(new PaginateUsers(1000));

        return $this->reporter->handlePromise($query)->getCollection();
    }
}
