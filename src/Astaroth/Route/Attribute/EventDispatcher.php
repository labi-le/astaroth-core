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
        ReflectionClass $classInfo,
        private DataFetcher $data
    )
    {
        (new Executor($classInfo))
            ->setCallableValidateAttribute($this->getValidateAttributeClosure())
            ->replaceObjects(new AdditionalParameter(
                $this->data::class,
                $this->data::class,
                false,
                $this->data
            ))
            ->launch();
    }


    /**
     * @return \Closure
     */
    private function getValidateAttributeClosure(): \Closure
    {
        return function ($attribute) {
            if ($attribute instanceof AttributeValidatorInterface) {
                if ($this->data->getType() === Events::MESSAGE_NEW) {
                    $this->messageNewValidate($attribute, $this->data->messageNew());
                }
                if ($this->data->getType()  === Events::MESSAGE_EVENT) {
                    $this->messageEventValidate($attribute, $this->data->messageEvent());
                }

                $this->anyEventValidate($attribute);

                return $attribute->validate();

            }

            return false;
        };
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

    private function messageEventValidate(AttributeValidatorInterface $attribute, MessageEvent $data):void
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