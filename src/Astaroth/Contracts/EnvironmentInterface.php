<?php

namespace Astaroth\Contracts;

interface EnvironmentInterface
{
    public function getEnv(): string|array;
    public function setEnv(array|string $env): bool;
}