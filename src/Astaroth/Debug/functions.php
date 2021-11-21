<?php

declare(strict_types=1);

namespace Astaroth\Debug;

use function function_exists;

if (!function_exists("dump")) {
    function dump(mixed ...$mixed): Dump
    {
        return new Dump($mixed);
    }
}


if (!function_exists("memory_stat")) {
    function memory_stat(callable $app, string $convertTo = "M"): Memory
    {
        return new Memory($app, $convertTo);
    }
}