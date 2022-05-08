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

    /**
     * @param ContainerInterface|null $container
     * @param ConfigurationInterface|null $configuration
     * @return FacadePlaceholder
     */
    public static function getInstance(ContainerInterface $container = null, ConfigurationInterface $configuration = null): FacadePlaceholder
    {
        if (self::$instance === null) {
            self::$instance = new self($container, $configuration);
        }

        return self::$instance;
    }
}