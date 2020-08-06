<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Application\Console;

use Illuminate\Console\Command;
use Plexikon\Chronicle\Reporter\LazyReporter;
use Plexikon\DevApp\Model\User\Command\ChangeUserPassword;

final class ChangeUserPasswordCommand extends Command
{
    protected $signature = 'app:change_user_password
                                {user_id}
                                {current_password}
                                {new_password}
                                {new_password_confirmed}';

    public function handle(): void
    {
        $reporter = $this->getLaravel()->get(LazyReporter::class);

        $reporter->publishCommand(
            ChangeUserPassword::forUser(
                $this->argument('user_id'),
                $this->argument('current_password'),
                $this->argument('new_password'),
                $this->argument('new_password_confirmed'),
            )
        );

        $this->info("User password changed");
    }
}
