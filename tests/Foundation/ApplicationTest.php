<?php

declare(strict_types=1);

namespace Foundation;

use Astaroth\Contracts\ConfigurationInterface;
use Astaroth\Foundation\Application;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertTrue;

class ApplicationTest extends TestCase
{
    protected function setUp(): void
    {
        $this->application = $this->createMock(Application::class);
    }

    public function testRunningInConsole(): void
    {
        assertTrue(Application::runningInConsole());
    }


    public function testRun(): void
    {
        $this->application->expects($this->once())->method('run');
        $this->application->run();
    }

    public function testGetContainer(): void
    {
        $this->application->expects($this->once())->method('getContainer');
        assertInstanceOf(ContainerInterface::class, $this->application->getContainer());
    }

    public function testGetConfiguration(): void
    {
        $this->application->expects($this->once())->method('getConfiguration');
        assertInstanceOf(ConfigurationInterface::class, $this->application->getConfiguration());
    }

    public function testGetLogger(): void
    {
        $this->application->expects($this->once())->method('getLogger');
        assertInstanceOf(LoggerInterface::class, $this->application->getLogger());
    }
}
