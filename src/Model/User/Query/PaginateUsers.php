<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Query;

use Plexikon\Chronicle\Reporter\Query;

final class PaginateUsers extends Query
{
    private int $limit;
    private string $column;
    private string $direction;
    private array $scopes;

    public function limit(): int
    {
        return $this->payload['limit'] ?? 10;
    }

    public function column(): string
    {
        return $this->payload['column'] ?? 'email';
    }

    public function direction(): string
    {
        return $this->payload['direction'] ?? 'asc';
    }

    public function scopes(): array
    {
        return $this->payload['scopes'] ?? [];
    }
}
