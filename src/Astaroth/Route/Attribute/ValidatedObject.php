<?php
declare(strict_types=1);

namespace Astaroth\Route\Attribute;

final class ValidatedObject
{
    /**
     * @param \ReflectionClass $object
     * @param \ReflectionMethod[] $methods
     */
    public function __construct(private \ReflectionClass $object, private array $methods)
    {

    }

    /**
     * @return \ReflectionClass
     */
    public function getObject(): \ReflectionClass
    {
        return $this->object;
    }

    /**
     * @return \ReflectionMethod[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }
}