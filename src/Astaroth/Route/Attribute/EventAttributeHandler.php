<?php

declare(strict_types=1);

namespace Astaroth\Route\Attribute;

use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Event\MessageEvent;
use Astaroth\Attribute\Event\MessageNew;
use Astaroth\Attribute\State;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\DataFetcher;
use ReflectionAttribute;
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
        $validate = false;
        foreach ($reflectionClass->getAttributes() as $reflectionAttribute) {
            $attribute = $reflectionAttribute->newInstance();

            if ($attribute instanceof AttributeValidatorInterface) {
                $validate = $attribute->setHaystack($data)->validate();
            } else {
                throw new \LogicException(
                    $reflectionAttribute->getName(). ' not implement '. AttributeValidatorInterface::class
                );
            }
        }

        return $validate;
    }
}