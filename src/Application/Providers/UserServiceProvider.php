<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Application\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Plexikon\Chronicle\ChronicleRepositoryManager;
use Plexikon\DevApp\Infrastructure\Repository\ChronicleUserCollection;
use Plexikon\DevApp\Infrastructure\Service\BcryptPasswordEncoder;
use Plexikon\DevApp\Infrastructure\Service\UniqueEmailFromRead;
use Plexikon\DevApp\Model\User\Service\PasswordEncoder;
use Plexikon\DevApp\Model\User\Repository\UserCollection;
use Plexikon\DevApp\Model\User\Service\UniqueEmail;
use Plexikon\DevApp\Projection\Stream;

final class UserServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $bindings = [
        PasswordEncoder::class => BcryptPasswordEncoder::class,
        UniqueEmail::class => UniqueEmailFromRead::class
    ];

    public function register(): void
    {
        $this->app->bind(UserCollection::class, function (Application $app): UserCollection {
            $repository = $app->get(ChronicleRepositoryManager::class)->create(Stream::USER_STREAM);

            return new ChronicleUserCollection($repository);
        });
    }

    public function provides()
    {
        return [UserCollection::class, PasswordEncoder::class, UniqueEmail::class];
    }
}
