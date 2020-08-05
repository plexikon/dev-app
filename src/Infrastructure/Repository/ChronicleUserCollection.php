<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Infrastructure\Repository;

use Plexikon\Chronicle\Support\Contract\Chronicling\Aggregate\AggregateRepository;
use Plexikon\Chronicle\Support\Contract\Chronicling\Aggregate\AggregateRoot;
use Plexikon\DevApp\Model\User\Repository\UserCollection;
use Plexikon\DevApp\Model\User\User;
use Plexikon\DevApp\Model\User\Value\UserId;

final class ChronicleUserCollection implements UserCollection
{
    /**
     * @var AggregateRepository
     */
    private AggregateRepository $aggregateRepository;

    public function __construct(AggregateRepository $aggregateRepository)
    {
        $this->aggregateRepository = $aggregateRepository;
    }

    /**
     * @param UserId $userId
     * @return User|AggregateRoot|null
     */
    public function get(UserId $userId): ?User
    {
        $user = $this->aggregateRepository->retrieve($userId);

        return $user->exists() ? $user : null;
    }

    public function store(User $user): void
    {
        $this->aggregateRepository->persist($user);
    }
}
