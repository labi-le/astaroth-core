<?php

declare(strict_types=1);

namespace Astaroth\Debug;

if (!function_exists("dump")) {
    function dump(mixed ...$mixed): Dump
    {
        return new Dump($mixed);
    }
}


if (!function_exists("memory_stat")) {
    function memory_stat(callable $app, string $convertTo = "M"): Dump
    {
        /**
         * Convert bytes to the unit specified by the $to parameter.
         *
         * @param int $bytes The filesize in Bytes.
         * @param string $to The unit type to convert to. Accepts K, M, or G for Kilobytes, Megabytes, or Gigabytes, respectively.
         * @param int $decimal_places The number of decimal places to return.
         *
         * @return integer Returns only the number of units, not the type letter. Returns 0 if the $to unit type is out of scope.
         *
         */
        function byte(int $bytes, string $to, int $decimal_places = 1): int
        {
            $formulas = array(
                'K' => number_format($bytes / 1024, $decimal_places),
                'M' => number_format($bytes / 1048576, $decimal_places),
                'G' => number_format($bytes / 1073741824, $decimal_places)
            );
            return $formulas[$to] ?? 0;
        }

        $base_memory_usage = memory_get_usage(true);
        $app();
        $end = memory_get_usage(true);


        $text = byte($base_memory_usage, $convertTo) . "\nend: " . byte($end, $convertTo);

        return new Dump($text);
    }
}