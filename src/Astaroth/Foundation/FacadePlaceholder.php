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


    private function __construct(private ContainerInterface $container, private Configuration $configuration)
    {
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
}