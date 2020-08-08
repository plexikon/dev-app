<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Projection\User;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Plexikon\Chronicle\Clock\SystemClock;

final class UserFinder
{
    private UserModel $model;

    public function __construct(UserModel $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $userId
     * @return UserModel|Model|null
     */
    public function userOfId(string $userId): ?UserModel
    {
        return $this->model->newQuery()->find($userId);
    }

    /**
     * @param string $email
     * @return UserModel|Model|null
     */
    public function userOfEmail(string $email): ?UserModel
    {
        return $this->model->newQuery()->where('email', $email)->first();
    }

    public function paginate(int $limit = 10,
                             string $column = 'email',
                             string $direction = 'asc',
                             array $scopes = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        $query->with('activation');

        if ($status = $scopes['status'] ?? null) {
            $query->where('status', $status);
        }

        $query->orderBy($column, $direction);

        return $query->paginate($limit);
    }

    /**
     * @param string $activationToken
     * @return UserModel|Model|null
     */
    public function userOfValidActivationToken(string $activationToken): ?UserModel
    {
        $now = (new SystemClock())->pointInTime()->toString();

        return $this->model
            ->newQuery()
            ->with('activation')
            ->whereHas('activation', function (Builder $builder) use ($activationToken, $now): void {
                $builder->where('token', $activationToken);
                $builder->where('expired_at', '>', $now);
            })
            ->first();
    }
}
