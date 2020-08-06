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

    public function getUserId(): UserId
    {
        return UserId::fromString($this['user_id']);
    }

    public function token(): ActivationTokenWithExpiration
    {
       return ActivationTokenWithExpiration::fromString(
            $this['token'], $this['expired_at']
        );
    }
}
