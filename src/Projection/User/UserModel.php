<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Projection\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Plexikon\DevApp\Model\User\Value\BcryptPassword;
use Plexikon\DevApp\Model\User\Value\EmailAddress;
use Plexikon\DevApp\Model\User\Value\UserId;
use Plexikon\DevApp\Projection\Table;

final class UserModel extends Model
{
    protected $table = Table::USER_TABLE;
    protected $fillable = [];
    protected $guarded = ['*'];
    protected $keyType = 'string';

    /**
     * @return HasOne|UserActivationModel
     */
    public function activation(): HasOne
    {
        return $this->hasOne(UserActivationModel::class, 'user_id', ' id');
    }

    public function getId(): UserId
    {
        return UserId::fromString($this->getKey());
    }

    public function email(): EmailAddress
    {
        return EmailAddress::fromString($this['email']);
    }

    public function password(): BcryptPassword
    {
        return BcryptPassword::fromString($this['password']);
    }
}
