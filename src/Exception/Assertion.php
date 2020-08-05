<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Exception;

final class Assertion extends \Assert\Assertion
{
    /**
     * @var string
     */
    protected static $exceptionClass = InvalidArgumentException::class;
}
