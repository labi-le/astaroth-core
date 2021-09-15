<?php

declare(strict_types=1);

namespace Astaroth\Attribute\Aspect;

use Astaroth\Contracts\InvokableInterface;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
/**
 * Perform certain operations after calling the method
 */
class After
{
    public function __construct(InvokableInterface $invocable, array $args = [])
    {
        register_shutdown_function(static fn() => $invocable($args));
    }
}