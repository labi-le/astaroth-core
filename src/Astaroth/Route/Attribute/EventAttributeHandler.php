<?php

declare(strict_types=1);

namespace Astaroth\Route\Attribute;

use Astaroth\Contracts\AttributeOptionalInterface;
use Astaroth\Contracts\AttributeRequiredInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\DataFetcher;
use ReflectionClass;
use ReflectionException;

class EventAttributeHandler
{
    /**
     * @param string[] $classMap
     * @param DataFetcher $data
     * @throws ReflectionException
     */
    public function __construct(
        array       $classMap,
        DataFetcher $data,
    )
    {
        foreach ($classMap as $class) {
            /** @psalm-suppress ArgumentTypeCoercion */
            $reflectionClass = new ReflectionClass($class);

            if ($this->classValidateAttr($reflectionClass, $data) === false) {
                break;
            }

            new EventDispatcher($reflectionClass, $data);
        }

    }

    private function classValidateAttr(ReflectionClass $reflectionClass, DataFetcher $data): bool
    {
        $mandatoryValidation = false;
        $optionalValidation = null;

        foreach ($reflectionClass->getAttributes() as $reflectionAttribute) {
            $attribute = $reflectionAttribute->newInstance();

            if ($attribute instanceof AttributeValidatorInterface && $attribute->setHaystack($data)) {

                if ($attribute instanceof AttributeRequiredInterface) {
                    $mandatoryValidation = $attribute->validate();
                }

                if ($attribute instanceof AttributeOptionalInterface) {
                    $optionalValidation = $attribute->validate();
                }

            } else {
                throw new \LogicException(
                    sprintf("%s not implement %s, %s, %s",
                        $reflectionAttribute->getName(),
                        AttributeValidatorInterface::class,
                        AttributeRequiredInterface::class,
                        AttributeOptionalInterface::class
                    )
                );
            }
        }

        //if there are no optional attributes
        if ($optionalValidation === null) {
            return $mandatoryValidation;
        }

        return $mandatoryValidation && $optionalValidation;
    }
}