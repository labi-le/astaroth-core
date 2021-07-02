<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

class Application
{
    private const VERSION = 2;

    /**
     * Checks if the application is running in the console
     * @return bool
     */
    public function runningInConsole(): bool
    {
        return \PHP_SAPI === "cli" || \PHP_SAPI === "phpdbg";
    }
}