<?php

declare(strict_types=1);

namespace Astaroth\Contracts;

/**
 * Contracts HandlerInterface
 * @package Astaroth\Contracts
 */
interface HandlerInterface
{
    public function listen(callable $func): void;
}