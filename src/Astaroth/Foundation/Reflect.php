<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

use Astaroth\Route\Attribute\AdditionalParameter;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;
use function array_filter;
use function current;
use function is_object;
use function is_string;

final class Reflect
{
    /**
     * @param ReflectionClass|string $reflectionClassOrClassName
     * @param AdditionalParameter[] $parameters
     * @return object
     * @throws ReflectionException
     */
    public static function instantiateClass(ReflectionClass|string $reflectionClassOrClassName, ...$parameters): object
    {
        if (is_string($reflectionClassOrClassName)) {
            /** @psalm-suppress ArgumentTypeCoercion */
            $reflectionClassOrClassName = new ReflectionClass($reflectionClassOrClassName);
        }

        $constructor = $reflectionClassOrClassName->getConstructor();
        $var = self::parameterNormalizer($constructor ? $constructor->getParameters() : [], $parameters);
        return $reflectionClassOrClassName->newInstance(
            ...$var
        );
    }

    /**
     * Adds the necessary parameters to the method that requires it
     * @param ReflectionParameter[] $reflectionParameters
     * @param AdditionalParameter[] $parameters
     * @return array
     * @throws ReflectionException
     */
    public static function parameterNormalizer(array $reflectionParameters, array $parameters): array
    {
        $methodParameters = [];
        foreach ($reflectionParameters as $schema) {
            foreach ($parameters as $extraParameter) {
                if ($schema->getType() !== null) {
                    $normalized = false;

                    if ($schema->getType() instanceof ReflectionUnionType) {
                        $normalized = self::normalizeUnionType($schema->getType()->getTypes(), $extraParameter);
                    }

                    if ($schema->getType() instanceof ReflectionNamedType) {
                        $normalized = self::normalizeNamedType($schema->getType(), $extraParameter);
                    }

                    if (is_object($normalized)) {
                        $methodParameters[] = $normalized;
                    }

                }
            }
        }

        return $methodParameters;
    }

    /**
     * @param ReflectionNamedType[] $reflectionTypes
     * @param AdditionalParameter $additionalParameter
     * @return ?object
     * @throws ReflectionException
     */
    private static function normalizeUnionType(array $reflectionTypes, AdditionalParameter $additionalParameter): ?object
    {
        $parameters = [];
        foreach ($reflectionTypes as $reflectionType) {
            $parameters[] = self::normalizeNamedType($reflectionType, $additionalParameter);
        }

        return current(array_filter($parameters)) ?: null;
    }

    /**
     * @param ReflectionNamedType $reflectionType
     * @param AdditionalParameter $additionalParameter
     * @return ?object
     * @throws ReflectionException
     */
    private static function normalizeNamedType(ReflectionNamedType $reflectionType, AdditionalParameter $additionalParameter): ?object
    {
        if ($reflectionType->getName() === $additionalParameter->getType()) {
            if ($additionalParameter->isNeedCreateInstance() === true) {
                return self::newInstance($additionalParameter->getType());
            }
            return $additionalParameter->getInstance();
        }

        return null;
    }

    /**
     * @psalm-suppress ArgumentTypeCoercion
     * @throws ReflectionException
     */
    private static function newInstance(string $class, mixed ...$parameters): object
    {
        return (new ReflectionClass($class))->newInstance(...$parameters);
    }

    /**
     * We call methods from the class on which the correct route is set
     * And add arguments
     * method_exist is not needed since method 100% exists
     *
     * @param object $object
     * @param ReflectionMethod $method
     * @param array $parameters
     * @return mixed
     * @throws ReflectionException
     *
     * @psalm-suppress MixedReturnStatement
     */
    public static function invoke(object $object, ReflectionMethod $method, array $parameters): mixed
    {
        return $method->invoke($object, ...$parameters);
    }

}