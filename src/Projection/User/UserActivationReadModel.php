<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Projection\User;

use Illuminate\Database\Schema\Blueprint;
use Plexikon\Chronicle\Support\Projection\HasConnectionOperation;
use Plexikon\Chronicle\Support\Projection\ReadModelConnection;
use Plexikon\DevApp\Model\User\Event\ActivationTokenRenewed;
use Plexikon\DevApp\Model\User\Value\ActivationToken;
use Plexikon\DevApp\Projection\Table;

final class UserActivationReadModel extends ReadModelConnection
{
    use HasConnectionOperation;

    protected function deleteOnUserActivated(string $userId): void
    {
        $this->queryBuilder()->where('user_id', $userId)->delete();
    }

    protected function renewActivationToken(ActivationTokenRenewed $event): void
    {
        $this->queryBuilder()->where('user_id', $event->aggregateRootId())->delete();

        $activationToken = $event->currentActivationToken();

        $this->queryBuilder()->insert([
            'user_id' => $event->aggregateRootId(),
            'token' => $activationToken->token()->toString(),
            'expired_at' => $activationToken->formatExpiredAt()
        ]);
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
