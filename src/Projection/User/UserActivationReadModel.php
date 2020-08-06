<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Projection\User;

use Illuminate\Database\Schema\Blueprint;
use Plexikon\Chronicle\Support\Projection\HasConnectionOperation;
use Plexikon\Chronicle\Support\Projection\ReadModelConnection;
use Plexikon\DevApp\Model\User\Value\ActivationToken;
use Plexikon\DevApp\Projection\Table;

final class UserActivationReadModel extends ReadModelConnection
{
    use HasConnectionOperation;

    protected function deleteOnUserActivated(string $userId): void
    {
        $this->queryBuilder()->where('user_id', $userId)->delete();
    }

    protected function tableName(): string
    {
        return Table::USER_ACTIVATION_TABLE;
    }

    protected function up(): callable
    {
        return function (Blueprint $table): void {
            $table->id('id');
            $table->uuid('user_id')->unique();
            $table->char('token', ActivationToken::LENGTH)->unique();
            $table->string('expired_at', 26);
        };
    }
}
