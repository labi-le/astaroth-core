<?php

declare(strict_types=1);

namespace Astaroth\Attribute\Aspect;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
/**
 * Perform certain operations after calling the method
 */
class After
{
    /**
     * Class being executed must be invokable
     * @param string $invokable
     * @param array $args
     */
    public function __construct(string $invokable, array $args = [])
    {
        register_shutdown_function(static fn() => $invokable($args));
    }
}