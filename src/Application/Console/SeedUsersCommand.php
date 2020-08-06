<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Application\Console;

use Faker\Factory;
use Illuminate\Console\Command;
use Plexikon\DevApp\Model\User\Exception\UserAlreadyExists;

final class SeedUsersCommand extends Command
{
    protected $signature = 'app:seed_users {num}';

    public function handle(): void
    {
        $faker = Factory::create();

        $count = $num = (int)$this->argument('num');

        while ($num !== 0) {
            try {
                $this->callSilent('app:register_user', [
                    'user_id' => $userId = $faker->uuid,
                    'user_email' => $faker->email,
                    'user_password' => 'password123',
                ]);

                --$num;
            } catch (UserAlreadyExists $exception) {
                $this->line('Email already exists ... keep running');
            }
        }

        $this->info("$count user(s) registered");
    }
}
