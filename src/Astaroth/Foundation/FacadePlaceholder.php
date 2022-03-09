<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

use Astaroth\Auth\Configuration;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use function is_subclass_of;

/**
 * Class Facade
 * Singleton
 * @package Astaroth\Support\Facades
 */
final class FacadePlaceholder
{
    private static ?FacadePlaceholder $instance = null;

    private ContainerInterface $container;

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
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

    private Configuration $configuration;

    private function __construct(Application $app)
    {
        $this->container = $app->getContainer();
        $this->configuration = $app->getConfiguration();
    }

    /**
     * @param object|null $app
     * @return FacadePlaceholder
     */
    public static function getInstance(object $app = null): FacadePlaceholder
    {
        if (is_subclass_of($app, Application::class)) {
            if (self::$instance === null) {
                self::$instance = new self($app);
            }
        } else {
            throw new RuntimeException('The app parameter must be an instance of the Astaroth\Foundation\Application class');
        }

        return self::$instance;
    }
}