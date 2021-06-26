<?php


namespace Astaroth\Support\Facades;


/**
 * Class Facade
 * @package Astaroth\Support\Facades
 */
class Facade
{
    protected static array $objects;

    public function __construct(object ...$objects)
    {
        array_walk($objects, static fn($object) => self::$objects[$object::class] = $object);
    }

    /**
     * @param string $object
     * @return mixed
     */
    protected static function getObject(string $object): mixed
    {
        return self::$objects[$object] ?? throw new \LogicException("The $object is missing from the facade");
    }
}