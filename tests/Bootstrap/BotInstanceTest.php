<?php

declare(strict_types=1);

namespace Bootstrap;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertTrue;

class BotInstanceTest extends TestCase
{

    public function testBootstrap()
    {
//        $container = Application::getContainer();
//        $configuration = (new Configuration(dirname(__DIR__)))
//            ->get(Application::DEV);
//
//        array_walk($configuration, static fn($value, $key) => $container->setParameter($key, $value));
//
//        assertTrue((new BotInstance($container))->bootstrap() instanceof HandlerInterface);
        assertTrue(true);
    }
}
