<?php

declare(strict_types=1);

namespace Astaroth\Route\Attribute;

use ReflectionClass;

final class ValidatedObject
{
    /**
     * @param ReflectionClass $object
     * @param ReflectionMethodDecorator[] $methods
     */
    public function __construct(
        private readonly ReflectionClass $object,
        private readonly array $methods,
    ) {
    }

    /**
     * @return ReflectionClass
     */
    public function getObject(): ReflectionClass
    {
        return $this->object;
    }

    /**
     * @return ReflectionMethodDecorator[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }
}
