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
     * @return array
     */
    private function getClassMap(): array
    {
        return $this->class_map;
    }

    /**
     * @param string $class_map
     * @return Route
     * @throws \Exception
     */
    public function setClassMap(string $class_map): static
    {
        $this->class_map = \HaydenPierce\ClassFinder\ClassFinder::getClassesInNamespace($class_map);
        return $this;
    }

    public function handle(): void
    {
        $this->handler->listen(function (\Astaroth\DataFetcher\DataFetcher $data) {
            (new ReflectionParser($this->getClassMap()))->handle($data);

        });
    }
}