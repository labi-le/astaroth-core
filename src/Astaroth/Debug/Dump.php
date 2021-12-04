<?php /** @noinspection ForgottenDebugOutputInspection */

declare(strict_types=1);

namespace Astaroth\Debug;

use function is_array;

final class Dump
{
    public function __construct
    (
        private mixed $data
    )
    {
    }

    public function toStdOut(): void
    {
        if (is_array($this->data) === false) {
            $this->data = [$this->data];
        }

        foreach ($this->data as $out) {
            file_put_contents('php://stdout', var_export($out, true));
        }
    }

    public function toPrint(): void
    {
        print_r($this->data);
    }

    /**
     * @psalm-suppress ForbiddenCode
     */
    public function toVar_Dump(): void
    {
        var_dump($this->data);
    }

    public function return(): string|bool
    {
        return print_r($this->data, true);
    }
}