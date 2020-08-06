<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Application\Console;

use Illuminate\Console\Command;
use Plexikon\DevApp\Projection\User\UserModel;

final class ActivateUsersCommand extends Command
{
    protected $signature = 'app:activate_users';

    public function handle(): void
    {
        // for simplicity
        $users = UserModel::has('activation')->get()->filter(function (UserModel $user): bool {
            return $user->activation->token()->isNotExpired();
        });

        if ($users->empty()) {
            $this->warn('No user to activate');
            return;
        }

        $users->each(function (UserModel $user): void {
            $this->call('app:activate_user', ['user_id' => $user->getId()->toString()]);
        });

        $this->info("{$users->count()} users registered");
    }
}
