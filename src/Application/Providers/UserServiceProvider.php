<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Application\Providers;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Plexikon\Chronicle\ChronicleRepositoryManager;
use Plexikon\Chronicle\Support\Contract\Chronicling\Chronicler;
use Plexikon\DevApp\Application\Console\RegisterUserCommand;
use Plexikon\DevApp\Application\Console\SeedUsersCommand;
use Plexikon\DevApp\Application\Console\UserReadModelProjectionCommand;
use Plexikon\DevApp\Infrastructure\Repository\ChronicleUserCollection;
use Plexikon\DevApp\Infrastructure\Service\BcryptPasswordEncoder;
use Plexikon\DevApp\Infrastructure\Service\UniqueEmailFromRead;
use Plexikon\DevApp\Model\User\Handler\GetUserByEmailHandler;
use Plexikon\DevApp\Model\User\Handler\GetUserByIdHandler;
use Plexikon\DevApp\Model\User\Handler\PaginateUsersHandler;
use Plexikon\DevApp\Model\User\Handler\RegisterUserHandler;
use Plexikon\DevApp\Model\User\Repository\UserCollection;
use Plexikon\DevApp\Model\User\Service\PasswordEncoder;
use Plexikon\DevApp\Model\User\Service\UniqueEmail;
use Plexikon\DevApp\Model\User\User;
use Plexikon\DevApp\Projection\Stream;

class UserServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $bindings = [
        PasswordEncoder::class => BcryptPasswordEncoder::class,
        UniqueEmail::class => UniqueEmailFromRead::class
    ];

    protected array $reporters = [
        'command' => [
            'register-user' => RegisterUserHandler::class
        ],

        'query' => [
            'get-user-by-id' => GetUserByIdHandler::class,
            'get-user-by-email' => GetUserByEmailHandler::class,
            'paginate-users' => PaginateUsersHandler::class,
        ],

        'event' => [
            'user-registered' => [
                //
            ]
        ]
    ];

    protected array $userRepository = [
        'stream_name' => User::class,
        'chronicler_id' => Chronicler::class,
        'cache' => 10000,
        'decorators' => []
    ];

    public function register(): void
    {
        /** @var Repository $repository */
        $repository = $this->app->get(Repository::class);

        if ($repository->has('chronicler')) {

            $repository->set('chronicler.repositories', array_merge(
                $repository->get('chronicler.repositories'),
                $this->userRepository
            ));

            $this->app->bind(UserCollection::class, function (Application $app): UserCollection {
                $repository = $app->get(ChronicleRepositoryManager::class)->create(Stream::USER_STREAM);

                return new ChronicleUserCollection($repository);
            });
        }

        if (!$repository->has('reporter')) {
            foreach ($this->reporters as $reporterType => $message) {
                $typeKey = "reporter.reporting.$reporterType.default.map";

                $repository->set($typeKey, array_merge(
                    $repository->get($typeKey),
                    $message
                ));
            }
        }
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RegisterUserCommand::class,
                SeedUsersCommand::class,
                UserReadModelProjectionCommand::class
            ]);
        }
    }

    public function provides()
    {
        return [UserCollection::class, PasswordEncoder::class, UniqueEmail::class];
    }
}
