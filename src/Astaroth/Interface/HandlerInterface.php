<?php

declare(strict_types=1);

namespace Astaroth\Interface;

/**
 * Interface HandlerInterface
 * @package Astaroth\Interface
 */
interface HandlerInterface
{
    public function listen(callable $func): void;
}