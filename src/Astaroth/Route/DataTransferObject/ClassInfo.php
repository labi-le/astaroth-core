<?php

declare(strict_types=1);

namespace Astaroth\Route\DataTransferObject;

final class ClassInfo
{
    /**
     * @param string $name
     * @param object[] $attribute
     * @param MethodInfo[] $methods
     * @param string $className
     */
    public function __construct
    (
        private string $name,
        private array  $attribute,
        private array  $methods,
        private string $className,
    )
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getAttribute(): array
    {
        return $this->attribute;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }


}