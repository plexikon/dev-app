<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Projection\User;

use Illuminate\Database\Eloquent\Model;
use Plexikon\DevApp\Model\User\Value\ActivationTokenWithExpiration;
use Plexikon\DevApp\Model\User\Value\UserId;
use Plexikon\DevApp\Projection\Table;

final class UserActivationModel extends Model
{
    protected $table = Table::USER_ACTIVATION_TABLE;
    protected $fillable = [];
    protected $guarded = ['*'];
    protected $keyType = 'string';

    public function getId(): UserId
    {
        return UserId::fromString($this->getKey());
    }

    public function activationToken(): ?ActivationTokenWithExpiration
    {
        if (null === $this['token'] && null === $this['expired_at']) {
            return null;
        }

        return ActivationTokenWithExpiration::fromString(
            $this['token'], $this['expired_at']
        );
    }
}
