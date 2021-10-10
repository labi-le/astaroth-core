<?php

declare(strict_types=1);

namespace Astaroth\Attribute\Aspect;

use Astaroth\Contracts\InvokableInterface;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
/**
 * Perform certain operations before calling the method
 */
final class Before
{
    /**
     * Class being executed must be invokable
     * @param class-string<InvokableInterface> $invokable
     * @param mixed ...$args
     */
    public function __construct(string $invokable, ...$args)
    {
        /**
         * @var InvokableInterface $object
         * @psalm-suppress UndefinedClass
         */
        $object = new $invokable;
        $object(...$args);
    }
}