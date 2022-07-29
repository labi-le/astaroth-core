<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

use Astaroth\Route\Attribute\AdditionalParameter;
use Astaroth\Route\Attribute\ReflectionMethodDecorator;
use ReflectionClass;
use ReflectionException;
use function array_merge;

final class Executor
{
    private ModifiedObject $replaced;


    /**
     * General event coordinator
     * @param ReflectionClass $reflectionClass
     * @param ReflectionMethodDecorator[] $reflectionMethods
     * @param AdditionalParameter[] $replaceableObjects
     */
    public function __construct(
        private readonly ReflectionClass $reflectionClass,
        private readonly array           $reflectionMethods,
        array                            $replaceableObjects
    )
    {
        $this->replaced = new ModifiedObject();
        foreach ($replaceableObjects as $replaceableObject) {
            $this->replaced->replaceObjects($replaceableObject);
        }
    }

    /**
     * Attempts to invoke methods and pass parameters
     *
     * @throws ReflectionException
     * @psalm-suppress MixedAssignment, PossiblyInvalidArgument
     */
    public function launch(callable $methodResponseHandler = null): void
    {
        $invokedClass = Reflect::instantiateClass($this->reflectionClass, ...$this->replaced->getReplaceableObjects());


        foreach ($this->reflectionMethods as $method) {
            $modified = new ModifiedObject();
            $modified->addReplaceableAttributes($method->getAttributes());
            $modified->initializeParameters($method->getParameters());

            $parameters = array_merge($modified->getParameters(), $modified->getReplaceableObjects(), $this->replaced->getReplaceableObjects());

            $method_return = Reflect::invoke
            (
                $invokedClass,
                $method,
                //normalize the parameter list for the method
                Reflect::parameterNormalizer($method->getParameters(), $parameters)
            );

            if ($methodResponseHandler !== null) {
                $methodResponseHandler($method_return);
            }
        }
    }
}