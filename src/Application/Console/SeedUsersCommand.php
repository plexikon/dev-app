<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Application\Console;

use Faker\Factory;
use Illuminate\Console\Command;
use Plexikon\DevApp\Model\User\Value\ClearPassword;

final class SeedUsersCommand extends Command
{
    protected $signature = 'app:seed_users {num}';

    public function handle(): void
    {
        $faker = Factory::create();

        $count = $num = $this->argument('num');

        while ($num !== 0) {
            $this->callSilent('app:register_user', [
                'user_id' => $faker->uuid,
                'user_email' => $faker->email,
                'user_password' => $faker->password(ClearPassword::MIN_LENGTH, ClearPassword::MAX_LENGTH),
            ]);

            --$num;
        }

        $this->info("$count user(s) registered");
    }
}
