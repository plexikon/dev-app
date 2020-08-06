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
        $users = UserModel::has('activation')->get()->each(function (UserModel $user): void {
            $this->call('app:activate_user',
                ['activation_token' => $user->activation->token]
            );
        });

        $this->info("{$users->count()} users activated");
    }
}
