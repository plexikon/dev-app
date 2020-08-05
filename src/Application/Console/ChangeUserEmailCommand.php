<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Application\Console;

use Illuminate\Console\Command;
use Plexikon\Chronicle\Reporter\LazyReporter;
use Plexikon\DevApp\Model\User\Command\ChangeUserEmail;

final class ChangeUserEmailCommand extends Command
{
    protected $signature = 'app:change_user_email {user_id} {new_user_email}';

    public function handle(): void
    {
        $reporter = $this->getLaravel()->get(LazyReporter::class);

        $reporter->publishCommand(
            ChangeUserEmail::forUser(
                $this->argument('user_id'),
                $this->argument('new_user_email')
            )
        );

        $this->info("User email changed");
    }
}
