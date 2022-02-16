<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

use Astaroth\Auth\Configuration;
use Astaroth\Bootstrap\BotInstance;
use Astaroth\Contracts\ContainerPlaceholderInterface;
use Astaroth\Enums\Configuration\ApplicationWorkMode;
use Astaroth\Handler\LazyHandler;
use Astaroth\Route\Route;
use HaydenPierce\ClassFinder\ClassFinder;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Throwable;
use function getcwd;
use function sprintf;
use const PHP_SAPI;

final class Application
{
    public const MAJOR_VERSION = 2;
    public const MINOR_VERSION = '2.9.0';

    public static ContainerBuilder $container;
    public static Configuration $configuration;

    public static LoggerInterface $logger;

    public function __construct(private ?string $envDir = null, private readonly ApplicationWorkMode $type = ApplicationWorkMode::DEVELOPMENT)
    {
        self::$container = new ContainerBuilder();
        self::$configuration = Configuration::set($this->envDir, $this->type);

        self::$logger = $this->configureLog();
    }

    private function configureLog(): LoggerInterface
    {
        $logger = new Logger("log");

        if (self::$configuration->isDebug() === true) {
            $logger->pushHandler(new StreamHandler(sprintf('%s%s%s', getcwd(), DIRECTORY_SEPARATOR, '.log')));
            if ($this->type === ApplicationWorkMode::DEVELOPMENT) {
                $logger->pushHandler(new StreamHandler(STDOUT));
            }
        } else {
            $logger->pushHandler(new NullHandler);
        }

        return $logger;
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
        self::$logger->info("App started");
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