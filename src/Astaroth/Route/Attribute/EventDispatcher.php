<?php
declare(strict_types=1);

namespace Astaroth\Route\Attribute;

use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\DataFetcher\Enums\Events;
use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Closure;
use ReflectionClass;
use ReflectionException;

class EventDispatcher
{
    /**
     * @throws ReflectionException
     */
    public function __construct(
        ReflectionClass     $classInfo,
        private DataFetcher $data
    )
    {
        $executor = (new Executor($classInfo))
            ->setCallableValidateAttribute($this->getValidateAttributeClosure());

        if ($data = self::fetchData($this->data)) {
            $executor->replaceObjects($data);
        }

        $executor->launch();
    }

    private static function fetchData(DataFetcher $data): MessageNew|MessageEvent|null
    {
        if ($data->getType() === Events::MESSAGE_NEW) {
            return $data->messageNew();
        }

        if ($data->getType() === Events::MESSAGE_EVENT) {
            return $data->messageEvent();
        }

        trigger_error($data->getType() . " not yet implemented, please create issue", E_USER_NOTICE);

        return null;
    }

    /**
     * @return Closure
     */
    private function getValidateAttributeClosure(): Closure
    {
        return function ($attribute) {
            if ($attribute instanceof AttributeValidatorInterface) {
                return $this->validateMethodAttr($attribute, $this->data);

            }
            return false;
        };
    }

    private function validateMethodAttr(AttributeValidatorInterface $attribute, DataFetcher $data): bool
    {
        return $attribute->setHaystack(self::fetchData($data))->validate();
    }
}