<?php

declare(strict_types=1);

namespace Astaroth\Debug;

class Memory
{
    /**
     * @var callable
     */
    private $app;

    public function __construct(callable $app, private string $convertTo = "M")
    {
        $this->app = $app;
    }

    public function getStat(): Dump
    {
        $base_memory_usage = memory_get_usage(true);
        ($this->app)();
        $end = memory_get_usage(true);

        $text = "\n\nInitial memory consumption: " . $this->convert($base_memory_usage, $this->convertTo) . $this->convertTo;
        $text .= "\nFinal memory consumption: " . $this->convert($end, $this->convertTo) . $this->convertTo . "\n\n";

        return new Dump($text);
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
}