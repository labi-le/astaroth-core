<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

use Astaroth\Auth\Configuration;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
        if (self::$instance === null) {
            /** @var Application $app extend */
            self::$instance = new self($app);
        }

        return self::$instance;
    }
}