<?php


namespace Astaroth\Route;


/**
 * Class Route
 * @package Astaroth\Route
 */
class Route
{
    private array $class_map;

    public function __construct(private \Astaroth\Handler\LazyHandler $handler)
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
     * @return Route
     * @throws \Exception
     */
    public function setClassMap(string $class_map): static
    {
        $this->class_map = \HaydenPierce\ClassFinder\ClassFinder::getClassesInNamespace($class_map);
        return $this;
    }


    /**
     * Routing data from VK
     * @throws \Throwable
     */
    public function handle(): void
    {
        $this->handler->listen(function (\Astaroth\DataFetcher\DataFetcher $data) {
            (new ReflectionParser($this->getClassMap()))->handle($data);

        });
    }
}