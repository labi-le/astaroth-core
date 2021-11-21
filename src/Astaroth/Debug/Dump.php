<?php /** @noinspection ForgottenDebugOutputInspection */

declare(strict_types=1);

namespace Astaroth\Debug;

use Astaroth\Foundation\Utils;

class Dump
{
    public function __construct
    (
        private mixed $data
    )
    {
    }

    public function toStdOut(): void
    {
        Utils::var_dumpToStdout(...$this->data);
    }

    public function toPrint(): void
    {
        print_r($this->data);
    }

    public function toVar_Dump(): void
    {
        var_dump($this->data);
    }

    public function return(): string|bool
    {
        return print_r($this->data, true);
    }
}