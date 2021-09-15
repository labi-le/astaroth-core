<?php

declare(strict_types=1);

namespace Astaroth\Route;

class ReturnResultHandler
{
    public function __construct(mixed $result = null)
    {
        $this->process($result);
    }

    public function process(mixed $result): void
    {
        /** If the user-method returned callable, then execute it */
        if (is_callable($result)) {
            $this->runCallable($result);
        }

        /** If the user-method returned false, then stop processing commands */
        if ($result === false) {
            $this->terminate();
        }
    }

    private function runCallable(callable $callable)
    {
        $callable();
    }

    private function terminate(): void
    {
        die;
    }
}