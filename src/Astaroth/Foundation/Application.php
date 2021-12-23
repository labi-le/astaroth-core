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

    public static ContainerBuilder $container;
    public static Configuration $configuration;

    public function __construct(private ?string $envDir = null, private string $type = Application::DEV)
    {
        self::$container = new ContainerBuilder();
        self::$configuration = Configuration::set($this->envDir, $this->type);
    }


    /**
     * Checks if the application is running in the console
     * @return bool
     */
    public static function runningInConsole(): bool
    {
        return PHP_SAPI === "cli" || PHP_SAPI === "phpdbg";
    }

    /**
     * @throws Throwable
     */
    public function run(): void
    {
        foreach (ClassFinder::getClassesInNamespace(Configuration::CONTAINER_NAMESPACE, ClassFinder::RECURSIVE_MODE) as $containerObject) {
            /**
             * @var ContainerPlaceholderInterface $instanceContainer
             */
            $instanceContainer = new $containerObject;
            $instanceContainer(self::$container, self::$configuration);
        }

        FacadePlaceholder::getInstance(self::$container, self::$configuration);

        (new Route(
            new LazyHandler((new BotInstance(self::$configuration))->bootstrap())))
            ->setClassMap(self::$configuration->getAppNamespace())
            ->handle();
    }
}