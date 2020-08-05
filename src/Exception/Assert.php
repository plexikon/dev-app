<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Exception;

final class Assert extends \Assert\Assert
{
    /**
     * @var string
     */
    protected static $assertionClass = Assertion::class;
}
