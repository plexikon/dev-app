<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Projection\User;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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

    public function paginate(int $limit = 10, string $column = 'email', string $direction = 'asc'): LengthAwarePaginator
    {
        return $this->model->newQuery()->orderBy($column, $direction)->paginate($limit);
    }

    /**
     * @param string $activationToken
     * @return UserModel|Model|null
     */
    public function userOfActivationToken(string $activationToken): ?UserModel
    {
        return $this->model
            ->newQuery()
            ->whereHas('activation', function (Builder $builder) use ($activationToken): void {
                $builder->where('token', $activationToken);
                // todo add expiration date
            })
            ->first();
    }
}
