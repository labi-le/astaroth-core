<?php

declare(strict_types=1);

namespace Astaroth\Contracts;

interface InvokableInterface
{
    /**
     * @param array $args
     * @return void
     */
    public function __invoke(array $args = []): void;
}