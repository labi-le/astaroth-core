<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

use Astaroth\Attribute\Method\Debug;
use Astaroth\Contracts\AttributeReturnInterface;
use Astaroth\Route\Attribute\AdditionalParameter;
use ReflectionAttribute;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;

use function debug_backtrace;

class ModifiedObject
{
    /** @var AdditionalParameter[] */
    private array $replaceableObjects = [];

    /** @var AdditionalParameter[] */
    private array $parameters = [];

    /**
     * Replace attributes that can be used as method parameters
     * @param object[] $attributes
     */
    public function addReplaceableAttributes(array $attributes): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute instanceof ReflectionAttribute) {
                $attribute = $attribute->newInstance();
            }

            if ($attribute instanceof AttributeReturnInterface) {
                $this->replaceObjects($attribute);
            }

            //for debug
            if ($attribute instanceof Debug) {
                $this->replaceObjects($attribute->setHaystack(debug_backtrace()));
            }
        }
    }

    /**
     * Add intercepted object from outside
     * @param object $instance
     * @return ModifiedObject
     */
    public function replaceObjects(object $instance): ModifiedObject
    {
        isset($this->getReplaceableObjects()[$instance::class]) ?:
            $this->replaceableObjects[$instance::class] = new AdditionalParameter(
                $instance::class,
                false,
                $instance
            );


        return $this;
    }

    /**
     * @return AdditionalParameter[]
     */
    public function getReplaceableObjects(): array
    {
        return $this->replaceableObjects;
    }

    /**
     * @return AdditionalParameter[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    private function addParameters(object ...$instances): void
    {
        foreach ($instances as $instance) {
            isset($this->getParameters()[$instance::class]) ?:
                $this->parameters[$instance::class] = new AdditionalParameter(
                    $instance::class,
                    true,
                    $instance
                );
        }
    }

    /**
     * @param ReflectionParameter[] $parameters
     * @throws ReflectionException
     */
    public function initializeParameters(array $parameters): void
    {
        foreach ($parameters as $parameter) {
            /** @psalm-suppress TypeDoesNotContainType */
            if ($parameter->getType() === ReflectionNamedType::class) {
                isset($this->getReplaceableObjects()[$parameter->getName()]) ?:
                    $this->addParameters(Reflect::instantiateClass($parameter->getName()));
            }
        }
    }
}
