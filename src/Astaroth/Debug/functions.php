<?php

declare(strict_types=1);

namespace Astaroth\Debug;

use JetBrains\PhpStorm\Pure;

#[Pure] function dump(mixed ...$mixed): Dump
{
    return new Dump($mixed);
}

function memory_stat(callable $app, string $convertTo = "M"): Memory
{
    return new Memory($app, $convertTo);
}

function time_stat(callable $app): TimePerformance
{
    return new TimePerformance($app);
}
