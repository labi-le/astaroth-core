<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;


use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Facade
 * @package Astaroth\Support\Facades
 */
final class Facade
{
    protected static ContainerInterface $container;

    private static $instance;

    /**
     * @param ContainerInterface|null $container
     * @return Facade
     */
    public static function getInstance(ContainerInterface $container = null): Facade
    {
        if (self::$instance === null) {
            self::$instance = new self($container);
        }

        return self::$instance;
    }

    public function getContainer(): ContainerInterface
    {
        return self::$container;
    }

    private function __construct(ContainerInterface $container)
    {
        self::$container = $container;
    }
}