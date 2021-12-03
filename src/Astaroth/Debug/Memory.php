<?php

declare(strict_types=1);

namespace Astaroth\Debug;

use Stringable;

class Memory implements Stringable
{

    private int $final_memory_usage;

    private int $base_memory_usage;
    public function __construct(callable $app, private string $convertTo = "M")
    {
        $this->base_memory_usage = memory_get_usage(true);
        $app();
        $this->final_memory_usage = memory_get_usage(true);
    }

    /**
     * Convert bytes to the unit specified by the $to parameter.
     *
     * @param int $bytes The filesize in Bytes.
     * @param string $to The unit type to convert to. Accepts K, M, or G for Kilobytes, Megabytes, or Gigabytes, respectively.
     * @param int $decimal_places The number of decimal places to return.
     *
     * @return string Returns only the number of units, not the type letter. Returns 0 if the $to unit type is out of scope.
     *
     * @noinspection PhpSameParameterValueInspection
     */
    private function convert(int $bytes, string $to, int $decimal_places = 1): string
    {
        $formulas = [
            'K' => number_format($bytes / 1024, $decimal_places),
            'M' => number_format($bytes / 1048576, $decimal_places),
            'G' => number_format($bytes / 1073741824, $decimal_places)
        ];
        return $formulas[$to] ?? "0";
    }

    /**
     * @return int
     */
    public function getFinalMemoryUsage(): int
    {
        return $this->final_memory_usage;
    }

    /**
     * @return int
     */
    public function getBaseMemoryUsage(): int
    {
        return $this->base_memory_usage;
    }

    public function __toString()
    {
        $text = "Base memory usage: " . $this->convert($this->getBaseMemoryUsage(), $this->convertTo) . $this->convertTo;
        $text .= "\nFinal memory consumption: " . $this->convert($this->getFinalMemoryUsage(), $this->convertTo) . $this->convertTo;

        return $text;
    }
}