<?php
declare(strict_types=1);

namespace Astaroth\Contracts;

interface EnvironmentInterface
{
    public function getEnv(): string|array;
    public function setEnv(array|string $env): bool;
}