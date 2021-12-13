<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

use Astaroth\Auth\Configuration;
use Astaroth\Bootstrap\BotInstance;
use Astaroth\Contracts\ContainerPlaceholderInterface;
use Astaroth\Handler\LazyHandler;
use Astaroth\Route\Route;
use HaydenPierce\ClassFinder\ClassFinder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Throwable;
use const PHP_SAPI;

final class Application
{
    public const VERSION = 2;

    public const DEV = "DEV";
    public const PRODUCTION = "PRODUCTION";

    public static ?ContainerBuilder $container = null;


    /**
     * Checks if the application is running in the console
     * @return bool
     */
    public static function runningInConsole(): bool
    {
        return PHP_SAPI === "cli" || PHP_SAPI === "phpdbg";
    }

    /**
     * @return ContainerBuilder
     */
    public static function getContainer(): ContainerBuilder
    {
        if (self::$container === null) {
            self::$container = new ContainerBuilder();
        }
        return self::$container;
    }

    /**
     * @throws Throwable
     */
    public function run(string $envDir = null, string $type = Application::DEV): void
    {
        $container = self::getContainer();
        $configuration = Configuration::set($envDir, $type);

        foreach (ClassFinder::getClassesInNamespace(Configuration::CONTAINER_NAMESPACE, ClassFinder::RECURSIVE_MODE) as $containerObject) {
            /**
             * @var ContainerPlaceholderInterface $instanceContainer
             */
            $instanceContainer = new $containerObject;
            $instanceContainer($container, $configuration);
        }

        FacadePlaceholder::getInstance($container, $configuration);

        (new Route(
            new LazyHandler((new BotInstance($configuration))->bootstrap())))
            ->setClassMap($configuration->getAppNamespace())
            ->handle();
    }
}