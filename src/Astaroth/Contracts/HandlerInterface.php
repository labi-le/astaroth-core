<?php

declare(strict_types=1);

namespace Astaroth\Contracts;

/**
 * Contracts HandlerInterface
 * Longpoll, callback, etc... interface
 * Allows you to process an event received from the server
 * @package Astaroth\Contracts
 */
interface HandlerInterface
{
    /**
     * @param callable $func
     * @return void
     */
    public function listen(callable $func): void;
}
