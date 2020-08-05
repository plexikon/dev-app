<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Exception;

use Assert\AssertionFailedException;

final class InvalidArgumentException extends \InvalidArgumentException implements DevDomainException, AssertionFailedException
{
    /**
     * @var mixed
     */
    private $value;
    private ?string $propertyPath;
    private array $constraints;

    public function __construct(string $message,
                                int $code,
                                string $propertyPath = null,
                                $value = null,
                                array $constraints = [])
    {
        parent::__construct($message, $code);

        $this->propertyPath = $propertyPath;
        $this->value = $value;
        $this->constraints = $constraints;
    }

    public function getPropertyPath()
    {
        return $this->propertyPath;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getConstraints(): array
    {
        return $this->constraints;
    }
}
