<?php

declare(strict_types=1);

namespace Astaroth\Route;


use Astaroth\Attribute\NotImplementedHaystackException;
use Astaroth\DataFetcher\DataFetcher;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

class ReflectionParser
{
    public function __construct(private array $class_map)
    {
    }

    /**
     * Parse the class that contains the attributes, etc...
     * @param DataFetcher $data
     * @throws \ReflectionException|NotImplementedHaystackException
     */
    public function handle(DataFetcher $data): void
    {
        new Attribute($this->parseClass(), $data);
    }


    /**
     * Parse method params from class
     * @param ReflectionParameter ...$parameters
     * @return array
     */
    private function parseMethodParameters(ReflectionParameter ...$parameters): array
    {
        $method_params = [];
        foreach ($parameters as $parameter) {
            $method_params[] =
                [
                    "name" => $parameter->getName(),
                    "type" => $parameter->getType()?->getName(),
                ];
        }
        return $method_params;
    }

    /**
     * Parse attributes from class\methods
     * @param ReflectionAttribute ...$parameters
     * @return array
     */
    private function parseAttribute(ReflectionAttribute ...$parameters): array
    {
        $attribute = [];
        foreach ($parameters as $parameter) {
            $attribute[] = $parameter->newInstance();
        }

        return $attribute;
    }

    /**
     * Parse method from class
     * @param ReflectionMethod ...$class
     * @return array
     */
    private function parseMethod(ReflectionMethod ...$class): array
    {
        $parameters = [];
        foreach ($class as $method) {
            if ($method->getAttributes() !== []) {
                $parameters[] =
                    [
                        "name" => $method->getName(),
                        "parameters" => $this->parseMethodParameters(...$method->getParameters()),
                        "attribute" => $this->parseAttribute(...$method->getAttributes()),
                    ];
            }
        }

        return $parameters;
    }

    /**
     * Parse class method, attribute
     * @return array
     * @throws \ReflectionException
     */
    private function parseClass(): array
    {
        $map = [];
        foreach ($this->class_map as $_map) {
            $reflectionClass = new ReflectionClass($_map);

            if ($reflectionClass->getAttributes() !== []) {
                $name = $reflectionClass->getName();
                $attribute = $this->parseAttribute(...$reflectionClass->getAttributes());
                $methods = $this->parseMethod(...$reflectionClass->getMethods());

                if ($attribute !== [] && $methods !== []) {
                    $map[] =
                        [
                            "name" => $name,
                            "attribute" => $attribute,
                            "methods" => $methods,
                            "instance" => $reflectionClass->newInstance()
                        ];
                }
            }
        }
        return $map;
    }
}