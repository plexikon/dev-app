<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Application\Console;

use Illuminate\Console\Command;
use Plexikon\Chronicle\Reporter\LazyReporter;
use Plexikon\DevApp\Model\User\Command\RegisterUser;

final class RegisterUserCommand extends Command
{
    protected $signature = 'app:register_user {user_id} {user_email} {user_password}';

    public function handle(): void
    {
        /** @var LazyReporter $reporter */
        $reporter = $this->getLaravel()->get(LazyReporter::class);

        $reporter->publishCommand(
            RegisterUser::withData(
                $this->argument('user_id'),
                $this->argument('user_email'),
                $this->argument('user_password'),
            )
        );

        $this->info('User registered');
    }
}
