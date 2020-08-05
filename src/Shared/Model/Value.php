<?php

namespace Plexikon\DevApp\Shared\Model;

interface Value
{
    /**
     * @param Value $aValue
     * @return bool
     */
    public function sameValueAs(Value $aValue): bool;
}
