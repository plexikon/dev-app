<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Projection\User;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class UserFinder
{
    private UserModel $model;

    public function __construct(UserModel $model)
    {
        $this->model = $model;
    }

    public function userOfId(string $userId): ?UserModel
    {
        return $this->model->newQuery()->find($userId);
    }

    public function userOfEmail(string $email): ?UserModel
    {
        return $this->model->newQuery()->where('email', $email)->first();
    }

    public function paginate(int $limit = 10, string $column = 'email', string $direction = 'asc'): LengthAwarePaginator
    {
        return $this->model->newQuery()->orderBy($column, $direction)->paginate($limit);
    }
}
