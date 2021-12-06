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

            if (
                (
                    $this->eventAttrValidate($reflectionClass, $data) ||
                    $this->customOptionalAttrValidate($reflectionClass, $data)
                ) === false
            ) {
                break;
            }

            new EventDispatcher($reflectionClass, $data);
        }
    }

    /**
     * @param ReflectionAttribute[] $reflectionAttributes
     */
    private function validateAttribute(array $reflectionAttributes, DataFetcher $data): ?bool
    {
        $validate = null;

        foreach ($reflectionAttributes as $reflectionAttribute) {
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

    /**
     * @param ReflectionClass $reflectionClass
     * @param DataFetcher $data
     * @return bool
     *
     * @see Conversation, State
     *
     * @psalm-suppress InvalidArgument
     */
    private function eventAttrValidate(ReflectionClass $reflectionClass, DataFetcher $data): bool
    {
        return
            $this->validateAttribute($reflectionClass->getAttributes(MessageNew::class), $data)
            ?? $this->validateAttribute($reflectionClass->getAttributes(MessageEvent::class), $data)
            ?? false;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param DataFetcher $data
     * @return bool|null
     *
     * @psalm-suppress InvalidArgument
     */
    private function customOptionalAttrValidate(ReflectionClass $reflectionClass, DataFetcher $data): ?bool
    {
        ///more attr...
        return
            $this->validateAttribute($reflectionClass->getAttributes(Conversation::class), $data)
            ?? $this->validateAttribute($reflectionClass->getAttributes(State::class), $data)
            ?? true;
    }
}