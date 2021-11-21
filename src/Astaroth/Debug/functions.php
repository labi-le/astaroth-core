<?php

declare(strict_types=1);

namespace Astaroth\Debug;

function dump(mixed ...$mixed): Dump
{
    return new Dump($mixed);
}

function memory_stat(callable $app, string $convertTo = "M"): Dump
{
    return (new Memory($app, $convertTo))->getStat();
}

function time_stat(callable $app): Dump
{
    return (new TimePerformance($app))->getStat();
}
