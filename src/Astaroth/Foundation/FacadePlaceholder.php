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

    private static ContainerInterface $container;
    private static Configuration $configuration;

    private function __construct(?ContainerInterface $container, ?Configuration $configuration)
    {
        self::$container = $container;
        self::$configuration = $configuration;
    }

    /**
     * @param ContainerInterface|null $container
     * @param Configuration|null $configuration
     * @return FacadePlaceholder
     */
    public static function getInstance(ContainerInterface $container = null, Configuration $configuration = null): FacadePlaceholder
    {
        if (self::$instance === null) {
            self::$instance = new self($container, $configuration);
        }

        return self::$instance;
    }

    /**
     * @return Configuration
     */
    public static function getConfiguration(): Configuration
    {
        return self::$configuration;
    }

    /**
     * @return ContainerInterface
     */
    public static function getContainer(): ContainerInterface
    {
        return self::$container;
    }
}