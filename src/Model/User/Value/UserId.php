<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Value;

use Plexikon\Chronicle\Chronicling\Aggregate\Concerns\HasUuidAggregateId;
use Plexikon\Chronicle\Support\Contract\Chronicling\Aggregate\AggregateId;

final class UserId implements AggregateId
{
    use HasUuidAggregateId;
}
