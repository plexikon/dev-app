<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Shared\Model;

use MabeEnum\EnumSerializableTrait;

abstract class Enum extends \MabeEnum\Enum implements \Serializable,Value
{
    use EnumSerializableTrait;

    public function sameValueAs(Value $object): bool
    {
        return $this->is($object);
    }

    public function toString(): string
    {
        return $this->getName();
    }
}
