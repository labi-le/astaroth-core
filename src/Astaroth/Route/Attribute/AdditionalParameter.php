<?php

declare(strict_types=1);

namespace Astaroth\Route\Attribute;

final class AdditionalParameter
{
    public function __construct(
        private readonly string $type,
        private readonly bool $needCreateInstance = false,
        private readonly ?object $instance = null,
    ) {
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isNeedCreateInstance(): bool
    {
        return $this->needCreateInstance;
    }

    /**
     * @return object|null
     */
    public function getInstance(): ?object
    {
        return $this->instance;
    }
}
