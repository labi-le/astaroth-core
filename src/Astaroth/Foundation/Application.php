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
use const DIRECTORY_SEPARATOR;
use const PHP_SAPI;

class Application
{
    public const MAJOR_VERSION = 2;
    public const MINOR_VERSION = '2.9.0';

    private ContainerBuilder $container;
    private Configuration $configuration;

    private LoggerInterface $logger;

    public function __construct(private ?string $envDir = null, private readonly ApplicationWorkMode $type = ApplicationWorkMode::DEVELOPMENT)
    {
        $this->container = new ContainerBuilder();
        $this->configuration = Configuration::set($this->envDir, $this->type);

        $this->logger = $this->configureLog();
    }

    protected function configureLog(): LoggerInterface
    {
        $logger = new Logger("log");

        if ($this->getConfiguration()->isDebug() === true) {
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
     * @return ContainerBuilder
     */
    public function getContainer(): ContainerBuilder
    {
        return $this->container;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @throws Throwable
     */
    public function run(): void
    {
        $this->getLogger()->info("App started");
        $this->fillContainers();
        $this->fillFacades();

        $this->bootstrap();
    }

    protected function fillContainers(): void
    {
        foreach (ClassFinder::getClassesInNamespace(Configuration::CONTAINER_NAMESPACE, ClassFinder::RECURSIVE_MODE) as $containerObject) {
            /**
             * @var ContainerPlaceholderInterface $instanceContainer
             */
            $instanceContainer = new $containerObject;
            $instanceContainer($this->getContainer(), $this->getConfiguration());
        }
    }

    protected function fillFacades(): void
    {
        FacadePlaceholder::getInstance($this);
    }

    /**
     * @return void
     * @throws Throwable
     */
    protected function bootstrap(): void
    {
        $botInstance = new BotInstance($this->getConfiguration());
        $lazyHandler = new LazyHandler($botInstance->bootstrap(), $this->getLogger());
        $route = new Route($lazyHandler, $this->getConfiguration()->getAppNamespace());
        $route->handle();
    }
}