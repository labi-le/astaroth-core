<?php

declare(strict_types=1);


namespace Astaroth\Route;


use Astaroth\Contracts\HandlerInterface;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\Route\Attribute\EventAttributeHandler;
use Exception;
use HaydenPierce\ClassFinder\ClassFinder;
use Throwable;

/**
 * Class Route
 * @package Astaroth\Route
 */
class Route
{
    private array $class_map;

    public function __construct(private HandlerInterface $handler)
    {
    }

    /**
     * Get class map
     * @return array
     */
    private function getClassMap(): array
    {
        return $this->class_map;
    }

    /**
     * Set class map
     * @param string $class_map
     * @return static
     * @throws Exception
     */
    public function setClassMap(string $class_map): static
    {
        $this->class_map = ClassFinder::getClassesInNamespace($class_map, ClassFinder::RECURSIVE_MODE);
        return $this;
    }

    /**
     * Routing data from VK
     * @throws Throwable
     */
    public function handle(): void
    {
        $this->handler->listen(function (DataFetcher $data) {
            new EventAttributeHandler($this->getClassMap(), $data);
        });
    }
}