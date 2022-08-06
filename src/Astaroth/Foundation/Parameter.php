<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

final class Parameter
{
    public function __construct(
        private readonly string $type,
        private readonly bool   $needCreateInstance = false,
        private readonly mixed  $arg = null,
    )
    {
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

    public function getArg(): mixed
    {
        return $this->arg;
    }
}
