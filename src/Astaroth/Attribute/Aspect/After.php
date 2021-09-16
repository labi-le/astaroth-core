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
    /**
     * Class being executed must be invokable
     * @param string $invokable
     * @param array $args
     */
    public function __construct(string $invokable, array $args = [])
    {
        register_shutdown_function(static function () use ($args, $invokable) {
            /**
             * @var InvokableInterface $object
             * @psalm-suppress UndefinedClass
             */
            $object = new $invokable;
            $object($args);
        });
    }
}