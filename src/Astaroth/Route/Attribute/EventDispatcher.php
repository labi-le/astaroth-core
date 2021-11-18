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
use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Parser\DataTransferObject\ClassInfo;

class EventDispatcher
{
    public function __construct(
        ClassInfo                       $classInfo,
        private MessageEvent|MessageNew $validateData
    )
    {
        (new MethodExecutor($classInfo->getName(), $classInfo->getMethods()))
            ->setCallableValidateAttribute($this->getValidateAttributeClosure())
            ->addExtraParameters(new AdditionalParameter(
                $this->validateData::class,
                $this->validateData::class,
                false,
                $this->validateData
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
                if ($this->validateData instanceof MessageNew) {
                    $this->messageNewValidate($attribute);
                }
                if ($this->validateData instanceof MessageNew) {
                    $this->messageEventValidate($attribute);
                }

                $this->anyEventValidate($attribute);

                return $attribute->validate();

            }

            return false;
        };
    }

    private function messageNewValidate(AttributeValidatorInterface $attribute): void
    {
        if ($attribute::class === Message::class || $attribute::class === MessageRegex::class) {
            $attribute->setHaystack($this->validateData->getText());
        }

        if (($attribute::class === Attachment::class)) {
            $attribute->setHaystack($this->validateData->getAttachments());
        }

        if (($attribute::class === Action::class)) {
            $attribute->setHaystack($this->validateData->getAction());
        }
    }

    private function messageEventValidate(AttributeValidatorInterface $attribute):void
    {
        if ($attribute::class === Payload::class) {
            $attribute->setHaystack($this->validateData->getPayload());
        }
    }

    private function anyEventValidate(AttributeValidatorInterface $attribute): void
    {
        if ($attribute instanceof Debug || $attribute instanceof State) {
            $attribute->setHaystack($this->validateData);
        }
        if ($attribute::class === ClientInfo::class) {
            $attribute->setHaystack($this->validateData->getClientInfo());
        }
    }

}