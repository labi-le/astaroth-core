<?php

declare(strict_types=1);

namespace Astaroth\Debug;

use JetBrains\PhpStorm\Pure;
use Stringable;
use function microtime;

final class TimePerformance implements Stringable
{
    private readonly int|float $time_start;
    private readonly int|float $time_end;

    public function __construct(callable $app)
    {
        $this->time_start = microtime(true);
        $app();
        $this->time_end = microtime(true) - $this->time_start;
    }

    /**
     * @return float|int
     */
    public function getTimeStart(): float|int
    {
        return $this->time_start;
    }

    /**
     * @return float|int
     */
    public function getTimeEnd(): float|int
    {
        return $this->time_end;
    }

    #[Pure] public function __toString()
    {
        return "Execution time for this piece of code: {$this->getTimeEnd()} ms";
    }
}