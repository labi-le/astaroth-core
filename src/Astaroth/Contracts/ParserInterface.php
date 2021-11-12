<?php
declare(strict_types=1);

namespace Astaroth\Contracts;

interface ParserInterface
{
    public function parse(): mixed;
}