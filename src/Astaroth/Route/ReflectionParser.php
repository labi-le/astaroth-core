<?php

declare(strict_types=1);

namespace Astaroth\Route;


use Astaroth\Route\DataTransferObject\ClassInfo;
use Astaroth\Route\DataTransferObject\MethodInfo;
use Astaroth\Route\DataTransferObject\MethodParamInfo;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

class ReflectionParser
{
    private function __construct(private array $class_map)
    {
    }

    public static function setClassMap(array $class_map)
    {
        return new static($class_map);
    }

    /**
     * @return ClassInfo[]
     * @throws \ReflectionException
     */
    public function parse(): array
    {
        return $this->parseClass();
    }


    /**
     * Parse method params from class
     * @param ReflectionParameter ...$parameters
     * @return MethodParamInfo[]
     * @psalm-suppress UndefinedMethod
     */
    private function parseMethodParameters(ReflectionParameter ...$parameters): array
    {
        $method_params = [];
        foreach ($parameters as $parameter) {
            $method_params[] = new MethodParamInfo
            (
                $parameter->getName(),
                $parameter->getType()?->getName()
            );
        }
        return $method_params;
    }

    /**
     * Parse attributes from class\methods
     * @param ReflectionAttribute ...$parameters
     * @return object[]
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
     * @return MethodInfo[]
     */
    private function parseMethod(ReflectionMethod ...$class): array
    {
        $parameters = [];
        foreach ($class as $method) {
            $attributes = $method->getAttributes();
            if ($attributes !== []) {
                $parameters[] = new MethodInfo
                (
                    $method->getName(),
                    $this->parseAttribute(...$attributes),
                    $this->parseMethodParameters(...$method->getParameters())
                );
            }
        }

        return $parameters;
    }

    /**
     * Parse class method, attribute
     * @return ClassInfo[]
     * @throws \ReflectionException
     */
    private function parseClass(): array
    {
        $map = [];
        foreach ($this->class_map as $_map) {
            $reflectionClass = new ReflectionClass($_map);

            if ($reflectionClass->getAttributes() !== []) {
                $attribute = $this->parseAttribute(...$reflectionClass->getAttributes());
                $methods = $this->parseMethod(...$reflectionClass->getMethods());

                if ($attribute !== [] && $methods !== []) {
                    $map[] = new ClassInfo
                    (
                        $reflectionClass->getName(),
                        $attribute,
                        $methods,
                        $reflectionClass->newInstance()
                    );
                }
            }
        }
        return $map;
    }
}