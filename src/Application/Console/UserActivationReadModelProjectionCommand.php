<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Application\Console;

use Plexikon\Chronicle\Support\Console\AbstractPersistentProjectionCommand;
use Plexikon\DevApp\Model\User\Event\ActivationTokenRequested;
use Plexikon\DevApp\Projection\Stream;
use Plexikon\DevApp\Projection\User\UserActivationReadModel;

final class UserActivationReadModelProjectionCommand extends AbstractPersistentProjectionCommand
{
    protected $signature = 'app:project-user_stream';

    public function handle(): void
    {
        $projection = $this->withProjection(Stream::USER_STREAM, UserActivationReadModel::class);
        $projection
            ->withQueryFilter($this->projectorManager()->projectionQueryScope()->fromIncludedPosition())
            ->fromStreams(Stream::USER_STREAM)
            ->when($this->fromUserHandlers())
            ->run(true);
    }

    private function fromUserHandlers(): array
    {
        return [
            'activation-token-requested' => function (array $state, ActivationTokenRequested $event): void {
                $this->readModel()->stack('insert', [
                    'id' => $event->aggregateRootId(),
                    'token' => $event->activationToken()->token()->toString(),
                    'expired_at' => $event->activationToken()->formatExpiredAt()
                ]);
            },
        ];
    }
}
