<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Application\Console;

use Plexikon\Chronicle\Support\Console\AbstractPersistentProjectionCommand;
use Plexikon\DevApp\Model\User\Event\ActivationTokenRenewed;
use Plexikon\DevApp\Model\User\Event\ActivationTokenRequested;
use Plexikon\DevApp\Model\User\Event\UserActivated;
use Plexikon\DevApp\Projection\Stream;
use Plexikon\DevApp\Projection\User\UserActivationReadModel;

final class UserActivationReadModelProjectionCommand extends AbstractPersistentProjectionCommand
{
    protected $signature = 'app:project-user_activation';

    public function handle(): void
    {
        $projection = $this->withProjection(Stream::USER_STREAM, UserActivationReadModel::class);
        $projection
            ->withQueryFilter($this->projectorManager()->projectionQueryScope()->fromIncludedPosition())
            ->fromStreams(Stream::USER_STREAM)
            ->when($this->fromUserActivationHandlers())
            ->run(true);
    }

    private function fromUserActivationHandlers(): array
    {
        return [
            'activation-token-requested' => function (array $state, ActivationTokenRequested $event): void {
                $activationToken = $event->currentActivationToken();

                $this->readModel()->stack('insert', [
                    'user_id' => $event->aggregateRootId(),
                    'token' => $activationToken->token()->toString(),
                    'expired_at' => $activationToken->formatExpiredAt()
                ]);
            },

            'activation-token-renewed' => function (array $state, ActivationTokenRenewed $event): void {
                $this->readModel()->stack('renewActivationToken', $event);
            },

            'user-activated' => function (array $state, UserActivated $event): void {
                $this->readModel()->stack('deleteOnUserActivated', $event->aggregateRootId());
            }
        ];
    }
}
