<?php
declare(strict_types=1);

namespace Astaroth\Route\Attribute;

use Astaroth\Attribute\Action;
use Astaroth\Attribute\Attachment;
use Astaroth\Attribute\ClientInfo;
use Astaroth\Attribute\Debug;
use Astaroth\Attribute\Message;
use Astaroth\Attribute\MessageRegex;
use Astaroth\Attribute\Payload;
use Astaroth\Attribute\State;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\DataFetcher\Enums\Events;
use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use ReflectionClass;

class EventDispatcher
{
    /**
     * @throws \ReflectionException
     */
    public function __construct(
        ReflectionClass     $classInfo,
        private DataFetcher $data
    )
    {
        (new Executor($classInfo))
            ->setCallableValidateAttribute($this->getValidateAttributeClosure())
            ->replaceObjects(self::fetchData($this->data))
            ->launch();
    }

    private static function fetchData(DataFetcher $data): MessageNew|MessageEvent|null
    {
        if ($data->getType() === Events::MESSAGE_NEW) {
            return $data->messageNew();
        }

        if ($data->getType() === Events::MESSAGE_EVENT) {
            return $data->messageEvent();
        }

        return null;

    }

    /**
     * @return \Closure
     */
    private function getValidateAttributeClosure(): \Closure
    {
        return function ($attribute) {
            if ($attribute instanceof AttributeValidatorInterface) {

                $this->vkEventValidate($attribute, self::fetchData($this->data));

                $this->anyEventValidate($attribute);

                return $attribute->validate();

            }

            return false;
        };
    }

    private function vkEventValidate(AttributeValidatorInterface $attribute, MessageNew|MessageEvent|null $data): void
    {
        if ($data instanceof MessageNew) {
            $this->messageNewValidate($attribute, $data);
        }
        if ($data instanceof MessageEvent) {
            $this->messageEventValidate($attribute, $data);
        }
    }

    private function messageNewValidate(AttributeValidatorInterface $attribute, MessageNew $data): void
    {
        if ($attribute::class === Message::class || $attribute::class === MessageRegex::class) {
            $attribute->setHaystack($data->getText());
        }

        if (($attribute::class === Attachment::class)) {
            $attribute->setHaystack($data->getAttachments());
        }

        if (($attribute::class === Action::class)) {
            $attribute->setHaystack($data->getAction());
        }
    }

    private function messageEventValidate(AttributeValidatorInterface $attribute, MessageEvent $data): void
    {
        if ($attribute::class === Payload::class) {
            $attribute->setHaystack($data->getPayload());
        }
    }

    private function anyEventValidate(AttributeValidatorInterface $attribute): void
    {
        if ($attribute instanceof Debug || $attribute instanceof State) {
            $attribute->setHaystack($this->data);
        }
        if ($attribute::class === ClientInfo::class) {
            $attribute->setHaystack($this->data->getClientInfo());
        }
    }

}