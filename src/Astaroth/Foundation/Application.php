<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

use Astaroth\Auth\Configuration;
use Astaroth\Bootstrap\BotInstance;
use Astaroth\Handler\LazyHandler;
use Astaroth\Route\Route;
use Astaroth\Support\Facades\FacadePlaceholder;
use HaydenPierce\ClassFinder\ClassFinder;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Application
{
    private const VERSION = 2;
    private const SERVICE_NAMESPACE = "Astaroth\Services";

    /**
     * Checks if the application is running in the console
     * @return bool
     */
    public function runningInConsole(): bool
    {
        return \PHP_SAPI === "cli" || \PHP_SAPI === "phpdbg";
    }

    /**
     * @throws \Throwable
     */
    public function run(string $dir = __DIR__): void
    {
        $container = new ContainerBuilder();
        $configuration = (new Configuration(dirname($dir)))
            ->get();

        array_walk($configuration, static fn($value, $key) => $container->setParameter($key, $value));

        foreach (ClassFinder::getClassesInNamespace(self::SERVICE_NAMESPACE) as $service) {
            $service = new $service;
            $service($container);
        }

        FacadePlaceholder::getInstance($container);

        (new Route(
            new LazyHandler((new BotInstance($container))->bootstrap())))
            ->setClassMap($container->getParameter("APP_NAMESPACE"))
            ->handle();
    }
}