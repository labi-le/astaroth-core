<?php

declare(strict_types=1);

namespace Astaroth\Parser\DataTransferObject;

use Astaroth\Attribute\Description;

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

    public function getDescription(): ?string
    {
        foreach ($this->getAttribute() as $attribute) {
            if ($attribute instanceof Description) {
                return $attribute->getDescription();
            }
        }
        return null;
    }

    /**
     * @return MethodParamInfo[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}