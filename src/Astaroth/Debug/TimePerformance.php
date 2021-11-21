<?php

declare(strict_types=1);

namespace Astaroth\Debug;

class TimePerformance
{
    /**
     * @var callable
     */
    private $app;

    public function __construct(callable $app)
    {
        $this->app = $app;
    }

    public function getStat(): Dump
    {
        $start = microtime(true);
        ($this->app)();
        $time = microtime(true) - $start;

        $text = "\n\nExecution time for this piece of code: $time ms\n\n";

        return new Dump($text);
    }
}