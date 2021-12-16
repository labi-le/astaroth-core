<?php

declare(strict_types=1);

namespace Astaroth\Contracts;

/**
 * Return result from attribute
 * @package Astaroth\Contracts
 */
interface AttributeReturnInterface
{
    public function return(): mixed;
}