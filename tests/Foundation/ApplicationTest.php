<?php

declare(strict_types=1);

namespace Foundation;

use Astaroth\Foundation\Application;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use function PHPUnit\Framework\assertTrue;

class ApplicationTest extends TestCase
{

    public function testRunningInConsole(): void
    {
        assertTrue(Application::runningInConsole());
    }

    public function testGetContainer(): void
    {
        assertTrue(Application::getContainer() instanceof ContainerInterface);
    }

}
