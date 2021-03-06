<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Application\Console;

use Plexikon\Chronicle\Support\Console\AbstractPersistentProjectionCommand;
use Plexikon\Chronicle\Support\Contract\Messaging\MessageHeader;
use Plexikon\DevApp\Model\User\Event\UserActivated;
use Plexikon\DevApp\Model\User\Event\UserEmailChanged;
use Plexikon\DevApp\Model\User\Event\UserPasswordChanged;
use Plexikon\DevApp\Model\User\Event\UserRegistered;
use Plexikon\DevApp\Projection\Stream;
use Plexikon\DevApp\Projection\User\UserReadModel;

final class UserReadModelProjectionCommand extends AbstractPersistentProjectionCommand
{
    protected $signature = 'app:project-user';

    public function handle(): void
    {
        $projection = $this->withProjection(Stream::USER_STREAM, UserReadModel::class);
        $projection
            ->initialize(fn(): array => ['pending' => 0, 'activated' => 0])
            ->withQueryFilter($this->projectorManager()->projectionQueryScope()->fromIncludedPosition())
            ->fromStreams(Stream::USER_STREAM)
            ->when($this->fromUserHandlers())
            ->run(true);
    }

    private function fromUserHandlers(): array
    {
        return [
            'user-registered' => function (array $state, UserRegistered $event): array {
                $this->readModel()->stack('insert', [
                    'id' => $event->aggregateRootId(),
                    'email' => $event->email()->toString(),
                    'password' => $event->password()->toString(),
                    'status' => $event->userStatus()->getValue(),
                    'created_at' => $event->header(MessageHeader::TIME_OF_RECORDING)
                ]);

                $state['pending']++;
                return $state;
            },

            'user-email-changed' => function (array $state, UserEmailChanged $event): void {
                $this->readModel()->stack('update', $event->aggregateRootId(), [
                    'email' => $event->currentEmail()->toString(),
                    'updated_at' => $event->header(MessageHeader::TIME_OF_RECORDING)
                ]);
            },

            'user-password-changed' => function (array $state, UserPasswordChanged $event): void {
                $this->readModel()->stack('update', $event->aggregateRootId(), [
                    'password' => $event->currentPassword()->toString(),
                    'updated_at' => $event->header(MessageHeader::TIME_OF_RECORDING),
                ]);
            },

            'user-activated' => function (array $state, UserActivated $event): array {
                $this->readModel()->stack('update', $event->aggregateRootId(),[
                    'status' => $event->currentUserStatus()->getValue(),
                    'updated_at' => $event->header(MessageHeader::TIME_OF_RECORDING)
                ]);

                $state['pending']--;
                $state['activated']++;

                return $state;
            }
        ];
    }
}
