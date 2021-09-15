<?php

declare(strict_types=1);

namespace Astaroth\Route\DataTransferObject;

final class MethodInfo
{
    /**
     * @param string $name
     * @param object[] $attribute
     * @param MethodParamInfo[] $parameters
     */
    public function __construct
    (
        private string $name,
        private array  $attribute,
        private array  $parameters,
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
     * @return object[]
     */
    public function getAttribute(): array
    {
        return $this->attribute;
    }

    /**
     * @return MethodParamInfo[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}