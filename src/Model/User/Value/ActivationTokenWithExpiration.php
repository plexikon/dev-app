<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Model\User\Value;

use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use Plexikon\DevApp\Shared\Model\Value;

final class ActivationTokenWithExpiration implements Value
{
    public const EXPIRED_AT_TIMEZONE = 'UTC';
    public const EXPIRED_AT_FORMAT = 'Y-m-d\TH:i:s.u';
    public const EXPIRED_AT_INTERVAL = 'PT3H';

    private ActivationToken $token;
    private DateTimeImmutable $expiredAt;

    private function __construct(ActivationToken $token, DateTimeImmutable $expiredAt)
    {
        $this->token = $token;
        $this->expiredAt = $expiredAt;
    }

    public static function create(): self
    {
        $now = new DateTimeImmutable('now', new DateTimeZone(self::EXPIRED_AT_TIMEZONE));

        return new self(
            ActivationToken::create(),
            $now->add(new DateInterval(self::EXPIRED_AT_INTERVAL))
        );
    }

    public static function fromString(string $token, string $expiredAt): self
    {
        $expiredAt = DateTimeImmutable::createFromFormat(
            self::EXPIRED_AT_FORMAT, $expiredAt,
            new DateTimeZone(self::EXPIRED_AT_TIMEZONE)
        );

        if (!$expiredAt instanceof DateTimeImmutable) {

        }

        return new self(ActivationToken::fromString($token), $expiredAt);
    }

    public function sameValueAs(Value $aValue): bool
    {
        return $aValue instanceof $this
            && $this->token() === $aValue->token
            && $this->expiredAt() === $aValue->expiredAt();
    }

    public function isExpired(): bool
    {
        $now = new DateTimeImmutable('now', new DateTimeZone(self::EXPIRED_AT_TIMEZONE));

        return ($now->add(new DateInterval(self::EXPIRED_AT_INTERVAL))) < $this->expiredAt;
    }

    public function isNotExpired(): bool
    {
        return !$this->isExpired();
    }

    public function token(): ActivationToken
    {
        return $this->token;
    }

    public function expiredAt(): DateTimeImmutable
    {
        return $this->expiredAt;
    }

    public function formatExpiredAt(): string
    {
        return $this->expiredAt->format(self::EXPIRED_AT_FORMAT);
    }
}
