<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;


use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Facade
 * @package Astaroth\Support\Facades
 */
final class FacadePlaceholder
{
    protected static ContainerInterface $container;

    private static ?FacadePlaceholder $instance = null;

    /**
     * @param ContainerInterface|null $container
     * @return FacadePlaceholder
     */
    public static function getInstance(ContainerInterface $container = null): FacadePlaceholder
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