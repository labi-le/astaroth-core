<?php

declare(strict_types=1);

namespace Astaroth\Attribute\Aspect;

use Astaroth\Contracts\InvokableInterface;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
/**
 * Perform certain operations before calling the method
 */
class Before
{
    public function __construct(InvokableInterface $invocable, array $args = [])
    {
        $invocable($args);
    }
}