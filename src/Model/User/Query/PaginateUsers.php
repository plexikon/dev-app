<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Query;

final class PaginateUsers
{
    private int $limit;
    private string $column;
    private string $direction;
    private array $scopes;

    public function __construct(int $limit = 10,
                                string $column = 'email',
                                string $direction = 'asc',
                                array $scopes = [])
    {
        $this->limit = $limit;
        $this->column = $column;
        $this->direction = $direction;
        $this->scopes = $scopes;
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public function column(): string
    {
        return $this->column;
    }

    public function direction(): string
    {
        return $this->direction;
    }

    public function scopes(): array
    {
        return $this->scopes;
    }
}
