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

        $count = $num = (int)$this->argument('num');

        while ($num !== 0) {
            $this->callSilent('app:register_user', [
                'user_id' => $userId = $faker->uuid,
                'user_email' => $faker->email,
                'user_password' => $faker->password(ClearPassword::MIN_LENGTH, ClearPassword::MAX_LENGTH),
            ]);

            $this->changeEmail($count, $userId, $faker->email);

            --$num;
        }

        $this->info("$count user(s) registered and email changed");
    }

    private function changeEmail(int $num, string $userId, string $newEmail): void
    {
        while ($num !== 0) {
            $this->callSilent('app:change_user_email', [
                'user_id' => $userId,
                'new_user_email' => $newEmail
            ]);

            --$num;
        }
    }
}
