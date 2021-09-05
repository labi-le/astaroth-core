<?php

declare(strict_types=1);

namespace Astaroth\Route;

class ReturnResultHandler
{
    private mixed $result;

    public function __construct(mixed $result = null)
    {
        $this->result = $result;
    }

    public function process(): void
    {
        if (is_callable($this->result)) {
            ($this->result)();
        }
        //
    }
}