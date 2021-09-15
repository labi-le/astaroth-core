<?php

declare(strict_types=1);

namespace Astaroth\Route\DataTransferObject;

final class MethodParamInfo
{
    public function __construct
    (
        private string $name,
        private string $type,

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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}