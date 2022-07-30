<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

use Astaroth\Contracts\ConfigurationInterface;
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
     * @param ContainerInterface $container
     * @param ConfigurationInterface $configuration
     * @return FacadePlaceholder
     */
    public static function fill(ContainerInterface $container, ConfigurationInterface $configuration): FacadePlaceholder
    {
        if (self::$instance === null) {
            self::$instance = new self($container, $configuration);
        }

        return self::$instance;
    }


    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @return ConfigurationInterface
     */
    public function getConfiguration(): ConfigurationInterface
    {
        return $this->configuration;
    }


    private function __construct(private readonly ContainerInterface $container, private readonly ConfigurationInterface $configuration)
    {
    }

    public static function getInstance(): ?FacadePlaceholder
    {
        return self::$instance;
    }
}
