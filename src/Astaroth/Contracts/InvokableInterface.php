<?php

declare(strict_types=1);

namespace Astaroth\Contracts;

interface InvokableInterface
{
    /**
     * @param mixed $args
     * @return void
     */
    public function __invoke(...$args): void;
}