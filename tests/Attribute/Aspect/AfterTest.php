<?php

declare(strict_types=1);

namespace Attribute\Aspect;

use Astaroth\Attribute\Aspect\After;
use Astaroth\Contracts\InvokableInterface;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertIsObject;

class AfterTest extends TestCase
{

    public function test__construct()
    {
        $class = new class implements InvokableInterface {
            public function __invoke(array $args = []): void
            {
                echo "after";
            }
        };
        assertIsObject(new After($class::class));
    }
}
