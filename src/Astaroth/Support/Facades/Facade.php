<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;


/**
 * Class Facade
 * @package Astaroth\Support\Facades
 */
class Facade
{
    protected static \Symfony\Component\DependencyInjection\ContainerBuilder $container;

    public function __construct(\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        self::$container = $container;
    }

    /**
     * Get an object by its name
     * @param string $object
     * @return object|null
     */
    protected static function getObject(string $object): ?object
    {
        try {
            return self::$container->get($object);
        } catch (\Exception) {
            throw new \LogicException("The $object is missing from the facade");
        }
    }
}