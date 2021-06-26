<?php

declare(strict_types=1);

namespace Astaroth\Route;


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

    public function handle(DataFetcher $data): void
    {
        new Attribute($this->parseClass(), $data);
    }


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

    private function parseAttribute(ReflectionAttribute ...$parameters): array
    {
        $attribute = [];
        foreach ($parameters as $parameter) {
            $attribute[] = $parameter->newInstance();
        }

        return $attribute;
    }

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