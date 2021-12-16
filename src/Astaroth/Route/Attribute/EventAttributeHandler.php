<?php

declare(strict_types=1);

namespace Astaroth\Route\Attribute;

use Astaroth\Contracts\AttributeClassInterface;
use Astaroth\Contracts\AttributeMethodInterface;
use Astaroth\Contracts\AttributeReturnInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Foundation\Enums\Events;
use LogicException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use function is_object;
use function sprintf;
use function trigger_error;
use const E_USER_WARNING;

final class EventAttributeHandler
{
    private null|MessageEvent|MessageNew $data;

    /**
     * @param string[] $classMap
     * @param DataFetcher $data
     */
    public function __construct(
        private array $classMap,
        DataFetcher   $data,
    )
    {
        $this->data = self::fetchData($data);
    }

    /**
     * @return ValidatedObject[]
     * @throws ReflectionException
     */
    public function validate(): array
    {
        $validatedObjects = [];
        foreach ($this->classMap as $class) {
            /** @psalm-suppress ArgumentTypeCoercion */
            $reflectionClass = new ReflectionClass($class);

            //if the validation of the top-level class attributes is false, then we validate another class
            if ($this->validateAttr($reflectionClass, false) === false) {
                continue;
            }

            $methods = $this->validateAttrMethods($reflectionClass);
            if ($methods === []) {
                continue;
            }

            $validatedObjects[] = new ValidatedObject($reflectionClass, $methods);
        }

        return $validatedObjects;

    }


    /**
     * Validating top-level attributes
     * @see https://i.imgur.com/zcylScY.png
     *
     * @param ReflectionClass|ReflectionMethod $reflection
     * @param bool $throwOnNotImplementAttr
     * @return bool|AttributeReturnInterface
     */
    private function validateAttr
    (
        ReflectionClass|ReflectionMethod $reflection,
        bool                             $throwOnNotImplementAttr = true
    ): bool|AttributeReturnInterface
    {
        $validatedAttr = false;

        foreach ($reflection->getAttributes() as $reflectionAttribute) {
            $attribute = $reflectionAttribute->newInstance();

            if (
                $attribute instanceof AttributeValidatorInterface
                && $attribute->setHaystack($this->data)
                && $attribute->validate()
            ) {

                if ($attribute instanceof AttributeClassInterface) {
                    $validatedAttr = true;
                }

                // will return an attribute that will be replaced in reflection
                if ($attribute instanceof AttributeReturnInterface) {
                    return $attribute;
                }
                // only the first attribute of the method is checked
                if ($attribute instanceof AttributeMethodInterface) {
                    return true;
                }

            } else if ($throwOnNotImplementAttr) {
                throw new LogicException(
                    sprintf("%s not implement %s, %s, %s",
                        $reflectionAttribute->getName(),
                        AttributeValidatorInterface::class,
                        AttributeMethodInterface::class,
                        AttributeClassInterface::class
                    )
                );
            }
        }

        return $validatedAttr;
    }

    /**
     * @throws ReflectionException
     */
    private function validateAttrMethods(
        ReflectionClass $classInfo,
    ): array
    {
        $validatedMethods = [];
        foreach ($classInfo->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($replaceAttr = $this->validateAttr($method, false)) {
                $md = new ReflectionMethodDecorator($classInfo->getName(), $method->getName());

                if (is_object($replaceAttr)) {
                    $md->addReplaceAttribute($replaceAttr);
                }
                $validatedMethods[] = $md;
            }
        }

        return $validatedMethods;
    }

    public static function fetchData(DataFetcher $data): MessageNew|MessageEvent|null
    {
        if ($data->getType() === Events::MESSAGE_NEW) {
            return $data->messageNew();
        }

        if ($data->getType() === Events::MESSAGE_EVENT) {
            return $data->messageEvent();
        }

        trigger_error($data->getType() . " not yet implemented, please create issue", E_USER_WARNING);

        return null;
    }
}