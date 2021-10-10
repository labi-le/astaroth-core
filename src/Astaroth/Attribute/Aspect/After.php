<?php

declare(strict_types=1);

namespace Astaroth\Attribute\Aspect;

use Astaroth\Contracts\InvokableInterface;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
/**
 * Perform certain operations after calling the method
 */
final class After
{
    /**
     * Class being executed must be invokable
     * @param class-string<InvokableInterface> $invokable
     * @param mixed ...$args
     */
    public function __construct(string $invokable, ...$args)
    {
        register_shutdown_function(static function () use ($args, $invokable) {
            /**
             * @var InvokableInterface $object
             * @psalm-suppress UndefinedClass
             */
            $object = new $invokable;
            $object(...$args);
        });
    }
}