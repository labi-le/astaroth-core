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

//todo speed feature
//        Fork::new()
//            ->concurrent(100)
//            ->run(...array_map(fn(string $class) => function () use ($class, $data) {
//                $reflectionClass = new ReflectionClass($class);
//
//                //if the validation of the top-level class attributes is false, then we validate another class
//                if ($this->classValidateAttr($reflectionClass, $data) === false) {
//                    die;
//                }
//
//                return new EventDispatcher($reflectionClass, $data);
//            }, $classMap));

        foreach ($classMap as $class) {
            /** @psalm-suppress ArgumentTypeCoercion */
            $reflectionClass = new ReflectionClass($class);

            //if the validation of the top-level class attributes is false, then we validate another class
            if ($this->classValidateAttr($reflectionClass, $data) === false) {
                continue;
            }

            new EventDispatcher($reflectionClass, $data);
        }

    }

    /**
     * Validating top-level attributes
     * @see https://i.imgur.com/zcylScY.png
     *
     * @param ReflectionClass $reflectionClass
     * @param DataFetcher $data
     * @return bool
     */
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